<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
if ($_POST["submit"]) {
    $TENTHUONGHIEU = $_POST["tenthuonghieu"];
            $query = "INSERT INTO thuonghieu (tenthuonghieu) VALUES ('$TENTHUONGHIEU')";
            if(mysqli_query($conn, $query)){
                $_SESSION['message'] = "Thêm thương hiệu thành công.";
                // viết vào bảng lịch sử nhân viên
                $ma_nhanvien = $_SESSION['ma_nhanvien'];
                $noidung = "Thêm thương hiệu: $TENTHUONGHIEU";
                $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
                $stmt_lichsu = $conn->prepare($sql_lichsu);
                $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
                $stmt_lichsu->execute();
                $stmt_lichsu->close();
                mysqli_close($conn);
                header("Location: ../themthuonghieu.php");
            }
        }
?>