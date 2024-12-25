<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer',
            // 'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'transaction_id' => 'required|string',
        ]);

        // Tạo orderID ngẫu nhiên
        $orderID = $this->generateOrderID();

        // Cấu hình Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            // Tạo PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->input('amount') * 100, // Stripe yêu cầu đơn vị là cents
                'currency' => 'vnd',
                'payment_method_types' => [$request->input('payment_method')],
                'metadata' => [
                    'booking_id' => $request->input('booking_id'),
                    'order_id' => $orderID,
                ],
            ]);

            // Lưu thông tin thanh toán với trạng thái "chờ"
            $payment = Payment::create([
                'booking_id' => $request->input('booking_id'),
                'amount' => $request->input('amount'),
                'order_id' => $orderID,
                'transaction_id' => $request->input('transaction_id'),
                'payment_method' => $request->input('payment_method'),
                'payment_status' => 2, // Trạng thái "chờ"
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment created with pending status',
                'data' => [
                    'client_secret' => $paymentIntent->client_secret,
                    'order_id' => $orderID, // OrderID ngẫu nhiên
                    'amount' => $payment->amount,
                    'payment_status' => $payment->payment_status,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create PaymentIntent',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function confirmPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $payment = Payment::where('payment_status', 2) // Chỉ xác nhận nếu trạng thái là "chờ"
                        ->firstOrFail();

            // Cập nhật trạng thái thanh toán thành "thành công" và thêm transaction_id (nếu có)
            $payment->update([
                'payment_status' => 1,
            ]);

            // Lưu thông tin vào bảng Invoice
            $invoice = Invoice::create([
                'payment_id' => $payment->id,
                'invoice_number' => $request->input('order_id'), // Lưu orderID vào invoice_number
                'total_amount' => $request->input('amount'),
                'created_at' => now(),
            ]);

            // Truy xuất thông tin liên quan
            $filmName = $payment->booking->showtime->film->film_name;
            $seats = $payment->booking->bookingSeats->map(function ($bookingSeat) {
                return [
                    'seat_id' => $bookingSeat->seat->id,
                    'seat_number' => $bookingSeat->seat->seat_number,
                ];
            });

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Payment confirmed and invoice created',
                'data' => [
                    'transaction_id' => $payment->transaction_id,
                    'invoice_number' => $invoice->invoice_number,
                    'film_name' => $filmName,
                    'seats' => $seats,
                    'total_amount' => $invoice->total_amount,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Payment confirmation failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function cancelPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string', // Xác định dựa trên order_id
        ]);

        DB::beginTransaction();

        try {
            // Tìm Payment với trạng thái "chờ" (payment_status = 2) và order_id tương ứng
            $payment = Payment::where('payment_status', 2) // Chỉ hủy khi trạng thái là "chờ"
                ->firstOrFail();

            // Cập nhật trạng thái thành "thất bại" (payment_status = 3)
            $payment->update([
                'payment_status' => 3, // Thất bại
                'transaction_id' => null, // Đặt transaction_id về null
                'payment_method' => null, // Đặt payment_method về null
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Payment cancelled successfully',
                'data' => [
                    'order_id' => $request->input('order_id'),
                    'payment_status' => $payment->payment_status,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Payment cancellation failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    private function generateOrderID()
    {
        return substr(str_shuffle(str_repeat('0123456789', 16)), 0, 16);
    }
}
