<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'showtime_id',
        'user_id',
        'booking_time',
    ];

    /**
     * Một booking thuộc về một showtime.
     */
    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    /**
     * Một booking thuộc về một user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Một booking có nhiều booking seats.
     */
    public function bookingSeats()
    {
        return $this->hasMany(BookingSeat::class);
    }

    /**
     * Một booking có nhiều seats thông qua booking_seat.
     */
    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'booking_seat');
    }

    /**
     * Một booking có một payment.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'payment_id', 'id');
    }
}
