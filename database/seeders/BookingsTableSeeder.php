<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Showtime;
use App\Models\User;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Ensure there are users and showtimes before creating bookings
        if (User::count() == 0) {
            $this->call(DatabaseSeeder::class);
        }

        Showtime::all()->each(function ($showtime) {
            // Create between 0 to 10 bookings per showtime
            Booking::factory()->count(rand(0, 10))->create([
                'showtime_id' => $showtime->id,
                'user_id'     => User::inRandomOrder()->first()->id,
            ]);
        });
    }
}
