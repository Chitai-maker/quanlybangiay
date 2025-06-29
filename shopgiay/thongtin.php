<!-- filepath: c:\xampp\htdocs\shopgiay\thongtin.php -->
<?php
session_start();
include "header.php";
include "chucnang/connectdb.php";

// Kiểm tra xem khách hàng đã đăng nhập hay chưa
if (!isset($_SESSION['makhachhang'])) {
    echo "<script>alert('Bạn cần đăng nhập để xem thông tin cá nhân!'); window.location.href='login.php';</script>";
    exit();
}

// Lấy thông tin khách hàng từ cơ sở dữ liệu
$makhachhang = $_SESSION['makhachhang'];
$query = "SELECT * FROM khachhang WHERE ma_khachhang = '$makhachhang'";
$result = mysqli_query($conn, $query);

// Kiểm tra nếu tìm thấy thông tin khách hàng
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "<script>alert('Không tìm thấy thông tin khách hàng!'); window.location.href='index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin khách hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="text-center mt-4">
        <div class="item">
            <h1 class="text-center">Thông tin cá nhân :</h1>
            <h3><strong>Họ tên:</strong> <?php echo $row['ten_khachhang']; ?></h3>
            <h3><strong>Email:</strong> <?php echo $row['email']; ?></h3>
            <h3><strong>Số điện thoại:</strong> <?php echo $row['sdt']; ?></h3>
            <h3><strong>Địa chỉ:</strong> <?php echo $row['diachi']; ?></h3>
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-primary">Quay lại trang chủ</a>
                <a href="suathongtin.php" class="btn btn-secondary">Sửa thông tin</a>
            </div>
        </div>
    </div>
</body>
</html>