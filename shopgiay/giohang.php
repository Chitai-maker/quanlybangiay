<?php

session_start();
include "chucnang/connectdb.php";
include "header.php";

// Xử lý cập nhật số lượng
if (isset($_POST['capnhat']) && isset($_POST['magiay']) && isset($_POST['soluong'])) {
    $magiay = $_POST['magiay'];
    $soluong = max(1, intval($_POST['soluong']));
    foreach ($_SESSION["giohang"] as $key => $item) {
        if ($item["magiay"] == $magiay) {
            $_SESSION["giohang"][$key]["soluong"] = $soluong;
            break;
        }
    }
    header("Location: giohang.php");
    exit;
}

// Xử lý coupon
$giamgia = 0;
$coupon_message = '';
if (isset($_POST['ap_dung_coupon']) && !empty($_POST['coupon_code'])) {
    $coupon_code = mysqli_real_escape_string($conn, $_POST['coupon_code']);
    $now = date('Y-m-d H:i:s');
    $sql_coupon = "SELECT * FROM coupon WHERE ten_coupon='$coupon_code'  AND ngayketthuc > '$now'";
    $result_coupon = mysqli_query($conn, $sql_coupon);
    if (mysqli_num_rows($result_coupon) > 0) {
        $row_coupon = mysqli_fetch_assoc($result_coupon);
        $giamgia = $row_coupon['giatri'];
        $coupon_message = "<div class='alert alert-success mb-2'>Áp dụng thành công mã coupon! Giảm $giamgia% tổng tiền.</div>";
    } else {
        $coupon_message = "<div class='alert alert-danger mb-2'>Mã coupon không hợp lệ hoặc đã hết hạn.</div>";
    }
}

// Lấy điểm thành viên hiện tại
$diemthanhvien = 0;
if (isset($_SESSION['makhachhang'])) {
    $makh = $_SESSION['makhachhang'];
    $rs_diem = mysqli_query($conn, "SELECT diemthanhvien FROM khachhang WHERE ma_khachhang = '$makh'");
    if ($row_diem = mysqli_fetch_assoc($rs_diem)) {
        $diemthanhvien = intval($row_diem['diemthanhvien']);
    }
}

// Xử lý giảm giá bằng điểm thành viên
$giamgia_diem = 0;
$diem_message = '';
$diem_giam_toi_da = 90; // Giới hạn tối đa 90 điểm
if (isset($_POST['ap_dung_diem']) && isset($_POST['so_diem_giam'])) {
    $so_diem_giam = intval($_POST['so_diem_giam']);
    if ($so_diem_giam > 0 && $so_diem_giam <= $diemthanhvien && $so_diem_giam <= $diem_giam_toi_da) {
        $giamgia_diem = $so_diem_giam;
        $diem_message = "<div class='alert alert-success mb-2'>Áp dụng thành công $giamgia_diem điểm thành viên! Giảm $giamgia_diem% tổng tiền.</div>";
    } else {
        $diem_message = "<div class='alert alert-danger mb-2'>Số điểm không hợp lệ (tối đa là " . min($diemthanhvien, $diem_giam_toi_da) . " điểm).</div>";
    }
}

