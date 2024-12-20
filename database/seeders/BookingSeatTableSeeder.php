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

            // Chọn ngẫu nhiên 2 ghế để đặt, ví dụ
            // Đảm bảo không trùng lặp ghế trong cùng một booking
            $selectedSeatIds = array_rand(array_flip($seats), 2);

            // Nếu chỉ có 1 ghế, array_rand sẽ trả về một giá trị đơn
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
