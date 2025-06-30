<?php
if (session_id() == "") {
    session_start();
}
?>
<!DOCTYPE html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="header">
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="sanphamhot.php">Hot</a>
            <a href="tat.php">Tất</a>
            <a href="sandal.php">Giày Sandal</a>
            <a href="dep.php">Dép</a>
            <a href="sneaker.php">Sneaker</a>
            <a href="thuonghieu.php">Thương hiệu</a>
            <a href="size.php">Size</a>
            <?php
            if (isset($_SESSION['tenkhachhang'])) { ?>

                <a href="giohang.php"><img src="anh/giohang.webp" alt="HTML tutorial" style="width:42px;height:42px;"></a>
                <!-- filepath: c:\xampp\htdocs\shopgiay\header.php -->
                <div class="float-end dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Welcome, <span style="color: aquamarine;"><?= $_SESSION['tenkhachhang'] ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item text-black" href="thongtin.php">Thông tin cá nhân</a></li>
                        <li><a class="dropdown-item text-black" href="donhang.php">Đơn mua</a></li>
                        <li><a class="dropdown-item text-black" href="chatbox.php">Liên hệ shop</a></li>
                        <li><a class="dropdown-item text-black" href="danhgiacuaban.php">Đánh giá của bạn</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item  text-danger" href="logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
            <?php } else { ?>
                <a href="login.php">Đăng nhập</a>
                <a href="signup.php">Đăng ký</a>
            <?php } ?>

        </div>
    </div>