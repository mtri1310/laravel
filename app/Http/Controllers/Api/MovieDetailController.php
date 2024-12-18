<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;

class MovieDetailController extends Controller
{
    public function getMovieDetails(Request $request)
    {
           // Lấy tham số ID từ query string
    $id = $request->input('id', null);

    // Kiểm tra nếu không có ID
    if (!$id) {
        return response()->json([
            'status' => 'error',
            'message' => 'No ID provided'
        ], 400);
    }

    // Truy vấn dữ liệu từ database với where
    $film = Film::where('id', $id)->first();

    // Kiểm tra nếu không tìm thấy phim
    if (!$film) {
        return response()->json([
            'status' => 'error',
            'message' => 'No film found with the provided ID'
        ], 404);
    }

    // Định dạng dữ liệu
    $formattedFilm = [
        'film_id' => $film->id,
        'thumbnail' => $film->thumbnail,
        'film_name' => $film->film_name,
        'duration' => $film->duration,
        'review' => $film->review,
        'movie_genre' => $film->movie_genre,
        'censorship' => $film->censorship,
        'Language' => $film->language,
        'Story_line' => $film->story_line,
        'Direction' => $film->director,
        'Actor' => $film->actor
    ];

    // Trả về kết quả JSON
    return response()->json([
        'status' => 'success',
        'message' => 'Movie details retrieved successfully',
        'data' => [
            'film' => $formattedFilm
        ]
    ]);
    }
}
