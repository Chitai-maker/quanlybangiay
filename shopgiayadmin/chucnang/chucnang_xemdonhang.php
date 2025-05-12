<?php
require 'connectdb.php';

$query = "
   SELECT 
    khachhang.ten_khachhang AS TenKhachHang,
    khachhang.sdt AS SDT,
    donhang.ma_donhang AS MaDonHang,
    donhang.ngaydat AS ngaydat,
    donhang.trangthai AS trangthai,
    giay.tengiay AS TenGiay,
    sanphamhot.giakhuyenmai AS GiayKhuyenMai,
    chitietdonhang.soluong AS SoLuong,
    giay.giaban AS GiaBan,
    ROUND(
        CASE 
            WHEN sanphamhot.giakhuyenmai IS NOT NULL 
            THEN giay.giaban - (giay.giaban * sanphamhot.giakhuyenmai / 100)
            ELSE giay.giaban
        END, 0) AS GiaBanKhuyenMai,
    ROUND(
        chitietdonhang.soluong * 
        CASE 
            WHEN sanphamhot.giakhuyenmai IS NOT NULL 
            THEN giay.giaban - (giay.giaban * sanphamhot.giakhuyenmai / 100)
            ELSE giay.giaban
        END, 0) AS TongTien
FROM 
    khachhang
JOIN 
    donhang ON khachhang.ma_khachhang = donhang.ma_khachhang
JOIN 
    chitietdonhang ON donhang.ma_donhang = chitietdonhang.ma_donhang
JOIN 
    giay ON chitietdonhang.ma_giay = giay.magiay
LEFT JOIN 
    sanphamhot ON giay.magiay = sanphamhot.magiay
ORDER BY 
    donhang.ma_donhang;


";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $orders = [];

    // Nhóm dữ liệu theo mã đơn hàng
    while ($row = mysqli_fetch_assoc($result)) {
        $maDonHang = $row['MaDonHang'];
        if (!isset($orders[$maDonHang])) {
            $orders[$maDonHang] = [
                'TenKhachHang' => $row['TenKhachHang'], // Thêm tên khách hàng
                'SDT' => $row['SDT'], // Thêm số điện thoại
                'ngaydat' => $row['ngaydat'],
                'trangthai' => $row['trangthai'],
                'sanpham' => []
            ];
        }
        $orders[$maDonHang]['sanpham'][] = [
            'TenGiay' => $row['TenGiay'],
            'SoLuong' => $row['SoLuong'],
            'GiaBan' => $row['GiaBan'],
            'GiayKhuyenMai' => $row['GiayKhuyenMai'],
            'GiaBanKhuyenMai' => $row['GiaBanKhuyenMai'],
            'TongTien' => $row['TongTien']
        ];
    }

    // Hiển thị dữ liệu
?>
    <div class="container mt-5">
        <table border="1" class="table">
            <thead>
                <tr>
                    <th>Tên khách hàng</th>
                    <th>SDT</th>
                    <th>Mã đơn hàng</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                    <th>Chi tiết sản phẩm</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $maDonHang => $order) { ?>
                    <tr>
                        <td><?php echo $order['TenKhachHang']; ?></td>
                        <td><?php echo $order['SDT']; ?></td>
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
                                        <th>Giá khuyến mãi</th>
                                        <th>Tổng tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order['sanpham'] as $sanpham) { ?>
                                        <tr>
                                            <td><?php echo $sanpham['TenGiay']; ?></td>
                                            <td><?php echo $sanpham['SoLuong']; ?></td>
                                            <td><?php echo number_format($sanpham['GiaBan'], 0, ',', '.'); ?> VND</td>
                                            
                                            <td>
                                                <?php
                                                if ($sanpham['GiayKhuyenMai'] > 0) {                     
                                                    echo number_format($sanpham['GiaBanKhuyenMai'], 0, ',', '.') . " VND";
                                                } else {
                                                    echo "Không có khuyến mãi";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo number_format($sanpham['TongTien'], 0, ',', '.'); ?> VND</td>
                                            
                                        </tr>
                                        
                                    <?php } ?>
                                    <tr>
                                        <td colspan="4" style="text-align: right; font-weight: bold;">Tổng tiền đơn hàng:</td>
                                        <td style="font-weight: bold;">
                                            <?php 
                                            $tongTienDonHang = 0;
                                            foreach ($order['sanpham'] as $sanpham) {
                                                $tongTienDonHang += $sanpham['TongTien'];
                                            }
                                            echo number_format($tongTienDonHang, 0, ',', '.') . ' VND';
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <form method="POST" action="chucnang/chucnang_xoadonhang.php" class="d-inline form-no-border">
                                <input type="hidden" name="ma_donhang" value="<?php echo $maDonHang; ?>">
                                <button style="border:none" type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">Xóa</button>
                            </form>
                            <?php if ($order['trangthai'] !== 'Hoàn thành' && $order['trangthai'] !== 'Đã hủy') { ?>
                                <form method="POST" action="chucnang/chucnang_capnhattrangthai.php" class="d-inline form-no-border">
                                    <input type="hidden" name="ma_donhang" value="<?php echo $maDonHang; ?>">
                                    <?php if ($order['trangthai'] === 'Đang giao hàng') { ?>
                                        <input type="hidden" name="trangthai" value="Hoàn thành">
                                        <button style="border:none" type="submit" class="btn btn-success" onclick="return confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng này thành Hoàn thành?');">Hoàn thành</button>
                                    <?php } else { ?>
                                        <input type="hidden" name="trangthai" value="Đang giao hàng">
                                        <button style="border:none" type="submit" class="btn btn-primary" onclick="return confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng này thành Đang giao hàng?');">Cập nhật trạng thái</button>
                                    <?php } ?>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
            <?php }
            } ?>