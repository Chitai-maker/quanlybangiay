<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}

// chức năng xoá giày
if (isset($_POST['xoa_giay'])) {
    $magiay = mysqli_real_escape_string($conn, $_POST['xoa_giay']);

    // Kiểm tra ràng buộc
    $check_hot = mysqli_query($conn, "SELECT 1 FROM sanphamhot WHERE magiay='$magiay' LIMIT 1");
    $check_danhgia = mysqli_query($conn, "SELECT 1 FROM danhgia WHERE magiay='$magiay' LIMIT 1");
    $check_ctdh = mysqli_query($conn, "SELECT 1 FROM chitietdonhang WHERE ma_giay='$magiay' LIMIT 1");

    if (
        mysqli_num_rows($check_hot) > 0 ||
        mysqli_num_rows($check_danhgia) > 0 ||
        mysqli_num_rows($check_ctdh) > 0
    ) {
        $_SESSION['message'] = "Không thể xoá: Sản phẩm đang tồn tại trong bảng sản phẩm hot, đánh giá hoặc chi tiết đơn hàng!";
        echo "<script>window.history.back();</script>";
        exit;
    }

    $query = "DELETE FROM giay WHERE magiay='$magiay'";
    $query_run = mysqli_query($conn, $query);
    if ($query_run) {
        $_SESSION['message'] = "Xoá sản phẩm thành công";
        echo "<script>window.history.back();</script>";
        exit;
    } else {
        $_SESSION['message'] = "không có giầy";
        echo "<script>window.history.back();</script>";
        exit;
    }
}
