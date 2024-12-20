<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Payment;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        // Lấy 50 booking đầu tiên
        $bookings = Booking::take(50)->get();

        $bookings->each(function ($booking) {
            // Tạo payment cho mỗi booking
            Payment::create([
                'booking_id'      => $booking->id,
                'transaction_id'  => rand(100000, 999999), // ID giao dịch ngẫu nhiên
                'amount'          => rand(5, 20) * 10000, // Số tiền ngẫu nhiên
                'payment_method'  => collect(['Credit Card', 'PayPal', 'Cash'])->random(), // Phương thức thanh toán ngẫu nhiên
                'payment_status'  => collect(['Completed', 'Pending', 'Failed'])->random(), // Trạng thái thanh toán ngẫu nhiên
                'created_at'      => now(),
            ]);
        });
    }
}
