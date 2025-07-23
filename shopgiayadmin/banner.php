<?php
session_start();
include "sidebar.php";
include "../shopgiay/chucnang/connectdb.php";

// Xử lý xóa banner
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM banner WHERE ma_banner = $id");
    header("Location: banner.php");
    exit();
}

// Lấy thông tin banner cần sửa (nếu có)
$edit_banner = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM banner WHERE ma_banner = $edit_id");
    $edit_banner = mysqli_fetch_assoc($result);
}

// Xử lý cập nhật banner
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_banner'])) {
    $id = intval($_POST['banner_id']);
    $ten_banner = trim($_POST['ten_banner']);
    $link_banner = trim($_POST['link_banner']);
    $trang_thai = isset($_POST['trang_thai']) ? 1 : 0;

    // Xử lý upload ảnh mới (nếu có)
    $anh_banner = $_POST['anh_banner_old'];
    if (isset($_FILES['anh_banner']) && $_FILES['anh_banner']['error'] == 0) {
        $target_dir = "../shopgiay/anh/";
        $file_name = basename($_FILES["anh_banner"]["name"]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["anh_banner"]["tmp_name"], $target_file)) {
            $anh_banner = $file_name;
        }
    }

    if ($ten_banner && $link_banner && $anh_banner) {
        $stmt = $conn->prepare("UPDATE banner SET ten_banner=?, link_banner=?, anh_banner=?, trang_thai=? WHERE ma_banner=?");
        $stmt->bind_param("sssii", $ten_banner, $link_banner, $anh_banner, $trang_thai, $id);
        $stmt->execute();
        $msg = "Cập nhật banner thành công!";
        // Sau khi cập nhật, load lại trang không còn ?edit
        header("Location: banner.php");
        exit();
    } else {
        $msg = "Vui lòng nhập đầy đủ thông tin và chọn ảnh!";
    }
}

// Xử lý thêm banner
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_banner'])) {
    $ten_banner = trim($_POST['ten_banner']);
    $link_banner = trim($_POST['link_banner']);
    $trang_thai = isset($_POST['trang_thai']) ? 1 : 0;

    // Xử lý upload ảnh
    $anh_banner = "";
    if (isset($_FILES['anh_banner']) && $_FILES['anh_banner']['error'] == 0) {
        $target_dir = "../shopgiay/anh/";
        $file_name = basename($_FILES["anh_banner"]["name"]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["anh_banner"]["tmp_name"], $target_file)) {
            $anh_banner = $file_name;
        }
    }

    if ($ten_banner && $link_banner && $anh_banner) {
        $stmt = $conn->prepare("INSERT INTO banner (ten_banner, link_banner, anh_banner, trang_thai) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $ten_banner, $link_banner, $anh_banner, $trang_thai);
        $stmt->execute();
        $msg = "Thêm banner thành công!";
    } else {
        $msg = "Vui lòng nhập đầy đủ thông tin và chọn ảnh!";
    }
}
?>

<div class="container mt-5">
    <div class="mx-auto" style="max-width:900px;">
            <h2 class="text-center mb-3" style="font-weight:700;">
                <?= $edit_banner ? "Sửa banner" : "Thêm banner" ?>
            </h2>
            <?php if (!empty($msg)): ?>
                <div class="alert alert-info text-center"><?= $msg ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <?php if ($edit_banner): ?>
                    <input type="hidden" name="banner_id" value="<?= $edit_banner['ma_banner'] ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên banner</label>
                    <input type="text" class="form-control" name="ten_banner"
                        value="<?= $edit_banner ? htmlspecialchars($edit_banner['ten_banner']) : "" ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Link banner</label>
                    <input type="text" class="form-control" name="link_banner"
                        value="<?= $edit_banner ? htmlspecialchars($edit_banner['link_banner']) : "" ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Ảnh banner</label>
                    <?php if ($edit_banner && $edit_banner['anh_banner']): ?>
                        <div class="mb-2">
                            <img src="../shopgiay/anh/<?= htmlspecialchars($edit_banner['anh_banner']) ?>" style="max-width:120px;max-height:60px;">
                        </div>
                        <input type="hidden" name="anh_banner_old" value="<?= htmlspecialchars($edit_banner['anh_banner']) ?>">
                    <?php endif; ?>
                    <input type="file" class="form-control" name="anh_banner" accept="image/*" <?= $edit_banner ? "" : "required" ?>>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="trang_thai" id="trang_thai"
                        <?= ($edit_banner && $edit_banner['trang_thai']) || !$edit_banner ? "checked" : "" ?>>
                    <label class="form-check-label" for="trang_thai">Hiển thị</label>
                </div>
                <button type="submit"
                        name="<?= $edit_banner ? "update_banner" : "add_banner" ?>"
                        class="btn btn-primary w-100">
                    <?= $edit_banner ? "Cập nhật banner" : "Thêm banner" ?>
                </button>
                <?php if ($edit_banner): ?>
                    <a href="banner.php" class="btn btn-secondary w-100 mt-2">Hủy</a>
                <?php endif; ?>
            </form>
        
    </div>
</div>
<?php
// Hiển thị danh sách banner
include "../shopgiayadmin/chucnang/chucnang_hiemthibanner.php";
?>