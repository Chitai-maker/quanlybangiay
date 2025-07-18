<?php

include "connectdb.php";

$ma_nhanvien   = isset($_POST['ma_nhanvien']) ? intval($_POST['ma_nhanvien']) : 0;
$ten_nhanvien  = isset($_POST['ten_nhanvien']) ? trim($_POST['ten_nhanvien']) : '';
$email         = isset($_POST['email']) ? trim($_POST['email']) : '';
$sdt           = isset($_POST['sdt']) ? trim($_POST['sdt']) : '';
$diachi        = isset($_POST['diachi']) ? trim($_POST['diachi']) : '';
$gioitinh      = isset($_POST['gioitinh']) ? trim($_POST['gioitinh']) : '';
$ngaysinh      = isset($_POST['ngaysinh']) ? trim($_POST['ngaysinh']) : '';
$luong         = isset($_POST['luong']) ? floatval($_POST['luong']) : 0;
$quyen         = isset($_POST['quyen']) ? intval($_POST['quyen']) : 1;

if ($ma_nhanvien && $ten_nhanvien && $email && $sdt && $diachi && $gioitinh && $ngaysinh && $luong !== null) {
    $stmt = $conn->prepare("UPDATE nhanvien SET ten_nhanvien=?, email=?, sdt=?, diachi=?, gioitinh=?, ngaysinh=?, luong=?, quyen=? WHERE ma_nhanvien=?");
    $stmt->bind_param("ssssssdii", $ten_nhanvien, $email, $sdt, $diachi, $gioitinh, $ngaysinh, $luong, $quyen, $ma_nhanvien);
    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = "Cập nhật thông tin nhân viên thành công.";
    // Viết vào bảng lịch sử nhân viên
    session_start();
    $ma_nhanvien_session = $_SESSION['ma_nhanvien'];
    $noidung = "Cập nhật thông tin nhân viên: Mã nhân viên $ma_nhanvien";
    $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
    $stmt_lichsu = $conn->prepare($sql_lichsu);
    $stmt_lichsu->bind_param("is", $ma_nhanvien_session, $noidung);
    $stmt_lichsu->execute();
    $stmt_lichsu->close();
    mysqli_close($conn);
    header("Location: ../nhanvien.php?msg=update_success");
    exit();
} else {
    header("Location: ../suathongtin.php?ma_nhanvien=$ma_nhanvien&error=1");
    exit();
}