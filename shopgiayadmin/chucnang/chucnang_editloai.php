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
    } else {
        $_SESSION['message'] = "Cập nhật loại giày thất bại. Vui lòng thử lại.";
    }

    // Chuyển hướng về trang danh sách loại giày
    header("Location: ../themloaigiay.php");
    exit;
}
?>