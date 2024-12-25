<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * Các ngoại lệ không cần báo cáo.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
    ];

    /**
     * Các trường không cần hiển thị trong phản hồi.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Ghi đè phương thức render để tùy chỉnh phản hồi ngoại lệ.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        // Kiểm tra nếu yêu cầu mong muốn nhận JSON (API)
        if ($request->expectsJson()) {
            $status = 'error';
            $message = 'Có lỗi xảy ra';

            $code = 500;

            // Xác định loại ngoại lệ và thông điệp tương ứng
            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                $message = $exception->validator->errors()->first();
                $code = 422;
            } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                $message = 'Chưa được xác thực';
                $code = 401;
            } elseif ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                $message = 'Không có quyền truy cập';
                $code = 403;
            } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                $message = 'Không tìm thấy tài nguyên';
                $code = 404;
            } elseif ($exception instanceof HttpExceptionInterface) {
                $message = $exception->getMessage() ?: $message;
                $code = $exception->getStatusCode();
            } else {
                // Trong môi trường phát triển, hiển thị chi tiết lỗi
                if (config('app.debug')) {
                    $message = $exception->getMessage();
                }
            }

            return response()->json([
                'status' => $status,
                'message' => $message
            ], $code);
        }

        // Nếu không phải yêu cầu JSON, tiếp tục xử lý mặc định
        return parent::render($request, $exception);
    }
}
