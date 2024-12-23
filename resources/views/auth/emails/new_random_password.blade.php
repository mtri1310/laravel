<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mật Khẩu Mới</title>
    <style>
        /* Các kiểu CSS cơ bản để làm cho email trông đẹp hơn */
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            width: 100%;
            padding: 20px 0;
            background-color: #f4f4f4;
        }
        .email-content {
            width: 600px;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            font-family: Arial, sans-serif;
        }
        .header {
            padding: 20px;
            background-color: #4CAF50;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
        }
        .body {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }
        .body p {
            margin: 0 0 15px;
        }
        .password-box {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
            font-size: 20px;
            letter-spacing: 2px;
            margin: 20px 0;
        }
        .footer {
            padding: 20px;
            background-color: #f4f4f4;
            text-align: center;
            color: #777777;
            font-size: 12px;
        }
        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <table class="email-container" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="email-content" cellpadding="0" cellspacing="0">
                    <!-- Header -->
                    <tr>
                        <td class="header">
                            <h1>TriNguCompany</h1>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td class="body">
                            <p>Chào {{ $user->name ?? 'Người dùng' }},</p>
    
                            <p>Chúng tôi đã nhận được yêu cầu tạo mật khẩu mới cho tài khoản của bạn. Dưới đây là mật khẩu mới của bạn:</p>
    
                            <div class="password-box">
                                {{ $newPassword }}
                            </div>
    
                            <p>Vui lòng đăng nhập vào tài khoản của bạn và thay đổi mật khẩu ngay lập tức để đảm bảo an toàn cho tài khoản.</p>
    
                            <p><strong>Lưu ý:</strong> Để bảo mật, mật khẩu mới này chỉ được sử dụng một lần. Sau khi đăng nhập, bạn sẽ cần tạo mật khẩu mới theo ý muốn.</p>
    
                            <p>Nếu bạn không yêu cầu tạo mật khẩu mới, vui lòng liên hệ với bộ phận hỗ trợ của chúng tôi ngay lập tức.</p>
    
                            <p>Trân trọng,<br/>Đội ngũ hỗ trợ</p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            © {{ date('Y') }} TriNguCompany. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
