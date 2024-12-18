<?php

namespace Database\Factories;

use App\Models\BookingSeat;
use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingSeatFactory extends Factory
{
    protected $model = BookingSeat::class;

    public function definition()
    {
        return [
            'booking_id' => Booking::factory(),
            'seat_id'    => Seat::factory(),
        ];
    }
}
