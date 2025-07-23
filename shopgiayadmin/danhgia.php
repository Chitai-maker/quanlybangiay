<?php
session_start();

include "../shopgiay/chucnang/connectdb.php";

// Xử lý xoá đánh giá
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $ma_danhgia = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM danhgia WHERE ma_danhgia = $ma_danhgia");
    header("Location: danhgia.php");
    exit();
}

// Xử lý xoá bình luận trả lời
if (isset($_GET['delete_reply']) && is_numeric($_GET['delete_reply'])) {
    $ma_binhluan = intval($_GET['delete_reply']);
    mysqli_query($conn, "DELETE FROM binhluandanhgia WHERE ma_binhluan = $ma_binhluan");
    header("Location: danhgia.php");
    exit();
}

// Lấy tất cả đánh giá, join với bảng sản phẩm và khách hàng
$sql = "SELECT d.*, g.tengiay, g.anhminhhoa,k.email
        FROM danhgia d
        LEFT JOIN giay g ON d.magiay = g.magiay
        LEFT JOIN khachhang k ON d.ma_khachhang = k.ma_khachhang
        ORDER BY d.ngaydanhgia DESC";
$result = mysqli_query($conn, $sql);
include "sidebar.php";
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
            echo ' <a href="traloidanhgia.php?madanhgia=' . $row['ma_danhgia'] . '" class="btn btn-danger btn-sm ms-2" >Trả lời</a>';

            echo '</div>'; // ms-3

            echo '</div>'; // d-flex
            // Hiển thị các bình luận trả lời nếu có
            $madanhgia = $row['ma_danhgia'];
            $reply_sql = "SELECT b.*, n.ten_nhanvien FROM binhluandanhgia b
                                                LEFT JOIN nhanvien n ON b.ma_nhanvien = n.ma_nhanvien
                                                WHERE b.ma_danhgia = '$madanhgia'
                                                ORDER BY b.thoigian ASC";
            $reply_result = mysqli_query($conn, $reply_sql);
            if (mysqli_num_rows($reply_result) > 0) {
                while ($reply = mysqli_fetch_assoc($reply_result)) {
                    echo '<div class="mt-2 p-2" style="background:#f8f9fa;border-radius:5px;">';
                    echo '<strong>Phản hồi của người bán</strong><br>';
                    echo '<span style="color:#333;">' . nl2br(htmlspecialchars($reply['noidung'])) . '</span>';
                    echo '<br><small class="text-muted">(' . htmlspecialchars($reply['ten_nhanvien'] ?? 'Admin') . ' - ' . $reply['thoigian'] . ')</small>';
                    echo ' <a href="?delete_reply=' . $reply['ma_binhluan'] . '" class="btn btn-danger btn-sm ms-2" onclick="return confirm(\'Bạn có chắc muốn xoá bình luận này?\')">Xoá</a>';
                    echo '</div>';
                }
            }
            echo '</div>'; // border rounded
        }
    } else {
        echo '<div class="text-muted">Bạn chưa có đánh giá nào.</div>';
    }
    ?>
</div>