<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'required|string',
            'booking_id' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            $user = auth()->user(); // Lấy thông tin user đã đăng nhập
            // Tìm Payment thông qua Booking và kiểm tra user_id
            $payment = Payment::whereHas('booking', function ($query) use ($user, $request) {
                $query->where('user_id', $user->id ) // Kiểm tra user_id
                      ->where('id', $request->input('booking_id')); // Kiểm tra booking_id
            })->where('payment_status', 2) // Chỉ xử lý nếu trạng thái là "chờ"
              ->first();
            
            if (!$payment) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment not found or already confirmed',
                ], 404);
            }
    
            // Cập nhật trạng thái thanh toán thành "thành công"
            $payment->update([
                'payment_status' => 1,
                'transaction_id' => $request->input('transaction_id'), // Lưu Transaction ID
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
            'booking_id' => 'required|integer', // Sử dụng booking_id để xác định payment
        ]);

        $user = auth()->user(); // Lấy thông tin user từ token

        DB::beginTransaction();

        try {
            // Tìm Payment với trạng thái "chờ" và thuộc về booking của user hiện tại
            $payment = Payment::where('payment_status', 2) // Chỉ hủy khi trạng thái là "chờ"
                ->whereHas('booking', function ($query) use ($user, $request) {
                    $query->where('user_id', $user->id)
                        ->where('id', $request->input('booking_id')); // Kiểm tra booking_id
                })
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
}