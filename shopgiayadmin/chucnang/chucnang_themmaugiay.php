<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
if ($_POST["submit"]) {
    $TENMAUGIAY = $_POST["tenmaugiay"];
    $check_query = "SELECT * FROM maugiay WHERE tenmaugiay = '$TENMAUGIAY'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['message'] = "Màu giày đã tồn tại.";
        header("Location: ../themmaugiay.php");
        exit();
    }
            $query = "INSERT INTO maugiay (tenmaugiay) VALUES ('$TENMAUGIAY')";
            if(mysqli_query($conn, $query)){
                echo "Thêm màu giày thành công.";
                // viết vào bảng lịch sử nhân viên
                $ma_nhanvien = $_SESSION['ma_nhanvien'];
                $noidung = "Thêm màu giày: $TENMAUGIAY";
                $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
                $stmt_lichsu = $conn->prepare($sql_lichsu);
                $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
                $stmt_lichsu->execute();
                $stmt_lichsu->close();
                mysqli_close($conn);
                // Chuyển hướng về trang danh sách màu giày
                header("Location: ../themmaugiay.php");
            }
        }
?>