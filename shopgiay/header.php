<?php
if (session_id() == "") {
    session_start();
}
?>
<!DOCTYPE html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="header">
        
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="sanphamhot.php" class="ms-2">Hot</a>
            <a href="danhmuc.php"><img src="anh/danhmuc.png" alt="HTML tutorial" ></a>
            <form method="get" action="index.php" class="d-inline-flex align-items-center ms-3" style="width:500px;max-width:70vw;">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="width:80%;">
                <button type="submit" class="btn btn-primary btn-sm ms-2">Tìm kiếm</button>
            </form>
            
            
            <?php
            if (isset($_SESSION['tenkhachhang'])) { ?>
                
                <a href="giohang.php"><img src="anh/giohang.webp" alt="HTML tutorial" style="width:42px;height:42px;"></a>

                <!-- filepath: c:\xampp\htdocs\shopgiay\header.php -->
                <div class="float-end dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Welcome, <span style="color: aquamarine;"><?= $_SESSION['tenkhachhang'] ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item text-black" href="thongtin.php">Thông tin cá nhân</a></li>
                        <li><a class="dropdown-item text-black" href="donhang.php">Đơn mua</a></li>
                        
                        <li><a class="dropdown-item text-black" href="danhgiacuaban.php">Đánh giá của bạn</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item  text-danger" href="logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
            <?php } else { ?>
                <a href="login.php">Đăng nhập</a>
                <a href="signup.php">Đăng ký</a>
            <?php } ?>

        </div>
        
        
    </div>
    <style>
        .chat-btn {
            display: inline-flex;
            align-items: center;
            background: #fff;
            border-radius: 30px;
            padding: 6px 18px 6px 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            position: relative;
            font-weight: 500;
            color: #1a1a1a;
            border: none;
            transition: box-shadow 0.2s;
        }

        .chat-btn:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .chat-icon {
            background: #d95363;
            border-radius: 50%;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            position: relative;
        }

        .chat-badge {
            position: absolute;
            left: 28px;
            top: -6px;
            background: #e74c3c;
            color: #fff;
            font-size: 15px;
            font-weight: bold;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            border: 2px solid #fff;
        }

        .chat-text {
            font-size: 20px;
            color: #1a1a1a;
            margin-left: 5px;
        }

        .chat-fixed {
            position: fixed;
            right: 30px;
            bottom: 30px;
            z-index: 9999;
        }
    </style>
<?php if (isset($_SESSION['makhachhang'])): ?>
<!-- Nút chat cố định góc phải dưới -->
<a href="javascript:void(0)" class="chat-btn chat-fixed" id="openChatBtn">
    <span class="chat-icon">
        <img src="anh/chat.png" alt="Chat" style="width:20px;height:20px;">
    </span>
    <span class="chat-text">Liên hệ shop</span>
</a>

<!-- Popup chatbox -->
<div id="chatPopup" style="display:none; position:fixed; right:30px; bottom:90px; width:500px; max-width:98vw; background:#fff; border-radius:12px; box-shadow:0 4px 24px rgba(0,0,0,0.18); z-index:10000; overflow:hidden;">
    <div style="background:#d95363; color:#fff; padding:10px 16px; font-weight:bold; display:flex; justify-content:space-between; align-items:center;">
        Chat với shop
        <span id="closeChatBtn" style="cursor:pointer; font-size:20px;">&times;</span>
    </div>
    <iframe src="chatbox.php" style="width:100%; height:400px; border:none;" id="chatFrame"></iframe>
</div>
<script>
document.getElementById('openChatBtn').onclick = function() {
    document.getElementById('chatPopup').style.display = 'block';
};
document.getElementById('closeChatBtn').onclick = function() {
    document.getElementById('chatPopup').style.display = 'none';
};
</script>
<?php endif; ?>