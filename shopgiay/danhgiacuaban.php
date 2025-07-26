<?php
include "chucnang/connectdb.php";
session_start();

if (!isset($_SESSION['makhachhang'])) {
    echo "<div class='container mt-5'><div class='alert alert-warning'>Bạn cần <a href='login.php'>đăng nhập</a> để xem đánh giá của mình.</div></div>";
    exit;
}

// Xử lý xoá đánh giá
if (isset($_GET['an']) && is_numeric($_GET['an'])) {
    $hide_id = intval($_GET['an']);
    $makhachhang = mysqli_real_escape_string($conn, $_SESSION['makhachhang']);
    $hide_sql = "UPDATE danhgia SET an = true WHERE ma_danhgia = $hide_id AND ma_khachhang = '$makhachhang'";
    mysqli_query($conn, $hide_sql);
    header("Location: danhgiacuaban.php");
    exit;
}

include "header.php";

$makhachhang = mysqli_real_escape_string($conn, $_SESSION['makhachhang']);
$sql = "SELECT d.*, g.tengiay, g.anhminhhoa 
FROM danhgia d 
LEFT JOIN giay g ON d.magiay = g.magiay 
WHERE d.ma_khachhang = '$makhachhang' AND d.an = 0 
ORDER BY d.ngaydanhgia DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="container mt-5">
    <h3>Đánh giá của bạn</h3>
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="border rounded p-2 mb-2 bg-light">';
            echo '<div class="d-flex align-items-center">';
            // Hiển thị ảnh sản phẩm
            echo '<img src="../shopgiayadmin/anhgiay/' . htmlspecialchars($row['anhminhhoa']) . '" alt="Ảnh sản phẩm" class="img-fluid" style="max-width: 100px; max-height: 100px; object-fit: cover;">';
            echo '<div class="ms-3">';
            // Hiển thị tên sản phẩm chấn chuyển trang
            echo '<a href="sanpham.php?masanpham=' . $row['magiay'] . '" class="text-decoration-none">';

            echo '<strong>Sản phẩm: ' . htmlspecialchars($row['tengiay']) . '</strong><br>';
            // Hiển thị số sao
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $row['danhgia']) {
                    echo '<span style="color: gold; font-size:16px;">&#9733;</span>';
                } else {
                    echo '<span style="color: #ccc; font-size:16px;">&#9733;</span>';
                }
            }
            echo '<br>';
            echo nl2br(htmlspecialchars($row['binhluan']));
            echo '<br><small class="text-muted">Ngày đánh giá: ' . $row['ngaydanhgia'] . '</small>';
            echo ' <a href="?an=' . $row['ma_danhgia'] . '" class="btn btn-danger btn-sm ms-2" onclick="return confirm(\'Bạn có chắc muốn xoá đánh giá này?\')">Xoá</a>';

            echo '</div>'; // ms-3
            echo '</div>'; // d-flex
            echo '</div>'; // border rounded
        }
    } else {
        echo '<div class="text-muted">Bạn chưa có đánh giá nào.</div>';
    }
    ?>
</div>