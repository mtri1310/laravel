<?php

namespace Database\Factories;

use App\Models\Seat;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatFactory extends Factory
{
    protected $model = Seat::class;

    public function definition()
    {
        // Assuming each room has seats labeled from A1 to J20
        $rows = range('A', 'J');
        $number = $this->faker->numberBetween(1, 20);
        return [
            'room_id'      => Room::factory(),
            'seat_number'  => $this->faker->randomElement($rows) . $number,
        ];
    }
}
