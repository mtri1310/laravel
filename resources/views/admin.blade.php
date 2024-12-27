<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Admin</title>
    <link href="{{ asset('assets/images/icon.png') }}" rel="icon" type = "image/x-icon">

    <!-- Bootstrap core -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.css" rel="stylesheet"
        type="text/css" />

    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <!-- Google Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">

</head>

<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            @include('fragments.sidebar', ['key' => 'dashboard', 'subkey' => ''])
            <div class="d-flex flex-column wrapper">
                @include('fragments.header')
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <!-- Small boxes (Stat box) -->
                        <div class="row">
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ $paymentsPending }}</h3>

                                        <p>Payments Pending</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ $seatsBooked }}</h3>

                                        <p>Seats Booked</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ $usersRegistered }}</h3>

                                        <p>User Registrations</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-person-add"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>{{ number_format($latestTotalAmount, 0) }} VNĐ</h3>

                                        <p>Total amount</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-pie-graph"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- ./col -->
                        </div>
                        <!-- /.row -->
                        <!-- Main row -->
                        <div class="row">
                            <!-- Left col -->
                            <section class="col-lg-7 connectedSortable">
                                <!-- Donut Chart - Total Revenue Per Month -->
                                <div class="card card-danger mb-4">
                                    <div class="card-header">
                                        <h3 class="card-title">Total Revenue Per Month</h3>
                            
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- Seats Booked Per Month -->
                                <div class="card card-warning mb-4">
                                    <div class="card-header">
                                        <h3 class="card-title">Seats Booked Per Month</h3>
                            
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <canvas id="seatsBookedChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>

                                
                            </section>
                            <!-- /.Left col -->
                            <!-- Right col (We are only adding the ID to make the widgets sortable)-->
                            <section class="col-lg-5 connectedSortable">
                                <!-- Payment Status Completed and Failed Per Month - Line Chart -->
                                <div class="card card-primary mb-4">
                                    <div class="card-header">
                                        <h3 class="card-title">Payment Status Completed and Failed Per Month</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="completedFailedPaymentsChart" aria-label="Completed and Failed Payments Per Month" role="img" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                
                                <!-- Calendar -->
                                <div class="card bg-gradient-success">
                                    <div class="card-header border-0">
                    
                                    <h3 class="card-title">
                                        <i class="far fa-calendar-alt"></i>
                                        Calendar
                                    </h3>
                                    <!-- tools card -->
                                    <div class="card-tools">
                                        <!-- button with a dropdown -->
                                        <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                                            <i class="fas fa-bars"></i>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                            <a href="#" class="dropdown-item">Add new event</a>
                                            <a href="#" class="dropdown-item">Clear events</a>
                                            <div class="dropdown-divider"></div>
                                            <a href="#" class="dropdown-item">View calendar</a>
                                        </div>
                                        </div>
                                        <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <!-- /. tools -->
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body pt-0">
                                    <!--The calendar -->
                                    <div id="calendar" style="width: 100%"></div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </section>
                            <!-- right col -->
                            
                        </div>
                        <!-- /.row (main row) -->
                    </div><!-- /.container-fluid -->
                </section>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>

    <!-- Sparkline -->
    <script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>

    <!-- daterangepicker -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // The Calender
            $('#calendar').datetimepicker({
                format: 'L',
                inline: true
            })
            // Data for Donut Chart - Total Revenue Per Month
            const totalAmountLabels = @json($totalAmountPerMonth->map(function($data) {
                return $data->month_name . ' ' . $data->year;
            }));
            const totalAmountData = @json($totalAmountPerMonth->pluck('total_amount'));

            // Generate distinct colors for each segment
            const backgroundColors = [
                '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de',
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
            ];

            const donutChartCanvas = document.getElementById('donutChart').getContext('2d');
            const donutData = {
                labels: totalAmountLabels,
                datasets: [{
                    data: totalAmountData,
                    backgroundColor: backgroundColors.slice(0, totalAmountData.length),
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            };
            const donutOptions = {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed);
                                }
                                return label;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    title: {
                        display: false,
                        text: 'Total Revenue Per Month'
                    }
                }
            };
            new Chart(donutChartCanvas, {
                type: 'doughnut',
                data: donutData,
                options: donutOptions
            });
            // Data for Seats Booked Per Month Chart
            const seatsBookedLabels = @json($seatsBookedPerMonth->map(function($data) {
                return ' ' . $data->month_name . '  ' . $data->year;
            }));
            const seatsBookedData = @json($seatsBookedPerMonth->pluck('seats_booked'));

            const ctx2 = document.getElementById('seatsBookedChart').getContext('2d');
            new Chart(ctx2, {
                type: 'bar', // Chart type: bar
                data: {
                    labels: seatsBookedLabels,
                    datasets: [{
                        label: 'Seats Booked',
                        data: seatsBookedData,
                        backgroundColor: 'rgba(255, 206, 86, 0.6)', // Column background color
                        borderColor: 'rgba(255, 206, 86, 1)', // Column border color
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // No currency formatting needed
                                callback: function(value) {
                                    return value;
                                }
                            },
                            title: {
                                display: true,
                                text: 'Number of Seats'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Month - Year'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y;
                                    }
                                    return label;
                                }
                            }
                        },
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: false,
                            text: 'Seats Booked Per Month'
                        }
                    }
                }
            });
            // Dữ liệu cho biểu đồ Pending Payments Theo Tháng
            

            const completedFailedPaymentsLabels = @json($completedAndFailedPaymentsPerMonth->map(function($data) {
                return ' ' . $data->month_name . '  ' . $data->year;
            }));

            const completedPaymentsData = @json($completedAndFailedPaymentsPerMonth->pluck('completed_count'));
            const failedPaymentsData = @json($completedAndFailedPaymentsPerMonth->pluck('failed_count'));
            const ctx3 = document.getElementById('completedFailedPaymentsChart').getContext('2d');
            new Chart(ctx3, {
                type: 'line',
                data: {
                    labels: completedFailedPaymentsLabels, // e.g., ['November 2023', 'December 2023', ...]
                    datasets: [
                    {
                        label: 'Completed Payments',
                        data: completedPaymentsData, // e.g., [50, 75, 100, ...]
                        fill: false,
                        borderColor: 'rgba(75, 192, 192, 1)', // Line color for Completed
                        backgroundColor: 'rgba(75, 192, 192, 0.6)', // Point background color for Completed
                        borderWidth: 2,
                        tension: 0.1, // Smoothness of the line
                        pointRadius: 5, // Size of the points
                        pointHoverRadius: 7,
                    },
                    {
                        label: 'Failed Payments',
                        data: failedPaymentsData, // e.g., [5, 10, 2, ...]
                        fill: false,
                        borderColor: 'rgba(255, 99, 132, 1)', // Line color for Failed
                        backgroundColor: 'rgba(255, 99, 132, 0.6)', // Point background color for Failed
                        borderWidth: 2,
                        tension: 0.1,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    }
                ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value;
                                }
                            },
                            title: {
                                display: true,
                                text: 'Number of Payments'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Month - Year'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y;
                                    }
                                    return label;
                                }
                            }
                        },
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: false
                        }
                    }
                }
            });
        });

            
        
    </script>

</body>

</html>