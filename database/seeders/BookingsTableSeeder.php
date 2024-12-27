<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Showtime;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        $showtimes = Showtime::all();
        $users = User::all();

        $bookings = [];

        foreach ($showtimes as $showtime) {
            foreach ($users as $user) {
                // Tạo một booking cho mỗi user và showtime
                $bookings[] = [
                    'showtime_id'  => $showtime->id,
                    'user_id'      => $user->id,
                    'status'       => collect([1, 2, 3, 4])->random(), //'pending', 'confirmed', 'failed', 'cancelled'
                    'created_at'   => Carbon::now()->addDays(rand(-60, 60)),
                    'updated_at'   => now(),
                ];
            }
        }

        Booking::insert($bookings);
    }
}
