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
    $mathuonghieu = mysqli_real_escape_string($conn, $_POST['mathuonghieu']);
    $tenthuonghieu = mysqli_real_escape_string($conn, $_POST['tenthuonghieu']);

    // Kiểm tra nếu tên thương hiệu bị trống
    if (empty($tenthuonghieu)) {
        $_SESSION['message'] = "Tên thương hiệu không được để trống.";
        header("Location: ../edit_thuonghieu.php?mathuonghieu=$mathuonghieu");
        exit;
    }

    // Cập nhật thương hiệu trong cơ sở dữ liệu
    $query = "UPDATE thuonghieu SET tenthuonghieu = ? WHERE mathuonghieu = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $tenthuonghieu, $mathuonghieu);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Cập nhật thương hiệu thành công.";
        // Viết vào bảng lịch sử nhân viên
        $ma_nhanvien = $_SESSION['ma_nhanvien'];
        $noidung = "Cập nhật thương hiệu: $tenthuonghieu";
        $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
        $stmt_lichsu = $conn->prepare($sql_lichsu);
        $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
        $stmt_lichsu->execute();
        $stmt_lichsu->close();
    } else {
        $_SESSION['message'] = "Cập nhật thương hiệu thất bại. Vui lòng thử lại.";
    }

    // Chuyển hướng về 
    header("Location: ../themthuonghieu.php");
    exit;
}
?>