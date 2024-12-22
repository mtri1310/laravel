<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Mật Khẩu</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 0 10px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 20px; background-color:#4CAF50;">
                            <h1 style="color:#ffffff; margin:0; font-family: Arial, sans-serif;">TriNguCompany</h1>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px; font-family: Arial, sans-serif; color:#333333;">
                            <p>Chào bạn,</p>
                            <p>Bạn đã yêu cầu reset mật khẩu. Vui lòng nhấn vào nút dưới đây để tiếp tục:</p>
                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ $resetLink }}" style="background-color:#4CAF50; color:#ffffff; padding: 15px 25px; text-decoration:none; border-radius:5px; display:inline-block;">Reset Mật Khẩu</a>
                            </p>
                            <p>Liên kết này sẽ hết hạn sau <strong>60 phút</strong>.</p>
                            <p>Nếu bạn không yêu cầu reset mật khẩu, vui lòng bỏ qua email này.</p>
                            <p>Trân trọng,<br/>Đội ngũ hỗ trợ</p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px; background-color:#f4f4f4; text-align: center; font-family: Arial, sans-serif; color:#777777; font-size:12px;">
                            © {{ date('Y') }} TriNguCompany. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
