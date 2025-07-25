
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
                    <?php if ($_SESSION['quyen'] == 0): // ADMIN ?>
                        <div class="welcome">
                        Welcome, <?= $_SESSION['name'] ?>
                        </div>
                        <a href="dashboard.php">Bảng tổng hợp</a>
                        
                        <b><p>Quản lý đơn hàng</p></b>
                        <a href="donhang.php">Tất cả</a>
                        <a href="donhang.php?trangthai=Chờ+xác+nhận">Chờ xác nhận</a>
                        <a href="donhang.php?trangthai=Chờ+xác+nhận+thanh+toán+QR">Chờ xác nhận thanh toán QR</a>
                        <a href="donhang.php?trangthai=Đang+giao+hàng">Bàn giao vận chuyển</a>
                        <a href="doitra.php">Yêu Cầu Đổi Trả</a>
                        <b><p>Quản lý sản phẩm</p></b>
                        <a href="index.php">Tất cả sản phẩm</a>
                        <a href="themgiay.php">Thêm sản phẩm</a>
                        <a href="hangmuc.php">Quản lý Hạng mục</a>
                        <a href="hangtonkho.php">Hàng tồn kho</a>
                        <b><p>Chăm sóc khách hàng</p></b>
                        <a href="chatbox.php">Quản lý Chat</a>
                        <a href="danhgia.php">Quản lý Đánh giá</a>
                        <a href="khachhang.php">Danh sách khách hàng</a>
                        <b><p>Quản lý marketing</p></b>
                        <a href="thongke.php">Thống kê</a>
                        <a href="banner.php">Banner</a>
                        <a href="khuyenmai.php">Khuyến mãi</a>
                        <a href="magiamgia.php">Mã giảm giá</a>
                        <b><p>Quản lý nhân viên</p></b>
                        <a href="nhanvien.php">Nhân viên</a>
                        <a href="lichsunhanvien.php">Log</a>
                        <a href="logout.php" class="logout"><img src="anh/logout.png" alt="Logout"
                                style="width:32px;height:32px;"></a>
                    <?php elseif ($_SESSION['quyen'] == 1): // Nhân viên kho 
                    ?>
                        <div class="welcome">
                            Welcome, <?= $_SESSION['name'] ?>
                        </div>
                        <b><p>Quản lý đơn hàng</p></b>
                        <a href="donhang.php">Tất cả</a>
                        <a href="donhang.php?trangthai=Chờ+xác+nhận">Chờ xác nhận</a>
                        <a href="donhang.php?trangthai=Chờ+xác+nhận+thanh+toán+QR">Chờ xác nhận thanh toán QR</a>
                        <a href="donhang.php?trangthai=Đang+giao+hàng">Vận chuyển</a>
                        <a href="doitra.php">Yêu Cầu Đổi Trả</a>
                        <b><p>Quản lý sản phẩm</p></b>
                        <a href="index.php">Tất cả sản phẩm</a>
                        <a href="themgiay.php">Thêm sản phẩm</a>
                        <a href="hangmuc.php">Quản lý Hạng mục</a>
                        <a href="hangtonkho.php">Hàng tồn kho</a>
                        <b><p>Chăm sóc khách hàng</p></b>
                        <a href="chatbox.php">Quản lý Chat</a>
                        <a href="danhgia.php">Quản lý Đánh giá</a>
                        <a href="khachhang.php">Danh sách khách hàng</a>
                        <a href="logout.php" class="logout"><img src="anh/logout.png" alt="Logout"
                                style="width:32px;height:32px;"></a>
                    <?php elseif ($_SESSION['quyen'] == 2): // Nhân viên bán hàng 
                    ?>
                        <div class="welcome">
                            Welcome, <?= $_SESSION['name'] ?>   
                        </div> 
                        <b><p>Quản lý sản phẩm</p></b>
                        <a href="index.php">Tất cả sản phẩm</a>
                        <b><p>Chăm sóc khách hàng</p></b>
                        <a href="chatbox.php">Quản lý Chat</a>
                        <a href="danhgia.php">Quản lý Đánh giá</a>
                        <a href="khachhang.php">Danh sách khách hàng</a>
                        <b><p>Quản lý marketing</p></b>
                        <a href="thongke.php">Thống kê</a>
                        <a href="banner.php">Banner</a>
                        <a href="khuyenmai.php">Khuyến mãi</a>
                        <a href="magiamgia.php">Mã giảm giá</a>
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