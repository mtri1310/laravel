<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Booking extends Model
{
    use HasFactory;
    const STATUS_PENDING = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_FAILED = 3;
    const STATUS_CANCELLED = 4;

    protected $fillable = [
        'showtime_id',
        'user_id',
        'status'
    ];

    /**
     * Một booking thuộc về một showtime.
     */
    public function showtime()
    {
        return $this->belongsTo(Showtime::class, 'showtime_id');
    }

    /**
     * Một booking thuộc về một user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }
}
