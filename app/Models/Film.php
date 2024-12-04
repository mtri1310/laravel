<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;
    protected $fillable = [
        'film_id',
        'film_name',
        'thumbnail',
        'duration',
        'review',
        'story_line',
        'movie_genre',
        'censorship',
        'language',
        'direction',
        'actor',
        'status'
    ];
}
