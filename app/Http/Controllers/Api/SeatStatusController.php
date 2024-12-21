<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Seat;
use App\Models\Showtime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeatStatusController extends Controller
{
       /**
     * Lấy danh sách ghế đã được đặt hoặc chưa trong một suất chiếu dựa trên thời gian và ngày.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSeatsByTimeAndDay(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Kiểm tra xem người dùng đã xác thực hay chưa
        if (!$user) {
            return response()->json([
                "status" => "error",
                "message" => "Unauthenticated"
            ], 401);
        }
        // Lấy dữ liệu từ request
        $day = $request->input('day'); // Ngày suất chiếu (định dạng: YYYY-MM-DD)
        $startTime = $request->input('start_time'); // Giờ bắt đầu (định dạng: HH:MM:SS)

        // Xác thực dữ liệu đầu vào
        $request->validate([
            'day' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i:s',
        ], [
            'day.required' => 'Ngày suất chiếu là bắt buộc.',
            'day.date_format' => 'Ngày phải đúng định dạng YYYY-MM-DD.',
            'start_time.required' => 'Giờ bắt đầu là bắt buộc.',
            'start_time.date_format' => 'Giờ bắt đầu phải đúng định dạng HH:MM:SS.',
        ]);

        // Tìm suất chiếu dựa trên ngày và giờ
        $showtime = Showtime::with('room.seats')
            ->where('day', $day)
            ->where('start_time', $startTime)
            ->first();

        // Kiểm tra nếu không tìm thấy suất chiếu
        if (!$showtime) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy suất chiếu cho ngày và giờ đã chọn.',
            ], 404);
        }

        // Lấy danh sách các ghế trong phòng
        $seats = $showtime->room->seats;

        // Lấy danh sách các `seat_id` đã được đặt từ bảng `booking_seat`
        $bookedSeatIds = Booking::where('showtime_id', $showtime->id)
            ->join('booking_seat', 'bookings.id', '=', 'booking_seat.booking_id')
            ->pluck('booking_seat.seat_id')
            ->unique()
            ->toArray();

        // Xây dựng danh sách trạng thái của các ghế
        $seatsStatus = $seats->map(function ($seat) use ($bookedSeatIds) {
            return [
                'seat_id' => $seat->id,
                'seat_number' => $seat->seat_number,
                'status' => in_array($seat->id, $bookedSeatIds) ? 'booked' : 'available',
            ];
        });

        // Trả về kết quả
        return response()->json([
            'status' => 'success',
            'message' => 'Trạng thái ghế đã được lấy thành công.',
            'data' => [
                'showtime_id' => $showtime->id,
                'film_name' => $showtime->film->film_name,
                'room_name' => $showtime->room->room_name,
                'start_time' => $showtime->start_time,
                'day' => $showtime->day,
                'seats' => $seatsStatus,
            ],
        ]);
    }
}
