<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
// chức năng xoá giày
if(isset($_POST['xoa_thuonghieu']))
{
    $mathuonghieu = mysqli_real_escape_string($conn, $_POST['xoa_thuonghieu']);

    $query = "DELETE FROM thuonghieu WHERE mathuonghieu='$mathuonghieu'";
    $query_run = mysqli_query($conn, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Xoá thành công";
        header("Location: ../themthuonghieu.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "không có thương hiệu";
        header("Location: ../themthuonghieu.php");
        exit(0);
    }
}
?>