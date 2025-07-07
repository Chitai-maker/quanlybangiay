<?php

include "sidebar.php";
include "chucnang/connectdb.php";

$ma_nhanvien = isset($_GET['ma_nhanvien']) ? intval($_GET['ma_nhanvien']) : 0;
$ten_nhanvien = isset($_GET['ten_nhanvien']) ? htmlspecialchars($_GET['ten_nhanvien']) : "";

// Lấy thông tin ngân hàng hiện tại
$ten_chutaikhoan = "";
$so_taikhoan = "";
$ma_nganhang = "";

if ($ma_nhanvien) {
    $stmt = $conn->prepare("SELECT ten_chutaikhoan, so_taikhoan, ma_nganhang FROM thongtinnganhang WHERE ma_nhanvien = ?");
    $stmt->bind_param("i", $ma_nhanvien);
    $stmt->execute();
    $stmt->bind_result($ten_chutaikhoan, $so_taikhoan, $ma_nganhang);
    $stmt->fetch();
    $stmt->close();
}

// Lấy danh sách ngân hàng từ API VietQR
$banks = [];
$ch = curl_init("https://api.vietqr.io/v2/banks");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);
if (isset($data['data']) && is_array($data['data'])) {
    $banks = $data['data'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sửa thông tin ngân hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <form method="post" action="chucnang/xuly_suathongtinnganhang.php">
        <h2>Sửa thông tin ngân hàng cho nhân viên: <?= $ten_nhanvien ?> (ID: <?= $ma_nhanvien ?>)</h2>
        <input type="hidden" name="ma_nhanvien" value="<?= $ma_nhanvien ?>">
        <div class="mb-3">
            <label for="ten_chutaikhoan" class="form-label">Tên chủ tài khoản</label>
            <input type="text" class="form-control" name="ten_chutaikhoan" id="ten_chutaikhoan" value="<?= htmlspecialchars($ten_chutaikhoan) ?>" required>
        </div>
        <div class="mb-3">
            <label for="so_taikhoan" class="form-label">Số tài khoản</label>
            <input type="text" class="form-control" name="so_taikhoan" id="so_taikhoan" value="<?= htmlspecialchars($so_taikhoan) ?>" required>
        </div>
        <div class="mb-3">
            <label for="ma_nganhang" class="form-label">Ngân hàng</label>
            <select class="form-control" name="ma_nganhang" id="ma_nganhang" required>
                <option value="">-- Chọn ngân hàng --</option>
                <?php foreach ($banks as $bank): ?>
                    <option value="<?= htmlspecialchars($bank['bin']) ?>" <?= ($bank['bin'] == $ma_nganhang) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($bank['shortName']) ?> - <?= htmlspecialchars($bank['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Cập nhật thông tin</button>
    </form>
</div>
</body>
</html>