<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Tạo tài khoản admin
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin'), // Mã hóa mật khẩu
            'email' => 'admin@gmail.com',
            'full_name' => 'Admin User',
            'picture' => 'default.png',
            'phone' => '1234567890',
            'role' => 1, // Role là admin
            'google_id' => '1'
        ]);

        // Tạo tài khoản user
        User::create([
            'username' => 'user',
            'password' => Hash::make('user'), // Mã hóa mật khẩu
            'email' => 'user@gmail.com',
            'full_name' => 'Normal User',
            'picture' => 'default.png',
            'phone' => '0987654321',
            'role' => 0, // Role là user
            'google_id' => '1'
        ]);
    }
}
