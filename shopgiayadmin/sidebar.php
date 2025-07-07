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
    <style>
        .sidebar {
            min-height: 100vh;
            background: rgb(46, 45, 105);
            color: #fff;
            padding-top: 30px;
            transition: margin-left 0.3s;
        }

        .sidebar a {
            color: #fff;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 6px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #17a2b8;
            color: #fff;
        }

        .sidebar .welcome {
            margin-top: 30px;
            color: aquamarine;
            font-size: 1rem;
        }

        .sidebar .logout {
            margin-left: 10px;
        }

        .sidebar.hide {
            margin-left: -220px;
            /* hoặc -100% nếu sidebar full chiều ngang */
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="sidebar" id="sidebar" style="width:190px;">
                <?php if (isset($_SESSION['name'])): ?>
                    <?php if ($_SESSION['quyen'] == 0): // ADMIN 
                    ?>
                        <div class="welcome">
                            Welcome, <?= $_SESSION['name'] ?>
                        </div>
                        <a href="dashboard.php">Bảng tổng hợp</a>
                        <a href="thongke.php">Thống kê</a>
                        <a href="chatbox.php">CSKH</a>
                        <a href="banner.php">Banner</a>
                        <a href="index.php">Sản phẩm</a>
                        <a href="hangtonkho.php">Hàng tồn kho</a>
                        <a href="khuyenmai.php">Khuyến mãi</a>
                        <a href="themloaigiay.php">Loại giày</a>
                        <a href="themmaugiay.php">Màu giày</a>
                        <a href="themthuonghieu.php">Thương hiệu</a>
                        <a href="themsize.php">Size</a>
                        <a href="donhang.php">Đơn hàng</a>
                        <a href="khachhang.php">Khách hàng</a>
                        <a href="nhanvien.php">Nhân viên</a>
                        <a href="logout.php" class="logout"><img src="anh/logout.png" alt="Logout"
                                style="width:32px;height:32px;"></a>
                    <?php elseif ($_SESSION['quyen'] == 1): // Nhân viên kho 
                    ?>
                        <div class="welcome">
                            Welcome, <?= $_SESSION['name'] ?>

                        </div>
                        <a href="index.php">Giày</a>
                        <a href="khuyenmai.php">Sản phẩm hot</a>
                        <a href="themloaigiay.php">Thêm loại giày</a>
                        <a href="themmaugiay.php">Thêm màu giày</a>
                        <a href="themthuonghieu.php">Thêm thương hiệu</a>
                        <a href="themgiay.php">Thêm giày</a>
                        <a href="themsize.php">Thêm size</a>
                        <a href="donhang.php">Đơn hàng</a>
                        <a href="logout.php" class="logout"><img src="anh/logout.png" alt="Logout"
                                style="width:32px;height:32px;"></a>
                    <?php elseif ($_SESSION['quyen'] == 2): // Nhân viên bán hàng 
                    ?>
                        <div class="welcome">
                            Welcome, <?= $_SESSION['name'] ?>
                        </div>
                        <a href="index.php">Giày</a>
                        <a href="logout.php" class="logout"><img src="anh/logout.png" alt="Logout"
                                style="width:32px;height:32px;"></a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php">Quản lý kho hàng</a>
                <?php endif; ?>

            </nav>
            <main class="col-10 p-4">
                
                <script>
                    document.getElementById('toggleSidebar').onclick = function() {
                        document.getElementById('sidebar').classList.toggle('hide');
                    }
                </script>
                <!-- Nội dung trang sẽ đặt ở đây -->