<?php
require 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maDonHang = $_POST['ma_donhang'];

    // Xóa chi tiết đơn hàng trước
    $deleteChiTietQuery = "DELETE FROM chitietdonhang WHERE ma_donhang = ?";
    $stmt = $conn->prepare($deleteChiTietQuery);
    $stmt->bind_param("i", $maDonHang);
    $stmt->execute();

    // Xóa đơn hàng
    $deleteDonHangQuery = "DELETE FROM donhang WHERE ma_donhang = ?";
    $stmt = $conn->prepare($deleteDonHangQuery);
    $stmt->bind_param("i", $maDonHang);
    $stmt->execute();

    // Kiểm tra nếu xóa thành công
    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Đơn hàng đã được xóa thành công.";
    } else {
        $_SESSION['message'] = "Không thể xóa đơn hàng. Vui lòng thử lại.";
    }

    // Chuyển hướng về trang đơn hàng
    header("Location: ../donhang.php");
    exit();
}
?>