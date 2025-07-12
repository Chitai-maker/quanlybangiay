<?php
include_once("../chucnang/connectdb.php");
session_start();

if (isset($_POST['magiay']) && isset($_POST['soluong'])) {
    $magiay = intval($_POST['magiay']);
    $soluong = intval($_POST['soluong']);
    if ($magiay > 0 && $soluong > 0) {
        $sql = "UPDATE giay SET soluongtonkho = soluongtonkho + $soluong WHERE magiay = $magiay";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Đã cộng thêm $soluong vào tồn kho cho mã giày $magiay.";
            // Viết vào bảng lịch sử nhân viên
            $ma_nhanvien = $_SESSION['ma_nhanvien'];
            $noidung = "Cộng thêm $soluong vào tồn kho cho mã giày: $magiay";
            $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
            $stmt_lichsu = $conn->prepare($sql_lichsu);
            $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
            $stmt_lichsu->execute();
            $stmt_lichsu->close();
            mysqli_close($conn);
            // Redirect back to the previous page
            header("Location: ../hangtonkho.php");
            echo "<script>window.history.back();</script>";
            exit;
        } else {
            $_SESSION['message'] = "Lỗi: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['message'] = "Dữ liệu không hợp lệ!";
    }
    header("Location: ../them_soluong.php?magiay=$magiay");
    exit;
} else {
    $_SESSION['message'] = "Thiếu dữ liệu!";
    header("Location: ../them_soluong.php");
    exit;
}
