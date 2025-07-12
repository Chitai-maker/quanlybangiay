<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
// chức năng xoá khách hàng
if(isset($_POST['xoa_nhanvien'])){
    $manhanvien = mysqli_real_escape_string($conn, $_POST['xoa_nhanvien']);

    $query = "DELETE FROM nhanvien WHERE ma_nhanvien='$manhanvien'";
    $query_run = mysqli_query($conn, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Xoá nhân viên thành công";
        // Viết vào bảng lịch sử nhân viên
        $ma_nhanvien = $_SESSION['ma_nhanvien'];
        $noidung = "Xoá nhân viên: Mã nhân viên $manhanvien";
        $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
        $stmt_lichsu = $conn->prepare($sql_lichsu);
        $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
        $stmt_lichsu->execute();
        $stmt_lichsu->close();
        mysqli_close($conn);
        header("Location: ../nhanvien.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "không có nhân viên";
        header("Location: ..//nhanvien.php");
        exit(0);
    }
}
