<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    //
    public function index() : View
    {
        return view('rooms.index', [
            'rooms' => Room::latest()->paginate(3)
        ]);
    }

    public function create() : View
    {
        return view('rooms.create');
    }

    public function store(StoreRoomRequest $request) : RedirectResponse
    {
        Room::create($request->all());
        return redirect()->route('rooms.index')
                ->withSuccess('New room is added successfully.');
    }

    public function show(Room $room) : View
    {
        return view('rooms.show', [
            'room' => $room
        ]);
    }

    public function edit(Room $room) : View
    {
        return view('rooms.edit', [
            'room' => $room
        ]);
    }

    public function update(UpdateRoomRequest $request, Room $room) : RedirectResponse
    {
        $room->update($request->all());
        return redirect()->back()
                ->withSuccess('Room is updated successfully.');
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
