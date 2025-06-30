<?php
include "connectdb.php";
if (isset($_POST['submit'])) {
    $ten_coupon = mysqli_real_escape_string($conn, $_POST['ten_coupon']);
    $giatri = intval($_POST['giatri']);
    $ngaybatdau = $_POST['ngaybatdau'];
    $ngayketthuc = $_POST['ngayketthuc'];
    $sql = "INSERT INTO coupon (ten_coupon, giatri, ngaybatdau, ngayketthuc) VALUES ('$ten_coupon', $giatri, '$ngaybatdau', '$ngayketthuc')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Thêm coupon thành công!');window.location='../magiamgia.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi thêm coupon!');window.location='../magiamgia.php';</script>";
    }
}
?>