<?php

namespace App\Http\Controllers\Api;
use App\Models\Booking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketHistoryController extends Controller
{
    public function getTicketHistory(Request $request)
    {
        // Lấy danh sách các booking của user hiện tại (dựa trên token)
        $user = auth()->user(); // Middleware đã xác thực token và lấy user
        if (!$user) {
            return response()->json([
                "status" => "error",
                "message" => "Unauthenticated"
            ], 401);
        }
        $bookings = Booking::where('user_id', $user->id)
            ->where('status', Booking::STATUS_CONFIRMED)
            ->with(['showtime.film'])
            ->get();

        // Chuẩn bị dữ liệu JSON
        $data = [
            'status' => 'success',
            'message' => 'Ticket History',
            'data' => [
                'film' => $bookings->map(function ($booking) {
                    return [
                        'booking_id' => $booking->id,
                        'thumbnail' => $booking->showtime->film->thumbnail,
                        'film_name' => $booking->showtime->film->film_name,
                        'showtime' => [
                            'start_time' => $booking->showtime->start_time,
                            'day' => $booking->showtime->dayformat('d-m-Y'),
                        ],
                    ];
                }),
            ],
        ];

        return response()->json($data);
    }
}