<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Login</title>
    <link href="{{ asset('assets/images/icon.png') }}" rel="icon" type="image/x-icon">

    <!-- Bootstrap core -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}"/>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
</head>
<body>

    <div class="container-fluid auth-container d-flex justify-content-center align-items-center">
        <form class="auth-form needs-validation" action="{{ route('auth.login') }}" method="POST">
            @csrf
            <div class="auth-form-wrapper-logo mb-4 pt-4 d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo-white.png') }}" alt="Logo">
            </div>
            <p class="auth-form-desc text-center mb-5">Đăng nhập và mua hàng tại Shopwise!</p>

            <!-- Bạn có thể loại bỏ đoạn này nếu sử dụng Toastr hoàn toàn -->
            @if(session('message'))
                <p class="text-warning text-center">{{ session('message.message') }}</p>
            @endif

            <div class="mb-4 d-flex justify-content-center align-items-center">
                <input type="email" name="email" class="form-control auth-input" placeholder="Email" id="email" required>
            </div>
            <div class="mb-3 d-flex justify-content-center align-items-center">
                <input type="password" name="password" class="form-control auth-input" placeholder="Mật khẩu" id="password" required>
            </div>
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <input class="form-check-input auth-check-input" type="checkbox" name="remember-me" id="remember-me">
                    <label class="form-check-label auth-check-label" for="remember-me">
                        Ghi nhớ đăng nhập                
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-danger auth-btn">Đăng nhập</button>
        </form>


        <div class="auth-footer">
            <img class="auth-footer-img" src="{{ asset('assets/images/Vectors.png') }}" alt="Footer Image">
        </div>
    </div>

    <!-- Tải jQuery trước -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>

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
        console.log(@json(session('message')));

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
