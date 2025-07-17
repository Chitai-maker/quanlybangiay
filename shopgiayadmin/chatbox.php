<?php
session_start();
include "../shopgiay/chucnang/connectdb.php";
// Kiểm tra đăng nhập
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit;
}
include "sidebar.php";

// Lấy danh sách khách hàng đã từng chat
$khach_query = mysqli_query($conn, "
    SELECT k.ma_khachhang, k.ten_khachhang, MAX(c.thoigian) AS last_time
    FROM chatbox c
    JOIN khachhang k ON c.ma_khachhang = k.ma_khachhang
    GROUP BY k.ma_khachhang, k.ten_khachhang
    ORDER BY last_time DESC
");

// Lấy toàn bộ khách hàng
$all_khach_query = mysqli_query($conn, "SELECT ma_khachhang, ten_khachhang FROM khachhang ORDER BY ten_khachhang ASC");

// Xác định khách đang chọn
$ma_khachhang = isset($_GET['ma_khachhang']) ? intval($_GET['ma_khachhang']) : 0;

// Lấy lịch sử chat với khách này
$chat_result = null;
if ($ma_khachhang) {
    $chat_result = mysqli_query($conn, "SELECT * FROM chatbox WHERE ma_khachhang = '$ma_khachhang' ORDER BY thoigian ASC");
}

// Gửi tin nhắn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ma_khachhang && !empty($_POST['noidung'])) {
    $noidung = mysqli_real_escape_string($conn, $_POST['noidung']);
    mysqli_query($conn, "INSERT INTO chatbox (ma_khachhang, noidung, nguoigui, thoigian) VALUES ('$ma_khachhang', '$noidung', 'shop',NOW())");
    header("Location: chatbox.php?ma_khachhang=$ma_khachhang");
    exit;
}
// nếu đã xem tin nhắn của khách thì cập nhật trạng thái
if ($ma_khachhang) {
    mysqli_query($conn, "UPDATE chatbox SET trang_thai='da_doc' WHERE ma_khachhang='$ma_khachhang' AND nguoigui='khach' AND trang_thai='chua_doc'");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Chatbox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f6fa; }
        .chat-sidebar,
        .chat-main,
        .chat-header,
        .chat-body,
        .chat-footer {
            background: transparent !important;
            border-radius: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }
        .chatbox {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }
        .chat-sidebar {
            background: #fff;
            border-radius: 20px 0 0 20px;
            height: 90vh;
            overflow-y: auto;
            padding: 0;
        }
        .chat-main {
            background: #fff;
            height: 90vh;
            border-radius: 0 20px 20px 0;
            display: flex;
            flex-direction: column;
            padding: 0;
        }
        .chat-list-item {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
            text-decoration: none;
            color: #222;
        }
        .chat-list-item.active, .chat-list-item:hover {
            background: #f0f4fa;
        }
        
        .chat-list-item .name { font-weight: 500; }
        .chat-list-item .time { font-size: 12px; color: #bbb; margin-left: auto; }
        .chat-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f0f0f0;
            background: #fff;
            font-weight: bold;
            font-size: 18px;
        }
        .chat-body {
            flex: 1;
            padding: 32px 0 24px 0; /* Trên, phải, dưới, trái */
            overflow-y: auto;
            background: #f8fafd;
        }
        .msg-row {
            display: flex;
            margin-bottom: 18px;
            margin-left: 24px;
            margin-right: 24px;
        }
        .msg-row.me { justify-content: flex-end; }
        .msg-bubble {
            max-width: 60vw; /* hoặc 500px nếu muốn cố định */
            padding: 16px 22px;
            border-radius: 20px;
            background: #1976d2;
            color: #fff;
            font-size: 16px;
            position: relative;
            word-break: break-word;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .msg-row:not(.me) .msg-bubble {
            background: #e3f0ff;
            color: #222;
        }
        .msg-time {
            font-size: 11px;
            color: #888;
            margin-top: 4px;
            margin-left: 6px;
        }
        .chat-footer {
            padding: 0 !important;
            border-top: 1px solid #f0f0f0;
            background: #fff;
        }
        .chat-footer input {
            border-radius: 20px;
            border: 1px solid #ddd;
            padding-left: 16px;
        }
        .chat-footer .btn {
            border-radius: 0 30px 30px 0 !important;
            width: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 0;
        }
        @media (max-width: 991px) {
            .chat-sidebar { border-radius: 20px 20px 0 0; }
            .chat-main { border-radius: 0 0 20px 20px; }
        }
        @media (max-width: 767px) {
            .chat-sidebar { display: none; }
            .chat-main { border-radius: 20px; }
        }
    </style>
</head>
<body>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <!-- Sidebar: Danh sách khách hàng -->
        <div class="col-lg-3 col-md-4 chat-sidebar p-0">
            <div class="p-3 pb-0">
                <div class="fw-bold mb-3">Khách hàng</div>
                <input type="text" class="form-control mb-3" placeholder="Tìm khách...">
            </div>
            <div>
                <div id="chat-list-default">
                    <?php while ($row = mysqli_fetch_assoc($khach_query)): ?>
                        <a href="?ma_khachhang=<?= $row['ma_khachhang'] ?>" class="chat-list-item<?= ($ma_khachhang == $row['ma_khachhang']) ? ' active' : '' ?>">
                            <div class="avatar"></div>
                            <div>
                                <div class="name"><?= htmlspecialchars($row['ten_khachhang']) ?></div>
                                <div class="time"><?= $row['last_time'] ? date('H:i d/m', strtotime($row['last_time'])) : '' ?></div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
                <div id="chat-list-all" style="display:none;">
                    <?php while ($row = mysqli_fetch_assoc($all_khach_query)): ?>
                        <a href="?ma_khachhang=<?= $row['ma_khachhang'] ?>" class="chat-list-item<?= ($ma_khachhang == $row['ma_khachhang']) ? ' active' : '' ?>">
                            <div class="avatar"></div>
                            <div>
                                <div class="name"><?= htmlspecialchars($row['ten_khachhang']) ?></div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <!-- Main chat -->
        <div class="col-lg-6 col-md-8 chat-main p-0">
            <div class="chat-header">
                <?php
                if ($ma_khachhang) {
                    $ten_khach = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ten_khachhang FROM khachhang WHERE ma_khachhang = '$ma_khachhang'"))['ten_khachhang'];
                    echo htmlspecialchars($ten_khach);
                } else {
                    echo "Chọn khách để chat";
                }
                ?>
            </div>
            <div class="chat-body" id="chatBody">
                <?php if ($chat_result): ?>
                    <?php while ($row = mysqli_fetch_assoc($chat_result)): ?>
                        <div class="msg-row<?= $row['nguoigui']=='shop' ? ' me' : '' ?>">
                            <div>
                                <div class="msg-bubble"><?= htmlspecialchars($row['noidung']) ?></div>
                                <div class="msg-time"><?= date('H:i d/m', strtotime($row['thoigian'])) ?> <?= $row['nguoigui']=='shop' ? '(Shop)' : '(Khách)' ?> <?= $row['trang_thai']=='chua_doc' ? 'Chưa đọc' : 'đã đọc' ?></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php elseif ($ma_khachhang): ?>
                    <div class="text-center text-muted">Chưa có tin nhắn.</div>
                <?php else: ?>
                    <div class="text-center text-muted">Chọn khách để xem lịch sử chat.</div>
                <?php endif; ?>
            </div>
            <?php if ($ma_khachhang): ?>
            <form method="post" class="chat-footer d-flex align-items-center" style="padding:0; border:none; background:transparent;">
                <div class="input-group" style="width:100%;">
                    <input type="text" name="noidung" class="form-control" placeholder="Nhập tin nhắn..." required autocomplete="off" style="border-radius: 30px 0 0 30px;">
                    <button class="btn btn-primary" type="submit" style="border-radius:0 30px 30px 0; margin-left:0; width:48px;">
                        <i class="fa fa-paper-plane"></i>
                    </button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- FontAwesome for icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
<script>
    // Tự động cuộn xuống cuối khung chat khi load trang
    window.onload = function() {
        var chatDiv = document.getElementById('chatBody');
        if (chatDiv) chatDiv.scrollTop = chatDiv.scrollHeight;
    }
    var isTyping = false;
    var isSearching = false;
    var inputField = document.querySelector('.chat-footer input');
    var searchField = document.querySelector('.form-control[placeholder="Tìm khách..."]');
    var chatListDefault = document.getElementById('chat-list-default');
    var chatListAll = document.getElementById('chat-list-all');

    if (inputField) {
        inputField.addEventListener('focus', function() { isTyping = true; });
        inputField.addEventListener('blur', function() { isTyping = false; });
    }

    if (searchField) {
        searchField.addEventListener('focus', function() {
            isSearching = true;
            chatListDefault.style.display = 'none';
            chatListAll.style.display = '';
            // Hiện tất cả khách khi vừa focus
            chatListAll.querySelectorAll('.chat-list-item').forEach(function(item) {
                item.style.display = '';
            });
        });
        searchField.addEventListener('blur', function() {
            setTimeout(function() { // Đợi sự kiện click trên danh sách
                isSearching = false;
                if (searchField.value.trim() === '') {
                    chatListDefault.style.display = '';
                    chatListAll.style.display = 'none';
                }
            }, 200);
        });
        searchField.addEventListener('input', function() {
            var keyword = this.value.toLowerCase();
            chatListAll.querySelectorAll('.chat-list-item').forEach(function(item) {
                var name = item.querySelector('.name').textContent.toLowerCase();
                if (name.indexOf(keyword) !== -1) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    function autoReloadChat() {
        if (!isTyping && !isSearching) {
            location.reload();
        }
    }
    setInterval(autoReloadChat, 5000);
</script>
</body>
</html>