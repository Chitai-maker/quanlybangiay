<?php
include "chucnang/chucnang_dangnhap.php";
include "header.php";
include_once "chucnang/connectdb.php";
// Cài đặt PHPMailer qua Composer hoặc tải về
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

if (isset($_POST['forgot_submit'])) {
    $forgot_email = mysqli_real_escape_string($conn, $_POST['forgot_email']);
    $sql = "SELECT * FROM khachhang WHERE email = '$forgot_email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // Tạo mã xác nhận
        $token = bin2hex(random_bytes(16));
        // Lưu token vào database (bạn nên tạo thêm cột reset_token, reset_expire trong bảng khachhang)
        mysqli_query($conn, "UPDATE khachhang SET reset_token='$token', reset_expire= NOW() + INTERVAL 15 MINUTE WHERE email='$forgot_email'");
        // Gửi email xác nhận
        
        $link = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/reset_password.php?email=$forgot_email&token=$token";
        $subject = "Xác nhận đổi mật khẩu";
        $message = "Nhấn vào liên kết sau để đổi mật khẩu (có hiệu lực 15 phút): $link";
        $headers = "From: no-reply@yourdomain.com\r\n";
        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            include "../config.php"; 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('your_gmail@gmail.com', 'Shop Giày');
            $mail->addAddress($forgot_email);

            $mail->Subject = $subject;
            $mail->Body    = $message;
            $mail->CharSet = 'UTF-8';

            $mail->send();
            echo "<div class='alert alert-success text-center'>Đã gửi email xác nhận đổi mật khẩu. Vui lòng kiểm tra hộp thư!</div>";
        } catch (Exception $e) {
            echo "<div class='alert alert-danger text-center'>Không gửi được email. Lỗi: {$mail->ErrorInfo}</div>";
        }
    } else {
        echo "<div class='alert alert-danger text-center'>Email không tồn tại trong hệ thống!</div>";
    }
}
?>

<div class="container">
    <h1 class="text-center mb-4" style="font-weight:700; font-size:48px;">Đăng nhập</h1>
    <div class="mx-auto" style="max-width:700px;">
        <div style="border:1px solid #d2cccc; border-radius:14px; padding:32px 28px 18px 28px; background:#fff;">
<?php
        $disp_email = !empty($email) ? $email : (isset($_COOKIE['cookie_email']) ? $_COOKIE['cookie_email'] : "");

        $checked = !empty($rem) ? "checked" : (isset($_COOKIE['cookie_rem']) ? "checked" : "");
        ?>
        <form action="" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?=$disp_email?>" placeholder="Nhập Email" />
                    <div class="text-danger"><?= $email_err?></div>
                </div>
                <div class="mb-3">
                    <label for="pwd" class="form-label fw-bold">Password</label>
                    <input type="password" class="form-control" name="pwd" id="pwd" placeholder="Nhập Password" />
                    <div class="text-danger"><?= $pwd_err?></div>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember" <?= $checked?>/>
                    <label class="form-check-label" for="remember"> Nhớ tôi </label>
                </div>
                <div class="register-btn d-flex align-items-center" style="gap:16px;">
                    <button type="submit" class="btn btn-success" name="submit">Đăng nhập</button>
                    <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#forgotModal">Quên mật khẩu?</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Quên mật khẩu -->
<div class="modal fade" id="forgotModal" tabindex="-1" aria-labelledby="forgotModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="forgotModalLabel">Quên mật khẩu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        <label for="forgot_email" class="form-label">Nhập email đã đăng ký:</label>
        <input type="email" class="form-control" name="forgot_email" id="forgot_email" required>
      </div>
      <div class="modal-footer">
        <button type="submit" name="forgot_submit" class="btn btn-primary">Gửi xác nhận</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>