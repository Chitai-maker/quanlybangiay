<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
    exit;
}
if (isset($_POST["submit"])) {
    $MAGIAY = $_POST["magiay"];
    $TENGIAY = $_POST["tengiay"];
    $MALOAIGIAY = $_POST["loaigiay"];
    $MATHUONGHIEU = $_POST["thuonghieu"];
    $MAMAUGIAY = $_POST["maugiay"];
    $DONVITINH = $_POST["donvitinh"];
    $GIABAN = $_POST["giaban"];
    $MOTA = $_POST["mota"];
    $ANHMINHHOA = $_FILES["anhminhhoa"]["name"];
    if (empty($ANHMINHHOA)) {
        // Không chọn ảnh mới, lấy ảnh cũ từ DB
        $sql_old = "SELECT anhminhhoa FROM giay WHERE magiay = '$MAGIAY'";
        $result_old = mysqli_query($conn, $sql_old);
        $row_old = mysqli_fetch_assoc($result_old);
        $ANHMINHHOA = $row_old['anhminhhoa'];
        $update_img = false;
    } else {
        $ext = pathinfo($ANHMINHHOA, PATHINFO_EXTENSION);
        $allowedTypes = array("jpg", "jpeg", "png", "gif");
        $tempName = $_FILES["anhminhhoa"]["tmp_name"];
        $targetPath = "../anhgiay/". $ANHMINHHOA;
        $update_img = true;
    }

    if (!$update_img || (in_array($ext, $allowedTypes) && move_uploaded_file($tempName, $targetPath))) {
        $query = "UPDATE `giay` SET `tengiay`='$TENGIAY',`maloaigiay`='$MALOAIGIAY',`mathuonghieu`='$MATHUONGHIEU',`mamaugiay`='$MAMAUGIAY',`donvitinh`='$DONVITINH',`giaban`='$GIABAN',`anhminhhoa`='$ANHMINHHOA',`mota`='$MOTA' WHERE magiay = '$MAGIAY'";
        if (mysqli_query($conn, $query)) {
            echo "Update successful.";
             // viết vào bảng lịch sử nhân viên
            $ma_nhanvien = $_SESSION['ma_nhanvien'];
            $noidung = "edit giày: $TENGIAY";

            $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
            $stmt_lichsu = $conn->prepare($sql_lichsu);
            $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
            $stmt_lichsu->execute();
            header("Location: ../index.php");
            exit;
        } else {
            echo "Something is wrong: " . mysqli_error($conn);
            exit;
        }
    } else {
        echo "File upload failed hoặc file không hợp lệ.";
        exit;
    }
}

