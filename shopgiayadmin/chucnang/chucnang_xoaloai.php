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
    // Kiểm tra ràng buộc: nếu loại giày đang được dùng trong bảng giay thì không cho xoá
    $check_giay = mysqli_query($conn, "SELECT 1 FROM giay WHERE maloaigiay='$maloaigiay' LIMIT 1");
    if (mysqli_num_rows($check_giay) > 0) {
        $_SESSION['message'] = "Không thể xoá: Loại giày này đang được sử dụng cho sản phẩm!";
        header("Location: ../themloaigiay.php");
        exit(0);
    }
    $query = "DELETE FROM loaigiay WHERE maloaigiay='$maloaigiay'";
    $query_run = mysqli_query($conn, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Xoá thành công";
        // Viết vào bảng lịch sử nhân viên
        $ma_nhanvien = $_SESSION['ma_nhanvien'];
        $noidung = "Xoá loại giày: Mã loại giày $maloaigiay";
        $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
        $stmt_lichsu = $conn->prepare($sql_lichsu);
        $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
        $stmt_lichsu->execute();
        $stmt_lichsu->close();
        mysqli_close($conn);
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