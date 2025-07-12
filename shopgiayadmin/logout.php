<?php
session_start();
// thêm vào lịch sử nhân viên
require 'chucnang/connectdb.php';
// thêm vào bảng lịch sử nhân viên
$ma_nhanvien = $_SESSION['ma_nhanvien'];
$noidung = "Đăng xuất khỏi hệ thống";
$sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
$stmt_lichsu = $conn->prepare($sql_lichsu);
$stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
$stmt_lichsu->execute();
session_unset();
session_destroy();
header("location:index.php");
