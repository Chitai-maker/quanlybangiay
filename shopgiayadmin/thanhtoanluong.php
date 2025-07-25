<?php
session_start();
// kiểm tra nếu không có parameter 'tongtien' thì trả về trang chủ
if (!isset($_POST['tongtien']) || empty($_POST['tongtien'])) {
    header("Location: index.php");
    exit();
}

// Xử lý xác nhận thanh toán
if (isset($_POST['xacnhan'])) {
    include "../shopgiayadmin/chucnang/connectdb.php";
    $ma_nhanvien = isset($_POST['ma_nhanvien']) ? intval($_POST['ma_nhanvien']) : 0;
    $amount = isset($_POST['tongtien']) ? intval($_POST['tongtien']) : 0;
    $now = date('Y-m-d H:i:s');
    // Lưu vào bảng lichsuthanhtoanluong
    if ($ma_nhanvien && $amount) {
        $stmt = $conn->prepare("INSERT INTO lichsuthanhtoanluong (ma_nhanvien, ngaythanhtoan, luong) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $ma_nhanvien, $now, $amount);
        $stmt->execute();
    }
    session_start();
    $_SESSION['message'] = "Cảm ơn bạn đã thanh toán. Đã lưu lịch sử thanh toán lương.";
    header("Location: nhanvien.php");
    exit();
}

include "sidebar.php";
// Thông tin tài khoản nhận tiền
$accountNo = "1017770113"; // Số tài khoản ngân hàng
$accountName = "NGUYEN DINH HUNG"; // Tên tài khoản
$acqId = "970436"; // Mã ngân hàng (VD: Vietcombank)
$ma_nhanvien = isset($_POST['ma_nhanvien']) ? $_POST['ma_nhanvien'] : '';
$ten_nhanvien = isset($_POST['ten_nhanvien']) ? $_POST['ten_nhanvien'] : '';
$amount = isset($_POST['tongtien']) ? intval($_POST['tongtien']) : 0;
$addInfo = "THANH TOAN LUONG CHO NHAN VIEN " . $ten_nhanvien . " (ID: $ma_nhanvien)";

// Kiểm tra nhân viên có thông tin ngân hàng chưa
include "../shopgiayadmin/chucnang/connectdb.php";
$hasBankInfo = false;
$accountNo = "";
$accountName = "";
$acqId = "";
if ($ma_nhanvien) {
    $stmt = $conn->prepare("SELECT so_taikhoan, ten_chutaikhoan, ma_nganhang FROM thongtinnganhang WHERE ma_nhanvien = ?");
    $stmt->bind_param("i", $ma_nhanvien);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $hasBankInfo = true;
        $stmt->bind_result($accountNo, $accountName, $acqId);
        $stmt->fetch();
    }
    $stmt->close();
}
// Nếu không có thì dùng tài khoản mặc định (nếu muốn)
if (!$hasBankInfo) {
    $accountNo = "1017770113";
    $accountName = "NGUYEN DINH HUNG";
    $acqId = "970436";
}

$qrImage = "";
$errorMsg = "";
// Chỉ tạo QR nếu có thông tin ngân hàng
if ($hasBankInfo) {
    // Tạo payload gửi đến API VietQR
    $data = [
        "accountNo" => $accountNo,
        "accountName" => $accountName,
        "acqId" => $acqId,
        "amount" => $amount,
        "addInfo" => $addInfo,
        "template" => "compact"
    ];

    // Gửi yêu cầu POST đến API VietQR
    $ch = curl_init("https://api.vietqr.io/v2/generate");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "x-client-id: c0898476-841e-4a50-83ea-2b0833c8ae84",
        "x-api-key: 34e4d413-7302-4d53-b3ff-37054111c3f1f"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    // Giải mã kết quả
    $result = json_decode($response, true);

    if (isset($result["code"]) && $result["code"] == 0) {
        // Thành công
        $qrImage = $result["data"]["qrDataURL"] ?? "";
    } else {
        // Thất bại, lấy thông báo lỗi
        $errorMsg = $result["desc"] ?? "Không thể tạo mã QR. Vui lòng kiểm tra API Key hoặc thông tin tài khoản.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>QR Chuyển Khoản</title></head>
<body>
  
  <!----căn lề giửa trang---->
<div class="text-center">
  <h2>Thanh toán lương cho <?php echo $ten_nhanvien  ?> </h2>
  <?php if ($hasBankInfo): ?>
    <img src="<?= $qrImage ?>" alt="QR Code" />
    <form method="post" action="thanhtoanluong.php">
        <input type="hidden" name="ma_nhanvien" value="<?= htmlspecialchars($ma_nhanvien) ?>">
        <input type="hidden" name="ten_nhanvien" value="<?= htmlspecialchars($ten_nhanvien) ?>">
        <input type="hidden" name="tongtien" value="<?= htmlspecialchars($amount) ?>">
        <button type="submit" name="xacnhan" class="btn btn-success mt-3">Tôi đã thanh toán</button>
    </form>
  <?php else: ?>
    <p>Nhân viên chưa có thông tin ngân hàng.</p>
    <form method="post" action="thanhtoanluong.php">
        <input type="hidden" name="ma_nhanvien" value="<?= htmlspecialchars($ma_nhanvien) ?>">
        <input type="hidden" name="ten_nhanvien" value="<?= htmlspecialchars($ten_nhanvien) ?>">
        <input type="hidden" name="tongtien" value="<?= htmlspecialchars($amount) ?>">
        <button type="submit" name="xacnhan" class="btn btn-warning mt-3">Thanh toán tiền mặt</button>
    </form>
  <?php endif; ?>

  <?php

?>
</div>
</body>
</html>