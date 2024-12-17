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
        'start_time',
        'day',
    ];

    /**
     * Một showtime thuộc về một film.
     */
    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    /**
     * Một showtime thuộc về một room.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Một showtime có nhiều bookings.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
