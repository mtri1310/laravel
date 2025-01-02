<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê</title>
    <link href="{{ asset('assets/images/icon.png') }}" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.css">
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/list.css') }}">
    <!-- Thêm Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            @include('fragments.sidebar', ['key' => 'statistics', 'subkey' => 'statistics_all'])
            <div class="d-flex flex-column wrapper">
                @include('fragments.header')
                <div class="container mt-4">
                    <h2 class="mb-4">Thống kê</h2>

                    <!-- Tổng Doanh Thu Theo Tuần -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Tổng Doanh Thu Theo Tuần</span>
                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTotalAmount" aria-expanded="true" aria-controls="collapseTotalAmount">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div id="collapseTotalAmount" class="collapse show">
                            <div class="card-body">
                                <canvas id="totalAmountChart"></canvas>
                                <table class="table table-bordered mt-4">
                                    <thead>
                                        <tr>
                                            <th>Tuần</th>
                                            <th>Tổng Doanh Thu (VND)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($totalAmountPerWeek as $data)
                                            <tr>
                                                <td>
                                                    Tháng {{ $data->month_name }} - Tuần {{ $data->week_num }} Năm {{ $data->year }}
                                                </td>
                                                <td>{{ number_format($data->total_amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Số Ghế Đặt Theo Tuần -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Số Ghế Đặt Theo Tuần</span>
                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeatsBooked" aria-expanded="true" aria-controls="collapseSeatsBooked">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div id="collapseSeatsBooked" class="collapse show">
                            <div class="card-body">
                                <canvas id="seatsBookedChart"></canvas>
                                <table class="table table-bordered mt-4">
                                    <thead>
                                        <tr>
                                            <th>Tuần</th>
                                            <th>Số Ghế Đặt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($seatsBookedPerWeek as $data)
                                            <tr>
                                                <td>
                                                    Tháng {{ $data->month_name }} - Tuần {{ $data->week_num }} Năm {{ $data->year }}
                                                </td>
                                                <td>{{ $data->seats_booked }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Biểu đồ Donut -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Biểu đồ Donut Tổng Doanh Thu Theo Tháng</span>
                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDonutChart" aria-expanded="true" aria-controls="collapseDonutChart">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div id="collapseDonutChart" class="collapse show">
                            <div class="card-body">
                                <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                <table class="table table-bordered mt-4">
                                    <thead>
                                        <tr>
                                            <th>Tháng</th>
                                            <th>Tổng Doanh Thu (VND)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($donutData as $data)
                                            <tr>
                                                <td>{{ $data->month_name }}</td>
                                                <td>{{ number_format($data->total_amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <!-- Thêm Popper.js nếu sử dụng Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
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

    <!-- Script để Vẽ Các Biểu đồ -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dữ liệu cho biểu đồ Tổng Doanh Thu
            const totalAmountLabels = @json($totalAmountPerWeek->map(function($data) {
                return 'Tháng ' . $data->month_name . ' - Tuần ' . $data->week_num . ' Năm ' . $data->year;
            }));
            const totalAmountData = @json($totalAmountPerWeek->pluck('total_amount'));

            const ctx1 = document.getElementById('totalAmountChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: totalAmountLabels,
                    datasets: [{
                        label: 'Tổng Doanh Thu (VND)',
                        data: totalAmountData,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Dữ liệu cho biểu đồ Số Ghế Đặt
            const seatsBookedLabels = @json($seatsBookedPerWeek->map(function($data) {
                return 'Tháng ' . $data->month_name . ' - Tuần ' . $data->week_num . ' Năm ' . $data->year;
            }));
            const seatsBookedData = @json($seatsBookedPerWeek->pluck('seats_booked'));

            const ctx2 = document.getElementById('seatsBookedChart').getContext('2d');
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: seatsBookedLabels,
                    datasets: [{
                        label: 'Số Ghế Đặt',
                        data: seatsBookedData,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        fill: false,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Dữ liệu cho biểu đồ Donut
            const donutLabels = @json($donutData->pluck('month_name'));
            const donutValues = @json($donutData->pluck('total_amount'));

            const ctx3 = document.getElementById('donutChart').getContext('2d');
            new Chart(ctx3, {
                type: 'doughnut',
                data: {
                    labels: donutLabels,
                    datasets: [{
                        data: donutValues,
                        backgroundColor: [
                            '#f56954',
                            '#00a65a',
                            '#f39c12',
                            '#00c0ef',
                            '#3c8dbc',
                            '#d2d6de',
                            '#8B008B',
                            '#FF1493',
                            '#00CED1',
                            '#FFD700',
                            '#32CD32',
                            '#FF4500'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Tổng Doanh Thu Theo Tháng'
                        }
                    }
                }
            });
        });
    </script>

    <!-- Script để Thay Đổi Icon Khi Đóng Mở Thẻ -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Hàm để thay đổi icon khi thẻ được mở hoặc đóng
            function toggleIcon(button) {
                const icon = button.querySelector('i');
                if (icon.classList.contains('fa-chevron-down')) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            }

            // Lấy tất cả các nút đóng mở
            const collapseButtons = document.querySelectorAll('[data-bs-toggle="collapse"]');

            collapseButtons.forEach(function(button) {
                const target = button.getAttribute('data-bs-target');
                const collapseElement = document.querySelector(target);

                // Nghe sự kiện 'shown.bs.collapse' (đã mở)
                collapseElement.addEventListener('shown.bs.collapse', function () {
                    toggleIcon(button);
                });

                // Nghe sự kiện 'hidden.bs.collapse' (đã đóng)
                collapseElement.addEventListener('hidden.bs.collapse', function () {
                    toggleIcon(button);
                });
            });
        });
    </script>
</body>
</html>
