<?php
session_start();
if (!isset($_SESSION['name']))
    header("location:login.php");
include "header.php";
include_once("chucnang/connectdb.php");
if($_SESSION['quyen'] > 0){
    header("location:dangnhap_quyencaohon.php");     
} 

// Đếm số đơn hàng theo trạng thái
$query = "SELECT COUNT(*) AS total FROM donhang WHERE trangthai = 'Đang xử lý'";
$query2 = "SELECT COUNT(*) AS total FROM donhang WHERE trangthai = 'Đang giao hàng'";
$query3 = "SELECT COUNT(*) AS total FROM donhang WHERE trangthai = 'Hoàn thành'";
$query4 = "SELECT COUNT(*) AS total FROM donhang WHERE trangthai = 'Đã hủy'";
$result = mysqli_query($conn, $query);
$result2 = mysqli_query($conn, $query2);
$result3 = mysqli_query($conn, $query3);
$result4 = mysqli_query($conn, $query4);
$row = mysqli_fetch_assoc($result);
$row2 = mysqli_fetch_assoc($result2);
$row3 = mysqli_fetch_assoc($result3);
$row4 = mysqli_fetch_assoc($result4);
$donhang_chuaduyet = $row['total'];
$donhang_danggiaohang = $row2['total'];
$donhang_hoanthanh = $row3['total'];
$donhang_huy = $row4['total'];
// Đếm số khách hàng
$query5 = "SELECT COUNT(*) AS total FROM khachhang";
$result5 = mysqli_query($conn, $query5);
$row5 = mysqli_fetch_assoc($result5);
$khachhang_count = $row5['total'];
// Đếm tổng doanh thu
$query6 = "SELECT SUM(chitietdonhang.soluong * giay.giaban) AS total FROM donhang JOIN chitietdonhang ON donhang.ma_donhang = chitietdonhang.ma_donhang JOIN giay ON chitietdonhang.ma_giay = giay.magiay WHERE donhang.trangthai = 'Hoàn thành'";
$result6 = mysqli_query($conn, $query6);
$row6 = mysqli_fetch_assoc($result6);
$doanhthu_total = $row6['total'] ? number_format($row6['total'], 0, ',', '.') : '0';
// Đếm số nhân viên
$query7 = "SELECT COUNT(*) AS total FROM nhanvien";
$result7 = mysqli_query($conn, $query7);
$row7 = mysqli_fetch_assoc($result7);
$nhanvien_count = $row7['total'];
// Đếm số sản phẩm
$query8 = "SELECT COUNT(*) AS total FROM giay";
$result8 = mysqli_query($conn, $query8);
$row8 = mysqli_fetch_assoc($result8);
$sanpham_count = $row8['total'];
// Đếm số loai giày
$query9 = "SELECT COUNT(*) AS total FROM loaigiay";
$result9 = mysqli_query($conn, $query9);
$row9 = mysqli_fetch_assoc($result9);
$loaigiay_count = $row9['total'];
// Đếm số thương hiệu
$query10 = "SELECT COUNT(*) AS total FROM thuonghieu";
$result10 = mysqli_query($conn, $query10);
$row10 = mysqli_fetch_assoc($result10);
$thuonghieu_count = $row10['total'];
// Đếm số màu giầy
$query11 = "SELECT COUNT(*) AS total FROM maugiay";
$result11 = mysqli_query($conn, $query11);
$row11 = mysqli_fetch_assoc($result11);
$maugiay_count = $row11['total'];
// Đếm số kích cỡ giầy
$query12 = "SELECT COUNT(*) AS total FROM sizegiay";
$result12 = mysqli_query($conn, $query12);
$row12 = mysqli_fetch_assoc($result12);
$kichco_count = $row12['total'];
// Đếm mặt hàng khuyến mãi
$query13 = "SELECT COUNT(*) AS total FROM sanphamhot";
$result13 = mysqli_query($conn, $query13);
$row13 = mysqli_fetch_assoc($result13);
$khuyenmai_count = $row13['total'];

