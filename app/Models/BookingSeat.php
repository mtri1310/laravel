<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


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
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }
}
