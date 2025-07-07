<?php

include "sidebar.php";
include "chucnang/connectdb.php";

$ma_nhanvien = isset($_GET['ma_nhanvien']) ? intval($_GET['ma_nhanvien']) : 0;
$ten_nhanvien = "";
$email = "";
$sdt = "";
$diachi = "";
$gioitinh = "";
$ngaysinh = "";
$luong = "";
$quyen = "";

if ($ma_nhanvien) {
    $stmt = $conn->prepare("SELECT ten_nhanvien, email, sdt, diachi, gioitinh, ngaysinh, luong, quyen FROM nhanvien WHERE ma_nhanvien = ?");
    $stmt->bind_param("i", $ma_nhanvien);
    $stmt->execute();
    $stmt->bind_result($ten_nhanvien, $email, $sdt, $diachi, $gioitinh, $ngaysinh, $luong, $quyen);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sửa thông tin nhân viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <form method="post" action="chucnang/xuly_suathongtin.php">
        <h2>Sửa thông tin nhân viên (ID: <?= $ma_nhanvien ?>)</h2>
        <input type="hidden" name="ma_nhanvien" value="<?= $ma_nhanvien ?>">
        <div class="mb-3">
            <label class="form-label">Tên nhân viên</label>
            <input type="text" class="form-control" name="ten_nhanvien" value="<?= htmlspecialchars($ten_nhanvien) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" name="sdt" value="<?= htmlspecialchars($sdt) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" class="form-control" name="diachi" value="<?= htmlspecialchars($diachi) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Giới tính</label>
            <select class="form-control" name="gioitinh" required>
                <option value="Nam" <?= ($gioitinh == "Nam") ? "selected" : "" ?>>Nam</option>
                <option value="Nữ" <?= ($gioitinh == "Nữ") ? "selected" : "" ?>>Nữ</option>
                <option value="Khác" <?= ($gioitinh == "Khác") ? "selected" : "" ?>>Khác</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày sinh</label>
            <input type="date" class="form-control" name="ngaysinh" value="<?= htmlspecialchars($ngaysinh) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Lương</label>
            <input type="number" class="form-control" name="luong" value="<?= htmlspecialchars($luong) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Quyền</label>
            <select class="form-control" name="quyen" required>
                <option value="0" <?= ($quyen == 0) ? "selected" : "" ?>>Admin</option>
                <option value="1" <?= ($quyen == 1) ? "selected" : "" ?>>Nhân viên bán hàng</option>
                <option value="2" <?= ($quyen == 2) ? "selected" : "" ?>>Nhân viên kho</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
    </form>
</div>
</body>
</html>