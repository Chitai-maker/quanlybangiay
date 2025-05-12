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
        header("Location: ../sanphamhot.php");
        exit;
    }

    // Thêm sản phẩm vào bảng sanphamhot với giá khuyến mãi
    $insertQuery = "INSERT INTO sanphamhot (magiay, giakhuyenmai) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ii", $magiay, $giakhuyenmai);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Thêm sản phẩm hot thành công.";
    } else {
        $_SESSION['message'] = "Thêm sản phẩm hot thất bại. Vui lòng thử lại.";
    }

    header("Location: ../sanphamhot.php");
    exit;
}
?>