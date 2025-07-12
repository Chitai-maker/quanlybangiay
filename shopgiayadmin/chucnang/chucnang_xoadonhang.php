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
    
    // Viết vào bảng lịch sử nhân viên
    session_start();
    $ma_nhanvien = $_SESSION['ma_nhanvien'];
    $noidung = "Xóa đơn hàng: $maDonHang";
    $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
    $stmt_lichsu = $conn->prepare($sql_lichsu);
    $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
    $stmt_lichsu->execute();
    $stmt_lichsu->close();
    mysqli_close($conn);

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
