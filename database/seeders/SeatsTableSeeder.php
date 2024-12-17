<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Seat;

class SeatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // For each room, create seats
        Room::all()->each(function ($room) {
            $rows = range('A', 'J'); // 10 rows
            $columns = 20; // 20 seats per row
            foreach ($rows as $row) {
                for ($i = 1; $i <= $columns; $i++) {
                    Seat::create([
                        'room_id'     => $room->id,
                        'seat_number' => $row . $i,
                    ]);
                }
            }
        });
    }
}
