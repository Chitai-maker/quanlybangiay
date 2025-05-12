<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
// chức năng xoá khách hàng
if(isset($_POST['xoa_nhanvien'])){
    $manhanvien = mysqli_real_escape_string($conn, $_POST['xoa_nhanvien']);

    $query = "DELETE FROM nhanvien WHERE ma_nhanvien='$manhanvien'";
    $query_run = mysqli_query($conn, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Xoá nhân viên thành công";
        header("Location: ../nhanvien.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "không có nhân viên";
        header("Location: ..//nhanvien.php");
        exit(0);
    }
}
