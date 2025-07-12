<?php
require 'connectdb.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_sanphamyeuthich = intval($_POST['ma_sanphamyeuthich']);

    $deleteQuery = "DELETE FROM sanphamhot WHERE ma_sanphamyeuthich = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $ma_sanphamyeuthich);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Xóa khuyến mãi thành công.";
        // Viết vào bảng lịch sử nhân viên
        $ma_nhanvien = $_SESSION['ma_nhanvien'];
        $noidung = "Xóa khuyến mãi: Mã sản phẩm $ma_sanphamyeuthich";
        $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
        $stmt_lichsu = $conn->prepare($sql_lichsu);
        $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
        $stmt_lichsu->execute();
        $stmt_lichsu->close();
        mysqli_close($conn);
    } else {
        $_SESSION['message'] = "Xóa khuyến mãi thất bại. Vui lòng thử lại.";
    }

    header("Location: ../khuyenmai.php");
    exit;
}
?>