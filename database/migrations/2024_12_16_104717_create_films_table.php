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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('film_name'); 
            $table->string('thumbnail')->nullable(); 
            $table->integer('duration'); 
            $table->float('review', 8, 2)->nullable(); 
            $table->text('story_line')->nullable();
            $table->string('movie_genre'); 
            $table->string('censorship')->nullable(); 
            $table->string('language'); 
            $table->string('director');
            $table->string('actor'); 
            $table->boolean('status');
            $table->date('release')->default(DB::raw('CURRENT_DATE'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
