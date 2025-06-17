<?php
include "sidebar.php";
include_once("chucnang/connectdb.php");


if (!isset($_SESSION['name'])) header("location:login.php");
if ($_SESSION['quyen'] > 1) header("location:dangnhap_quyencaohon.php");

$ma_donhang = isset($_GET['ma_donhang']) ? intval($_GET['ma_donhang']) : 0;
if ($ma_donhang <= 0) {
    echo "<div class='alert alert-danger'>Mã đơn hàng không hợp lệ!</div>";
    exit;
}

// Lấy thông tin đơn hàng
$sql_dh = "SELECT dh.*, kh.ten_khachhang, kh.email, kh.sdt, kh.diachi 
           FROM donhang dh 
           JOIN khachhang kh ON dh.ma_khachhang = kh.ma_khachhang 
           WHERE dh.ma_donhang = $ma_donhang";
$res_dh = mysqli_query($conn, $sql_dh);
$donhang = mysqli_fetch_assoc($res_dh);

if (!$donhang) {
    echo "<div class='alert alert-danger'>Không tìm thấy đơn hàng!</div>";
    exit;
}

// Lấy chi tiết sản phẩm trong đơn hàng
$sql_ct = "SELECT ct.*, g.tengiay, g.giaban, g.anhminhhoa 
           FROM chitietdonhang ct 
           JOIN giay g ON ct.ma_giay = g.magiay 
           WHERE ct.ma_donhang = $ma_donhang";
$res_ct = mysqli_query($conn, $sql_ct);
?>

<style>
@media print {
    .btn, .sidebar, .header, .footer {
        display: none !important;
    }
    body {
        background: #fff !important;
    }
    
}
</style>
<a href="donhang.php" class="btn btn-secondary">← Quay lại</a>
<div class="container mt-4">
    <h2 class="mb-4 text-center"><i class="fa fa-file-invoice"></i> Hoá đơn đơn hàng Đơn Hàng #<?= $ma_donhang ?></h2>
    <div class="mb-3">
        <b>Khách hàng:</b> <?= htmlspecialchars($donhang['ten_khachhang']) ?><br>
        <b>Email:</b> <?= htmlspecialchars($donhang['email']) ?><br>
        <b>SĐT:</b> <?= htmlspecialchars($donhang['sdt']) ?><br>
        <b>Địa chỉ:</b> <?= htmlspecialchars($donhang['diachi']) ?><br>
        <b>Ngày đặt:</b> <?= htmlspecialchars($donhang['ngaydat']) ?><br>
        <b>Trạng thái:</b> <?= htmlspecialchars($donhang['trangthai']) ?><br>
    </div>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>STT</th>
                <th>Ảnh</th>
                <th>Tên giày</th>
                <th>Giá bán</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $tong = 0; $i = 1;
        while ($row = mysqli_fetch_assoc($res_ct)): 
            $thanhtien = $row['giaban'] * $row['soluong'];
            $tong += $thanhtien;
        ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><img src="../img/<?= htmlspecialchars($row['anhminhhoa']) ?>" alt="" style="width:60px"></td>
                <td><?= htmlspecialchars($row['tengiay']) ?></td>
                <td><?= number_format($row['giaban'], 0, ',', '.') ?> VND</td>
                <td><?= $row['soluong'] ?></td>
                <td><?= number_format($thanhtien, 0, ',', '.') ?> VND</td>
            </tr>
        <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-end">Tổng cộng:</th>
                <th><?= number_format($tong, 0, ',', '.') ?> VND</th>
            </tr>
        </tfoot>
    </table>
    <button class="btn btn-success mb-3" onclick="window.print()">
        <i class="fa fa-print"></i> In hóa đơn
    </button>
    
</div>