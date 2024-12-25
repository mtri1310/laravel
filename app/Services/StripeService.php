<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createPaymentIntent($amount, $metadata)
    {
        return PaymentIntent::create([
            'amount' => $amount * 100, // VNĐ sang đơn vị nhỏ nhất (đồng)
            'currency' => 'vnd',
            'metadata' => $metadata, // Lưu thông tin thêm: film, ghế, phòng, ...
        ]);
    }
}