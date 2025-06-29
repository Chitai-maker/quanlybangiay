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

if (isset($_POST['save'])) {
    $ten = mysqli_real_escape_string($conn, $_POST['ten_khachhang']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sdt = mysqli_real_escape_string($conn, $_POST['sdt']);
    $diachi = mysqli_real_escape_string($conn, $_POST['diachi']);
    $sql_update = "UPDATE khachhang SET ten_khachhang='$ten', email='$email', sdt='$sdt', diachi='$diachi' WHERE ma_khachhang='$makhachhang'";
    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('Cập nhật thành công!');window.location.href='suathongtin.php';</script>";
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Cập nhật thất bại!</div>";
    }
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
           
            <h1 class="text-center">Sửa Thông tin cá nhân :</h1>
            <form method="post" action="">
                <div class="form-group row mb-3">
                    <label class="col-sm-3 col-form-label text-right"><strong>Họ tên:</strong></label>
                    <div class="col-sm-9">
                        <input type="text" name="ten_khachhang" class="form-control form-control-sm w-75" value="<?php echo htmlspecialchars($row['ten_khachhang']); ?>" required>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label class="col-sm-3 col-form-label text-right"><strong>Email:</strong></label>
                    <div class="col-sm-9">
                        <input type="email" name="email" class="form-control form-control-sm w-75" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label class="col-sm-3 col-form-label text-right"><strong>Số điện thoại:</strong></label>
                    <div class="col-sm-9">
                        <input type="text" name="sdt" class="form-control form-control-sm w-75" value="<?php echo htmlspecialchars($row['sdt']); ?>" required>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label class="col-sm-3 col-form-label text-right"><strong>Địa chỉ:</strong></label>
                    <div class="col-sm-9">
                        <input type="text" name="diachi" class="form-control form-control-sm w-75" value="<?php echo htmlspecialchars($row['diachi']); ?>" required>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" name="save" class="btn btn-success">Lưu</button>
                    <a href="index.php" class="btn btn-primary ml-2">Quay lại trang chủ</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>