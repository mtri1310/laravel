<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSeat extends Model
{
    use HasFactory;

    protected $table = 'booking_seat';

    protected $fillable = [
        'booking_id',
        'seat_id',
    ];

    /**
     * Một booking seat thuộc về một booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Một booking seat thuộc về một seat.
     */
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
