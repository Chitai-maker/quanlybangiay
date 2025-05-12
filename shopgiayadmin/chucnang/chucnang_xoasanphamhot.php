<?php
require 'connectdb.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_sanphamyeuthich = intval($_POST['ma_sanphamyeuthich']);

    $deleteQuery = "DELETE FROM sanphamhot WHERE ma_sanphamyeuthich = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $ma_sanphamyeuthich);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Xóa sản phẩm hot thành công.";
    } else {
        $_SESSION['message'] = "Xóa sản phẩm hot thất bại. Vui lòng thử lại.";
    }

    header("Location: ../sanphamhot.php");
    exit;
}
?>