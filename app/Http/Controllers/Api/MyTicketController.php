<?php

namespace App\Http\Controllers\Api;
use App\Models\Booking;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MyTicketController extends Controller
{
    public function getTicketDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }
        $booking = Booking::with([
            'showtime.film',
            'showtime.room',
            'seats',
            'invoice'
        ])->where('id', 2)->first();

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
                    'day' => $booking->showtime->day->format('d-m-Y'),
                ],
                'seat' => [
                    'seat_number' => $booking->seats->pluck('seat_number')->join(', ')
                ],
                'room' => [
                    'room_name' => $booking->showtime->room->room_name
                ],
                'invoice' => [
                    'total_amount' => $booking->payment->invoice->total_amount
                ]
            ]
        ];

        return response()->json($data);
    }
    
}
