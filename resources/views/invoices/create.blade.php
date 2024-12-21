<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - {{ isset($invoice->id) ? 'Edit Invoice' : 'Add New Invoice' }}</title>
    <link href="{{ asset('assets/images/icon.png') }}" rel="icon" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" type="text/css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/add_form.css') }}">
</head>

@php
    // Ensure $film is always defined
    if (!isset($invoice)) {
        $invoice = new \App\Models\Invoice();
    }
@endphp

<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            <div>
                {{-- Sidebar --}}
                @include('fragments.sidebar', ['key' => 'film', 'subkey' => isset($film->id) ? 'film_all' : 'film_news'])
            </div>

            <div class="d-flex flex-column wrapper">
                {{-- Header --}}
                @include('fragments.header')

                <div class="content">
                    <section class="form-container">
                        <div class="form-container-header d-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3">
                                {{ isset($invoice->id) ? 'Edit invoice Information' : 'Add New invoice' }}
                            </h1>
                        </div>
                        <div class="form-container-content">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-8">
                                    {{-- Hiển thị thông báo lỗi --}}
                                    @if(session('messageError'))
                                        <div class="alert alert-danger">
                                            {{ session('messageError') }}
                                        </div>
                                    @endif

                                    @if($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form 
                                        action="{{ isset($invoice->id) ? route('invoices.update', $invoice->id) : route('invoices.store') }}" 
                                        method="POST" id="form-invoice">
                                        @csrf
                                        @if(isset($invoice->id))
                                            @method('PUT')
                                        @endif

                                        {{-- Username --}}
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <select class="form-select @error('username') is-invalid @enderror" id="username" name="username" required>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ old('username', $invoice->payment->booking->user->id ?? '') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->username }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('username')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Film --}}
                                        <div class="mb-3">
                                            <label for="film" class="form-label">Film</label>
                                            <select class="form-select @error('film') is-invalid @enderror" id="film" name="film" required>
                                                @foreach ($films as $film)
                                                    <option value="{{ $film->id }}"
                                                        {{ old('film', $invoice->payment->booking->showtime->film->id ?? '') == $film->id ? 'selected' : '' }}>
                                                        {{ $film->film_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('film')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Start Time --}}
                                        <div class="mb-3">
                                            <label for="start_time" class="form-label">Start Time</label>
                                            <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                                id="start_time" name="start_time"
                                                value="{{ old('start_time', $invoice->payment->booking->showtime->start_time ?? '') }}" required>
                                            @error('start_time')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Day --}}
                                        <div class="mb-3">
                                            <label for="day" class="form-label">Day</label>
                                            <input type="date" class="form-control @error('day') is-invalid @enderror" id="day"
                                                name="day"
                                                value="{{ old('day', isset($invoice->payment->booking->showtime->day) ? \Carbon\Carbon::parse($invoice->payment->booking->showtime->day)->format('Y-m-d') : '') }}" required>
                                            @error('day')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        {{-- Room --}}
                                        <div class="mb-3">
                                            <label for="room" class="form-label">Room</label>
                                            <select class="form-select @error('room') is-invalid @enderror" id="room" name="room" required>
                                                @foreach ($rooms as $room)
                                                    <option value="{{ $room->id }}"
                                                        {{ old('room', $invoice->payment->booking->showtime->room->id ?? '') == $room->id ? 'selected' : '' }}>
                                                        {{ $room->room_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('room')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Seats --}}
                                        <div class="mb-3">
                                            <label for="seat_count" class="form-label">How many seats? (1-3)</label>
                                            <input 
                                                type="number" 
                                                class="form-control" 
                                                id="seat_count" 
                                                name="seat_count" 
                                                min="1" 
                                                max="3"
                                                value="{{ old('seat_count', isset($invoice->payment->booking->seats) ? $invoice->payment->booking->seats->count() : '') }}" 
                                                readonly>
                                            <div id="seat-count-error" class="text-danger" style="display: none;">You can only choose between 1 and 3 seats!</div>
                                        </div>

                                        {{-- Total Amount --}}
                                        <div class="mb-3">
                                            <label for="total_amount" class="form-label">Total Amount</label>
                                            <input type="hidden" id="total_amount" name="total_amount"
                                                value="{{ old('total_amount', $invoice->total_amount ?? 0) }}" required>
                                            <input type="text" id="total_amount_display" class="form-control"
                                                value="{{ isset($invoice->id) ? number_format($invoice->total_amount) : number_format(old('total_amount', 0)) }}" readonly>
                                        </div>

                                        {{-- Transaction ID --}}
                                        <div class="mb-3">
                                            <label for="transaction_id" class="form-label">Transaction ID</label>
                                            <input type="text" class="form-control @error('transaction_id') is-invalid @enderror"
                                                id="transaction_id" name="transaction_id"
                                                value="{{ old('transaction_id', $invoice->payment->transaction_id ?? '') }}" readonly>
                                            @error('transaction_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Payment Method --}}
                                        <div class="mb-3">
                                            <label for="payment_method" class="form-label">Payment Method</label>
                                            <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                                <option value="Credit Card"
                                                    {{ old('payment_method', $invoice->payment->payment_method ?? '') == 'Credit Card' ? 'selected' : '' }}>
                                                    Credit Card
                                                </option>
                                                <option value="PayPal"
                                                    {{ old('payment_method', $invoice->payment->payment_method ?? '') == 'PayPal' ? 'selected' : '' }}>
                                                    PayPal
                                                </option>
                                                <option value="Cash"
                                                    {{ old('payment_method', $invoice->payment->payment_method ?? '') == 'Cash' ? 'selected' : '' }}>
                                                    Cash
                                                </option>
                                            </select>
                                            @error('payment_method')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Payment Status --}}
                                        <div class="mb-3">
                                            <label for="payment_status" class="form-label">Payment Status</label>
                                            <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                                                <option value="Completed"
                                                    {{ old('payment_status', $invoice->payment->payment_status ?? '') == 'Completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                                <option value="Pending"
                                                    {{ old('payment_status', $invoice->payment->payment_status ?? '') == 'Pending' ? 'selected' : '' }}>
                                                    Pending
                                                </option>
                                                <option value="Failed"
                                                    {{ old('payment_status', $invoice->payment->payment_status ?? '') == 'Failed' ? 'selected' : '' }}>
                                                    Failed
                                                </option>
                                            </select>
                                            @error('payment_status')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        
                                        {{-- Payment ID --}}
                                        <div class="mb-3">
                                            <label for="payment_id" class="form-label">Payment ID</label>
                                            <select class="form-select @error('payment_id') is-invalid @enderror" id="payment_id" name="payment_id" required>
                                                <option value="">Choose Payment ID</option>
                                                @foreach ($payments as $payment)
                                                    <option value="{{ $payment->id }}" {{ old('payment_id', $invoice->payment_id ?? '') == $payment->id ? 'selected' : '' }}>
                                                        {{ $payment->id }} - {{ $payment->transaction_id }} - {{ number_format($payment->amount) }} VND
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('payment_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        
                
                                        {{-- Submit Button --}}
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                
                
            </div>
        </div>

        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- jQuery -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <!-- Bootstrap JS -->
        <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- Custom JS -->
        <script src="{{ asset('assets/js/index.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
    const seatCountInput = document.getElementById('seat_count');
    const seatSelectContainer = document.getElementById('seat-select-container');
    const totalAmountInput = document.getElementById('total_amount');
    const totalAmountDisplay = document.getElementById('total_amount_display'); // Hiển thị số đã format
    const availableSeats = @json($seats); // Assuming $seats is passed from the backend.
    const seatPrice = 100000; // Giá mỗi ghế

    // Hàm tính tổng tiền
    function calculateTotalAmount() {
        const seatDropdowns = seatSelectContainer.querySelectorAll('select');
        let selectedCount = 0;

        // Đếm số ghế được chọn
        seatDropdowns.forEach(dropdown => {
            if (dropdown.value) selectedCount++;
        });

        // Tính tổng tiền
        const totalAmount = selectedCount * seatPrice;

        // Cập nhật giá trị input ẩn cho backend
        totalAmountInput.value = totalAmount;

        // Cập nhật giá trị hiển thị (format cho người dùng)
        if (totalAmountDisplay) {
            totalAmountDisplay.value = totalAmount.toLocaleString('en-US'); // Format thành chuỗi hiển thị
        }
    }

    // Lắng nghe sự kiện thay đổi số lượng ghế
    seatCountInput.addEventListener('input', function () {
        const seatCount = parseInt(seatCountInput.value);

        // Kiểm tra hợp lệ
        if (isNaN(seatCount) || seatCount < 1 || seatCount > 3) {
            document.getElementById('seat-count-error').style.display = 'block';
            seatSelectContainer.innerHTML = ''; // Xóa các dropdown cũ
            totalAmountInput.value = '0'; // Reset tổng tiền
            if (totalAmountDisplay) {
                totalAmountDisplay.value = '0';
            }
            return;
        } else {
            document.getElementById('seat-count-error').style.display = 'none';
        }

        // Xóa các dropdown cũ
        seatSelectContainer.innerHTML = '';

        // Tạo các dropdown mới
        for (let i = 0; i < seatCount; i++) {
            const select = document.createElement('select');
            select.name = `seats[${i}]`;
            select.classList.add('form-select', 'mb-2');

            // Thêm tùy chọn mặc định
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Choose a seat';
            select.appendChild(defaultOption);

            // Thêm các ghế có sẵn
            availableSeats.forEach(seat => {
                const option = document.createElement('option');
                option.value = seat.id;
                option.textContent = seat.seat_number;
                select.appendChild(option);
            });

            // Lắng nghe sự kiện thay đổi trên từng dropdown để tính lại tổng tiền
            select.addEventListener('change', calculateTotalAmount);

            seatSelectContainer.appendChild(select);
        }

        // Tính lại tổng tiền
        calculateTotalAmount();
    });

    // Tính tổng tiền ngay khi trang được tải (trường hợp chỉnh sửa)
    calculateTotalAmount();
});

        </script>
    </body>

</html>
