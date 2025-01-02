<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Room extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($room) {
            // Xóa showtimes liên quan
            $room->showtimes()->each(function ($showtime) {
                $showtime->delete();
            });

            // Xóa seats liên quan
            $room->seats()->each(function ($seat) {
                $seat->delete();
            });

            // Thêm xóa các liên kết khác nếu cần
        });
    }

    protected $fillable = [
        'room_name',
        'capacity',
        'room_type',
    ];

    /**
     * Một room có nhiều seats.
     */
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Một room có nhiều showtimes.
     */
    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
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
