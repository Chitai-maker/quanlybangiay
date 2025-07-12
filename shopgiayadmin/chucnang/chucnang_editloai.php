<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'connectdb.php';
session_start();

if (!isset($_SESSION['name'])) {
    header("location:login.php");
    exit;
}

if (isset($_POST['submit'])) {
    $maloaigiay = mysqli_real_escape_string($conn, $_POST['maloaigiay']);
    $tenloaigiay = mysqli_real_escape_string($conn, $_POST['tenloaigiay']);

    // Kiểm tra nếu tên loại giày bị trống
    if (empty($tenloaigiay)) {
        $_SESSION['message'] = "Tên loại giày không được để trống.";
        header("Location: ../edit_loai.php?maloaigiay=$maloaigiay");
        exit;
    }

    // Cập nhật loại giày trong cơ sở dữ liệu
    $query = "UPDATE loaigiay SET tenloaigiay = ? WHERE maloaigiay = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $tenloaigiay, $maloaigiay);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Cập nhật loại giày thành công.";
        // Viết vào bảng lịch sử nhân viên
        $ma_nhanvien = $_SESSION['ma_nhanvien'];
        $noidung = "Cập nhật loại giày: $tenloaigiay";
        $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
        $stmt_lichsu = $conn->prepare($sql_lichsu);
        $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
        $stmt_lichsu->execute();
        $stmt_lichsu->close();
        
    } else {
        $_SESSION['message'] = "Cập nhật loại giày thất bại. Vui lòng thử lại.";
    }

    // Chuyển hướng về trang danh sách loại giày
    header("Location: ../themloaigiay.php");
    exit;
}
?>