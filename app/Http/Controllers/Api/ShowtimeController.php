<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Showtime;

class ShowtimeController extends Controller
{
    public function getShowtimes(Request $request)
    {
        // Validate the request input
        $request->validate([
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i:s', // Giờ không bắt buộc
        ]);

        
        $date = $request->input('date');
        $time = $request->input('time');

    
        $query = Showtime::where('day', $date);

        if ($time) {
            $query->where('start_time', $time);
        }

        $showtimes = $query->with(['film' => function ($query) {
            $query->select('id', 'film_name', 'thumbnail', 'duration', 'movie_genre', 'censorship', 'language', 'director', 'actor');
        }])->orderBy('start_time')->get();

        if ($showtimes->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không có phim trong khung giờ bạn tìm.',
            ], 404);
        }

        $data = $showtimes->map(function ($showtime) {
            return [
                'time' => $showtime->start_time,
                'film_name' => $showtime->film->film_name,
                'thumbnail' => $showtime->film->thumbnail,
                'duration' => $showtime->film->duration,
                'movie_genre' => $showtime->film->movie_genre,
                'censorship' => $showtime->film->censorship,
                'language' => $showtime->film->language,
                'director' => $showtime->film->director,
                'actor' => $showtime->film->actor,
            ];
        });

        
        return response()->json([
            'status' => 'success',
            'message' => 'Danh sách phim được tìm thấy.',
            'data' => [
                'date' => $date,
                'time' => $time,
                'showtimes' => $data,
            ],
        ]);
    }
}
