<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Showtime;
use App\Models\Film;
use App\Models\Room;

class ShowtimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Assuming each film has multiple showtimes
        Film::all()->each(function ($film) {
            // Create between 1 to 5 showtimes per film
            Showtime::factory()->count(rand(1, 5))->create([
                'film_id' => $film->id,
                'room_id' => Room::inRandomOrder()->first()->id,
            ]);
        });
    }
}
