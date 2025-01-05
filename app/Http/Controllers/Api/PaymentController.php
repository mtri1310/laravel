<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createPayment(Request $request): JsonResponse
    {
        $user = Auth::user();
        Stripe::setApiKey(config('services.stripe.secret'));

        // Lấy thông tin từ request
        $bookingId = $request->input('booking_id');
        $orderID = $request->input('order_id');
        $amount = $request->input('amount'); // Đơn vị VNĐ

        if (!$bookingId || !$amount || !$orderID) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking ID, Order ID, amount are required.',
            ], 400);
        }

        // Tìm Booking theo booking_id
        $booking = Booking::find($bookingId);

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking not found.',
            ], 404);
        }

        // Kiểm tra Booking thuộc về người dùng hiện tại
        if ($booking->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized.',
            ], 403);
        }

        // Kiểm tra trạng thái Booking
        if ($booking->status !== Booking::STATUS_PENDING) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking is not pending payment.',
            ], 400);
        }

        // Kiểm tra số tiền
        // $calculatedAmount = $this->calculateAmount($booking);
        // if ($amount != $calculatedAmount) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Invalid amount.',
        //     ], 400);
        // }

        try {
            // Tạo PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount, // Chuyển đổi VNĐ sang đồng nhỏ nhất (ví dụ: 100 VNĐ = 100)
                'currency' => 'vnd',
                'metadata' => [
                    'booking_id' => $booking->id,
                    'order_id' => $orderID,
                    'user_id' => $user->id,
                ],
            ]);

            return response()->json([
                'status' => 'success',
                'client_secret' => $paymentIntent->client_secret,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error creating PaymentIntent: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create PaymentIntent.',
            ], 500);
        }
    }

    /**
     * Webhook để xử lý sự kiện từ Stripe
     */
    public function webhook(Request $request): JsonResponse
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $webhookSecret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentSucceeded($paymentIntent);
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentFailed($paymentIntent);
                break;

            default:
                return response()->json(['message' => 'Event not handled'], 200);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Xử lý sự kiện thanh toán thành công
     */
    private function handlePaymentSucceeded($paymentIntent)
    {
        // Lấy booking_id và user_id từ metadata
        $bookingId = $paymentIntent->metadata->booking_id ?? null;
        $orderId = $paymentIntent->metadata->order_id ?? null;
        $userId = $paymentIntent->metadata->user_id ?? null;

         if (!$bookingId || !$orderId || !$userId) {
            Log::error("Missing metadata in PaymentIntent: " . $paymentIntent->id);
            return;
        }

        // Tìm Booking theo booking_id
        $booking = Booking::find($bookingId);

        if (!$booking) {
            Log::error("Booking not found: ID " . $bookingId);
            return;
        }

        // Kiểm tra nếu đã có thanh toán cho booking này
        if ($booking->status === Booking::STATUS_CONFIRMED) {
            Log::info("Booking already paid: ID " . $bookingId);
            return;
        }
        if ($booking->status === Booking::STATUS_CANCELLED) {
            Log::info("Booking has been cancelled : ID " . $bookingId);
            return;
        }

        DB::beginTransaction();

        try {
            // Tạo bản ghi Payment
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'transaction_id' => $paymentIntent->id,
                'amount' => $paymentIntent->amount, // Chuyển đổi từ đồng nhỏ nhất sang VNĐ
                'payment_method' => 'stripe',
                'payment_status' => Payment::STATUS_COMPLETED, // 1: Thành công
                'created_at' => now(),
            ]);

            // Tạo Invoice
            $invoice = Invoice::create([
                'payment_id' => $payment->id,
                'invoice_number' => $orderId, // Tạo mã hóa đơn
                'total_amount' => $payment->amount,
                'created_at' => now(),
            ]);

            // Cập nhật trạng thái Booking
            $booking->status = Booking::STATUS_CONFIRMED; // 1: Đã thanh toán
            $booking->save();

            DB::commit();

            Log::info("Payment succeeded for Booking ID: " . $bookingId);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to process payment for Booking ID " . $bookingId . ": " . $e->getMessage());
        }
    }
    private function handlePaymentFailed($paymentIntent)
    {
        // Lấy booking_id, order_id và user_id từ metadata
        $bookingId = $paymentIntent->metadata->booking_id ?? null;
        $orderId = $paymentIntent->metadata->order_id ?? null;
        $userId = $paymentIntent->metadata->user_id ?? null;

        if (!$bookingId || !$orderId || !$userId) {
            Log::error("Missing metadata in PaymentIntent: " . $paymentIntent->id);
            return;
        }

        // Tìm Booking theo booking_id
        $booking = Booking::find($bookingId);

        if (!$booking) {
            Log::error("Booking not found: ID " . $bookingId);
            return;
        }

        // Kiểm tra nếu Booking đang ở trạng thái Pending
        if ($booking->status === Booking::STATUS_PENDING) {
            DB::beginTransaction();

            try {
                // Tạo bản ghi Payment với trạng thái thất bại
                $payment = Payment::create([
                    'booking_id' => $booking->id,
                    'transaction_id' => $paymentIntent->id,
                    'amount' => $paymentIntent->amount, // Chuyển đổi từ đồng nhỏ nhất sang VNĐ
                    'payment_method' => 'stripe',
                    'payment_status' => Payment::STATUS_FAILED, // Trạng thái thất bại
                    'created_at' => now(),
                ]);

                // Tạo Invoice với trạng thái thất bại
                $invoice = Invoice::create([
                    'payment_id' => $payment->id,
                    'invoice_number' => $orderId, // Tạo mã hóa đơn
                    'total_amount' => $payment->amount,
                    'created_at' => now(),
                ]);

                // Cập nhật trạng thái Booking
                $booking->status = Booking::STATUS_FAILED; // Trạng thái thanh toán thất bại
                $booking->save();

                DB::commit();

                Log::info("Payment failed and recorded for Booking ID: " . $bookingId);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to process failed payment for Booking ID " . $bookingId . ": " . $e->getMessage());
            }
        } else {
            Log::info("Booking is not in a pending state: ID " . $bookingId);
        }
    }

    /**
     * Hàm tính toán số tiền cần thanh toán dựa trên Booking
     */
    // private function calculateAmount(Booking $booking)
    // {
    //     // Implement logic để tính toán tổng số tiền dựa trên chi tiết Booking
    //     // Ví dụ: mỗi vé 100.000 VNĐ và số lượng ghế là số bản ghi trong booking_seat
    //     $seatCount = $booking->seats()->count();
    //     $amount = $seatCount * 100000; // VNĐ
    //     return $amount;
    // }
}
