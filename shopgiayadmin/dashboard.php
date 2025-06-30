<?php
session_start();
if (!isset($_SESSION['name']))
    header("location:login.php");
include "sidebar.php";
include_once("chucnang/connectdb.php");
if ($_SESSION['quyen'] > 0) {
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
//đếm sản phẩm hết hàng
$query14 = "SELECT COUNT(*) AS total FROM giay WHERE soluongtonkho = 0";
$result14 = mysqli_query($conn, $query14);
$row14 = mysqli_fetch_assoc($result14);
$hethang_count = $row14['total'];
// Đếm số lượng sản phẩm sắp hết hàng
$query15 = "SELECT COUNT(*) AS total FROM giay WHERE soluongtonkho < 5";
$result15 = mysqli_query($conn, $query15);
$row15 = mysqli_fetch_assoc($result15);
$saphethang_count = $row15['total'];
//đém số đánh giá
$query16 = "SELECT COUNT(*) AS total FROM danhgia";
$result16 = mysqli_query($conn, $query16);
$row16 = mysqli_fetch_assoc($result16);
$danhgia_count = $row16['total'];
//đếm sô lượng coupon còn hiệu lực
$query17 = "SELECT COUNT(*) AS total FROM coupon WHERE ngayketthuc > NOW()
AND ngaybatdau <= NOW()";
$result17 = mysqli_query($conn, $query17);
$row17 = mysqli_fetch_assoc($result17);
$coupons_count = $row17['total'];
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
<title>Dashboard - Quản trị Shop Giày</title>
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
    <a href="index.php" class="dashboard-card">
        <span class="dashboard-icon bg-pink"><i class="fa fa-store"></i></span>
        <div>
            <div class="card-label">Mặt hàng</div>
            <div class="card-value"><?= $sanpham_count ?></div>
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
    <a href="khuyenmai.php" class="dashboard-card">
        <span class="dashboard-icon bg-orange"><i class="fa fa-fire"></i></span>
        <div>
            <div class="card-label">Khuyến mãi</div>
            <div class="card-value"><?= $khuyenmai_count ?></div>
        </div>
    </a>
    <a href="thongke.php" class="dashboard-card">
        <span class="dashboard-icon bg-green"><i class="fa fa-dollar-sign"></i></span>
        <div>
            <div class="card-label">Doanh số</div>
            <div class="card-value"><?= $doanhthu_total ?> đ</div>
        </div>
    </a>
    <a href="hethang.php" class="dashboard-card">
        <span class="dashboard-icon bg-red"><i class="fa fa-box-open"></i></span>
        <div>
            <div class="card-label">Hết hàng</div>
            <div class="card-value"><?= $hethang_count ?></div>
        </div>
    </a>
    <a href="saphethang.php" class="dashboard-card">
        <span class="dashboard-icon bg-blue"><i class="fa fa-boxes"></i></span>
        <div>
            <div class="card-label">Sắp hết hàng</div>
            <div class="card-value"><?= $saphethang_count ?></div>
        </div>
    </a>
    <a href="danhgia.php" class="dashboard-card">
        <span class="dashboard-icon bg-purple"><i class="fa fa-star"></i></span>
        <div>
            <div class="card-label">Đánh giá</div>
            <div class="card-value"><?= $danhgia_count ?></div>
        </div>
    </a>
    <a href="magiamgia.php" class="dashboard-card">
        <span class="dashboard-icon bg-green"><i class="fa fa-ticket-alt"></i></span>
        <div>
            <div class="card-label">Số coupon hiệu lực</div>
            <div class="card-value"><?= $coupons_count ?></div>
        </div>
    </a>
</div>

<div class="row mb-4">
    <!-- Bảng khách hàng mới -->
    <div class="col-md-6">
        <div class="card shadow-sm mb-4" style="max-width:400px;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><b>Khách hàng mới</b></span>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-ellipsis-h"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="khachhang.php">Xem tất cả</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-2">
                <ul class="list-unstyled mb-0">
                    <?php
                    // Lấy 6 khách hàng mới nhất
                    $sql = "SELECT ten_khachhang, email, sdt FROM khachhang ORDER BY ma_khachhang DESC LIMIT 6";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)):
                    ?>
                        <li class="d-flex align-items-center mb-2">
                            <i class="fa fa-user-circle fa-2x me-2"></i>
                            <div class="flex-grow-1">
                                <div><b><?= htmlspecialchars($row['ten_khachhang']) ?></b></div>
                                <div style="font-size:13px; color:#888;">
                                    <?= htmlspecialchars($row['email']) ?> | <?= htmlspecialchars($row['sdt']) ?>
                                </div>
                            </div>
                            <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="text-primary me-2" title="Gửi mail">
                                <i class="fa fa-envelope"></i>
                            </a>

                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
    <!-- Bảng đơn hàng mới -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><b>Đơn hàng mới</b></span>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-ellipsis-h"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="donhang.php">Xem tất cả</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-2">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="color:#b0b3b9;">MÃ ĐƠN HÀNG</th>
                            <th style="color:#b0b3b9;">NGÀY ĐẶT HÀNG</th>

                            <th style="color:#b0b3b9;">TRẠNG THÁI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Lấy đơn hàng mới nhất
                        $sql = "SELECT ma_donhang, ngaydat, trangthai
                                FROM donhang
                                WHERE trangthai = 'Đang xử lý'
                                ORDER BY ma_donhang DESC
                                LIMIT 3";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result)):
                        ?>
                            <tr>
                                <td>
                                    <span class="badge bg-primary rounded-circle me-2" style="width:28px;height:28px;"><i class="fa fa-check fa-lg"></i></span>
                                    <b>#<?= $row['ma_donhang'] ?></b>
                                </td>
                                <td><?= date('d/m/Y H:i:s', strtotime($row['ngaydat'])) ?></td>

                                <td>
                                    <span class="btn btn-sm btn-outline-primary"><?= htmlspecialchars($row['trangthai']) ?></span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- bảng đánh giá mới -->
<div class="col-md-12 mt-4">
    <div class="card shadow-sm mb-4" style="max-width:100%;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><b>Đánh giá mới nhất</b></span>
            <div class="dropdown">
                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-ellipsis-h"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="danhgia.php">Xem tất cả</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body p-2">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="color:#b0b3b9;">Sản phẩm</th>
                        <th style="color:#b0b3b9;">Khách hàng</th>
                        <th style="color:#b0b3b9;">Số sao</th>
                        <th style="color:#b0b3b9;">Bình luận</th>
                        <th style="color:#b0b3b9;">Ngày đánh giá</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Lấy 5 đánh giá mới nhất
                    $sql = "SELECT d.*, g.tengiay, k.ten_khachhang 
                            FROM danhgia d
                            LEFT JOIN giay g ON d.magiay = g.magiay
                            LEFT JOIN khachhang k ON d.ma_khachhang = k.ma_khachhang
                            ORDER BY d.ngaydanhgia DESC
                            LIMIT 5";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)):
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['tengiay']) ?></td>
                            <td><?= htmlspecialchars($row['ten_khachhang']) ?></td>
                            <td>
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $row['danhgia']) {
                                        echo '<span style="color: gold;">&#9733;</span>';
                                    } else {
                                        echo '<span style="color: #ccc;">&#9733;</span>';
                                    }
                                }
                                ?>
                            </td>
                            <td><?= nl2br(htmlspecialchars($row['binhluan'])) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($row['ngaydanhgia'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
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