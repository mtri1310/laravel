<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Admin</title>
    <link href="{{ asset('/images/icon.png') }}" rel="icon" type="image/x-icon">

    <!-- Bootstrap core -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}"/>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/list.css') }}">
</head>
<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            @include('fragments.sidebar', ['key' => 'user', 'subkey' => 'user_all'])
            <div class="d-flex flex-column wrapper">
                @include('fragments.header')
                <div class="content">
                    <div class="d-flex justify-content-between align-items-center mt-3 mb-5">
                        <h1 class="title">Người dùng</h1>
                        <a href="{{ route('users.create') }}">
                            <button class="btn btn-primary d-flex align-items-center">
                                <i class="fas fa-plus" style="margin-right: 0.5rem"></i>
                                <span>Người dùng mới</span>
                            </button>
                        </a>
                    </div>
                    <section class="list-table">
                        <div class="list-table-header d-flex align-items-center justify-content-between">
                            @include('fragments.search', ['entityName' => 'users'])
                        </div>
                        <div class="list-table-content">
                            <div class="table-responsive">
                                <table class="table table-borderless table-striped table-vcenter">
                                    <thead>
                                        <tr>
                                            <th class="d-none d-sm-table-cell text-center">ID</th>
                                            <th class="d-none d-sm-table-cell text-center">Name</th>
                                            <th class="d-none d-sm-table-cell text-center">User Name</th>
                                            <th class="d-none d-sm-table-cell text-center">Profile</th>
                                            <th class="d-none d-sm-table-cell text-center">Email</th>
                                            <th class="d-none d-sm-table-cell text-center">Phone</th>
                                            <th class="text-center" style="width: 100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $user)
                                            <tr>
                                                <td class="text-center fs-sm">
                                                    <strong>{{ $user->id }}</strong>
                                                </td>
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    <strong>{{ $user->full_name }}</strong>
                                                </td>
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    <strong>{{ $user->username }}</strong>
                                                </td>
                                                <td class="d-none d-md-table-cell fs-sm">
                                                    <img src="{{ $user->picture }}" alt="{{ $user->username }} Thumbnail" style="width: 200px;">
                                                </td>
                                                <td class="d-none d-sm-table-cell text-center fs-sm">
                                                    <div>{{ $user->email }}</div>
                                                </td>
                                                <td class="d-none d-sm-table-cell text-center fs-sm">
                                                    <div>{{ $user->phone }}</div>
                                                </td>
                                                
                                                <td class="text-center fs-sm">
                                                    <a class="btn btn-sm btn-alt-secondary" href="{{ route('users.edit', $user->id) }}" data-toggle="tooltip" data-placement="top" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-alt-danger" href="{{ route('users.destroy', $user->id) }}" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="fa fa-fw fa-times text-danger"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="empty d-flex flex-column align-items-center">
                                                        <div class="empty-image d-flex justify-content-center align-items-center" style="margin-bottom: 10px">
                                                            <img src="{{ asset('assets/images/empty-icon.svg') }}" style="height: 200px"/>
                                                        </div>
                                                        <a href="{{ route('users.create') }}">
                                                            <button class="btn btn-primary d-flex align-items-center">
                                                                <span>Tạo tài khoản</span>
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
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
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

        if (messageError) {
            Swal.fire({
                title: '',
                text: messageError,
                icon: 'error'
            });
        }
    });
</script>
