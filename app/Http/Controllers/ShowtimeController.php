<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowtimeRequest;
use App\Models\Film;
use App\Models\Room;
use App\Models\Showtime;
use Carbon\Carbon;
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
        $day = $request->input('day'); // Nhận tham số ngày
        $startTime = $request->input('start_time'); // Nhận tham số giờ bắt đầu

        // Xây dựng query cơ bản với các mối quan hệ cần thiết
        $query = Showtime::with(['film', 'room']);

        // Lọc theo từ khóa nếu có
        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->whereHas('film', function($q) use ($keyword) {
                    $q->where('film_name', 'like', "%{$keyword}%");
                })
                ->orWhereHas('room', function($q) use ($keyword) {
                    $q->where('room_name', 'like', "%{$keyword}%");
                })
                ->orWhere('start_time', 'like', "%{$keyword}%") // Tìm kiếm theo start_time
                ->orWhereDate('day', '=', $keyword); // Tìm kiếm theo ngày
            });
        }

        // Lọc theo ngày nếu có
        if ($day) {
            try {
                $dayParsed = Carbon::parse($day)->startOfDay();
                $query->whereDate('day', $dayParsed);
            } catch (\Exception $e) {
                // Xử lý lỗi nếu định dạng ngày không hợp lệ
                Log::error('Invalid day format for showtime filtering', [
                    'day' => $day,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Lọc theo giờ bắt đầu nếu có
        if ($startTime) {
            try {
                $startTimeParsed = Carbon::createFromFormat('H:i', $startTime)->format('H:i:s');
                $query->whereTime('start_time', $startTimeParsed);
            } catch (\Exception $e) {
                // Xử lý lỗi nếu định dạng giờ không hợp lệ
                Log::error('Invalid start_time format for showtime filtering', [
                    'start_time' => $startTime,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Sắp xếp và phân trang
        $showtimes = $query->orderBy('day', 'desc') // Sắp xếp theo ngày giảm dần
                          ->orderBy('start_time', 'desc') // Sắp xếp theo giờ bắt đầu giảm dần
                          ->paginate(10)
                          ->appends([
                              'keyword' => $keyword,
                              'day' => $day,
                              'start_time' => $startTime,
                          ]);

        return view('showtimes.index', compact('showtimes', 'keyword', 'day', 'startTime'));
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


    // public function destroy(Showtime $showtime) : RedirectResponse
    // {
    //     $showtime->delete();
    //     return redirect()->route('showtimes.index')
    //             ->withSuccess('Showtime is deleted successfully.');
    // }
    public function destroy(Request $request, Showtime $showtime): RedirectResponse
    {
        // Kiểm tra xem có xác nhận xóa dữ liệu liên quan hay không
        if ($request->input('confirm') === 'yes') {
            // Xóa các dữ liệu liên quan
            $showtime->bookings()->delete();
            // Nếu có các bảng khác liên quan, thêm các lệnh xóa tương tự ở đây
            // Ví dụ: Xóa các payment và invoice liên quan
            foreach ($showtime->bookings as $booking) {
                $booking->payments()->delete();
                // Nếu invoice có mối quan hệ trực tiếp với payment, bạn cần xóa invoice trước
                foreach ($booking->payments as $payment) {
                    $payment->invoices()->delete();
                }
            }

            // Cuối cùng, xóa Showtime
            $showtime->delete();

            return redirect()->route('showtimes.index')
                ->with('messageSuccess', 'Showtime và các dữ liệu liên quan đã được xóa thành công.');
        }

        // Nếu không có xác nhận, kiểm tra xem có dữ liệu liên quan hay không
        $hasDependencies = $showtime->bookings()->exists();
        if ($hasDependencies) {
            return redirect()->route('showtimes.index')
                ->with('messageError', 'Showtime này có dữ liệu liên quan. Vui lòng xác nhận để xóa tất cả các dữ liệu liên quan.');
        }

        // Nếu không có dữ liệu liên quan, xóa Showtime
        $showtime->delete();
        return redirect()->route('showtimes.index')
            ->with('messageSuccess', 'Showtime đã được xóa thành công.');
    }

    // Phương thức để kiểm tra dữ liệu liên quan (sử dụng AJAX)
    public function checkDependencies(Showtime $showtime)
    {
        // Kiểm tra các bảng liên quan
        $hasBookings = $showtime->bookings()->exists();
        // Bạn có thể thêm các kiểm tra khác nếu cần, ví dụ: payments, invoices

        $dependencies = [];
        if ($hasBookings) {
            $dependencies[] = 'Bookings';
        }
        // Thêm các bảng khác vào danh sách nếu cần

        return response()->json([
            'hasDependencies' => !empty($dependencies),
            'dependencies' => $dependencies,
        ]);
    }
}
