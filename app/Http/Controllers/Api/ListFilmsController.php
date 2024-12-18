<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Film;

class ListFilmsController extends Controller
{
    public function listFilms(Request $request)
    {
        $type = $request->type;

        if (!in_array($type, [0, 1])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid type parameter. Use type=0 for Now Playing or type=1 for Coming Soon.',
            ], 400);
        }

        $films = Film::where('status', $type)->get();
       
        $customFilms = $films->map(function ($film) use ($type) {
            $data = [
                'id' => $film->id,
                'film_name' => $film->film_name,
                'thumbnail' => $film->thumbnail,
                'duration' => $film->duration,
                'review' => $film->review,
                'story_line' => $film->story_line,
                'movie_genre' => $film->movie_genre,
                'censorship' => $film->censorship,
                'language' => $film->language,
                'director' => $film->director,
                'actor' => $film->actor,
                'status' => $film->status,
            ];
        
            if ($type == 1) {
                $data['release'] = $film->release->format('d-m-Y');
            }
            return $data;
        });

        $message = $type == 0 ? 'Now Playing films retrieved successfully' : 'Coming Soon films retrieved successfully';

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $customFilms,
        ]);
        
    }
}
