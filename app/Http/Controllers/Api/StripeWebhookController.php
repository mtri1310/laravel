<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;
use Stripe\Webhook;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\DB;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Lấy Webhook Secret từ .env
        $webhookSecret = env('STRIPE_WEBHOOK_SECRET');

        // Lấy payload và Stripe-Signature
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            // Xác minh webhook từ Stripe
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);

            // Xử lý từng sự kiện
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentSucceeded($paymentIntent);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentFailed($paymentIntent);
                    break;

                case 'payment_intent.canceled':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentCanceled($paymentIntent);
                    break;

                default:
                    return response()->json(['message' => 'Event not handled'], 200);
            }

            return response()->json(['message' => 'Webhook handled successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    protected function handlePaymentSucceeded($paymentIntent)
    {
        DB::transaction(function () use ($paymentIntent) {
            // Tìm Payment theo transaction_id
            $payment = Payment::where('transaction_id', $paymentIntent->id)->first();

            if ($payment) {
                // Cập nhật trạng thái thanh toán
                $payment->update(['payment_status' => 1]);

                // Tạo hóa đơn trong bảng Invoice
                Invoice::create([
                    'payment_id' => $payment->id,
                    'invoice_number' => $payment->order_id,
                    'total_amount' => $payment->amount,
                    'created_at' => now(),
                ]);
            }
        });
    }

    protected function handlePaymentFailed($paymentIntent)
    {
        DB::transaction(function () use ($paymentIntent) {
            // Tìm Payment theo transaction_id
            $payment = Payment::where('transaction_id', $paymentIntent->id)->first();

            if ($payment) {
                // Cập nhật trạng thái thanh toán thất bại
                $payment->update(['payment_status' => 3]);
            }
        });
    }

    protected function handlePaymentCanceled($paymentIntent)
    {
        DB::transaction(function () use ($paymentIntent) {
            // Tìm Payment theo transaction_id
            $payment = Payment::where('transaction_id', $paymentIntent->id)->first();

            if ($payment) {
                // Cập nhật trạng thái thanh toán đã hủy
                $payment->update(['payment_status' => 4]); // 4: Hủy
            }
        });
    }
}
