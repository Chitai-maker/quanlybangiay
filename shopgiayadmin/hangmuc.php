<?php
session_start();
if (!isset($_SESSION['name']))
    header("location:login.php");
include "sidebar.php";
include_once("chucnang/connectdb.php");
if ($_SESSION['quyen'] > 0) {
    header("location:dangnhap_quyencaohon.php");
}

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
<title>Hạng muc</title>
<h1>Hạng mục</h1>
<div class="dashboard-cards">
    
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
</div>


<?php
// Đóng kết nối
mysqli_close($conn);
?>
<!-- Thêm vào cuối trang (nếu chưa có) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>