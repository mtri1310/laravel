<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Film;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SelectSeatController extends Controller
{
    /**
     * Xử lý việc chọn ghế và đặt vé.
     */
    public function getSelectSeat(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                "status" => "error",
                "message" => "Unauthenticated"
            ], 401);
        }

        $seatIds = $request->input('seat_id'); // danh sách 604,605
        $showtimeId = $request->input('showtime_id'); 
        $amount = $request->input('amount');

        if (!$seatIds || !$showtimeId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seat IDs and Showtime ID are required.',
            ], 400);
        }

        // Chuyển đổi chuỗi seat_ids thành mảng số nguyên
        $seatIdsArray = array_map('trim', explode(',', $seatIds));
        $seatIdsArray = array_map('intval', $seatIdsArray);

        DB::beginTransaction();

        try {
            // Lấy thông tin suất chiếu cùng với phòng và các ghế trong phòng
            $showtime = Showtime::with('room.seats')->lockForUpdate()->find($showtimeId);
            if (!$showtime) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid Showtime ID.',
                ], 404);
            }

            $film = $showtime->film;

            // Kiểm tra các ghế có hợp lệ và chưa được đặt
            $invalidSeats = [];
            $validSeats = [];

            foreach ($seatIdsArray as $seatId) {
                $seat = Seat::find($seatId);

                // Kiểm tra ghế có tồn tại và thuộc phòng của suất chiếu không
                if (!$seat || $seat->room_id !== $showtime->room_id) {
                    $invalidSeats[] = $seatId;
                    continue;
                }

                // Kiểm tra ghế đã được đặt trong suất chiếu này chưa
                $existingBooking = Booking::where('showtime_id', $showtimeId)
                    ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
                    ->whereHas('seats', function ($query) use ($seatId) {
                        $query->where('id', $seatId);
                    })
                    ->exists();

                if ($existingBooking) {
                    $invalidSeats[] = $seatId;
                    continue;
                }

                $validSeats[] = $seat;
            }

            // Nếu có ghế không hợp lệ, trả về thông báo lỗi
            if (!empty($invalidSeats)) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Some seats are invalid or already booked.',
                    'invalid_seats' => $invalidSeats,
                ], 400);
            }

            // Bước 1: Tạo Booking với status Pending
            $booking = Booking::create([
                'showtime_id' => $showtimeId,
                'user_id' => $user->id,
                'status' => Booking::STATUS_PENDING, // Đặt trạng thái là Pending
                'expires_at' => now()->addMinutes(15), // Thêm trường expires_at nếu bạn muốn
            ]);

            // Bước 2: Liên kết các ghế đã chọn với Booking thông qua bảng pivot booking_seat
            $booking->seats()->attach($seatIdsArray);

            DB::commit();

            // Truy xuất thông tin liên quan
            $seatData = array_map(function ($seat) {
                return [
                    'seat_id' => $seat->id,
                    'seat_number' => $seat->seat_number,
                ];
            }, $validSeats);

            return response()->json([
                'status' => 'success',
                'message' => 'Seats reserved successfully. Please complete the payment to confirm booking.',
                'data' => [
                    'order_id' => $this->generateOrderID(),
                    'booking_id' => $booking->id,
                    'amount' => $amount,
                    'film' => [
                        'id' => $film->id,
                        'film_name' => $film->film_name,
                        'thumbnail' => $film->thumbnail,
                        'duration' => $film->duration,
                        'review' => $film->review,
                        'story_line' => $film->story_line,
                        'trailer_link' => $film->link_trailer,
                        'movie_genre' => $film->movie_genre,
                        'censorship' => $film->censorship,
                        'language' => $film->language,
                        'director' => $film->director,
                        'actor' => $film->actor,
                        'status' => $film->status,
                        'release' => $film->release->format('d-m-Y'),
                    ],
                    'user' => [
                        'user_id' => $user->id,
                        'name' => $user->full_name,
                        'email' => $user->email,
                    ],
                    'seats' => $seatData,
                    'showtime_id' => $showtime->id,
                ],
            ], 201); // 201 Created

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Booking failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Booking failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function cancelPurchase(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                "status" => "error",
                "message" => "Unauthenticated"
            ], 401);
        }

        $bookingId = $request->input('booking_id');

        if (!$bookingId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking ID is required.',
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Tìm Booking và kiểm tra quyền sở hữu
            $booking = Booking::where('id', $bookingId)
                ->where('user_id', $user->id)
                ->where('status', Booking::STATUS_PENDING) // Chỉ có thể hủy nếu status là Pending
                ->lockForUpdate()
                ->first();

            if (!$booking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found or cannot be cancelled.',
                ], 404);
            }

            // Xóa các liên kết với ghế trong bảng pivot booking_seat
            $booking->seats()->detach();

            // Cập nhật trạng thái booking thành Cancelled
            $booking->update([
                'status' => Booking::STATUS_CANCELLED,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Booking cancelled successfully.',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Booking cancellation failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Booking cancellation failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tạo mã đơn hàng dựa trên invoice_number lớn nhất + 1.
     */
    private function generateOrderID()
    {
        // Lấy invoice_number lớn nhất hiện tại
        $maxInvoiceNumber = Invoice::whereNotNull('invoice_number')->max('invoice_number');

        if ($maxInvoiceNumber) {
            $newOrderID = (string)((int)$maxInvoiceNumber + 1);
        } else {
            // Nếu chưa có invoice nào, bắt đầu từ 1000000000000000
            $newOrderID = '1000000000000000';
        }

        // Đảm bảo rằng order_id là chuỗi 16 chữ số
        return str_pad($newOrderID, 16, '0', STR_PAD_LEFT);
    }
}
