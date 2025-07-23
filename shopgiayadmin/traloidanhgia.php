<?php
session_start();
include "sidebar.php";
include "../shopgiay/chucnang/connectdb.php";

// Lấy mã đánh giá từ URL
$ma_danhgia = isset($_GET['madanhgia']) ? intval($_GET['madanhgia']) : 0;

// Xử lý khi gửi form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ma_danhgia > 0) {
    $noidung = trim($_POST['noidung']);
    $ma_nhanvien = 1; // Ví dụ: lấy từ session hoặc gán cứng (admin)
    $thoigian = date('Y-m-d H:i:s');
    if ($noidung !== '') {
        $sql = "INSERT INTO binhluandanhgia (ma_danhgia, ma_nhanvien, noidung, thoigian) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iiss", $ma_danhgia, $ma_nhanvien, $noidung, $thoigian);
        mysqli_stmt_execute($stmt);
        echo '<div class="alert alert-success mt-3">Đã trả lời đánh giá!</div>';
    } else {
        echo '<div class="alert alert-danger mt-3">Vui lòng nhập nội dung trả lời.</div>';
    }
}

// Lấy thông tin đánh giá để hiển thị
$danhgia = null;
if ($ma_danhgia > 0) {
    $sql = "SELECT d.*, k.email, g.tengiay FROM danhgia d
            LEFT JOIN khachhang k ON d.ma_khachhang = k.ma_khachhang
            LEFT JOIN giay g ON d.magiay = g.magiay
            WHERE d.ma_danhgia = $ma_danhgia";
    $result = mysqli_query($conn, $sql);
    $danhgia = mysqli_fetch_assoc($result);
}
?>

<div class="container mt-5">
    <h3>Trả lời đánh giá</h3>
    <?php if ($danhgia): ?>
        <div class="border rounded p-2 mb-2 bg-light">
            <strong>Sản phẩm: <?= htmlspecialchars($danhgia['tengiay']) ?></strong><br>
            <small class="text-muted"><?= htmlspecialchars($danhgia['email']) ?></small><br>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <?php if ($i <= $danhgia['danhgia']): ?>
                    <span style="color: gold; font-size:16px;">&#9733;</span>
                <?php else: ?>
                    <span style="color: #ccc; font-size:16px;">&#9733;</span>
                <?php endif; ?>
            <?php endfor; ?>
            <br>
            <?= nl2br(htmlspecialchars($danhgia['binhluan'])) ?>
            <br><small class="text-muted">Ngày đánh giá: <?= $danhgia['ngaydanhgia'] ?></small>
        </div>
        <?php
        // Lấy các bình luận trả lời cho đánh giá này
        $sql_bl = "SELECT b.*, n.ten_nhanvien FROM binhluandanhgia b
                   LEFT JOIN nhanvien n ON b.ma_nhanvien = n.ma_nhanvien
                   WHERE b.ma_danhgia = $ma_danhgia
                   ORDER BY b.thoigian ASC";
        $result_bl = mysqli_query($conn, $sql_bl);
        if (mysqli_num_rows($result_bl) > 0) {
            echo '<div class="mb-3"><strong>Trả lời:</strong>';
            while ($bl = mysqli_fetch_assoc($result_bl)) {
                echo '<div class="border rounded p-2 mb-2">';
                echo '<strong>' . htmlspecialchars($bl['ten_nhanvien'] ?? 'Admin') . ':</strong> ';
                echo nl2br(htmlspecialchars($bl['noidung']));
                echo '<br><small class="text-muted">Thời gian: ' . $bl['thoigian'] . '</small>';
                echo '</div>';
            }
            echo '</div>';
        }
        ?>
        <form method="post">
            <div class="mb-3">
                <label for="noidung" class="form-label">Nội dung trả lời:</label>
                <textarea name="noidung" id="noidung" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Gửi trả lời</button>
            <a href="danhgia.php" class="btn btn-secondary">Quay lại</a>
        </form>
    <?php else: ?>
        <div class="alert alert-danger">Không tìm thấy đánh giá!</div>
    <?php endif; ?>
</div>
