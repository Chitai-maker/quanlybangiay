<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
if ($_POST["submit"]) {
    $TENLOAIGIAY = $_POST["tenloaigiay"];
            $query = "INSERT INTO loaigiay (tenloaigiay) VALUES ('$TENLOAIGIAY')";
            if(mysqli_query($conn, $query)){
                echo "Thêm loại giày thành công.";
                // viết vào bảng lịch sử nhân viên
                $ma_nhanvien = $_SESSION['ma_nhanvien'];
                $noidung = "Thêm loại giày: $TENLOAIGIAY";
                $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
                $stmt_lichsu = $conn->prepare($sql_lichsu);
                $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
                $stmt_lichsu->execute();
                $stmt_lichsu->close();
                mysqli_close($conn);
                header("Location: ../themloaigiay.php");
            }
        }
?>