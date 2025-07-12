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
        // Viết vào bảng lịch sử nhân viên
        $ma_nhanvien = $_SESSION['ma_nhanvien'];
        $noidung = "Xoá size: Mã size $masize";
        $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
        $stmt_lichsu = $conn->prepare($sql_lichsu);
        $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
        $stmt_lichsu->execute();
        $stmt_lichsu->close();
        mysqli_close($conn);
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
