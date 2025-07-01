<?php

session_start();
include "chucnang/connectdb.php";


// Giả sử đã đăng nhập, lấy mã khách hàng
$ma_khachhang = $_SESSION['makhachhang'] ?? 0;

// Gửi tin nhắn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['noidung'])) {
    $noidung = mysqli_real_escape_string($conn, $_POST['noidung']);
    mysqli_query($conn, "INSERT INTO chatbox (ma_khachhang, noidung, nguoigui) VALUES ('$ma_khachhang', '$noidung', 'khach')");
    header("Location: chatbox.php");
    exit;
}

// Lấy lịch sử chat
$result = mysqli_query($conn, "SELECT * FROM chatbox WHERE ma_khachhang = '$ma_khachhang' ORDER BY thoigian ASC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chat với Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .chatbox { 
            max-width: 500px; 
            /* margin: 40px auto; */ /* XÓA hoặc comment dòng này */
            border: 1px solid #ccc; 
            border-radius: 8px; 
            padding: 16px; 
            background: #fff;
        }
        .msg-khach { text-align: right; }
        .msg-shop { text-align: left; color: #0d6efd; }
        .msg { margin-bottom: 8px; }
        .msg-time { font-size: 11px; color: #888; }
    </style>
</head>
<body>
<div class="chatbox">
    <div style="height:310px;overflow-y:auto;background:#f8f9fa;padding:10px;border-radius:6px;">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="msg <?= $row['nguoigui']=='khach' ? 'msg-khach' : 'msg-shop' ?>">
                <div><?= htmlspecialchars($row['noidung']) ?></div>
                <div class="msg-time"><?= date('H:i d/m', strtotime($row['thoigian'])) ?> <?= $row['nguoigui']=='khach' ? '(Bạn)' : '(Shop)' ?></div>
            </div>
        <?php endwhile; ?>
    </div>
    <form method="post" class="mt-3 d-flex">
        <input type="text" name="noidung" class="form-control me-2" placeholder="Nhập tin nhắn..." required autocomplete="off">
        <button class="btn btn-primary" type="submit">Gửi</button>
    </form>
</div>
</body>
<script>
    <?php if ($ma_khachhang): ?>
    setInterval(function() {
        var input = document.querySelector('input[name="noidung"]');
        if (document.activeElement !== input) {
            location.reload();
        }
    }, 5000);
    // Tự động cuộn xuống cuối khung chat khi load trang
    window.onload = function() {
        var chatDiv = document.querySelector('.chatbox > div[style*="overflow-y:auto"]');
        if (chatDiv) chatDiv.scrollTop = chatDiv.scrollHeight;
    }
    <?php endif; ?>
</script>
</html>