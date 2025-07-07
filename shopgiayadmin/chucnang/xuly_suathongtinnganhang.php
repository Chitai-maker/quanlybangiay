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
    header("Location: ../nhanvien.php?msg=update_bank_success");
    exit();
} else {
    header("Location: ../suathongtinnganhang.php?ma_nhanvien=$ma_nhanvien&error=1");
}