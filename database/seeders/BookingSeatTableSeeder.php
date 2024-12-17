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
    public function run()
    {
        Booking::all()->each(function ($booking) {
            // Each booking can have 1 to 5 seats
            $numberOfSeats = rand(1, 5);
            $availableSeats = Seat::whereNotIn('id', function ($query) use ($booking) {
                $query->select('seat_id')->from('booking_seat')->where('booking_id', '!=', $booking->id);
            })->inRandomOrder()->limit($numberOfSeats)->pluck('id');

            foreach ($availableSeats as $seatId) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_id'    => $seatId,
                ]);
            }
        });
    }
}
