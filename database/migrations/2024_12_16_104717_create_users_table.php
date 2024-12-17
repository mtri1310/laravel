<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('email')->unique();
            $table->string('full_name');
            $table->string('phone');
            $table->string('picture')->nullable();
            $table->boolean('role');
<<<<<<< HEAD:database/migrations/2014_10_12_000000_create_users_table.php
            $table->string('google_id')->nullable()->unique();
=======
            $table->string('google_id')->nullable();
>>>>>>> d2463777561e1bbd21e8c0e80abed4e3881307fd:database/migrations/2024_12_16_104717_create_users_table.php
            $table->rememberToken()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
