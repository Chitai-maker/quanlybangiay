<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
if ($_POST["submit"]) {
    $TENGIAY = $_POST["tengiay"];
    $MALOAIGIAY = $_POST["loaigiay"];
    $MATHUONGHIEU = $_POST["thuonghieu"];
    $MAMAUGIAY = $_POST["maugiay"];
    $MASIZE = $_POST["sizegiay"];
    $DONVITINH = $_POST["donvitinh"];
    $GIABAN = $_POST["giaban"];
    $MOTA = $_POST["mota"];
    $ANHMINHHOA = $_FILES["anhminhhoa"]["name"];
    $ext = pathinfo($ANHMINHHOA, PATHINFO_EXTENSION);
    $allowedTypes = array("jpg", "jpeg", "png", "gif");
    $tempName = $_FILES["anhminhhoa"]["tmp_name"];
    $targetPath = "../anhgiay/".$ANHMINHHOA;
    if(in_array($ext, $allowedTypes)){
        if(move_uploaded_file($tempName, $targetPath)){
            $query = "INSERT INTO `giay`(`magiay`, `tengiay`, `maloaigiay`, `mathuonghieu`, `mamaugiay`, `masize`, `donvitinh`, `giaban`, `anhminhhoa`, `mota`)
             VALUES  (NULL ,'$TENGIAY', '$MALOAIGIAY','$MATHUONGHIEU','$MAMAUGIAY','$MASIZE','$DONVITINH','$GIABAN','$ANHMINHHOA','$MOTA')";
             
            if(mysqli_query($conn, $query)){
                echo "Thêm giày thành công.";
                // viết vào bảng lịch sử nhân viên
                $ma_nhanvien = $_SESSION['ma_nhanvien'];
                $noidung = "Thêm giày: $TENGIAY";

                $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
                $stmt_lichsu = $conn->prepare($sql_lichsu);
                $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
                $stmt_lichsu->execute();
               header("Location: ../index.php");
            }else{
                echo "Something is wrong";
            }
        }else{
            echo "File is not uploaded";
        }
    }else{
        echo "Your files are not allowed";
    }
}
?>