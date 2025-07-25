<?php
session_start();
if (!isset($_SESSION['name']))
    header("location:login.php");

include "sidebar.php";
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
<title>Thống kê - Báo Cáo Tổng Hợp</title>
<h1>📊 Báo Cáo Tổng Hợp</h1>
<div class="dashboard-cards">
    
    <a href="doanhthu.php" class="dashboard-card">
        <span class="dashboard-icon bg-green"><i class="fa fa-dollar-sign"></i></span>
        <div>
            <div class="card-label">Doanh thu theo tháng</div>
            
        </div>
    </a>
    <a href="doanhthusanpham.php" class="dashboard-card">
        <span class="dashboard-icon bg-green"><i class="fa fa-chart-bar"></i></span>
        <div>
            <div class="card-label">Thống kê sản phẩm bán chạy nhất</div>
        </div>
    </a>
    <a href="doanhthukhachhang.php" class="dashboard-card">
        <span class="dashboard-icon bg-green"><i class="fa-solid fa-user"></i></span>
        <div>
            <div class="card-label">Doanh thu theo khách hàng</div>
        </div>
    </a>
    <a href="doanhthuthuonghieu.php" class="dashboard-card">
        <span class="dashboard-icon bg-green"><i class="fa-solid fa-copyright"></i></i></span>
        <div>
            <div class="card-label">Doanh thu theo thương hiệu</div>
        </div>
    </a>
    <a href="doanhthuloai.php" class="dashboard-card">
        <span class="dashboard-icon bg-green"><i class="fa-solid fa-list"></i></span>
        <div>
            <div class="card-label">Doanh thu theo loại</div>
        </div>
    </a>

</div>
</body>

</html>