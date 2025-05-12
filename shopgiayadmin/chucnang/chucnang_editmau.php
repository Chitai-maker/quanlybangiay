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
    $mamaugiay = mysqli_real_escape_string($conn, $_POST['mamaugiay']);
    $tenmaugiay = mysqli_real_escape_string($conn, $_POST['tenmaugiay']);

    // Kiểm tra nếu tên màu giày bị trống
    if (empty($tenmaugiay)) {
        $_SESSION['message'] = "Tên màu giày không được để trống.";
        header("Location: ../edit_mau.php?mamaugiay=$mamaugiay");
        exit;
    }

    // Cập nhật màu giày trong cơ sở dữ liệu
    $query = "UPDATE maugiay SET tenmaugiay = ? WHERE mamaugiay = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $tenmaugiay, $mamaugiay);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Cập nhật màu giày thành công.";
    } else {
        $_SESSION['message'] = "Cập nhật màu giày thất bại. Vui lòng thử lại.";
    }

    // Chuyển hướng về trang danh sách màu giày
    header("Location: ../themmaugiay.php");
    exit;
}
?>