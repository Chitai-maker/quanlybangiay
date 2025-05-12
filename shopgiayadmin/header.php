<?php
if (session_id() == "") {
    session_start();
}
?>
<!DOCTYPE html>
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
            <?php
            if (isset($_SESSION['name'])) { ?>
                <?php if ($_SESSION['quyen'] == 0) { //nếu quyên là ADMIN?>
                    
                    <a href="dashboard.php">Dashboard</a>
                    <a href="index.php">Sản phẩm</a>
                    <a href="themloaigiay.php">Loại giầy</a>
                    <a href="themmaugiay.php">Màu giầy</a>
                    <a href="themthuonghieu.php">Thương hiệu</a>
                    <a href="themsize.php">Size</a>
                    <a href="themgiay.php">Thêm Giầy</a>
                    <a href="donhang.php">Đơn hàng</a>
                    <a href="khachhang.php">Khách hàng</a>
                    <a href="nhanvien.php">Nhân viên</a> 
                    <a href="sanphamhot.php">Sản phẩm hot</a>                  
                    <div class="float-end">
                        <span style="color:aquamarine;">Welcome <?= $_SESSION['name'] ?></span><a href="logout.php"><img src="anh/logout.png" alt="Logout" style="width:42px;height:42px;"></a>
                    </div>
                <?php } else if ($_SESSION['quyen'] == 1) { //nếu quyền là nhân viên kho?>
                    <a href="index_giay.php">Giầy</a>
                    <a href="sanphamhot.php">Sản phẩm hot</a>
                    <a href="themloaigiay.php">Thêm loại giầy</a>
                    <a href="themmaugiay.php">Thêm màu giầy</a>
                    <a href="themthuonghieu.php">Thêm thương hiệu</a>
                    <a href="themgiay.php">Thêm giầy</a>
                    <a hre="themsize.php">Thêm size</a>
                    <a href="donhang.php">Đơn hàng</a>
                    <div class="float-end">
                        <span style="color:aquamarine;">Welcome <?= $_SESSION['name'] ?></span><a href="logout.php"><img src="anh/logout.png" alt="Logout" style="width:42px;height:42px;"></a>
                    </div>
                <?php } else if ($_SESSION['quyen'] == 2) { //nếu quyền là nhân viên bán hàng?>
                    <a href="index_giay.php">Giầy</a>
                    <div class="float-end">
                        <span style="color:aquamarine;">Welcome <?= $_SESSION['name'] ?></span><a href="logout.php"><img src="anh/logout.png" alt="Logout" style="width:42px;height:42px;"></a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <a href="login.php">Quản lý kho hàng </a>
            <?php } ?>

        </div>
    </div>