<?php
require 'connectdb.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $magiay = intval($_POST['magiay']);
    $giakhuyenmai = intval($_POST['giakhuyenmai']);

    // Kiểm tra nếu sản phẩm đã tồn tại trong bảng sanphamhot
    $checkQuery = "SELECT * FROM sanphamhot WHERE magiay = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $magiay);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "Sản phẩm này đã có trong danh sách sản phẩm hot.";
        header("Location: ../khuyenmai.php");
        exit;
    }

    // Thêm sản phẩm vào bảng sanphamhot với giá khuyến mãi
    $insertQuery = "INSERT INTO sanphamhot (magiay, giakhuyenmai) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ii", $magiay, $giakhuyenmai);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Thêm khuyến mãi thành công.";
        // Viết vào bảng lịch sử nhân viên
        $ma_nhanvien = $_SESSION['ma_nhanvien'];
        $noidung = "Thêm khuyến mãi: Mã giày $magiay với giá khuyến mãi $giakhuyenmai";
        $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
        $stmt_lichsu = $conn->prepare($sql_lichsu);
        $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
        $stmt_lichsu->execute();
        $stmt_lichsu->close();
        mysqli_close($conn);
    } else {
        $_SESSION['message'] = "Thêm khuyến mãi thất bại. Vui lòng thử lại.";
    }

    header("Location: ../khuyenmai.php");
    exit;
}
?>