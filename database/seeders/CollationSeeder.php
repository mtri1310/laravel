<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("ALTER TABLE `films` CHANGE `film_name` `film_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

        $this->command->info('Collation for table `film` has been updated to utf8mb4_general_ci!');
    }
}
