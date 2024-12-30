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
        // Lấy tất cả các Booking có trạng thái 'Confirmed' (2) hoặc 'Failed' (3)
        $bookings = Booking::whereIn('status', [2, 3])->get();

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

            // Xác định payment_status và payment_method dựa trên trạng thái của Booking
            if ($booking->status === 2) { // Confirmed
                $paymentStatus = 1; // Completed
                $paymentMethod = collect($paymentMethods)->random();
            } elseif ($booking->status === 3) { // Failed
                $paymentStatus = 2; // Failed
                $paymentMethod = 'Stripe';
            } else {
                // Nếu trạng thái không phải 2 hoặc 3, không tạo Payment
                return;
            }

            // Tạo Payment cho mỗi Booking
            Payment::create([
                'booking_id'      => $booking->id,
                'transaction_id'  => rand(100000, 999999), // ID giao dịch ngẫu nhiên
                'amount'          => $amount, // Số tiền dựa trên số ghế
                'payment_method'  => $paymentMethod,
                'payment_status'  => $paymentStatus,
                'created_at'      => $paymentCreatedAt,
                'updated_at'      => $paymentCreatedAt,
            ]);
        }
    }
}