// Tính tổng tiền
$total = 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top: 50px;">
    <h3 class="text-center mb-4">Giỏ Hàng</h3>
    <?php if (!empty($coupon_message)) echo $coupon_message; ?>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <tr>
                <th width="30%">Sản phẩm</th>
                <th width="13%">Đơn giá</th>
                <th width="10%">Số lượng</th>
                <th width="10%">Số tiền</th>
                <th width="17%">Thao tác</th>
            </tr>
            <?php
            if (!empty($_SESSION["giohang"])) {
                foreach ($_SESSION["giohang"] as $key => $value) {
                    $sql = "SELECT * FROM giay WHERE magiay = '" . $value["magiay"] . "'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_array($result);

                    // Lấy giảm giá nếu có
                    $magiay = $value['magiay'];
                    $query_discount = "SELECT giakhuyenmai FROM sanphamhot WHERE magiay = '$magiay'";
                    $result_discount = mysqli_query($conn, $query_discount);
                    $discount = 0;
                    if (mysqli_num_rows($result_discount) > 0) {
                        $discount_row = mysqli_fetch_assoc($result_discount);
                        $discount = $discount_row['giakhuyenmai'];
                    }

                    // Tính giá sau khi giảm
                    $original_price = $row["giaban"];
                    $final_price = $discount > 0 ? $original_price * (1 - $discount / 100) : $original_price;
                    $subtotal = $value["soluong"] * $final_price;
                    $total += $subtotal;
            ?>
            <tr>
                <td>
                    <a href="sanpham.php?masanpham=<?php echo $value['magiay']; ?>">
                        <?php echo htmlspecialchars($value["tengiay"]); ?>
                    </a>
                    <?php if ($discount > 0): ?>
                        <br><span class="badge bg-danger">Giảm <?php echo $discount; ?>%</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php
                    if ($discount > 0) {
                        echo "<del>" . number_format($original_price, 0, ',', '.') . " đ</del><br>";
                        echo "<span class='text-success'>" . number_format($final_price, 0, ',', '.') . " đ</span>";
                    } else {
                        echo number_format($original_price, 0, ',', '.') . " đ";
                    }
                    ?>
                </td>
                <td>
                    <form method="post" action="giohang.php" class="d-flex align-items-center">
                        <input type="hidden" name="magiay" value="<?php echo $value["magiay"]; ?>">
                        <input type="number" name="soluong" min="1" max="<?php echo $row['soluongtonkho']; ?>" value="<?php echo $value["soluong"]; ?>" class="form-control form-control-sm" style="width:70px;">
                        <button type="submit" name="capnhat" class="btn btn-sm btn-secondary ms-2">Cập nhật</button>
                    </form>
                </td>
                <td><?php echo number_format($subtotal, 0, ',', '.') . " đ"; ?></td>
                <td>
                    <a href="chucnang/chucnang_giohang.php?action=delete&magiay=<?php echo $value["magiay"]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')">Xóa</a>
                </td>
            </tr>
            <?php
                }
            ?>
            <!-- Box nhập coupon -->
            <tr>
                <td colspan="3" align="right">
                    <form method="post" action="">
                        <div class="input-group">
                            <input type="text" name="coupon_code" class="form-control form-control-sm" placeholder="Nhập mã coupon..." value="<?php echo isset($_POST['coupon_code']) ? htmlspecialchars($_POST['coupon_code']) : ''; ?>">
                            <button type="submit" name="ap_dung_coupon" class="btn btn-info btn-sm">Áp dụng</button>
                        </div>
                    </form>
                </td>
                <td colspan="2"></td>
            </tr>
            <!-- Tổng tiền -->
            <tr>
                <td colspan="3" align="right"><strong>Tổng cộng:</strong></td>
                <td colspan="2">
                    <?php
                    // Tính tổng phần trăm giảm giá
                    $giamgia_coupon = isset($giamgia) && $giamgia > 0 ? $giamgia : 0;
                    $giamgia_diem = isset($giamgia_diem) && $giamgia_diem > 0 ? $giamgia_diem : 0;
                    $tong_giam = $giamgia_coupon + $giamgia_diem;
                    if ($tong_giam > 100) $tong_giam = 100; // Không vượt quá 100%

                    if ($tong_giam > 0) {
                        $total_discount = $total * $tong_giam / 100;
                        $total_after = $total - $total_discount;
                        echo "<del>" . number_format($total, 0, ',', '.') . " đ</del><br>";
                        echo "<span class='text-success'>" . number_format($total_after, 0, ',', '.') . " đ</span>";
                        echo "<br><small class='text-muted'>Đã giảm tổng cộng: $tong_giam%</small>";
                    } else {
                        echo number_format($total, 0, ',', '.') . " đ";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="5" class="text-end">
                    <form id="formDatHang" method="post" action="chucnang/chucnang_dathang.php">
                        <input type="hidden" name="tongtien" value="<?php echo isset($total_after) ? $total_after : $total; ?>">
                        <input type="hidden" name="diemdadung" value="<?php echo isset($giamgia_diem) ? intval($giamgia_diem) : 0; ?>">
                        <div class="d-inline-block me-2">
                            <select name="hinhthucthanhtoan" id="hinhthucthanhtoan" class="form-select form-select-sm" style="width:auto;display:inline-block;">
                                <option value="cod">Thanh toán khi nhận hàng</option>
                                <option value="qrpay">QR Pay</option>
                            </select>
                        </div>
                        <button type="submit" name="dathang" class="btn btn-success">Đặt hàng</button>
                    </form>
                    <script>
                    document.getElementById('formDatHang').addEventListener('submit', function(e) {
                        var hinhthuc = document.getElementById('hinhthucthanhtoan').value;
                        if (hinhthuc === 'qrpay') {
                            this.action = 'chucnang/chucnang_dathang.php';
                        } else {
                            this.action = 'chucnang/chucnang_dathang.php';
                        }
                    });
                    </script>
                </td>
            </tr>
            <?php
            } else {
                echo '<tr><td colspan="5" class="text-center">Giỏ hàng trống.</td></tr>';
            }
            ?>
        </table>
    </div>

    <div class="mb-3">
        <h5>Bạn đang có <span class="text-success"><?php echo $diemthanhvien; ?></span> điểm thành viên.</h5>
        <form method="post" action="">
            <div class="input-group" style="max-width:350px;">
                <input type="number" min="1" max="<?php echo min($diemthanhvien, $diem_giam_toi_da); ?>" name="so_diem_giam" class="form-control" placeholder="Nhập số điểm muốn dùng (1 điểm = 1%)" required>
                <button type="submit" name="ap_dung_diem" class="btn btn-primary">Dùng điểm giảm giá</button>
            </div>
            <small class="text-muted">Mỗi điểm giảm 1% tổng tiền, tối đa <?php echo $diem_giam_toi_da; ?> điểm cho 90%. Lưa ý không hoàn trả điểm khi đã đặt</small>
        </form>
        <?php if (!empty($diem_message)) echo $diem_message; ?>
    </div>
</div>
</body>
</html>