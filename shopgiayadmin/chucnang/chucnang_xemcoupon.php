<?php
include "connectdb.php";

// Xử lý xóa coupon
if (isset($_GET['delete_coupon'])) {
    $id = intval($_GET['delete_coupon']);
    mysqli_query($conn, "DELETE FROM coupon WHERE ma_coupon = $id");
    echo "<script>alert('Đã xoá coupon!');window.location='".$_SERVER['PHP_SELF']."';</script>";
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM coupon ORDER BY ngaybatdau DESC");
echo '<div class="mt-5"><h4>Danh sách coupon</h4>';
echo '<table class="table table-bordered"><tr><th>Tên coupon</th><th>Giá trị (%)</th><th>Bắt đầu</th><th>Kết thúc</th><th>Trạng thái</th><th>Xoá</th></tr>';
while ($row = mysqli_fetch_assoc($result)) {
    $now = date('Y-m-d H:i:s');
    $trangthai = (strtotime($row['ngayketthuc']) < strtotime($now)) ? '<span class="text-danger">Hết hạn</span>' : '<span class="text-success">Còn hiệu lực</span>';
    echo '<tr>
        <td>' . htmlspecialchars($row['ten_coupon']) . '</td>
        <td>' . $row['giatri'] . '</td>
        <td>' . $row['ngaybatdau'] . '</td>
        <td>' . $row['ngayketthuc'] . '</td>
        <td>' . $trangthai . '</td>
        <td><a href="?delete_coupon=' . $row['ma_coupon'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Xoá coupon này?\')">Xoá</a></td>
    </tr>';
}
echo '</table></div>';
?>