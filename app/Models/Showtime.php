<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'film_id',
        'room_id',
        'start_time',
        'day',
    ];
    protected $casts = [
        'day' => 'date',
        'start_time' => 'string', // Remains a string
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
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }
}
