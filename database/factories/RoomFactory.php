<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition()
    {
        return [
            'room_name'  => 'Room ' . $this->faker->unique()->numberBetween(1, 10),
            'capacity'   => $this->faker->randomElement([50, 100]),
            'room_type'  => $this->faker->randomElement(['IMAX', '3D', 'Standard']),
        ];
    }
}
