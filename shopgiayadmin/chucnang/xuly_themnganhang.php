<?php

include "connectdb.php";

$ma_nhanvien = isset($_POST['ma_nhanvien']) ? intval($_POST['ma_nhanvien']) : 0;
$ten_chutaikhoan = isset($_POST['ten_chutaikhoan']) ? trim($_POST['ten_chutaikhoan']) : '';
$so_taikhoan = isset($_POST['so_taikhoan']) ? trim($_POST['so_taikhoan']) : '';
$ma_nganhang = isset($_POST['ma_nganhang']) ? trim($_POST['ma_nganhang']) : '';

if ($ma_nhanvien && $ten_chutaikhoan && $so_taikhoan && $ma_nganhang) {
    // Kiểm tra đã có thông tin ngân hàng cho nhân viên này chưa
    $stmt = $conn->prepare("SELECT 1 FROM thongtinnganhang WHERE ma_nhanvien = ?");
    $stmt->bind_param("i", $ma_nhanvien);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        // Đã có, cập nhật
        $stmt->close();
        $stmt = $conn->prepare("UPDATE thongtinnganhang SET ten_chutaikhoan=?, so_taikhoan=?, ma_nganhang=? WHERE ma_nhanvien=?");
        $stmt->bind_param("sssi", $ten_chutaikhoan, $so_taikhoan, $ma_nganhang, $ma_nhanvien);
        $stmt->execute();
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
    } else {
        // Chưa có, thêm mới
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO thongtinnganhang (ma_nhanvien, ten_chutaikhoan, so_taikhoan, ma_nganhang) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $ma_nhanvien, $ten_chutaikhoan, $so_taikhoan, $ma_nganhang);
        $stmt->execute();
        $_SESSION['message'] = "Thêm thông tin ngân hàng thành công.";
        // Viết vào bảng lịch sử nhân viên
        session_start();
        $ma_nhanvien_session = $_SESSION['ma_nhanvien'];
        $noidung = "Thêm thông tin ngân hàng: Mã nhân viên $ma_nhanvien";
        $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
        $stmt_lichsu = $conn->prepare($sql_lichsu);
        $stmt_lichsu->bind_param("is", $ma_nhanvien_session, $noidung);
        $stmt_lichsu->execute();
        $stmt_lichsu->close();
        mysqli_close($conn);
    }
    $stmt->close();
    header("Location: ../nhanvien.php");
    exit();
} else {
    header("Location: themnganhang.php?ma_nhanvien=$ma_nhanvien&ten_nhanvien=" . urlencode($_POST['ten_nhanvien'] ?? '') . "&error=1");
    exit();
}