<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
// chức năng xoá size
if(isset($_POST['xoa_size']))
{
    $masize = mysqli_real_escape_string($conn, $_POST['xoa_size']);

    // Kiểm tra ràng buộc: nếu size đang được dùng trong bảng giay thì không cho xoá
    $check_giay = mysqli_query($conn, "SELECT 1 FROM giay WHERE masize='$masize' LIMIT 1");
    if (mysqli_num_rows($check_giay) > 0) {
        $_SESSION['message'] = "Không thể xoá: Size này đang được sử dụng cho sản phẩm!";
        header("Location: ../themsize.php");
        exit(0);
    }

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
