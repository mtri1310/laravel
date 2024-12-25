<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'seat_number',
    ];
    

    /**
     * Một seat thuộc về một room.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Một seat có thể thuộc về nhiều bookings thông qua booking_seat.
     */
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_seat');
    }

    /**
     * Một seat có nhiều booking_seats.
     */
    public function bookingSeats()
    {
        return $this->hasMany(BookingSeat::class);
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
