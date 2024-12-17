<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function  payment(Request $request)
    {
        // Các điều kiện kiểm tra
        $paymentMethod = $request->input('payment_method'); // Phương thức thanh toán
        $filmId = $request->input('film_id'); // ID phim
        $seatNumber = $request->input('seat_number'); // Số ghế

        // Kiểm tra điều kiện
        if (!$paymentMethod || !in_array($paymentMethod, ['Zalo Pay', 'Shoppe Pay', 'ATM Card'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid payment method',
                'error_code' => 'PAY001',
            ], 400);
        }

        if (!$filmId || $filmId !== '001') {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid film ID',
                'error_code' => 'FILM001',
            ], 400);
        }

        if (!$seatNumber || empty($seatNumber)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seat number is required',
                'error_code' => 'SEAT001',
            ], 400);
        }
        // Stripe Payment
        Stripe::setApiKey(config('stripe.sk')); // Lấy API Key từ config


        $payment = [
            'status' => 'success',
            'message' => 'Payment',
            'data' => [
                'payment_id' => '123123',
                'payment_method' => [
                    [
                        'id' => 123,
                        'name' => 'Zalo Pay'
                    ],
                    [
                        'id' => 124,
                        'name' => 'Shoppe Pay'
                    ],
                    [
                        'id' => 125,
                        'name' => 'ATM Card'
                    ],
                ],
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
