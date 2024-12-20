<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Seat;

class BookingSeatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $bookings = Booking::with('showtime.room.seats')->get();
        $bookingSeats = [];

        foreach ($bookings as $booking) {
            $showtime = $booking->showtime;
            $room = $showtime->room;
            $seats = $room->seats->pluck('id')->toArray();

            if (count($seats) == 0) {
                continue; 
            }

            // Chọn số lượng ghế ngẫu nhiên từ 1 đến 3
            $numberOfSeats = rand(1, 3);

            // Chọn ngẫu nhiên các ghế từ danh sách ghế
            $selectedSeatIds = array_rand(array_flip($seats), min($numberOfSeats, count($seats)));

            // Nếu chỉ có 1 ghế, array_rand trả về một giá trị đơn
            if (!is_array($selectedSeatIds)) {
                $selectedSeatIds = [$selectedSeatIds];
            }

            foreach ($selectedSeatIds as $seatId) {
                $bookingSeats[] = [
                    'booking_id' => $booking->id,
                    'seat_id'    => $seatId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Lưu tất cả booking_seat vào database
        \DB::table('booking_seat')->insert($bookingSeats);
    }
}