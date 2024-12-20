<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Invoices</title>
    <link href="{{ asset('assets/images/icon.png') }}" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.css">
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/list.css') }}">
</head>
<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            @include('fragments.sidebar', ['key' => 'room', 'subkey' => 'room_all'])
            <div class="d-flex flex-column wrapper">
                @include('fragments.header')
                <div class="content">
                    <div class="d-flex justify-content-between align-items-center mt-3 mb-5">
                        <h1 class="title">Invoices</h1>
                    </div>
                    <section class="list-table">
                        <div class="list-table-header d-flex align-items-center justify-content-between">
                            @include('fragments.search', ['entityName' => 'invoices'])
                        </div>
                        <div class="list-table-content">
                            <div class="table-responsive">
                                <table class="table table-borderless table-striped table-vcenter">
                                    <thead>
                                        <tr>
                                            <th class="d-none d-sm-table-cell text-center">Invoice ID</th>
                                            <th class="d-none d-sm-table-cell text-center">Booking ID</th>
                                            <th class="d-none d-sm-table-cell text-center">Showtime ID</th>
                                            <th class="d-none d-sm-table-cell text-center">Film</th>
                                            <th class="d-none d-sm-table-cell text-center">User</th>
                                            <th class="d-none d-sm-table-cell text-center">Showtime</th>
                                            <th class="d-none d-sm-table-cell text-center">Seats</th>
                                            <th class="d-none d-sm-table-cell text-center">Total Amount</th>
                                            <th class="text-center" style="width: 100px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($invoices as $invoice)
                                            <tr>
                                                <td class="text-center fs-sm"><strong>{{ $invoice->id }}</strong></td>
                                                <td class="text-center fs-sm"><strong>{{ $invoice->payment->booking->id }}</strong></td>
                                                <td class="text-center fs-sm"><strong>{{ $invoice->payment->booking->showtime->id }}</strong></td>
                                                <td class="d-none d-md-table-cell fs-sm"><strong>{{ $invoice->payment->booking->showtime->film->film_name }}</strong></td>
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    {{ $invoice->payment->booking->user->full_name }}
                                                </td>

                                                <td class="d-none d-md-table-cell fs-sm">
                                                    {{ $invoice->payment->booking->showtime->start_time }} {{ $invoice->payment->booking->showtime->day }}
                                                </td>
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    @foreach ($invoice->payment->booking->bookingSeats as $seat)
                                                        {{ $seat->seat->seat_number }},
                                                    @endforeach
                                                </td>
                                                <td class="text-center fs-sm">{{ number_format($invoice->total_amount, 0) }} VND</td>
                                                <td class="text-center fs-sm" style="width: 100px">
                                                    <a href="#" class="btn btn-sm btn-alt-secondary" title="View Details">
                                                        <i class="fas fa-eye text-primary"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="empty d-flex flex-column align-items-center">
                                                        <div class="empty-image d-flex justify-content-center align-items-center mb-3">
                                                            <img src="{{ asset('assets/images/empty-icon.svg') }}" alt="No invoices" style="height: 200px;">
                                                        </div>
                                                        <p>No invoices found</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                    <!-- Laravel Pagination Links -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $invoices->links() }}
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
