<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Admin</title>
    <link th:href="@{/images/icon.png}" rel="icon" type = "image/x-icon">

    <!-- Bootstrap core -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}"/>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
</head>
<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            @include('fragments.sidebar', ['key' => 'dashboard', 'subkey' => ''])
            <div class="d-flex flex-column wrapper">
                @include('fragments.header')
                <div class="content">
                    <h1 class="title">Dashboard</h1>
                    <h2 class="sub-title">Welcome <span style="color: #4c78dd;text-transform: capitalize">Name</span>, everything looks great.</h2>
                    <section class="statistical row">
                        <div class="col statistical-item d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column justify-content-center">
                                {{-- <div class="number">{{ $countOrderByWeek }}</div> --}}
                                <div class="text-description">Đơn hàng chờ xác nhận</div>
                            </div>
                            <div class="statistical-icon">
                                <i class="far fa-gem fs-3 text-primary"></i>
                            </div>
                        </div>
                        <div class="col statistical-item d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column justify-content-center">
                                {{-- <div class="number">{{ $countUserByWeek }}</div> --}}
                                <div class="text-description">Khách hàng mới</div>
                            </div>
                            <div class="statistical-icon">
                                <i class="far fa-user-circle fs-3 text-primary"></i>
                            </div>
                        </div>
                        <div class="col statistical-item d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column justify-content-center">
                                <div class="number">1</div>
                                <div class="text-description">Đánh giá mới</div>
                            </div>
                            <div class="statistical-icon">
                                <i class="far fa-paper-plane fs-3 text-primary"></i>
                            </div>
                        </div>
                        <div class="col statistical-item d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column justify-content-center">
                                <div class="number d-flex">
                                    <!-- Hàm format_currency sẽ được làm sau -->
                                    {{-- <strong>{{ number_format($totalOrderByWeek) }}₫</strong> --}}
                                </div>
                                <div class="text-description">Tổng doanh thu theo tuần</div>
                            </div>
                            <div class="statistical-icon">
                                <i class="fa fa-chart-bar fs-3 text-primary"></i>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="{{ asset('assets/js/index.js') }}"></script>
</body>
</html>
