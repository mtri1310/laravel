<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Admin</title>
    <link href="{{ asset('assets/images/icon.png') }}" rel="icon" type = "image/x-icon">

    <!-- Bootstrap core -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}"/>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/list.css') }}">
    
</head>

<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            @include('fragments.sidebar', ['key' => 'film', 'subkey' => 'film_all'])
            <div class="d-flex flex-column wrapper">
                @include('fragments.header')
                <div class="content">
                    <div class="d-flex justify-content-between align-items-center mt-3 mb-5">
                        <h1 class="title">Phim</h1>
                        <a href="{{ route('films.create') }}">
                            <button class="btn btn-primary d-flex align-items-center">
                                <i class="fas fa-plus" style="margin-right: 0.5rem"></i>
                                <span>Phim mới</span>
                            </button>
                        </a>

                    </div>
                    <section class="list-table">
                        <div class="list-table-header d-flex align-items-center justify-content-between">
                            @include('fragments.search', ['entityName' => 'film'])
                        </div>
                        <div class="list-table-content">
                            <div class="table-responsive">
                                <table class="table table-borderless table-striped table-vcenter">
                                    <thead>
                                        <tr>
                                            <th class="d-none d-sm-table-cell text-center">ID</th>
                                            <th class="d-none d-sm-table-cell text-center">Tên phim</th>
                                            <th class="d-none d-sm-table-cell text-center">Thumbnail</th>
                                            <th class="d-none d-sm-table-cell text-center">Thời lượng</th>
                                            <th class="d-none d-sm-table-cell text-center">Review</th>
                                            {{-- <th class="d-none d-sm-table-cell text-center">Story_line</th> --}}
                                            <th class="d-none d-sm-table-cell text-center">Movie_Genre</th>
                                            <th class="d-none d-sm-table-cell text-center">Censorship</th>
                                            <th class="d-none d-sm-table-cell text-center">Ngôn ngữ</th>
                                            <th class="d-none d-sm-table-cell text-center">Director</th>
                                            <th class="d-none d-sm-table-cell text-center">Actor</th>
                                            <th class="d-none d-sm-table-cell text-end">Release</th>
                                            <th>Trạng thái</th>
                                            <th class="text-center" style="width: 100px">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($films as $film)
                                            <tr>
                                                <td class="text-center fs-sm">
                                                    <a>
                                                        <strong>{{ $film->id }}</strong>
                                                    </a>
                                                </td>
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    <strong>{{ $film->film_name }}</strong>
                                                </td>
                                                <td class="d-none d-sm-table-cell text-center fs-sm format-date">
                                                    <div>{{ $film->thumbnail }}</div>
                                                </td>
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    <div>{{ $film->duration }}</div>
                                                </td>
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    {{ $film->review }}
                                                </td>
                                                {{-- <td class="text-end d-none d-sm-table-cell fs-sm">
                                                    <div>{{ $film->story_line }}</div>
                                                </td> --}}
                                                <td class="text-end d-none d-sm-table-cell fs-sm">
                                                    <div>{{ $film->movie_genre }}</div>
                                                </td>
                                                <td class="text-end d-none d-sm-table-cell fs-sm">
                                                    <div>{{ $film->censorship }}</div>
                                                </td>
                                                <td class="text-end d-none d-sm-table-cell fs-sm">
                                                    <div>{{ $film->language }}</div>
                                                </td>
                                                <td class="text-end d-none d-sm-table-cell fs-sm">
                                                    <div>{{ $film->director }}</div>
                                                </td>
                                                <td class="text-end d-none d-sm-table-cell fs-sm">
                                                    <div>{{ $film->actor }}</div>
                                                </td>
                                                <td class="text-end d-none d-sm-table-cell fs-sm">
                                                    <div>{{ $film->release }}</div>
                                                </td>
                                                <td>
                                                    @if ($film->status == 1)
                                                    <span class="badge bg-success">Đang hiển thị</span>
                                                    @elseif ($film->status == 0)
                                                        <span class="badge bg-danger">Đang gỡ xuống</span>
                                                    @else
                                                        <span class="badge bg-warning">Trạng thái không xác định</span>
                                                    @endif
                                                </td>
                                                <td class="text-center fs-sm" style="width: 100px">
                                                    <a class="btn btn-sm btn-alt-secondary" href="{{ route('films.edit', $film->id) }}" data-toggle="tooltip" data-placement="top" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-alt-danger" href="{{ route('films.destroy', $film->id) }}" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure you want to delete this film?')">
                                                        <i class="fa fa-fw fa-times text-danger"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="13" class="text-center">
                                                    <div class="empty d-flex flex-column align-items-center">
                                                        <div class="empty-image d-flex justify-content-center align-items-center" style="margin-bottom: 10px">
                                                            <img src="{{ asset('assets/images/empty-icon.svg') }}" style="height: 200px"/>
                                                        </div>
                                                        {{-- <a href="{{ route('film.create') }}"> --}}
                                                            <button class="btn btn-primary d-flex align-items-center">
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
                    <div th:replace="~{fragments :: pagination('product')}"></div>
                </div>
            </div>
        </div>
    </div>
</body>



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

        // Kiểm tra và hiển thị thông báo lỗi nếu có
        if (messageError) {
            Swal.fire({
                title: '',
                text: messageError,
                icon: 'error'
            });
        }
    });
</script>
    


