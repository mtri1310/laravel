<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use Faker\Factory as Faker;
class RoomsTableSeeder extends Seeder
{
    public function run()
    {
        // Khởi tạo Faker
        $faker = Faker::create();

        // Tạo 10 phòng với tên từ Room 1 đến Room 10
        for ($i = 1; $i <= 10; $i++) {
            Room::create([
                'room_name'  => 'Room ' . $i,  // Tên phòng: Room 1, Room 2, ..., Room 10
                'capacity'   => $i % 2 == 0 ? 100 : 50,  // Giả sử số chẵn có capacity là 100, số lẻ có capacity là 50
                'room_type'  => $faker->randomElement(['IMAX', '3D', 'Standard']),  // Loại phòng ngẫu nhiên
            ]);
        }
    }
}