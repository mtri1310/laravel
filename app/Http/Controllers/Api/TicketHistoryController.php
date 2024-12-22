<?php

namespace App\Http\Controllers\Api;
use App\Models\Booking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketHistoryController extends Controller
{
    public function getTicketHistory($userId)
    {
        $bookings = Booking::with(['showtime.film'])
            ->where('user_id', $userId)
            ->get();

        $ticketHistory = [
            'status' => 'success',
            'message' => 'Ticket History',
            'data' => [
                'user_id' => $userId,
                'film' => []
            ]
        ];

        foreach ($bookings as $booking) {
            $film = $booking->showtime->film;

            $ticketHistory['data']['film'][] = [
                'thumbnail' => $film->thumbnail,
                'film_name' => $film->film_name,
                'showtime' => [
                    'start_time' => $booking->showtime->start_time,
                    'day' => $booking->showtime->day
                ]
            ];
        }

        // Trả về JSON
        return response()->json($ticketHistory);
    }
}
