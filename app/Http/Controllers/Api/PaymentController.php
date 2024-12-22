<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function PaymentDetails($bookingId)
    {
        $booking = Booking::with(['showtime.film', 'invoice', 'payment'])
            ->where('id', $bookingId)
            ->first();

        if (!$booking) {
            return response()->json(['status' => 'error', 'message' => 'Booking not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Payment',
            'data' => [
                'booking_id' => $booking->id,
                'film' => [
                    'film_id' => $booking->showtime->film->id,
                    'film_name' => $booking->showtime->film->film_name,
                    'thumbnail' => $booking->showtime->film->thumbnail,
                    'movie_genre' => $booking->showtime->film->movie_genre,
                ],
                'showtime' => [
                    'start_time' => $booking->showtime->start_time,
                    'day' => $booking->showtime->day,
                ],
                'seat' => [
                    'seat_number' => $booking->seats->pluck('seat_number'),
                ],
                'invoice' => [
                    'total_amount' => $booking->invoice->total_amount,
                ],
                'payment_method' => $booking->payment->payment_method,
            ]
        ]);
    }
}
