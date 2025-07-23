<?php
session_start();
if (!isset($_SESSION['name']))
    header("location:login.php");
if ($_SESSION['quyen'] > 1) {
    header("location:dangnhap_quyencaohon.php");
}
include "sidebar.php";
include "chucnang/connectdb.php";
$conn->set_charset("utf8mb4");

// Lấy mã đổi trả từ URL
$ma_doitrahang = isset($_GET['ma_doitrahang']) ? intval($_GET['ma_doitrahang']) : 0;

// Lấy thông tin đổi trả
$sql = "SELECT doitrahang.*, donhang.ma_khachhang, khachhang.ten_khachhang, khachhang.sdt, donhang.ma_donhang
        FROM doitrahang
        JOIN donhang ON doitrahang.ma_donhang = donhang.ma_donhang
        JOIN khachhang ON donhang.ma_khachhang = khachhang.ma_khachhang
        WHERE doitrahang.ma_doitrahang = $ma_doitrahang";
$result = $conn->query($sql);
$doitra = $result->fetch_assoc();

// Lấy chi tiết sản phẩm trong đơn hàng
$sql_ct = "SELECT chitietdonhang.*, giay.tengiay
           FROM chitietdonhang
           JOIN giay ON chitietdonhang.ma_giay = giay.magiay
           WHERE chitietdonhang.ma_donhang = {$doitra['ma_donhang']}";
$result_ct = $conn->query($sql_ct);

// Xử lý xác nhận/từ chối
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trangthai = $_POST['action'] == 'accept' ? 'Đã xác nhận đổi trả' : 'Từ chối đổi trả';
    $update_sql = "UPDATE doitrahang SET trangthai = '$trangthai' WHERE ma_doitrahang = $ma_doitrahang";
    $conn->query($update_sql);
    header("Location: doitra.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đổi trả</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Chi tiết đổi trả #<?php echo $ma_doitrahang; ?></h2>
    <div class="card mb-3">
        <div class="card-header">Thông tin khách hàng</div>
        <div class="card-body">
            <p><strong>Tên khách hàng:</strong> <?php echo $doitra['ten_khachhang']; ?></p>
            <p><strong>SĐT:</strong> <?php echo $doitra['sdt']; ?></p>
            <p><strong>Mã đơn hàng:</strong> <?php echo $doitra['ma_donhang']; ?></p>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">Ảnh đổi trả & Lý do</div>
        <div class="card-body">
            <img src="../shopgiay/<?php echo $doitra['anh']; ?>" style="max-width:200px;max-height:200px" class="mb-3"><br>
            <strong>Lý do:</strong> <?php echo htmlspecialchars($doitra['lydo']); ?><br>
            <strong>Thời gian yêu cầu:</strong> <?php echo $doitra['thoigian']; ?><br>
            <strong>Trạng thái:</strong> <?php echo $doitra['trangthai']; ?>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">Sản phẩm trong đơn hàng</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tên giày</th>
                        <th>Số lượng</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = $result_ct->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['tengiay']; ?></td>
                        <td><?php echo $row['soluong']; ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            <?php if ($doitra['trangthai'] == '' || $doitra['trangthai'] == 'đang xử lý'): ?>
    <form method="post" class="d-flex gap-2">
        <button type="submit" name="action" value="accept" class="btn btn-success">Xác nhận đổi trả</button>
        <button type="submit" name="action" value="deny" class="btn btn-danger">Từ chối đổi trả</button>
    </form>
    <?php endif; ?>
        </div>
    </div>
    
    <a href="doitra.php" class="btn btn-secondary mt-3">Quay lại danh sách</a>
</div>
</body>