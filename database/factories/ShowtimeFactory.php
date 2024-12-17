<?php

namespace Database\Factories;

use App\Models\Showtime;
use App\Models\Film;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShowtimeFactory extends Factory
{
    protected $model = Showtime::class;

    public function definition()
    {
        return [
            'film_id'     => Film::factory(),
            'room_id'     => Room::factory(),
            'start_time'  => $this->faker->time('H:i:s'), // Generates a time string like '14:30:00'
            'day'         => $this->faker->date('Y-m-d'), // Generates a date string like '2024-12-17'
        ];
    }
}