<?php
require 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maDonHang = $_POST['ma_donhang'];
    $trangThai = $_POST['trangthai'];

    // Cập nhật trạng thái đơn hàng
    $updateQuery = "UPDATE donhang SET trangthai = ? WHERE ma_donhang = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $trangThai, $maDonHang);
    $stmt->execute();

    // Kiểm tra nếu cập nhật thành công
    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Trạng thái đơn hàng đã được cập nhật thành công.";
    } else {
        $_SESSION['message'] = "Không thể cập nhật trạng thái đơn hàng. Vui lòng thử lại.";
    }

    // Chuyển hướng về trang đơn hàng
    header("Location: ../donhang.php?trangthai=$trangThai");
    $stmt->close();
    exit();
}
?>