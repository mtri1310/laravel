<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Films</title>
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
            @include('fragments.sidebar', ['key' => 'film', 'subkey' => 'film_all'])
            <div class="d-flex flex-column wrapper">
                @include('fragments.header')
                <div class="content">
                    <div class="d-flex justify-content-between align-items-center mt-3 mb-5">
                        <h1 class="title">Films</h1>
                        <a href="{{ route('films.create') }}">
                            <button class="btn btn-primary d-flex align-items-center">
                                <i class="fas fa-plus mr-2"></i>
                                <span>Add New Film</span>
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
                                            <th class="d-none d-sm-table-cell text-center">Film Name</th>
                                            <th class="d-none d-sm-table-cell text-center">Thumbnail</th>
                                            <th class="d-none d-sm-table-cell text-center">Duration</th>
                                            <th class="d-none d-sm-table-cell text-center">Review</th>
                                            <th class="d-none d-sm-table-cell text-center">Movie Genre</th>
                                            <th class="d-none d-sm-table-cell text-center">Censorship</th>
                                            <th class="d-none d-sm-table-cell text-center">Language</th>
                                            <th class="d-none d-sm-table-cell text-center">Director</th>
                                            <th class="d-none d-sm-table-cell text-center">Actor</th>
                                            <th class="d-none d-sm-table-cell text-end">Release</th>
                                            <th>Status</th>
                                            <th class="text-center" style="width: 100px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($films as $film)
                                            <tr>
                                                <td class="text-center fs-sm"><strong>{{ $film->id }}</strong></td>
                                                <td class="d-none d-md-table-cell fs-sm"><strong>{{ $film->film_name }}</strong></td>
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    <img src="{{ $film->thumbnail }}" alt="{{ $film->film_name }} Thumbnail" style="width: 200px;">
                                                </td>
                                                <td class="d-none d-md-table-cell fs-sm">{{ $film->duration }}</td>
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    @php
                                                        $fullStars = floor($film->review);
                                                        $halfStar = ($film->review - $fullStars) >= 0.5 ? 1 : 0;
                                                        $emptyStars = 5 - $fullStars - $halfStar;
                                                    @endphp
                                                
                                                    @for ($i = 0; $i < $fullStars; $i++)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @endfor
                                                
                                                    @if ($halfStar)
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    @endif
                                                
                                                    @for ($i = 0; $i < $emptyStars; $i++)
                                                        <i class="far fa-star text-warning"></i>
                                                    @endfor
                                                
                                                    <span class="ms-2">({{ number_format($film->review, 1) }})</span>
                                                </td>
                                                <td class="text-end d-none d-sm-table-cell fs-sm">{{ $film->movie_genre }}</td>
                                                <td class="text-end d-none d-sm-table-cell fs-sm">{{ $film->censorship }}</td>
                                                <td class="text-end d-none d-sm-table-cell fs-sm">{{ $film->language }}</td>
                                                <td class="text-end d-none d-sm-table-cell fs-sm">{{ $film->director }}</td>
                                                <td class="text-end d-none d-sm-table-cell fs-sm">{{ $film->actor }}</td>
                                                <td class="text-end d-none d-sm-table-cell fs-sm">
                                                    {{ $film->release ? $film->release->format('d/m/Y') : 'N/A' }}
                                                </td>
                                                <td>
                                                    @if ($film->status == 1)
                                                        <span class="badge bg-success">Visible</span>
                                                    @elseif ($film->status == 0)
                                                        <span class="badge bg-danger">Hidden</span>
                                                    @else
                                                        <span class="badge bg-warning">Unknown Status</span>
                                                    @endif
                                                </td>
                                                <td class="text-center fs-sm" style="width: 100px">
                                                    <a href="{{ route('films.edit', $film->id) }}" class="btn btn-sm btn-alt-secondary" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <form action="{{ route('films.destroy', $film->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this film?')">
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
                                                        <a href="{{ route('films.create') }}">
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
                        {{ $films->links() }}
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
