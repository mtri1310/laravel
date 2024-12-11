<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    use HasFactory;
    protected $fillable = [
        'film_id',
        'room_id',
        'day',
        'start_time'
    ];
    public function film()
    {
        return $this->belongsTo(Film::class); // Liên kết với model Film
    }

    public function room()
    {
        return $this->belongsTo(Room::class); // Liên kết với model Room
    }
}
