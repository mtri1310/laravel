<?php

namespace Database\Factories;

use App\Models\Showtime;
use App\Models\Film;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShowtimeFactory extends Factory
{
    protected $model = Showtime::class;

    public function definition()
    {
        $dayOffsets = [-3, -2, -1, 1, 2, 3];
        $selectedOffset = $this->faker->randomElement($dayOffsets);
        return [
            'film_id'     => Film::factory(),
            'room_id'     => Room::factory(),
            'start_time'  => $this->faker->time('H:i:s'), // Generates a time string like '14:30:00'
            'day'         => Carbon::now()->addDays($selectedOffset)->toDateString(),
        ];
    }
}
