<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'transaction_id',
        'amount',
        'payment_method',
        'payment_status',
        'created_at',
    ];

    /**
     * Một payment thuộc về một booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Một payment có một invoice.
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
    /**
     * Truy xuất thông tin người dùng (User) qua Booking.
     */
    public function user()
    {
        return $this->belongsToThrough(User::class, Booking::class);
    }
    /**
     * Truy xuất thông tin film qua Showtime (thông qua Booking).
     */
    public function film()
    {
        return $this->hasOneThrough(Film::class, Showtime::class, 'id', 'id', 'booking_id', 'film_id');
    }
    /**
     * Truy xuất thông tin phòng (Room) qua Showtime (thông qua Booking).
     */
    public function room()
    {
        return $this->hasOneThrough(Room::class, Showtime::class, 'id', 'id', 'booking_id', 'room_id');
    }
}
