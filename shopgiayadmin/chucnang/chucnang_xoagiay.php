<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
// chức năng xoá giày
if (isset($_POST['xoa_giay'])) {
    $magiay = mysqli_real_escape_string($conn, $_POST['xoa_giay']);

    $query = "DELETE FROM giay WHERE magiay='$magiay'";
    $query_run = mysqli_query($conn, $query);
    if ($query_run) {
        $_SESSION['message'] = "Xoá sản phẩm thành công";
        echo "<script>window.history.back();</script>";
        exit;
    } else {
        $_SESSION['message'] = "không có giầy";
        echo "<script>window.history.back();</script>";
        exit;;
    }
}
