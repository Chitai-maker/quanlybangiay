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
    // Kiểm tra ràng buộc: nếu thương hiệu đang được dùng trong bảng giay thì không cho xoá
    $check_giay = mysqli_query($conn, "SELECT 1 FROM giay WHERE mathuonghieu='$mathuonghieu' LIMIT 1");
    if (mysqli_num_rows($check_giay) > 0) {
        $_SESSION['message'] = "Không thể xoá: Thương hiệu này đang được sử dụng cho sản phẩm!";
        header("Location: ../themthuonghieu.php");
        exit(0);
    }
    $query = "DELETE FROM thuonghieu WHERE mathuonghieu='$mathuonghieu'";
    $query_run = mysqli_query($conn, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Xoá thành công";
        // Viết vào bảng lịch sử nhân viên
        $ma_nhanvien = $_SESSION['ma_nhanvien'];
        $noidung = "Xoá thương hiệu: Mã thương hiệu $mathuonghieu";
        $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
        $stmt_lichsu = $conn->prepare($sql_lichsu);
        $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
        $stmt_lichsu->execute();
        $stmt_lichsu->close();
        mysqli_close($conn);
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