// Đóng kết nối
mysqli_close($conn);
?>
<style>
    .dashboard-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        margin: 30px 0 30px 0;
        justify-content: flex-start;
    }

    .dashboard-card {
        flex: 1 1 250px;
        min-width: 220px;
        max-width: 320px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        padding: 24px 32px;
        gap: 18px;
        transition: box-shadow 0.2s;
        text-decoration: none;
    }

    .dashboard-card:hover {
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.12);
        text-decoration: none;
    }

    .dashboard-icon {
        font-size: 2.2rem;
        color: #fff;
        border-radius: 10px;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-blue {
        background: #2563eb;
    }

    .bg-green {
        background: #22c55e;
    }

    .bg-red {
        background: #ef4444;
    }

    .bg-purple {
        background: #7c3aed;
    }

    .bg-pink {
        background: #e11d48;
    }

    .bg-cyan {
        background: #06b6d4;
    }

    .bg-orange {
        background: #f59e42;
    }

    .bg-gray {
        background: #64748b;
    }

    .card-label {
        font-size: 1.1rem;
        color: #444;
        margin-bottom: 2px;
    }

    .card-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #222;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<h1>Bảng Tổng Hợp</h1>
<div class="dashboard-cards">
    <a href="donhang.php?trangthai=Đang+xử+lý" class="dashboard-card">
        <span class="dashboard-icon bg-purple"><i class="fa fa-receipt"></i></span>
        <div>
            <div class="card-label">Đơn đặt hàng</div>
            <div class="card-value"><?= $donhang_chuaduyet ?></div>
        </div>
    </a>
    <a href="donhang.php?trangthai=Đang+giao+hàng" class="dashboard-card">
        <span class="dashboard-icon bg-blue"><i class="fa fa-truck"></i></span>
        <div>
            <div class="card-label">Đơn hàng đang giao hàng</div>
            <div class="card-value"><?= $donhang_danggiaohang ?></div>
        </div>
    </a>
    <a href="donhang.php?trangthai=Hoàn+thành" class="dashboard-card">
        <span class="dashboard-icon bg-cyan"><i class="fa fa-check-circle"></i></span>
        <div>
            <div class="card-label">Đơn hàng hoàn thành</div>
            <div class="card-value"><?= $donhang_hoanthanh ?></div>
        </div>
    </a>
    <a href="donhang.php?trangthai=Đã+hủy" class="dashboard-card">
        <span class="dashboard-icon bg-red"><i class="fa fa-ban"></i></span>
        <div>
            <div class="card-label">Đơn hàng đã hủy</div>
            <div class="card-value"><?= $donhang_huy ?></div>
        </div>
    </a>
    <a href="index.php" class="dashboard-card">
        <span class="dashboard-icon bg-pink"><i class="fa fa-store"></i></span>
        <div>
            <div class="card-label">Mặt hàng</div>
            <div class="card-value"><?= $sanpham_count ?></div>
        </div>
    </a>
    <a href="khachhang.php" class="dashboard-card">
        <span class="dashboard-icon bg-blue"><i class="fa fa-users"></i></span>
        <div>
            <div class="card-label">Khách hàng</div>
            <div class="card-value"><?= $khachhang_count ?></div>
        </div>
    </a>
    <a href="nhanvien.php" class="dashboard-card">
        <span class="dashboard-icon bg-orange"><i class="fa fa-user-tie"></i></span>
        <div>
            <div class="card-label">Nhân viên</div>
            <div class="card-value"><?= $nhanvien_count ?></div>
        </div>
    </a>
    <a href="thongke.php" class="dashboard-card">
        <span class="dashboard-icon bg-green"><i class="fa fa-dollar-sign"></i></span>
        <div>
            <div class="card-label">Doanh số</div>
            <div class="card-value"><?= $doanhthu_total ?> đ</div>
        </div>
    </a>
    <a href="themloaigiay.php" class="dashboard-card">
        <span class="dashboard-icon bg-purple"><i class="fa fa-shoe-prints"></i></span>
        <div>
            <div class="card-label">Loại giày</div>
            <div class="card-value"><?= $loaigiay_count ?></div>
        </div>
    </a>
    <a href="themmaugiay.php" class="dashboard-card">
        <span class="dashboard-icon bg-pink"><i class="fa fa-palette"></i></span>
        <div>
            <div class="card-label">Màu giày</div>
            <div class="card-value"><?= $maugiay_count ?></div>
        </div>
    </a>
    <a href="themthuonghieu.php" class="dashboard-card">
        <span class="dashboard-icon bg-cyan"><i class="fa fa-tag"></i></span>
        <div>
            <div class="card-label">Thương hiệu</div>
            <div class="card-value"><?= $thuonghieu_count ?></div>
        </div>
    </a>
    <a href="themsize.php" class="dashboard-card">
        <span class="dashboard-icon bg-gray"><i class="fa fa-ruler"></i></span>
        <div>
            <div class="card-label">Kích cỡ</div>
            <div class="card-value"><?= $kichco_count ?></div>
        </div>
    </a>
    <a href="sanphamhot.php" class="dashboard-card">
        <span class="dashboard-icon bg-orange"><i class="fa fa-fire"></i></span>
        <div>
            <div class="card-label">Khuyến mãi</div>
            <div class="card-value"><?= $khuyenmai_count ?></div>
        </div>
    </a>
</div>
</body>

</html>