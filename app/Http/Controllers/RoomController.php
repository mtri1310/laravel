<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;


class RoomController extends Controller
{
    //
    public function index(Request $request): View
    { 
        $keyword = $request->input('keyword');

        $rooms = Room::latest()
                    ->when($keyword, function($query, $keyword) {
                        return $query->where(function($q) use ($keyword) {
                            $q->where('room_name', 'like', "%{$keyword}%")
                              ->orWhere('capacity', 'like', "%{$keyword}%")
                              ->orWhere('room_type', 'like', "%{$keyword}%");
                        });
                    })
                    ->paginate(10)
                    ->appends(['keyword' => $keyword]); // Đảm bảo từ khóa được giữ lại trong các liên kết phân trang

        return view('rooms.index', compact('rooms', 'keyword'));
    }

    public function create() : View
    {
        return view('rooms.create');
    }

    public function store(RoomRequest $request) : RedirectResponse
    {
        try {
            $data = $request->validated();

            Room::create($data);

            return redirect()->route('rooms.index')
                ->with('messageSuccess', 'New room has been added successfully.');
        } catch (\Exception $e) {
            Log::error('Room Store Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while adding the room.');
        }
    }

    public function edit(Room $room) : View
    {
        return view('rooms.create', compact('room')); 

    }

    public function update(RoomRequest $request, Room $room) : RedirectResponse
    {
        try {
            $data = $request->validated();


            $room->update($data);

            return redirect()->route('rooms.index')
                ->with('messageSuccess', 'Room has been updated successfully.');
        } catch (\Exception $e) {
            Log::error('Room Update Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while updating the room.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room) : RedirectResponse
    {
        $room->delete();
        return redirect()->route('rooms.index')
                ->withSuccess('Room is deleted successfully.');
    }
}
