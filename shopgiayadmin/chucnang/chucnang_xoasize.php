<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
// chức năng xoá giày
if(isset($_POST['xoa_size']))
{
    $masize = mysqli_real_escape_string($conn, $_POST['xoa_size']);

    $query = "DELETE FROM sizegiay WHERE masize ='$masize'";
    $query_run = mysqli_query($conn, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Xoá thành công";
        header("Location: ../themsize.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "không có size";
        header("Location: ../themsize.php");
        exit(0);
    }
}
