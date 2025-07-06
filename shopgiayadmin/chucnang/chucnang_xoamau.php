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
    // Kiểm tra ràng buộc: nếu màu đang được dùng trong bảng giay thì không cho xoá
    $check_giay = mysqli_query($conn, "SELECT 1 FROM giay WHERE mamaugiay='$mamau' LIMIT 1");
    if (mysqli_num_rows($check_giay) > 0) {
        $_SESSION['message'] = "Không thể xoá: Màu này đang được sử dụng cho sản phẩm!";
        header("Location: ../themmaugiay.php");
        exit(0);
    }
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
