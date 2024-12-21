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
use Carbon\Carbon; // Thêm sử dụng Carbon

class SeatStatusController extends Controller
{
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
        $dayInput = $request->input('day'); // Ngày suất chiếu (định dạng: DD-MM-YYYY)
        $startTime = $request->input('start_time'); // Giờ bắt đầu (định dạng: HH:MM:SS)

        // Xác thực dữ liệu đầu vào với định dạng d-m-Y
        $request->validate([
            'day' => 'required|date_format:d-m-Y',
            'start_time' => 'required|date_format:H:i:s',
        ], [
            'day.required' => 'Ngày suất chiếu là bắt buộc.',
            'day.date_format' => 'Ngày phải đúng định dạng DD-MM-YYYY.',
            'start_time.required' => 'Giờ bắt đầu là bắt buộc.',
            'start_time.date_format' => 'Giờ bắt đầu phải đúng định dạng HH:MM:SS.',
        ]);

        // Chuyển đổi ngày từ d-m-Y sang Y-m-d
        try {
            $day = Carbon::createFromFormat('d-m-Y', $dayInput)->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ngày không hợp lệ.',
            ], 400);
        }

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
