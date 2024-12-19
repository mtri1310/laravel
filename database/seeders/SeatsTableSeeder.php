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
            $columns = 10; // Số ghế mỗi hàng
            $rows = $room->capacity == 50 ? range('A', 'E') : range('A', 'J'); // Chọn số hàng theo capacity
        
            foreach ($rows as $row) {
                for ($i = 1; $i <= $columns; $i++) {
                    Seat::create([
                        'room_id'     => $room->id,
                        'seat_number' => $row . $i, // Ghép hàng và số ghế
                    ]);
                }
            }
        });
    }
}
