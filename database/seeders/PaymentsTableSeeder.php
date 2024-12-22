<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Payment;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        $bookings = Booking::all();

        $bookings->each(function ($booking) {
            // Lấy số lượng ghế đã đặt
            $seatCount = $booking->bookingSeats->count();
    
            // Tính số tiền dựa trên số ghế và giá tiền mỗi ghế
            $amount = $seatCount * 100000; // 100,000 VND mỗi ghế
    
            // Tạo payment cho mỗi booking
            Payment::create([
                'booking_id'      => $booking->id,
                'transaction_id'  => rand(100000, 999999), // ID giao dịch ngẫu nhiên
                'amount'          => $amount, // Số tiền dựa trên số ghế
                'payment_method'  => collect(['Credit Card', 'PayPal', 'Cash'])->random(), // Phương thức thanh toán ngẫu nhiên
                'payment_status'  => collect(['Completed', 'Pending', 'Failed'])->random(), // Trạng thái thanh toán ngẫu nhiên
                'created_at'      => now(),
            ]);
        });
    }
}
