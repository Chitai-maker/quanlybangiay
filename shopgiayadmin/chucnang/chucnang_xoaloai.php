<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
// chức năng xoá giày
if(isset($_POST['xoa_loai']))
{
    $maloaigiay = mysqli_real_escape_string($conn, $_POST['xoa_loai']);

    $query = "DELETE FROM loaigiay WHERE maloaigiay='$maloaigiay'";
    $query_run = mysqli_query($conn, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Xoá thành công";
        header("Location: ../themloaigiay.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "không có loại giày";
        header("Location: ../themloaigiay.php");
        exit(0);
    }
}

?>