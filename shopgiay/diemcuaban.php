<?php
// filepath: c:\xampp\htdocs\quanlybangiay\shopgiay\diemthanhvien.php
session_start();
include "chucnang/connectdb.php";
include "header.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION['makhachhang'])) {
    echo "<div class='alert alert-danger'>Bạn cần đăng nhập để xem điểm thành viên!</div>";
    exit;
}

$ma_khachhang = $_SESSION['makhachhang'];
$sql = "SELECT ten_khachhang, diemthanhvien FROM khachhang WHERE ma_khachhang = '$ma_khachhang'";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) { ?>
    <div class='container mt-5'>
    <h4>Bạn hiện có <span class='text-success'><?php echo intval($row['diemthanhvien'])  ?> </span> điểm thành viên.</h4>
    </div>
    <?php
} else {
    echo "<div class='alert alert-danger'>Không tìm thấy thông tin khách hàng.</div>";
}
// Hiển thị coupon có thể đổi và nút tạo coupon mới
$diem = intval($row['diemthanhvien']);

// Quy định: 5 điểm đổi 1 coupon giảm 20%
$diem_can = 5;
$giatri_coupon = 20;

echo "<div class='container mt-3'>";
echo "<h5>Coupon có thể đổi:</h5>";
if ($diem >= $diem_can) {
    echo "<form method='post' action=''>";
    echo "<button type='submit' name='tao_coupon' class='btn btn-primary mb-2'>Tạo coupon mới (tốn $diem_can điểm, giảm $giatri_coupon%)</button>";
    echo "</form>";
} else {
    echo "<div class='alert alert-info'>Bạn cần ít nhất $diem_can điểm để đổi coupon mới.</div>";
}

// Xử lý tạo coupon mới
if (isset($_POST['tao_coupon']) && $diem >= $diem_can) {
    // Sinh mã coupon ngẫu nhiên
    $ma_coupon = strtoupper(substr(md5(uniqid()), 0, 8));
    $now = date('Y-m-d H:i:s');
    $ngayketthuc = date('Y-m-d H:i:s', strtotime('+7 days'));
    // Thêm coupon vào bảng coupon (KHÔNG có ma_khachhang)
    $sql_add = "INSERT INTO coupon (ten_coupon, giatri, ngaybatdau, ngayketthuc) VALUES ('$ma_coupon', $giatri_coupon, '$now', '$ngayketthuc')";
    if (mysqli_query($conn, $sql_add)) {
        // Trừ điểm
        mysqli_query($conn, "UPDATE khachhang SET diemthanhvien = diemthanhvien - $diem_can WHERE ma_khachhang = '$ma_khachhang'");
        echo "<div class='alert alert-success'>Đã tạo coupon mới: <b>$ma_coupon</b> (giảm $giatri_coupon%)</div>";
        // Reload để cập nhật điểm
        echo "<script>window.location.reload();</script>";
    } else {
        echo "<div class='alert alert-danger'>Lỗi khi tạo coupon!</div>";
    }
}


echo "</div>";
?>
