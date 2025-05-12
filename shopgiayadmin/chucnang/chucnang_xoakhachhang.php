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
