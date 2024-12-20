<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Showtime;

class ShowtimeController extends Controller
{
    public function getShowtimesByFilm(Request $request)
    {
      
        $request->validate([
            'film_id' => 'required|exists:films,id',
        ]);

       
        $filmId = $request->input('film_id');

        // Truy vấn danh sách suất chiếu của phim
        $showtimes = Showtime::where('film_id', $filmId)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get(['day', 'start_time']);

        // Kiểm tra nếu không có suất chiếu
        if ($showtimes->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không có suất chiếu nào cho phim này.',
            ], 404);
        }

        // Chuyển đổi dữ liệu sang định dạng mong muốn
        $data = $showtimes->map(function ($showtime) {
            return [
                'date' => $showtime->day,
                'time' => $showtime->start_time,
            ];
        });

        // Trả về phản hồi
        return response()->json([
            'status' => 'success',
            'message' => 'Danh sách suất chiếu được tìm thấy.',
            'data' => $data,
        ]);
    }
}
