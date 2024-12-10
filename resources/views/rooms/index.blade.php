
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
    <link rel="stylesheet" href="{{ asset('assets/css/list.css') }}">
</head>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/index.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.4/dist/sweetalert2.all.min.js"></script>

<script th:inline="javascript">
    $(document).ready(function() {
        let messageError = [[${messageError}]];
        let messageSuccess = [[${messageSuccess}]];

        if(messageSuccess) {
            Swal.fire({
                title: '',
                text: messageSuccess,
                icon: 'success',
                confirmButtonColor: '#3085d6'
            })
        }

        if(messageError ) {
            Swal.fire({
                title: '',
                text: messageError,
                icon: 'error'
            })
        }
    })
</script>

<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            @include('fragments.sidebar', ['key' => 'film', 'subkey' => 'film_all'])
            <div class="d-flex flex-column wrapper">
                @include('fragments.header')
                <div class="content">
                    <div class="d-flex justify-content-between align-items-center mt-3 mb-5">
                        <h1 class="title">rooms</h1>
                        <a href="{{ route('rooms.create') }}">
                            <button class="btn btn-primary d-flex align-items-center">
                                <i class="fas fa-plus" style="margin-right: 0.5rem"></i>
                                <span>New room</span>
                            </button>
                        </a>
                    </div>
                    <section class="list-table">
                        <div class="list-table-header d-flex align-items-center justify-content-between">
                            @include('fragments.search', ['entityName' => 'room'])
                        </div>
                        <div class="list-table-content">
                            <div class="table-responsive">
                                <table class="table table-borderless table-striped table-vcenter">
                                    <thead>
                                        <tr>
                                            <th class="d-none d-sm-table-cell text-center">ID</th>
                                            <th class="d-none d-sm-table-cell text-center">Room</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($rooms as $room)
                                            <tr>
                                                <td class="text-center fs-sm">
                                                    <a>
                                                        <strong>{{ $room->id }}</strong>
                                                    </a>
                                                </td>

                                                <td class="d-none d-sm-table-cell text-center fs-sm">
                                                    <div>{{ $room->room_name }}</div>
                                                </td>
                                                
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <div class="empty d-flex flex-column align-items-center">
                                                        <div class="empty-image d-flex justify-content-center align-items-center" style="margin-bottom: 10px">
                                                            <img src="{{ asset('assets/images/empty-icon.svg') }}" style="height: 200px"/>
                                                        </div>
                                                        <button class="btn btn-primary d-flex align-items-center">
                                                            <span>Create Now</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>                    
                    <div th:replace="~{fragments :: pagination('room')}"></div>
                </div>
            </div>
        </div>
    </div>
</body>

