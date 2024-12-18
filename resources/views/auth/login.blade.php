<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="{{ asset('assets/images/icon.png') }}" rel="icon" type="image/x-icon">

    <!-- Bootstrap core -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}"/>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"/>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
</head>
<body>

    <div class="container-fluid auth-container d-flex justify-content-center align-items-center">
        <div class="auth-form-wrapper">
            <form class="auth-form needs-validation" action="{{ route('auth.login') }}" method="POST">
                @csrf
                <div class="auth-form-wrapper-logo mb-4 pt-4 d-flex justify-content-center">
                    <img src="{{ asset('assets/images/logo-white.png') }}" alt="Logo" class="img-fluid">
                </div>
                <p class="auth-form-desc text-center mb-5">Đăng nhập và mua hàng tại Shopwise!</p>

                <!-- Hiển thị thông điệp nếu có -->
                @if(session('message'))
                    <div class="alert alert-{{ session('message.type') }} text-center">
                        {{ session('message.message') }}
                    </div>
                @endif

                <div class="form-group mb-4">
                    <input type="email" name="email" class="form-control auth-input" placeholder="Email" id="email" required>
                </div>
                <div class="form-group mb-3">
                    <input type="password" name="password" class="form-control auth-input" placeholder="Mật khẩu" id="password" required>
                </div>
                <div class="form-group mb-3 d-flex align-items-center justify-content-between">
                    <div class="form-check">
                        <input class="form-check-input auth-check-input" type="checkbox" name="remember-me" id="remember-me">
                        <label class="form-check-label auth-check-label" for="remember-me">
                            Ghi nhớ đăng nhập                
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-danger auth-btn w-100 mb-3">Đăng nhập</button>

                <div class="divider my-4 text-center">
                    <span class="divider-text">Hoặc</span>
                </div>

                <!-- Nút đăng nhập bằng Google -->
                <a href="{{ route('auth.google') }}" class="btn btn-outline-danger auth-google-btn w-100">
                    <i class="fab fa-google me-2"></i> Đăng nhập với Google
                </a>
            </form>

            <div class="auth-footer mt-4 text-center">
                <img class="auth-footer-img img-fluid" src="{{ asset('assets/images/Vectors.png') }}" alt="Footer Image">
            </div>
        </div>
    </div>

    <!-- Tải jQuery trước -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Tải Toastr JS sau khi jQuery đã được tải -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Cấu hình và khởi tạo Toastr -->
    <script>
        // Cấu hình Toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        $(document).ready(function() {
            let message = @json(session('message'));
            if (message) {
                // Kiểm tra xem loại thông điệp có hợp lệ không
                let type = message.type;
                if (!['success', 'info', 'warning', 'error'].includes(type)) {
                    type = 'info'; // Mặc định là 'info' nếu không hợp lệ
                }
                toastr[type](message.message || '', message.title || '');
            }
        });
    </script>
</body>
</html>
