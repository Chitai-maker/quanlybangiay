<?php
// filepath: c:\xampp\htdocs\quanlybangiay\shopgiay\chucnang\chucnang_dathang.php
session_start();
include "connectdb.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION['makhachhang']) || empty($_SESSION["giohang"])) {
    header("Location: ../giohangv2.php");
    exit;
}

$ma_khachhang = $_SESSION['makhachhang'];
$ngaydat = date('Y-m-d H:i:s');
$trangthai = "Chờ xác nhận";

// Tính tổng tiền, giá từng sản phẩm (có khuyến mãi)
$total = 0;
$cart = $_SESSION["giohang"];
$chitiet = [];
foreach ($cart as $item) {
    $magiay = $item["magiay"];
    $soluong = $item["soluong"];

    // Lấy giá gốc
    $sql = "SELECT giaban FROM giay WHERE magiay = '$magiay'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $giaban = $row['giaban'];

    // Lấy giảm giá nếu có
    $query_discount = "SELECT giakhuyenmai FROM sanphamhot WHERE magiay = '$magiay'";
    $result_discount = mysqli_query($conn, $query_discount);
    $discount = 0;
    if (mysqli_num_rows($result_discount) > 0) {
        $discount_row = mysqli_fetch_assoc($result_discount);
        $discount = $discount_row['giakhuyenmai'];
    }
    $final_price = $discount > 0 ? $giaban * (1 - $discount / 100) : $giaban;
    $subtotal = $final_price * $soluong;
    $total += $subtotal;

    $chitiet[] = [
        'magiay' => $magiay,
        'soluong' => $soluong
    ];
}

// Áp dụng mã giảm giá nếu có
$giamgia = 0;
if (isset($_POST['tongtien'])) {
    // Nếu tổng tiền đã được tính sẵn từ form (sau khi áp dụng coupon)
    $tongtien = intval($_POST['tongtien']);
} else {
    // Nếu không, dùng tổng đã tính ở trên
    $tongtien = $total;
}

// Lấy hình thức thanh toán
$hinhthucthanhtoan = isset($_POST['hinhthucthanhtoan']) && $_POST['hinhthucthanhtoan'] === 'qrpay' ? 'qr' : 'cod';

// Nếu là QR thì trạng thái là "Chưa thanh toán", ngược lại là "Chờ xác nhận"
if ($hinhthucthanhtoan === 'qr') {
    $trangthai = "Chờ xác nhận thanh toán QR";
} else {
    $trangthai = "Chờ xác nhận";
}

// Thêm đơn hàng vào bảng donhang
$sql_donhang = "INSERT INTO donhang (ma_khachhang, ngaydat, trangthai, tongtien, hinhthucthanhtoan) VALUES ('$ma_khachhang', now(), '$trangthai', '$tongtien', '$hinhthucthanhtoan')";
if (mysqli_query($conn, $sql_donhang)) {
    $ma_donhang = mysqli_insert_id($conn);

    // Thêm chi tiết đơn hàng
    foreach ($chitiet as $ct) {
        $magiay = $ct['magiay'];
        $soluong = $ct['soluong'];

        // Lấy giá gốc
        $sql = "SELECT giaban FROM giay WHERE magiay = '$magiay'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $giaban = $row['giaban'];

        // Lấy giảm giá nếu có
        $query_discount = "SELECT giakhuyenmai FROM sanphamhot WHERE magiay = '$magiay'";
        $result_discount = mysqli_query($conn, $query_discount);
        $discount = 0;
        if (mysqli_num_rows($result_discount) > 0) {
            $discount_row = mysqli_fetch_assoc($result_discount);
            $discount = $discount_row['giakhuyenmai'];
        }
        $final_price = $discount > 0 ? $giaban * (1 - $discount / 100) : $giaban;

        // Lưu giá bán thực tế vào chi tiết đơn hàng
        mysqli_query($conn, "INSERT INTO chitietdonhang (ma_donhang, ma_giay, soluong, giaban) VALUES ('$ma_donhang', '$magiay', '$soluong', '$final_price')");

        // Trừ số lượng tồn kho
        mysqli_query($conn, "UPDATE giay SET soluongtonkho = soluongtonkho - $soluong WHERE magiay = '$magiay'");
    }

    // Trừ điểm thành viên nếu có dùng điểm giảm giá
    if (isset($_POST['diemdadung']) && intval($_POST['diemdadung']) > 0 && isset($_SESSION['makhachhang'])) {
        $diem_tru = intval($_POST['diemdadung']);
        $ma_khachhang = $_SESSION['makhachhang'];
        mysqli_query($conn, "UPDATE khachhang SET diemthanhvien = GREATEST(diemthanhvien - $diem_tru, 0) WHERE ma_khachhang = '$ma_khachhang'");
    }

    // Xóa giỏ hàng sau khi đặt hàng thành công
    unset($_SESSION["giohang"]);

    if ($hinhthucthanhtoan === 'qr') {
        // Chuyển hướng sang trang thanh toán QR, truyền tổng tiền
        echo '<form id="redirectForm" method="post" action="../thanhtoan.php">';
        echo '<input type="hidden" name="tongtien" value="' . htmlspecialchars($tongtien) . '">';
        echo '</form>';
        echo '<script>document.getElementById("redirectForm").submit();</script>';
        exit;
    } else {
        echo "<script>alert('Đặt hàng thành công!');window.location='../index.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Có lỗi khi đặt hàng. Vui lòng thử lại!');window.location='../giohangv2.php';</script>";
    exit;
}
?>