<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShowtimeRequest;
use App\Http\Requests\UpdateShowtimeRequest;
use App\Models\Film;
use App\Models\Room;
use App\Models\Showtime;
use Exception;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    public function index() : View
    {
        return view('showtimes.index', [
            'showtimes' => Showtime::latest()->paginate(3)
        ]);
    }

    public function create() : View
    {
        $rooms = Room::all(); // Lấy tất cả các phòng từ cơ sở dữ liệu
        $films = Film::all(); // Lấy tất cả các phim từ cơ sở dữ liệu
        return view('showtimes.create', compact('films', 'rooms')); // Truyền cả biến $films và $rooms vào view
    }
    
    public function store(StoreShowtimeRequest $request) : RedirectResponse
    {
        try{
            Showtime::create($request->all());
            session()->flash('messageSuccess', 'New showtime is added successfully.');
            return redirect()->route('showtimes.index');
        }
        catch(Exception $e){
            dd($e->getMessage());
        }
    }

    public function show(Showtime $showtime) : View
    {
        return view('showtimes.show', [
            'showtime' => $showtime
        ]);
    }

    public function edit(Showtime $showtime) : View
    {
        return view('showtimes.edit', [
            'showtime' => $showtime
        ]);
    }

    public function update(UpdateShowtimeRequest $request, Showtime $showtime) : RedirectResponse
    {
        $showtime->update($request->all());
        return redirect()->back()
                ->withSuccess('Showtime is updated successfully.');
    }

    public function destroy(Showtime $showtime) : RedirectResponse
    {
        $showtime->delete();
        return redirect()->route('showtimes.index')
                ->withSuccess('Showtime is deleted successfully.');
    }
}
