<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Showtime;
use App\Models\User;
use Carbon\Carbon;

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
                // Tạo một booking cho mỗi user và showtime với trạng thái mặc định là 'pending'
                $bookings[] = [
                    'showtime_id' => $showtime->id,
                    'user_id'     => $user->id,
                    'status'      => Booking::STATUS_PENDING, //'Pending', Confirmed, Failed, 'Cancelled'
                    'created_at'  => Carbon::now()->addDays(rand(-120, 2)),
                    'updated_at'  => now(),
                ];
            }
        }

        Booking::insert($bookings);
    }
}

