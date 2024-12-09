<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function payment(){
        $payment = [
            'status' => 'success',
            'message' => 'Payment',
            'data' => [
                'payment_id' => '123123',
                'payment_method' => ['Zalo Pay', 'MoMo', 'ATM Card'],
                'film' => [
                    'film_id' => '001',
                    'film_name' => 'The Marvels',
                    'movie_genre' => ['Action', 'Adventure', 'Sci-Fi'],
                    'thumbnail' => 'https://example.com/poster/the-marvels.jpg',
                ],
                'showtime' => [
                    'start_time' => '14:15',
                    'day' => '20.2.2021',
                ],
                'booking' => [
                    'booking_id' => '123',
                    'seat_number' => 'H7, H8',
                ],
                'invoice' => [
                    'total_amount' => '12313vnd',
                ],
            ],
        ];
        return response()->json($payment);
    }
}
