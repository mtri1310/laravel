<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Showtimes</title>
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
                        <h1 class="title">Showtimes</h1>
                        <a href="{{ route('showtimes.create') }}">
                            <button class="btn btn-primary d-flex align-items-center">
                                <i class="fas fa-plus mr-2"></i>
                                <span>Add New Showtime</span>
                            </button>
                        </a>
                    </div>
                    <section class="list-table">
                        <div class="list-table-header d-flex align-items-center justify-content-between">
                            @include('fragments.search', ['entityName' => 'showtimes'])
                        </div>
                        <div class="list-table-content">
                            <div class="table-responsive">
                                <table class="table table-borderless table-striped table-vcenter">
                                    <thead>
                                        <tr>
                                            <th class="d-none d-sm-table-cell text-center">ID</th>
                                            <th class="d-none d-sm-table-cell text-center">Film Name</th>
                                            <th class="d-none d-sm-table-cell text-center">Room Name</th>
                                            <th class="d-none d-sm-table-cell text-center">Start Time</th>
                                            <th class="d-none d-sm-table-cell text-center">Day</th>
                                            <th class="text-center" style="width: 100px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($showtimes as $showtime)
                                            <tr>
                                                <td class="text-center fs-sm"><strong>{{ $showtime->id }}</strong></td>
                                                <td class="d-none d-md-table-cell fs-sm"><strong>{{ $showtime->film->film_name }}</strong></td>
                                                <td class="d-none d-md-table-cell fs-sm">{{ $showtime->room->room_name }}</td>
                                                {{-- <td class="d-none d-md-table-cell fs-sm">{{ $showtime->start_time }}</td> --}}
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}
                                                </td>
                                                <td class="d-none d-md-table-cell fs-sm">{{ $showtime->day ? $showtime->day->format('d/m/Y') : 'N/A' }}</td>
                                                <td class="text-center fs-sm" style="width: 100px">
                                                    <a href="{{ route('showtimes.edit', $showtime->id) }}" class="btn btn-sm btn-alt-secondary" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <form action="{{ route('showtimes.destroy', $showtime->id) }}" method="POST" class="d-inline form-delete">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-alt-danger btn-delete" data-showtime-id="{{ $showtime->id }}" title="Delete">
                                                            <i class="fa fa-fw fa-times text-danger"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="empty d-flex flex-column align-items-center">
                                                        <div class="empty-image d-flex justify-content-center align-items-center mb-3">
                                                            <img src="{{ asset('assets/images/empty-icon.svg') }}" alt="No showtimes" style="height: 200px;">
                                                        </div>
                                                        <a href="{{ route('showtimes.create') }}">
                                                            <button class="btn btn-primary">
                                                                <span>Create Now</span>
                                                            </button>
                                                        </a>
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
                        {{ $showtimes->links() }}
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
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                let showtimeId = $(this).data('showtime-id');
                let form = $(this).closest('form');
    
                // Gọi AJAX để kiểm tra dữ liệu liên quan
                $.ajax({
                    url: `/showtimes/${showtimeId}/dependencies`,
                    method: 'GET',
                    success: function(response) {
                        if (response.hasDependencies) {
                            // Tạo thông báo cảnh báo với tùy chọn Yes/No
                            let dependencyDetails = response.dependencies.join(', ');
    
                            Swal.fire({
                                title: 'Cảnh báo!',
                                html: `Showtime này có dữ liệu liên quan trong các bảng: <strong>${dependencyDetails}</strong>. Bạn có chắc chắn muốn xóa showtime và tất cả các dữ liệu liên quan?`,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, delete it!',
                                cancelButtonText: 'No, cancel',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Thêm input xác nhận xóa dữ liệu liên quan
                                    $('<input>').attr({
                                        type: 'hidden',
                                        name: 'confirm',
                                        value: 'yes'
                                    }).appendTo(form);
    
                                    // Gửi form để xóa
                                    form.submit();
                                } else if (result.dismiss === Swal.DismissReason.cancel) {
                                    Swal.fire(
                                        'Hủy bỏ',
                                        'Showtime không bị xóa.',
                                        'info'
                                    );
                                }
                            });
                        } else {
                            // Nếu không có dữ liệu liên quan, xác nhận xóa thông thường
                            Swal.fire({
                                title: 'Bạn có chắc chắn?',
                                text: "Bạn sẽ không thể khôi phục lại showtime này!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, delete it!',
                                cancelButtonText: 'No, cancel',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit();
                                } else if (result.dismiss === Swal.DismissReason.cancel) {
                                    Swal.fire(
                                        'Hủy bỏ',
                                        'Showtime không bị xóa.',
                                        'info'
                                    );
                                }
                            });
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Lỗi!',
                            'Đã xảy ra lỗi khi kiểm tra dữ liệu liên quan.',
                            'error'
                        );
                    }
                });
            });
    
            // Xử lý các thông báo thành công và lỗi như trước
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
