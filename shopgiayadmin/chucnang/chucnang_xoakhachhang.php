<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
// chức năng xoá khách hàng
if(isset($_POST['xoa_khachhang']))
{
    $makhachhang = mysqli_real_escape_string($conn, $_POST['xoa_khachhang']);
    // Kiểm tra ràng buộc: nếu khách hàng có đơn hàng thì không cho xoá
    $check_donhang = mysqli_query($conn, "SELECT 1 FROM donhang WHERE ma_khachhang='$makhachhang' LIMIT 1");
    $check_danhgia = mysqli_query($conn, "SELECT 1 FROM danhgia WHERE ma_khachhang='$makhachhang' LIMIT 1");
    if (mysqli_num_rows($check_donhang) > 0
        || mysqli_num_rows($check_danhgia) > 0) {
        $_SESSION['message'] = "Không thể xoá: Khách hàng này đang có đơn hàng! Hoặc đã đánh giá sản phẩm!";
        header("Location: ../khachhang.php");
        exit(0);
    }

    $query = "DELETE FROM khachhang WHERE ma_khachhang='$makhachhang'";
    $query_run = mysqli_query($conn, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Xoá khách hàng thành công";
        header("Location: ../khachhang.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "không có khách hàng";
        header("Location: ..//khachhang.php");
        exit(0);
    }
}
