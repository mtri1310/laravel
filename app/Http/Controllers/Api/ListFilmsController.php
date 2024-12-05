<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ListFilmsController extends Controller
{
    public function listFilms(Request $request)
    {
        // Fake data của bảng film
        $films = [
            [
                'film_id' => '001',
                'film_name' => 'The Marvels',
                'thumbnail' => 'https://example.com/poster/the-marvels.jpg',
                'duration' => '2 hours 5 minutes',
                'review' => 8.5,
                'movie_genre' => ['Action', 'Adventure', 'Sci-Fi'],
                'status' => true, // Now Playing
            ],
            [
                'film_id' => '002',
                'film_name' => 'The Marvels',
                'thumbnail' => 'https://example.com/poster/the-marvels.jpg',
                'duration' => '2 hours 5 minutes',
                'review' => 8.5,
                'movie_genre' => ['Action', 'Adventure', 'Sci-Fi'],
                'status' => true, // Now Playing
            ],
            [
                'film_id' => '003',
                'film_name' => 'Avatar 3',
                'thumbnail' => 'https://example.com/poster/avatar-3.jpg',
                'movie_genre' => ['Action', 'Adventure', 'Sci-Fi'],
                'release' => '2025-12-20',
                'status' => false, // Coming Soon
            ],
            [
                'film_id' => '004',
                'film_name' => 'Frozen 3',
                'thumbnail' => 'https://example.com/poster/frozen-3.jpg',
                'movie_genre' => ['Action', 'Adventure', 'Sci-Fi'],
                'release' => '2026-11-22',
                'status' => false, // Coming Soon
            ],
        ];

        // Lấy tham số type từ query string
        $type = $request->query('type');

        // Lọc dữ liệu theo status
        if ($type == 1) {
            $filteredFilms = array_filter($films, fn($film) => $film['status'] === true);
            $message = 'Now Playing films retrieved successfully';
        } elseif ($type == 2) {
            $filteredFilms = array_filter($films, fn($film) => $film['status'] === false);
            $message = 'Coming Soon films retrieved successfully';
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid type parameter. Use type=1 for Now Playing or type=2 for Coming Soon.',
            ], 400);
        }

        // Trả về dữ liệu
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => array_values($filteredFilms),
        ]);
    }

}
