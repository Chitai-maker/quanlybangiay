<?php
include "chucnang/chucnang_dangky.php";
include "header.php";
?>
<style>
.error-message {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}
</style>
<div class="container">
    <h1 class="text-center mb-4" style="font-weight:700; font-size:48px;">Đăng ký</h1>
    <div class="mx-auto" style="max-width:700px;">
        <div style="border:1px solid #d2cccc; border-radius:14px; padding:32px 28px 18px 28px; background:#fff;">
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="tenkhachhang" class="form-label fw-bold">Tên</label>
                    <input type="text" class="form-control" name="tenkhachhang" id="tenkhachhang" value="" placeholder="Nhập tên" />
                </div>
                <div class="mb-3">
                    <label for="sdt" class="form-label fw-bold">Số điện thoại</label>
                    <input type="text" class="form-control" name="sdt" id="sdt" value="" placeholder="Nhập số điện thoại" />
                </div>
                <div class="mb-3">
                    <label for="diachi" class="form-label fw-bold">Địa chỉ</label>
                    <input type="text" class="form-control" name="diachi" id="diachi" value="" placeholder="Nhập địa chỉ" />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="" placeholder="Nhập Email" />
                </div>
                <div class="mb-3">
                    <label for="psw" class="form-label fw-bold">Password</label>
                    <input type="password" class="form-control" name="psw" id="psw" placeholder="Nhập Password" />
                </div>
                <div class="register-btn d-flex align-items-center" style="gap:16px;">
                    <button type="submit" class="btn btn-success" name="submit">Đăng ký</button>
                    <a href="login.php" class="btn btn-link p-0">Đã có tài khoản?</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
