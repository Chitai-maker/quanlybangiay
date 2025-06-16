<?php
require 'connectdb.php';
if (isset($_POST['huy_donhang'])) {
    $maDonHang = $_POST['ma_donhang'];

    // Cập nhật trạng thái đơn hàng thành "Đã hủy"
    $query_update = "UPDATE donhang SET trangthai = 'Đã hủy' WHERE ma_donhang = '$maDonHang'";
    mysqli_query($conn, $query_update);
    // Thông báo và tải lại trang
    $_SESSION['message'] = "Đơn hàng đã được hủy thành công!";
    // Chuyển hướng về trang đơn hàng   

}
?>
<?php
require 'connectdb.php';
$makhachhang = $_SESSION['makhachhang'];
$query = "
   SELECT 
    donhang.ma_donhang AS MaDonHang,
    donhang.ngaydat AS ngaydat,
    donhang.trangthai AS trangthai,
    giay.tengiay AS TenGiay,
    chitietdonhang.soluong AS SoLuong,
    giay.giaban AS GiaBan,
    giay.magiay AS magiay,
    (chitietdonhang.soluong * giay.giaban) AS TongTien
FROM 
    khachhang
JOIN 
    donhang ON khachhang.ma_khachhang = donhang.ma_khachhang
JOIN 
    chitietdonhang ON donhang.ma_donhang = chitietdonhang.ma_donhang
JOIN 
    giay ON chitietdonhang.ma_giay = giay.magiay
WHERE 
    khachhang.ma_khachhang = $makhachhang
ORDER BY 
    donhang.ma_donhang DESC
";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $orders = [];

    // Nhóm dữ liệu theo mã đơn hàng
    while ($row = mysqli_fetch_assoc($result)) {
        $maDonHang = $row['MaDonHang'];
        if (!isset($orders[$maDonHang])) {
            $orders[$maDonHang] = [
                'ngaydat' => $row['ngaydat'],
                'trangthai' => $row['trangthai'],
                'sanpham' => []
            ];
        }
        $orders[$maDonHang]['sanpham'][] = [
            'TenGiay' => $row['TenGiay'],
            'SoLuong' => $row['SoLuong'],
            'GiaBan' => $row['GiaBan'],
            'TongTien' => $row['TongTien'],
            'magiay' => $row['magiay']
        ];
    }

    // Hiển thị dữ liệu
?>
 <div class="container mt-5">
    <table border="1" class="table">
        <thead>
            <tr>
                <th>Mã đơn hàng</th>
                <th>Ngày đặt</th>
                <th>Trạng thái</th>
                <th>Chi tiết sản phẩm</th>
                <th>Thao tác</th> <!-- Thêm cột Thao tác -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $maDonHang => $order) { 
                // Tính tổng tiền của đơn hàng
                $tongTienDonHang = 0;
                foreach ($order['sanpham'] as $sanpham) {
                    $query_discount = "SELECT giakhuyenmai FROM sanphamhot WHERE magiay = '" . $sanpham['magiay'] . "'";
                    $result_discount = mysqli_query($conn, $query_discount);
                    $discount = 0;

                    if (mysqli_num_rows($result_discount) > 0) {
                        $discount_row = mysqli_fetch_assoc($result_discount);
                        $discount = $discount_row['giakhuyenmai'];
                    }

                    // Tính giá sau khi giảm
                    $original_price = $sanpham['GiaBan'];
                    $final_price = $discount > 0 ? $original_price * (1 - $discount / 100) : $original_price;

                    // Cập nhật giá và tổng tiền
                    $sanpham['GiaBan'] = $final_price;
                    $sanpham['TongTien'] = $sanpham['SoLuong'] * $final_price;

                    $tongTienDonHang += $sanpham['TongTien'];
                }
            ?>
                <tr>
                    <td><?php echo $maDonHang; ?></td>
                    <td><?php echo $order['ngaydat']; ?></td>
                    <td><?php echo $order['trangthai']; ?></td>
                    <td>
                        <table border="1" class="table">
                            <thead>
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Tổng tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['sanpham'] as $sanpham) { ?>
                                    <tr>
                                        <td><?php echo $sanpham['TenGiay']; ?></td>
                                        <td><?php echo $sanpham['SoLuong']; ?></td>
                                        <td><?php echo $sanpham['GiaBan']; ?> VND</td>
                                        <td><?php echo $sanpham['TongTien']; ?> VND</td>
                                    </tr>
                                <?php } ?>
                                <!-- Dòng tổng tiền -->
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Tổng tiền đơn hàng:</strong></td>
                                    <td><strong><?php echo number_format($tongTienDonHang, 0, ',', '.'); ?> VND</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>
                        <!-- Nút Hủy đơn hàng -->
                        <?php if ($order['trangthai'] !== 'Đã hủy' && $order['trangthai'] !== 'Hoàn thành') { ?>
                            <form method="post" action="">
                                <input type="hidden" name="ma_donhang" value="<?php echo $maDonHang; ?>">
                                <button type="submit" name="huy_donhang" class="btn btn-danger">Hủy đơn hàng</button>
                            </form>
                        <?php } elseif ($order['trangthai'] === 'Đã hủy') { ?>
                            <span class="text-muted">Đã hủy</span>
                        <?php } elseif ($order['trangthai'] === 'Hoàn thành') { ?>
                            <span class="text-success">Hoàn thành</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
} else {
?>
    <div class="text-center">
        <p>Chưa có đơn hàng</p>
    </div>
<?php
}
?>