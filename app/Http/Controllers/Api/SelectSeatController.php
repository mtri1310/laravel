<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Seat;
use App\Models\Showtime;

class SelectSeatController extends Controller
{

    public function getSelectSeat(Request $request): JsonResponse
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
        $seatId = $request->input('seat_id'); // ID ghế cần đặt
        $showtimeId = $request->input('showtime_id'); // ID suất chiếu

        // Kiểm tra dữ liệu đầu vào
        if (!$seatId || !$showtimeId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seat ID and Showtime ID are required.',
            ], 400);
        }

        $showtime = Showtime::find($showtimeId);
        $seat = Seat::find($seatId);

        // Kiểm tra nếu không tìm thấy suất chiếu hoặc ghế
        if (!$showtime || !$seat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Showtime ID or Seat ID.',
            ], 404);
        }

        // So sánh `room_id` giữa `Showtime` và `Seat`
        if ($showtime->room_id !== $seat->room_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'The selected seat does not belong to the room of the showtime.',
            ], 400);
        }

        // Kiểm tra xem ghế đã được đặt bởi người dùng hiện tại hay chưa
        $existingBooking = Booking::where('showtime_id', $showtimeId)
            ->where('user_id', $user->id)
            ->whereHas('seats', function ($query) use ($seatId) {
                $query->where('id', $seatId);
            })
            ->first();

        if ($existingBooking) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already booked this seat for the selected showtime.',
            ], 400);
        }

        // Nếu chưa đặt, thêm ghế vào đặt chỗ
        $booking = Booking::firstOrCreate([
            'showtime_id' => $showtimeId,
            'user_id' => $user->id,
        ]);
        
        $booking->seats()->attach($seatId);

        // Trả về kết quả
        return response()->json([
            'status' => 'success',
            'message' => 'Seat booked successfully.',
            'data' => [
                'booking_id' => $booking->id,
                'user' => [
                    'user_id' => $user->id,
                    'name' => $user->full_name,
                    'email' => $user->email,
                ],
                'seat' => [
                    'seat_id' => $seatId,
                    'seat_number' => $seat->seat_number,
                ],
                'showtime' => [
                    'showtime_id' => $showtime->id,
                    'start_time' => $showtime->start_time,
                    'day' => $showtime->day,
                    'room_id' => $showtime->room_id,
                ],
            ],
        ]);
    }
}
