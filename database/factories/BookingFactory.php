<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'showtime_id'  => Showtime::factory(),
            'user_id'      => User::factory(),
            'booking_time' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
