<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Models\Room;
use App\Models\Seat;
use Exception;
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

            // Tạo phòng mới
            $room = Room::create($data);

            // Tự động tạo ghế dựa trên capacity
            $this->createSeatsForRoom($room);

            return redirect()->route('rooms.index')
                ->with('messageSuccess', 'New room has been added successfully with seats.');
        } catch (\Exception $e) {
            Log::error('Room Store Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while adding the room.');
        }
    }
    /**
     * Tạo ghế tự động cho một phòng
     */
    private function createSeatsForRoom(Room $room)
    {
        $capacity = $room->capacity;
        $rows = ceil($capacity / 10); // Số hàng (ví dụ: 50 ghế => 5 hàng)
        $seats = [];

        for ($row = 0; $row < $rows; $row++) {
            $rowLetter = chr(65 + $row); // Chuyển số hàng thành chữ cái (A, B, C,...)
            for ($col = 1; $col <= 10; $col++) {
                $seatNumber = $rowLetter . $col;
                $seats[] = [
                    'room_id' => $room->id,
                    'seat_number' => $seatNumber,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                // Dừng tạo nếu đã đủ số ghế
                if (count($seats) >= $capacity) {
                    break 2;
                }
            }
        }

        // Lưu tất cả ghế vào database
        Seat::insert($seats);
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
