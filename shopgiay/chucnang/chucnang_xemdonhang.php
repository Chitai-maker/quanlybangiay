<?php
// filepath: c:\xampp\htdocs\quanlybangiay\shopgiay\chucnang\chucnang_xemdonhang.php

include "connectdb.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION['makhachhang'])) {
    echo "<div class='alert alert-danger'>Bạn cần đăng nhập để xem đơn hàng!</div>";
    exit;
}

$ma_khachhang = $_SESSION['makhachhang'];

// Xử lý huỷ đơn hàng
if (isset($_POST['huydon']) && isset($_POST['ma_donhang'])) {
    $ma_donhang = intval($_POST['ma_donhang']);
    // Chỉ cho phép huỷ đơn hàng của chính mình và trạng thái "Chờ xác nhận"
    $sql_check = "SELECT * FROM donhang WHERE ma_donhang='$ma_donhang' AND ma_khachhang='$ma_khachhang' AND trangthai='Chờ xác nhận'";
    $result_check = mysqli_query($conn, $sql_check);
    if (mysqli_num_rows($result_check) > 0) {
        // Trả lại số lượng tồn kho cho từng sản phẩm trong đơn hàng
        $sql_ct = "SELECT ma_giay, soluong FROM chitietdonhang WHERE ma_donhang = '$ma_donhang'";
        $result_ct = mysqli_query($conn, $sql_ct);
        while ($row_ct = mysqli_fetch_assoc($result_ct)) {
            $magiay = $row_ct['ma_giay'];
            $soluong = $row_ct['soluong'];
            mysqli_query($conn, "UPDATE giay SET soluongtonkho = soluongtonkho + $soluong WHERE magiay = '$magiay'");
        }
        // Cập nhật trạng thái đơn hàng
        mysqli_query($conn, "UPDATE donhang SET trangthai='Đã huỷ' WHERE ma_donhang='$ma_donhang'");
        echo "<div class='alert alert-success'>Huỷ đơn hàng thành công!</div>";
    } else {
        echo "<div class='alert alert-danger'>Không thể huỷ đơn hàng này!</div>";
    }
}

// Lấy danh sách đơn hàng của khách
$sql = "SELECT * FROM donhang WHERE ma_khachhang = '$ma_khachhang' ORDER BY ngaydat DESC";
$result = mysqli_query($conn, $sql);

echo "<div class='container mt-4'>";
echo "<h3 class='mb-4'>Đơn hàng của bạn</h3>";

if (mysqli_num_rows($result) > 0) {
    while ($dh = mysqli_fetch_assoc($result)) {
        echo "<div class='card mb-4'>";
        echo "<div class='card-header bg-primary text-white'>";
        echo "Mã đơn hàng: <strong>{$dh['ma_donhang']}</strong> | Ngày đặt: {$dh['ngaydat']} | Trạng thái: <span class='fw-bold'>{$dh['trangthai']}</span> | Tổng tiền: <span class='text-danger fw-bold'>" . number_format($dh['tongtien'], 0, ',', '.') . " đ</span>";
        // Nút huỷ đơn hàng nếu trạng thái là "Chờ xác nhận"
        if ($dh['trangthai'] == 'Chờ xác nhận') {
            echo '<form method="post" action="" class="d-inline ms-3" onsubmit="return confirm(\'Bạn chắc chắn muốn huỷ đơn hàng này?\')">';
            echo '<input type="hidden" name="ma_donhang" value="' . $dh['ma_donhang'] . '">';
            echo '<button type="submit" name="huydon" class="btn btn-danger btn-sm">Huỷ đơn hàng</button>';
            echo '</form>';
        }
        // Thanh quá trình đơn hàng (ẩn nếu đã huỷ)
        if ($dh['trangthai'] != 'Đã huỷ') {
            // Xác định bước hiện tại
            $step = 1;
            if ($dh['trangthai'] == 'Chờ xác nhận') $step = 1;
            elseif ($dh['trangthai'] == 'Đang giao hàng') $step = 2;
            elseif ($dh['trangthai'] == 'Hoàn thành') $step = 3;
            echo '<div class="mt-2 mb-1">';
            echo '<div class="progress" style="height: 22px;">';
            // Chờ xác nhận
            echo '<div class="progress-bar bg-info position-relative" role="progressbar" style="width:33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">';
            echo '<span>';
            echo ($step >= 1 ? '<b>Chờ xác nhận</b>' : 'Chờ xác nhận');
            if ($step > 1) echo ' <span style="color:green;font-size:18px;">✔</span>';
            echo '</span>';
            echo '</div>';
            // Đang giao hàng
            echo '<div class="progress-bar ' . ($step >= 2 ? 'bg-warning' : 'bg-light text-dark') . ' position-relative" role="progressbar" style="width:34%;">';
            echo '<span>';
            echo ($step >= 2 ? '<b>Đang giao hàng</b>' : 'Đang giao hàng');
            if ($step > 2) echo ' <span style="color:green;font-size:18px;">✔</span>';
            echo '</span>';
            echo '</div>';
            // Hoàn thành
            echo '<div class="progress-bar ' . ($step == 3 ? 'bg-success' : 'bg-light text-dark') . ' position-relative" role="progressbar" style="width:33%;">';
            echo '<span>';
            echo ($step == 3 ? '<b>Hoàn thành</b>' : 'Hoàn thành');
            if ($step == 3) echo ' <span style="color:green;font-size:18px;">✔</span>';
            echo '</span>';
            echo '</div>';
            echo '</div></div>';
        }
        
        echo "</div>";
        echo "<div class='card-body p-0'>";
        // Lấy chi tiết đơn hàng
        $ma_donhang = $dh['ma_donhang'];
        $sql_ct = "SELECT ct.*, g.tengiay, g.anhminhhoa FROM chitietdonhang ct 
                   JOIN giay g ON ct.ma_giay = g.magiay
                   WHERE ct.ma_donhang = '$ma_donhang'";
        $result_ct = mysqli_query($conn, $sql_ct);
        echo "<table class='table table-bordered mb-0'>";
        echo "<tr class='table-light'><th>Ảnh</th><th>Tên sản phẩm</th><th>Số lượng</th></tr>";
        while ($ct = mysqli_fetch_assoc($result_ct)) {
            echo "<tr>";
            echo "<td><img src='../shopgiayadmin/anhgiay/" . htmlspecialchars($ct['anhminhhoa']) . "' style='width:60px;height:60px;object-fit:cover;'></td>";
            echo "<td>" . htmlspecialchars($ct['tengiay']) . "</td>";
            echo "<td>" . $ct['soluong'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<div class='alert alert-info'>Bạn chưa có đơn hàng nào.</div>";
}
echo "</div>";
?>