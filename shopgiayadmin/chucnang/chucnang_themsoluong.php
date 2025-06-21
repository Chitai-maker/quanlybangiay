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
