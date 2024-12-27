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

class PaymentController extends Controller
{
    /**
     * Xác nhận thanh toán thành công và cập nhật trạng thái booking.
     */
    public function confirmPayment(Request $request)
    {
        // Bước 1: Validate dữ liệu đầu vào
        $request->validate([
            'order_id' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'required|string',
            'booking_id' => 'required|integer',
        ], [
            'order_id.required' => 'Mã đơn hàng là bắt buộc.',
            'amount.required' => 'Số tiền là bắt buộc.',
            'transaction_id.required' => 'Mã giao dịch là bắt buộc.',
            'booking_id.required' => 'Mã booking là bắt buộc.',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user(); // Lấy thông tin user đã đăng nhập

            // Bước 2: Tìm Booking và kiểm tra quyền sở hữu
            $booking = Booking::where('id', $request->input('booking_id'))
                ->where('user_id', $user->id)
                ->first();

            if (!$booking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found or does not belong to the user.',
                ], 404);
            }

            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_status' => Payment::STATUS_COMPLETED,
                'amount'=> $request->input('amount'),
                'transaction_id' => $request->input('transaction_id'),
                // 'payment_method' => $request->input('payment_method'), // Nếu có
                'payment_method' => "Stripe",
            ]);

            // Bước 4: Cập nhật trạng thái booking thành "Confirmed"
            $booking->update([
                'status' => Booking::STATUS_CONFIRMED,
            ]);

            // Bước 5: Tạo bản ghi Invoice
            $invoice = Invoice::create([
                'payment_id' => $payment->id,
                'invoice_number' => $request->input('order_id'),
                'total_amount' => $request->input('amount'),
                'created_at' => now(),
            ]);

            // Bước 6: Truy xuất thông tin liên quan
            $filmName = $booking->showtime->film->film_name;
            $seats = $booking->bookingSeats->map(function ($bookingSeat) {
                return [
                    'seat_id' => $bookingSeat->seat->id,
                    'seat_number' => $bookingSeat->seat->seat_number,
                ];
            });

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Payment confirmed and invoice created successfully.',
                'data' => [
                    'invoice_number' => $invoice->invoice_number,
                    'film_name' => $filmName,
                    'seats' => $seats,
                    'total_amount' => $invoice->total_amount,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payment confirmation failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Payment confirmation failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hủy thanh toán và cập nhật trạng thái booking.
     */
    public function cancelPayment(Request $request)
    {
        // Bước 1: Validate dữ liệu đầu vào
        $request->validate([
            'booking_id' => 'required|integer',
        ], [
            'booking_id.required' => 'Mã booking là bắt buộc.',
            'booking_id.integer' => 'Mã booking phải là số nguyên.',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user(); // Lấy thông tin user đã đăng nhập

            // Bước 2: Tìm Booking và kiểm tra quyền sở hữu
            $booking = Booking::where('id', $request->input('booking_id'))
                ->where('user_id', $user->id)
                ->first();

            if (!$booking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found or does not belong to the user.',
                ], 404);
            }

            // Bước 3: Tìm Payment liên kết với Booking
            $payment = $booking->payment;

            if ($payment) {
                // Kiểm tra trạng thái hiện tại của Payment
                if ($payment->payment_status === Payment::STATUS_COMPLETED) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Cannot cancel a completed payment.',
                    ], 400);
                }

                if ($payment->payment_status === Payment::STATUS_FAILED) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Payment has already been failed.',
                    ], 400);
                }

                // Cập nhật Payment đã tồn tại
                $payment->update([
                    'payment_status' => Payment::STATUS_FAILED,
                    'transaction_id' => null,
                    'payment_method' => null, // Nếu có
                ]);
            } else {
                // Tạo Payment mới với trạng thái Failed nếu chưa tồn tại
                $payment = Payment::create([
                    'booking_id' => $booking->id,
                    'payment_status' => Payment::STATUS_FAILED,
                    'transaction_id' => null,
                    'payment_method' => null, // Nếu có
                ]);
            }

            // Bước 4: Cập nhật trạng thái booking thành "Failed"
            $booking->update([
                'status' => Booking::STATUS_FAILED,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Payment cancelled and booking status updated successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payment cancellation failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Payment cancellation failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
