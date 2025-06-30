<?php
include "header.php";

// Thông tin tài khoản nhận tiền
$accountNo = "1017770113"; // Số tài khoản ngân hàng
$accountName = "NGUYEN DINH HUNG"; // Tên tài khoản
$acqId = "970436"; // Mã ngân hàng (VD: Vietcombank)
$amount = isset($_POST['tongtien']) ? intval($_POST['tongtien']) : 0; // Số tiền cần thanh toán; // Số tiền cần thanh toán (12.000 VNĐ)
// Thông tin bổ sung (có thể để trống)

$addInfo = "THANH TOAN DON HANG ";

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
    "x-api-key: 34e4d413-7302-4d53-b3ff-3705411c3f1f"
]);

$response = curl_exec($ch);
curl_close($ch);

// Giải mã kết quả
$result = json_decode($response, true);

$qrImage = "";
$errorMsg = "";

if (isset($result["code"]) && $result["code"] == 0) {
    // Thành công
    $qrImage = $result["data"]["qrDataURL"] ?? "";
} else {
    // Thất bại, lấy thông báo lỗi
    $errorMsg = $result["desc"] ?? "Không thể tạo mã QR. Vui lòng kiểm tra API Key hoặc thông tin tài khoản.";
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>QR Chuyển Khoản</title></head>
<body>
  <h2>Quét mã QR để thanh toán</h2>
  <?php if ($qrImage): ?>
    <img src="<?= $qrImage ?>" alt="QR Code" />
  <?php else: ?>
    <p><?= htmlspecialchars($errorMsg) ?></p>
  <?php endif; ?>
</body>
</html>