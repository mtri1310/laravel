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
            'room_name'  => 'Room ' . $this->faker->unique()->numberBetween(1, 20),
            'capacity'   => $this->faker->numberBetween(50, 300),
            'room_type'  => $this->faker->randomElement(['IMAX', '3D', 'Standard']),
        ];
    }
}
