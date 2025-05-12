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
    $masize = mysqli_real_escape_string($conn, $_POST['masize']);
    $tensize = mysqli_real_escape_string($conn, $_POST['tensize']);

    // Kiểm tra nếu tên size bị trống
    if (empty($tensize)) {
        $_SESSION['message'] = "Tên size không được để trống.";
        header("Location: ../edit_size.php?masize=$masize");
        exit;
    }

    // Cập nhật size giày trong cơ sở dữ liệu
    $query = "UPDATE sizegiay SET tensize = ? WHERE masize = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $tensize, $masize);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Cập nhật size thành công.";
    } else {
        $_SESSION['message'] = "Cập nhật size thất bại. Vui lòng thử lại.";
    }

    // Chuyển hướng về trang danh sách size
    header("Location: ../themsize.php");
    exit;
}
?>