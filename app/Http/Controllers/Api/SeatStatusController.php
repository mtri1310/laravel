<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Seat;
use App\Models\Showtime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Log;

class SeatStatusController extends Controller
{
    public function getSeatsByTimeAndDay(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                "status" => "error",
                "message" => "Unauthenticated"
            ], 401);
        }

        // Lấy dữ liệu từ request
        $dayInput = $request->input('day');
        $startTime = $request->input('start_time');

        // Validate request
        $request->validate([
            'day' => 'required|date_format:d-m-Y',
            'start_time' => 'required|date_format:H:i:s',
        ], [
            'day.required' => 'Ngày suất chiếu là bắt buộc.',
            'day.date_format' => 'Ngày phải đúng định dạng DD-MM-YYYY.',
            'start_time.required' => 'Giờ bắt đầu là bắt buộc.',
            'start_time.date_format' => 'Giờ bắt đầu phải đúng định dạng HH:MM:SS.',            
        ]);

        try {
            $day = Carbon::createFromFormat('d-m-Y', $dayInput)->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ngày không hợp lệ.',
            ], 400);
        }

        // Tìm suất chiếu dựa trên ngày và giờ
        $showtime = Showtime::with('room.seats', 'film')
            ->where('day', $day)
            ->where('start_time', $startTime)
            ->first();

        // Kiểm tra nếu không tìm thấy suất chiếu
        if (!$showtime) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy suất chiếu cho ngày, giờ, và phòng đã chọn.',
            ], 404);
        }

        // Lấy danh sách các ghế trong phòng
        $seats = $showtime->room->seats;

        $bookedSeatIds = Booking::where('showtime_id', $showtime->id)
            ->where('status', Booking::STATUS_CONFIRMED) // Chỉ lấy các booking đã xác nhận
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

        return response()->json([
            'status' => 'success',
            'message' => 'Trạng thái ghế đã được lấy thành công.',
            'data' => [
                'showtime_id' => $showtime->id,
                'film_name' => $showtime->film->film_name,
                'room_name' => $showtime->room->room_name,
                'start_time' => $showtime->start_time,
                'day' => Carbon::parse($showtime->day)->format('d-m-Y'),
                'seats' => $seatsStatus,
            ],  
        ]);
    }
}
