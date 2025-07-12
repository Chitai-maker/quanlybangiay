<?php
require 'connectdb.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maDonHang = $_POST['ma_donhang'];
    $trangThai = $_POST['trangthai'];

    // Cập nhật trạng thái đơn hàng
    $updateQuery = "UPDATE donhang SET trangthai = ? WHERE ma_donhang = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $trangThai, $maDonHang);
    $stmt->execute();
    // thêm vào bảng lịch sử nhân viên
    $ma_nhanvien = $_SESSION['ma_nhanvien'];
    $noidung = "Cập nhật trạng thái đơn hàng: $maDonHang sang $trangThai";
    $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
    $stmt_lichsu = $conn->prepare($sql_lichsu);
    $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
    $stmt_lichsu->execute();

    // Nếu trạng thái là "Hoàn thành" thì cộng 1 điểm cho khách hàng
    if ($trangThai === 'Hoàn thành') {
        // Lấy mã khách hàng từ đơn hàng
        $sql_get_kh = "SELECT ma_khachhang FROM donhang WHERE ma_donhang='$maDonHang'";
        $result_kh = mysqli_query($conn, $sql_get_kh);
        if ($row_kh = mysqli_fetch_assoc($result_kh)) {
            $ma_khachhang = $row_kh['ma_khachhang'];
            mysqli_query($conn, "UPDATE khachhang SET diemthanhvien = diemthanhvien + 1 WHERE ma_khachhang = '$ma_khachhang'");
        }
    }

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