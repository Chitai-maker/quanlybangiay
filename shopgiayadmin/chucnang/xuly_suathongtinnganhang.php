<?php
include "connectdb.php";

$ma_nhanvien = isset($_POST['ma_nhanvien']) ? intval($_POST['ma_nhanvien']) : 0;
$ten_chutaikhoan = isset($_POST['ten_chutaikhoan']) ? trim($_POST['ten_chutaikhoan']) : '';
$so_taikhoan = isset($_POST['so_taikhoan']) ? trim($_POST['so_taikhoan']) : '';
$ma_nganhang = isset($_POST['ma_nganhang']) ? trim($_POST['ma_nganhang']) : '';

if ($ma_nhanvien && $ten_chutaikhoan && $so_taikhoan && $ma_nganhang) {
    $stmt = $conn->prepare("UPDATE thongtinnganhang SET ten_chutaikhoan=?, so_taikhoan=?, ma_nganhang=? WHERE ma_nhanvien=?");
    $stmt->bind_param("sssi", $ten_chutaikhoan, $so_taikhoan, $ma_nganhang, $ma_nhanvien);
    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = "Cập nhật thông tin ngân hàng thành công.";
    // Viết vào bảng lịch sử nhân viên
    session_start();
    $ma_nhanvien_session = $_SESSION['ma_nhanvien'];
    $noidung = "Cập nhật thông tin ngân hàng: Mã nhân viên $ma_nhanvien";
    $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
    $stmt_lichsu = $conn->prepare($sql_lichsu);
    $stmt_lichsu->bind_param("is", $ma_nhanvien_session, $noidung);
    $stmt_lichsu->execute();
    $stmt_lichsu->close();
    mysqli_close($conn);
    header("Location: ../nhanvien.php?msg=update_bank_success");
    exit();
} else {
    header("Location: ../suathongtinnganhang.php?ma_nhanvien=$ma_nhanvien&error=1");
}