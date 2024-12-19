<!-- resources/views/seats/index.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Seats</title>
    <link href="{{ asset('assets/images/icon.png') }}" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.css">
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/list.css') }}">
    <style>
        .seat {
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            cursor: pointer;
            width: 50px;
            height: 50px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .seat:hover {
            background-color: #007bff;
            color: #fff;
        }
        .seat-row {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            @include('fragments.sidebar', ['key' => 'room', 'subkey' => 'room_all'])
            <div class="d-flex flex-column wrapper">
                @include('fragments.header')
                <div class="content container mt-4">
                    <h1 class="title mb-4">Manage Seats for Room: {{ $room->room_name }}</h1>
                    {{-- <a href="{{ route('seats.create', $room->id) }}" class="btn btn-primary mb-4">
                        <i class="fas fa-plus"></i> Add New Seat
                    </a> --}}

                    <!-- Sơ đồ ghế -->
                    <div class="seat-layout mb-5">
                        @php
                            $capacity = $room->capacity; // Sức chứa phòng
                            $seatsPerRow = 10; // Số ghế mỗi hàng
                            $rows = ceil($capacity / $seatsPerRow); // Tính số hàng
                            $seatNumber = 1; // Số ghế bắt đầu
                            $currentRowLetter = 'A'; // Bắt đầu từ hàng A
                        @endphp
                    
                        @for ($i = 0; $i < $rows; $i++) <!-- Vòng lặp theo hàng -->
                            <div class="seat-row">
                                @for ($j = 1; $j <= $seatsPerRow; $j++) <!-- Vòng lặp theo ghế trong hàng -->
                                    @if ($seatNumber <= $capacity)
                                        @php
                                            $seatLabel = $currentRowLetter . $j; // Định dạng số ghế (A1, A2,...)
                                        @endphp
                                        <div class="seat" title="Seat {{ $seatLabel }}">
                                            {{ $seatLabel }}
                                        </div>
                                        @php $seatNumber++; @endphp
                                    @endif
                                @endfor
                            </div>
                            @php $currentRowLetter = chr(ord($currentRowLetter) + 1); @endphp <!-- Chuyển sang hàng tiếp theo -->
                        @endfor
                    </div>
                    

                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.4/dist/sweetalert2.all.min.js"></script>
    
    <script>
        $(document).ready(function() {
            let messageError = "{{ session('messageError') }}";
            let messageSuccess = "{{ session('messageSuccess') }}";

            if (messageSuccess) {
                Swal.fire({
                    title: '',
                    text: messageSuccess,
                    icon: 'success',
                    confirmButtonColor: '#3085d6'
                });
            }

            if (messageError) {
                Swal.fire({
                    title: '',
                    text: messageError,
                    icon: 'error'
                });
            }
        });
    </script>
</body>
</html>
