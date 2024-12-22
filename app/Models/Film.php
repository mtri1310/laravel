<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Film extends Model
{
    use HasFactory;
    protected $fillable = [
        'film_name',
        'thumbnail',
        'duration',
        'review',
        'story_line',
        'movie_genre',
        'censorship',
        'language',
        'director',
        'actor',
        'status',
        'release',
        'link_trailer',
    ];
    protected $casts = [
        'release' => 'date', 
    ];
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
