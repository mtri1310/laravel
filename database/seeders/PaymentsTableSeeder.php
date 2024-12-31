<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        // Lấy tất cả các Booking có trạng thái 'Pending'
        $bookings = Booking::where('status', Booking::STATUS_PENDING)->get();

        $paymentMethods = ['Stripe', 'Credit Card', 'PayPal', 'Cash'];

        foreach ($bookings as $booking) {
            // Lấy số lượng ghế đã đặt
            $seatCount = $booking->bookingSeats()->count();

            // Tính số tiền dựa trên số ghế và giá tiền mỗi ghế
            $amount = $seatCount * 100000; // 100,000 VND mỗi ghế

            // Lấy thời gian tạo của booking
            $bookingCreatedAt = Carbon::parse($booking->created_at);

            // Thêm từ 3 đến 5 phút vào thời gian tạo của booking
            $paymentCreatedAt = $bookingCreatedAt->copy()->addMinutes(rand(3, 5));

            $isSuccessful = rand(1, 100) <= 80; // 80% thành công

            if ($isSuccessful) {
                $paymentStatus  = Payment::STATUS_COMPLETED;
                $paymentMethod  = 'Stripe'; 
                $transactionId  = 'pi_' . strtoupper(\Str::random(14)); // Giống Stripe
                $bookingStatus  = Booking::STATUS_CONFIRMED;
            } else {
                $paymentStatus  = Payment::STATUS_FAILED;
                $paymentMethod  = null; // Theo yêu cầu
                $transactionId  = null; // Theo yêu cầu
                $bookingStatus  = Booking::STATUS_FAILED;
            }

            // Tạo Payment cho mỗi Booking
            Payment::create([
                'booking_id'      => $booking->id,
                'transaction_id'  => $transactionId,
                'amount'          => $amount, // Số tiền dựa trên số ghế
                'payment_method'  => $paymentMethod,
                'payment_status'  => $paymentStatus,
                'created_at'      => $paymentCreatedAt,
                'updated_at'      => $paymentCreatedAt,
            ]);

            // Cập nhật trạng thái của Booking dựa trên Payment status
            $booking->update([
                'status' => $bookingStatus,
            ]);
        }
    }
}
