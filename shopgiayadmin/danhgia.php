<?php

include "sidebar.php";
include "../shopgiay/chucnang/connectdb.php"; // Đường dẫn tùy vị trí file admin

// Lấy tất cả đánh giá, join với bảng sản phẩm và khách hàng
$sql = "SELECT d.*, g.tengiay, g.anhminhhoa,k.email
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
            echo '<div class="d-flex align-items-center">';
            // Hiển thị ảnh sản phẩm
            echo '<img src="../shopgiayadmin/anhgiay/' . htmlspecialchars($row['anhminhhoa']) . '" alt="Ảnh sản phẩm" class="img-fluid" style="max-width: 100px; max-height: 100px; object-fit: cover;">';
            echo '<div class="ms-3">';
            // Hiển thị tên sản phẩm chấn chuyển trang
            echo '<a href="sanpham.php?masanpham=' . $row['magiay'] . '" class="text-decoration-none">';

            echo '<strong>Sản phẩm: ' . htmlspecialchars($row['tengiay']) . '</strong><br>';
            //hiện thị tên khách hàng
            echo '<small class="text-muted">' . htmlspecialchars($row['email']) . '</small><br>';
            echo '</a>';
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
            echo ' <a href="?delete=' . $row['ma_danhgia'] . '" class="btn btn-danger btn-sm ms-2" onclick="return confirm(\'Bạn có chắc muốn xoá đánh giá này?\')">Xoá</a>';

            echo '</div>'; // ms-3
            echo '</div>'; // d-flex
            echo '</div>'; // border rounded
        }
    } else {
        echo '<div class="text-muted">Bạn chưa có đánh giá nào.</div>';
    }
    ?>
</div>