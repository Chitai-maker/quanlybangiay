<?php
include "connectdb.php";
if (isset($_POST['submit'])) {
    $ten_coupon = mysqli_real_escape_string($conn, $_POST['ten_coupon']);
    $giatri = intval($_POST['giatri']);
    $ngaybatdau = $_POST['ngaybatdau'];
    $ngayketthuc = $_POST['ngayketthuc'];
    $sql = "INSERT INTO coupon (ten_coupon, giatri, ngaybatdau, ngayketthuc) VALUES ('$ten_coupon', $giatri, '$ngaybatdau', '$ngayketthuc')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Thêm coupon thành công!');</script>";
        // Viết vào bảng lịch sử nhân viên
        session_start();
        $ma_nhanvien = $_SESSION['ma_nhanvien'];
        $noidung = "Thêm coupon: $ten_coupon với giá trị $giatri % từ $ngaybatdau đến $ngayketthuc";
        $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
        $stmt_lichsu = $conn->prepare($sql_lichsu);
        $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
        $stmt_lichsu->execute();
        $stmt_lichsu->close();
        mysqli_close($conn);
        header("Location: ../magiamgia.php");
        exit;
    } else {
        echo "<script>alert('Lỗi khi thêm coupon!');window.location='../magiamgia.php';</script>";
    }
}
?>