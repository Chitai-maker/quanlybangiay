<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
// chức năng xoá giày
if(isset($_POST['xoa_mau']))
{
    $mamau = mysqli_real_escape_string($conn, $_POST['xoa_mau']);

    $query = "DELETE FROM maugiay WHERE mamaugiay ='$mamau'";
    $query_run = mysqli_query($conn, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Xoá thành công";
        header("Location: ../themmaugiay.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "không có màu";
        header("Location: ../themmaugiay.php");
        exit(0);
    }
}
