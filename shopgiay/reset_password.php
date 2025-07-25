<?php
// filepath: c:\xampp\htdocs\quanlybangiay\shopgiay\reset_password.php
include_once "chucnang/connectdb.php";
include "header.php";

$email = isset($_GET['email']) ? mysqli_real_escape_string($conn, $_GET['email']) : '';
$token = isset($_GET['token']) ? mysqli_real_escape_string($conn, $_GET['token']) : '';
$show_form = false;
$message = '';

if ($email && $token) {
    // Kiểm tra token và hạn sử dụng
    $sql = "SELECT * FROM khachhang WHERE email='$email' AND reset_token='$token' AND reset_expire >= NOW()";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $show_form = true;
        if (isset($_POST['reset_submit'])) {
            $newpass = mysqli_real_escape_string($conn, $_POST['newpass']);
            $renewpass = mysqli_real_escape_string($conn, $_POST['renewpass']);
            if ($newpass === $renewpass && strlen($newpass) >= 6) {
                $hashed = password_hash($newpass, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE khachhang SET matkhau='$hashed', reset_token=NULL, reset_expire=NULL WHERE email='$email'");
                $message = "<div class='alert alert-success text-center'>Đổi mật khẩu thành công! <a href='login.php'>Đăng nhập</a></div>";
                $show_form = false;
            } else {
                $message = "<div class='alert alert-danger text-center'>Mật khẩu không khớp hoặc quá ngắn (tối thiểu 6 ký tự).</div>";
            }
        }
    } else {
        $message = "<div class='alert alert-danger text-center'>Liên kết không hợp lệ hoặc đã hết hạn!</div>";
    }
} else {
    $message = "<div class='alert alert-danger text-center'>Thiếu thông tin xác thực!</div>";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
    
</head>
<body>
<?= $message ?>
<?php if ($show_form): ?>
    <div class="container">
        <div class="mx-auto" style="max-width:700px;">
            <div style="border:1px solid #d2cccc; border-radius:14px; padding:32px 28px 18px 28px; background:#fff;">
                <form method="post">
                    <h4 class="mb-3 text-center">Đặt lại mật khẩu</h4>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="newpass" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nhập lại mật khẩu</label>
                        <input type="password" name="renewpass" class="form-control" required minlength="6">
                    </div>
                    <button type="submit" name="reset_submit" class="btn btn-primary w-100">Đặt lại mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>