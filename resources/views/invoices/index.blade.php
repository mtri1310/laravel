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
                        <h1 class="title">Invoice </h1>
                        
                    </div>
                    <section class="list-table">
                        <div class="mb-4" style="margin-top: 50px;"  >
                            <div class="row g-3">
                                <!-- Biểu mẫu Tìm Kiếm (Bên Trái) -->
                                <div class="col-md-6" >
                                    @include('fragments.search', ['entityName' => 'invoices'])
                                </div>
                
                                <!-- Biểu mẫu Lọc Theo Ngày (Bên Phải) -->
                                <div class="col-md-6">
                                    <form method="GET" action="{{ route('invoices.index') }}">
                                        <!-- Hiển thị thông báo lỗi nếu có -->
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                
                                        <div class="row g-3 align-items-end justify-content-end">
                                            <!-- Trường Từ Ngày -->
                                            <div class="col-md-4">
                                                <label for="date_from" class="form-label">From</label>
                                                <input type="date" name="date_from" id="date_from" class="form-control @error('date_from') is-invalid @enderror" 
                                                    value="{{ request('date_from') }}">
                                                @error('date_from')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                
                                            <!-- Trường Đến Ngày -->
                                            <div class="col-md-4">
                                                <label for="date_to" class="form-label">To</label>
                                                <input type="date" name="date_to" id="date_to" class="form-control @error('date_to') is-invalid @enderror" 
                                                    value="{{ request('date_to') }}">
                                                @error('date_to')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                
                                            <!-- Nút Lọc và Reset -->
                                            <div class="col-md-4 d-flex">
                                                <button type="submit" class="btn btn-primary me-2 w-100" style="margin-right: 10px">Filter</button>
                                                <a href="{{ route('invoices.index') }}" class="btn btn-secondary w-100" style="margin-right: 10px">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-table-content">
                            <div class="table-responsive">
                                <table class="table table-borderless table-striped table-vcenter">
                                    <thead>
                                        <tr>
                                            <th class="d-none d-sm-table-cell text-center">Invoice Number</th>
                                            <th class="d-none d-sm-table-cell text-center">Day Create Invoice</th>
                                            <th class="d-none d-sm-table-cell text-center">Username</th>
                                            <th class="d-none d-sm-table-cell text-center">Film</th>
                                            <th class="d-none d-sm-table-cell text-center">Start time</th>
                                            <th class="d-none d-sm-table-cell text-center">Day</th>
                                            <th class="d-none d-sm-table-cell text-center">Room</th>

                                            <th class="d-none d-sm-table-cell text-center">Seats</th>
                                            <th class="d-none d-sm-table-cell text-center">Total Amount</th>
                                            <th class="d-none d-sm-table-cell text-center">Transaction ID</th>
                                            <th class="d-none d-sm-table-cell text-center">Payment Method</th>
                                            <th class="d-none d-sm-table-cell text-center">Payment Status</th>
                                            <th class="text-center" style="width: 100px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($invoices as $invoice)
                                            <tr>
                                                <td class="d-none d-md-table-cell fs-sm text-center">
                                                    {{ $invoice->invoice_number }}
                                                </td>
                                                <td class="d-none d-md-table-cell fs-sm text-center">
                                                    {{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y H:i') }}
                                                </td>
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    {{ $invoice->payment->booking->user->username }}
                                                </td>
                                                <td class="d-none d-md-table-cell fs-sm"><strong>{{ $invoice->payment->booking->showtime->film->film_name }}</strong></td>

                                                <!-- Định dạng giờ -->
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    {{ \Carbon\Carbon::parse($invoice->payment->booking->showtime->start_time)->format('H:i') }}
                                                </td>

                                                <!-- Định dạng ngày -->
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    {{ \Carbon\Carbon::parse($invoice->payment->booking->showtime->day)->format('d/m/Y') }}
                                                </td>
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    {{ $invoice->payment->booking->showtime->room->room_name }}
                                                </td>
                                                <!-- Seat Numbers -->
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    @if($invoice->payment->booking->seats->isEmpty())
                                                        <span class="badge bg-secondary">No Seats</span>
                                                    @else
                                                        @foreach ($invoice->payment->booking->seats as $seat)
                                                            <span class="badge bg-primary">{{ $seat->seat_number }}</span>
                                                        @endforeach
                                                    @endif
                                                </td>

                                                <td class="text-center fs-sm">{{ number_format($invoice->total_amount, 0) }} VND</td>
                                                <td class="text-center fs-sm">
                                                    <strong>{{ $invoice->payment->transaction_id }}</strong>
                                                </td>
                                                <td class="text-center fs-sm">
                                                    <strong>{{ $invoice->payment->payment_method }}</strong>
                                                </td>
                                                <td class="text-center fs-sm">
                                                    @if ($invoice->payment->payment_status === 1)
                                                        <span class="badge bg-success">Pending</span>
                                                    @elseif ($invoice->payment->payment_status === 2)
                                                        <span class="badge bg-secondary">Completed</span>
                                                    @elseif ($invoice->payment->payment_status === 3)
                                                        <span class="badge bg-danger">Failed</span>
                                                    @else
                                                        <span class="badge bg-info">{{ $invoice->payment->payment_status }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center fs-sm" style="width: 100px">
                                                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm btn-alt-secondary" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this film?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-alt-danger" title="Delete">
                                                            <i class="fa fa-fw fa-times text-danger"></i>
                                                        </button>
                                                    </form>
                                                    
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="13" class="text-center">
                                                    <div class="empty d-flex flex-column align-items-center">
                                                        <div class="empty-image d-flex justify-content-center align-items-center mb-3">
                                                            <img src="{{ asset('assets/images/empty-icon.svg') }}" alt="No Films" style="height: 200px;">
                                                        </div>
                                                        <span>No Invoices Found</span>
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
