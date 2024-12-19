<?php

namespace App\Http\Controllers\Api;
use App\Models\Booking;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class MyTicketController extends Controller
{
    public function getTicketDetails($bookingId)
    {
        $booking = Booking::with([
            'showtime.film',
            'showtime.room',
            'seats',
            'invoice'
        ])->where('id', $bookingId)->first();

        // Handle case where booking ID is not found
        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking ID not found',
                'data' => null
            ], 404);
        }

        // Build JSON response
        $data = [
            'status' => 'success',
            'message' => 'Ticket details retrieved successfully',
            'data' => [
                'booking_id' => $booking->id,
                'film' => [
                    'film_name' => $booking->showtime->film->film_name,
                    'movie_genre' => $booking->showtime->film->movie_genre,
                    'duration' => $booking->showtime->film->duration,
                    'thumbnail' => $booking->showtime->film->thumbnail
                ],
                'showtime' => [
                    'showtime_id' => $booking->showtime->id,
                    'start_time' => $booking->showtime->start_time,
                    'day' => $booking->showtime->day
                ],
                'seat' => [
                    'seat_number' => $booking->seats->pluck('seat_number')->join(', ')
                ],
                'room' => [
                    'room_name' => $booking->showtime->room->room_name
                ],
                'invoice' => [
                    'total_amount' => $booking->invoice->total_amount
                ]
            ]
        ];

        return response()->json($data);
    }
    
}
