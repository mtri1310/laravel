<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowtimeRequest;
use App\Models\Film;
use App\Models\Room;
use App\Models\Showtime;
use Exception;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShowtimeController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = $request->input('keyword');
    
        $showtimes = Showtime::with(['film', 'room']) // Eager load relationships
                            ->when($keyword, function($query, $keyword) {
                                return $query->whereHas('film', function($q) use ($keyword) {
                                    $q->where('film_name', 'like', "%{$keyword}%");
                                })->orWhereHas('room', function($q) use ($keyword) {
                                    $q->where('room_name', 'like', "%{$keyword}%");
                                });
                            })
                            ->orderBy('day', 'desc') // Sắp xếp theo ngày giảm dần
                            ->orderBy('start_time', 'desc') // Sắp xếp theo giờ bắt đầu giảm dần
                            ->orderBy('id', 'desc') // Sắp xếp theo ID giảm dần để đảm bảo duy nhất
                            ->paginate(10)
                            ->appends(['keyword' => $keyword]);
    
        return view('showtimes.index', compact('showtimes', 'keyword'));
    }
    
    

    public function create() : View
    {
        $rooms = Room::all(); // Lấy tất cả các phòng từ cơ sở dữ liệu
        $films = Film::all(); // Lấy tất cả các phim từ cơ sở dữ liệu
        return view('showtimes.create', compact('films', 'rooms')); // Truyền cả biến $films và $rooms vào view
    }
    
    public function store(ShowtimeRequest $request) : RedirectResponse
    {
        try {
            $data = $request->validated();

            Showtime::create($data);

            return redirect()->route('showtimes.index')
                ->with('messageSuccess', 'New showtime has been added successfully.');
        } catch (\Exception $e) {
            Log::error('Showtime Store Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while adding the showtime.');
        }
    }

    public function edit(Showtime $showtime) : View
    {
        $rooms = Room::all(); 
        $films = Film::all(); 
        return view('showtimes.create', compact('showtime', 'films', 'rooms'));
    }

    public function update(ShowtimeRequest $request, Showtime $showtime) : RedirectResponse
    {
        try {
            $data = $request->validated();

            $showtime->update($data);

            return redirect()->route('showtimes.index')
                ->with('messageSuccess', 'Showtime has been updated successfully.');
        } catch (\Exception $e) {
            Log::error('Showtime Update Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while updating the showtime.');
        }
    }


    public function destroy(Showtime $showtime) : RedirectResponse
    {
        $showtime->delete();
        return redirect()->route('showtimes.index')
                ->withSuccess('Showtime is deleted successfully.');
    }
}
