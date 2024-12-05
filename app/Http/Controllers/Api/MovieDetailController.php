<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MovieDetailController extends Controller
{
    public function getMovieDetails(): JsonResponse
    {
        // Dữ liệu phim bạn muốn trả về dưới dạng JSON
        $movieData = [
            "status" => "success",
            "message" => "Movie details",
            "data" => [
                "film" => [
                    "film_id" => "12345",
                    "thumbnail" => "https://example.com/poster/avengers-endgame.jpg",
                    "film_name" => "Avengers: Infinity War",
                    "duration" => "2 hours 29 minutes",
                    "review" => "4.8",
                    "movie_genre" => ["Action", "Adventure", "Sci-Fi"],
                    "censorship" => "13+",
                    "Language" => "English",
                    "Story_line" => "As the Avengers and their allies have continued to protect the world from threats too large for any one hero to handle, a new danger has emerged from the cosmic shadows: Thanos.... See more",
                    "Direction" => "Anthony Russo",
                    "Actor" => "Robert"
                ]
            ]
        ];

        // Trả về dữ liệu dưới dạng JSON
        return response()->json($movieData);
    }
}
