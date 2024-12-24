<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistic</title>
    <link href="{{ asset('assets/images/icon.png') }}" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.css">
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/list.css') }}">
    <!-- Add Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            @include('fragments.sidebar', ['key' => 'statistics', 'subkey' => 'statistics_all'])
            <div class="d-flex flex-column wrapper">
                @include('fragments.header')
                <div class="container mt-4">
                    <h2 class="mb-4">Invoice Statistics</h2>
                
                    <!-- Total Amount Per Week -->
                    <div class="card mb-4">
                        <div class="card-header">
                            Total Amount Per Week
                        </div>
                        <div class="card-body">
                            <canvas id="totalAmountChart"></canvas>
                            <table class="table table-bordered mt-4">
                                <thead>
                                    <tr>
                                        <th>Week</th>
                                        <th>Total Amount (VND)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($totalAmountPerWeek as $data)
                                        <tr>
                                            <td>
                                                Month {{ \Carbon\Carbon::create()->month($data->month)->isoFormat('MMMM') }} - Week {{ $data->week_num }} Year {{ $data->year }}
                                            </td>
                                            <td>{{ number_format($data->total_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                
                    <!-- Seats Booked Per Week -->
                    <div class="card">
                        <div class="card-header">
                            Seats Booked Per Week
                        </div>
                        <div class="card-body">
                            <canvas id="seatsBookedChart"></canvas>
                            <table class="table table-bordered mt-4">
                                <thead>
                                    <tr>
                                        <th>Week</th>
                                        <th>Seats Booked</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($seatsBookedPerWeek as $data)
                                        <tr>
                                            <td>
                                                Month {{ \Carbon\Carbon::create()->month($data->month)->isoFormat('MMMM') }} - Week {{ $data->week_num }} Year {{ $data->year }}
                                            </td>
                                            <td>{{ $data->seats_booked }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

    <!-- Script to Draw Charts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data for Total Amount
            const totalAmountLabels = @json($totalAmountPerWeek->map(function($data) {
                return 'Month ' . \Carbon\Carbon::create()->month($data->month)->isoFormat('MMMM') . ' - Week ' . $data->week_num . ' Year ' . $data->year;
            }));
            const totalAmountData = @json($totalAmountPerWeek->pluck('total_amount'));

            const ctx1 = document.getElementById('totalAmountChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: totalAmountLabels,
                    datasets: [{
                        label: 'Total Amount (VND)',
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

            // Data for Seats Booked
            const seatsBookedLabels = @json($seatsBookedPerWeek->map(function($data) {
                return 'Month ' . \Carbon\Carbon::create()->month($data->month)->isoFormat('MMMM') . ' - Week ' . $data->week_num . ' Year ' . $data->year;
            }));
            const seatsBookedData = @json($seatsBookedPerWeek->pluck('seats_booked'));

            const ctx2 = document.getElementById('seatsBookedChart').getContext('2d');
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: seatsBookedLabels,
                    datasets: [{
                        label: 'Seats Booked',
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
        });
    </script>
</body>
</html>
