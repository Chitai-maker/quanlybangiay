<?php

include "header.php";
include "chucnang/connectdb.php";

if (!isset($_SESSION['makhachhang'])) {
    echo "<div class='container mt-5'><div class='alert alert-warning'>Bạn cần <a href='login.php'>đăng nhập</a> để xem đánh giá của mình.</div></div>";
    exit;
}

// Xử lý xoá đánh giá
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $makhachhang = mysqli_real_escape_string($conn, $_SESSION['makhachhang']);
    // Chỉ cho phép xoá đánh giá của chính mình
    $del_sql = "DELETE FROM danhgia WHERE ma_danhgia = $delete_id AND ma_khachhang = '$makhachhang'";
    mysqli_query($conn, $del_sql);
    // Sau khi xoá, load lại trang để cập nhật danh sách
    header("Location: danhgiacuaban.php");
    exit;
}

$makhachhang = mysqli_real_escape_string($conn, $_SESSION['makhachhang']);
$sql = "SELECT d.*, g.tengiay FROM danhgia d 
        LEFT JOIN giay g ON d.magiay = g.magiay 
        WHERE d.ma_khachhang = '$makhachhang' ORDER BY d.ngaydanhgia DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="container mt-5">
    <h3>Đánh giá của bạn</h3>
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="border rounded p-2 mb-2 bg-light">';
            echo '<strong>Sản phẩm: ' . htmlspecialchars($row['tengiay']) . '</strong><br>';
            // Hiển thị số sao
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $row['danhgia']) {
                    echo '<span style="color: gold; font-size:16px;">&#9733;</span>';
                } else {
                    echo '<span style="color: #ccc; font-size:16px;">&#9733;</span>';
                }
            }
            // Nút xoá
            echo '<br>';
            echo nl2br(htmlspecialchars($row['binhluan']));
            echo '<br><small class="text-muted">Ngày đánh giá: ' . $row['ngaydanhgia'] . '</small>';
            echo ' <a href="?delete=' . $row['ma_danhgia'] . '" class="btn btn-danger btn-sm ms-2" onclick="return confirm(\'Bạn có chắc muốn xoá đánh giá này?\')">Xoá</a>';

            echo '</div>';
        }
    } else {
        echo '<div class="text-muted">Bạn chưa có đánh giá nào.</div>';
    }
    ?>
</div>