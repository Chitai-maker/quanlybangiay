<?php

include "sidebar.php";
include "../shopgiay/chucnang/connectdb.php"; // Đường dẫn tùy vị trí file admin

// Lấy tất cả đánh giá, join với bảng sản phẩm và khách hàng
$sql = "SELECT d.*, g.tengiay, k.ten_khachhang 
        FROM danhgia d
        LEFT JOIN giay g ON d.magiay = g.magiay
        LEFT JOIN khachhang k ON d.ma_khachhang = k.ma_khachhang
        ORDER BY d.ngaydanhgia DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="container mt-5">
    <h3>Tất cả đánh giá của khách hàng</h3>
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="border rounded p-2 mb-2 bg-light">';
            echo '<strong>Sản phẩm: ' . htmlspecialchars($row['tengiay']) . '</strong><br>';
            echo '<strong>Khách hàng: ' . htmlspecialchars($row['ten_khachhang']) . '</strong><br>';
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
            echo '</div>';
        }
    } else {
        echo '<div class="text-muted">Chưa có đánh giá nào.</div>';
    }
    ?>
</div>