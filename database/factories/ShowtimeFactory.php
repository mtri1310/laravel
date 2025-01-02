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
        $minutesOptions = [0, 10, 20, 30, 40, 50];
        $selectedMinutes = $this->faker->randomElement($minutesOptions);

        // Chọn ngày trong khoảng từ 6 ngày trước đến 6 ngày sau
        $dayOffset = $this->faker->numberBetween(-6, 6);
        $selectedDay = Carbon::now()->addDays($dayOffset)->toDateString();

        return [
            'film_id'    => Film::inRandomOrder()->first()->id, // Giả sử đã có films từ id 1->19
            'room_id'    => Room::inRandomOrder()->first()->id,
            'start_time' => Carbon::createFromTime(
                $this->faker->numberBetween(0, 23),
                $selectedMinutes,
                0
            )->format('H:i'),
            'day'        => $selectedDay,
        ];
    }
}
