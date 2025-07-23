<?php
// Kết nối CSDL
session_start();
if (!isset($_SESSION['tenkhachhang']))
    header("location:login.php");
include "chucnang/connectdb.php";
$conn->set_charset("utf8mb4");

// Lấy mã đơn hàng từ URL
$ma_donhang = isset($_GET['ma_donhang']) ? intval($_GET['ma_donhang']) : 0;

// Xử lý khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ma_donhang > 0) {
    $lydo = $conn->real_escape_string($_POST['lydo']);
    $anh = '';

    if (isset($_FILES['anh']) && $_FILES['anh']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "anhdoitra/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $filename = time() . '_' . basename($_FILES["anh"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["anh"]["tmp_name"], $target_file)) {
            $anh = $conn->real_escape_string($target_file);
        } else {
            echo "<p style='color:red'>Lỗi upload ảnh!</p>";
        }
    }

    if ($anh) {
        $sql = "INSERT INTO doitrahang (ma_donhang, anh, lydo, thoigian) VALUES ($ma_donhang, '$anh', '$lydo', now())";
        if ($conn->query($sql)) {
            $_SESSION['message'] = "Yêu cầu đổi trả hàng đã được gửi thành công.";
            header("Location: donhang.php?message=Yêu cầu đổi trả hàng đã được gửi thành công.");
            exit;
        } else {
            echo "<p style='color:red'>Lỗi: " . $conn->error . "</p>";
        }
    }
}
include 'header.php';
?>


<body>
    <h1>Yêu cầu đổi trả hàng</h1>
    <?php
    // Kiểm tra trạng thái đổi trả
    $check_sql = "SELECT trangthai FROM doitrahang WHERE ma_donhang = $ma_donhang ORDER BY ma_doitrahang DESC LIMIT 1";
    $check_result = $conn->query($check_sql);
    $da_yeucau = false;
    $trangthai_doitra = '';
    if ($check_result && $check_result->num_rows > 0) {
        $row = $check_result->fetch_assoc();
        $trangthai_doitra = $row['trangthai'];
        $da_yeucau = true;
    }

    if ($ma_donhang > 0):
        if ($da_yeucau && ($trangthai_doitra == 'đang xử lý' ||  $trangthai_doitra == 'Từ chối đổi trả')): ?>
            <div class="alert alert-warning mt-5 text-center">
                Đơn hàng này đã yêu cầu đổi trả (Trạng thái: <?php echo $trangthai_doitra ?: 'đang xử lý'; ?>).
            </div>
        <?php elseif ($da_yeucau && ($trangthai_doitra == 'Đã xác nhận đổi trả' )): ?>
            <div class="alert alert-warning mt-5 text-center">
                
                Đơn hàng này đã được xác nhận đổi trả. Vui lòng in tem vận chuyển và gửi hàng đến kho.

            </div>
        <?php else: ?>
            <div class="container mt-5">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                    <label>Mã đơn hàng: </label>
                    <input type="text"  name="ma_donhang" value="<?php echo $ma_donhang; ?>" readonly><br><br>
                    <label>Lý do đổi trả:</label><br>
                    <textarea name="lydo" class="form-control" rows="4" cols="50" required></textarea><br><br>
                    <label>Ảnh minh họa:</label>
                    <input type="file" class="form-control" name="anh" accept="image/*" required><br><br>
                    <button type="submit" class="btn btn-success">Gửi yêu cầu</button>
                    </div>
                </form>
            </div>
        <?php endif;
    else: ?>
        <p>Không tìm thấy mã đơn hàng hợp lệ!</p>
    <?php endif; ?>
</body>
</html>