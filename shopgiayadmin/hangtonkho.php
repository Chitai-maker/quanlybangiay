<?php

session_start();
if (!isset($_SESSION['name']))
  header("location:login.php");
if ($_SESSION['quyen'] > 1) {// Nhân viên kho trở lên
    header("location:dangnhap_quyencaohon.php");
}
include "sidebar.php"; 
include_once("chucnang/connectdb.php");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách tồn kho giày</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
        // Display session message if set
        if (isset($_SESSION['message'])) {
            echo "<div class='session-message text-center'>"; // Add a wrapper with a class
            echo "<div class='alert alert-success alert-dismissible fade show d-inline-block' role='alert'>";
            echo $_SESSION['message'];
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            echo "</div>";
            echo "</div>";
            unset($_SESSION['message']); // Clear the message after displaying it
        }
        ?>
        <h2 class="mb-4">Danh sách tồn kho giày</h2>
<div class="container mt-4 d-flex justify-content-center align-items-center" style="border:none; box-shadow:none;">
    
    <form method="get" action="hangtonkho.php" class="d-flex flex-grow-1 justify-content-center" style="border:none; box-shadow:none;">
        <input type="text" name="search" class="form-control w-50" placeholder="Tìm kiếm sản phẩm..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
        
        <select name="sort_magiay" class="form-select ms-3" style="width:auto;">
            <option value="">-- Sắp xếp mặc định --</option>
            <option value="asc" <?= (isset($_GET['sort_magiay']) && $_GET['sort_magiay'] == 'asc') ? 'selected' : '' ?>>Cũ đến mới</option>
            <option value="desc" <?= (isset($_GET['sort_magiay']) && $_GET['sort_magiay'] == 'desc') ? 'selected' : '' ?>>Mới đến cũ</option>
        </select>
        <div class="form-check ms-3">
            <input class="form-check-input" type="checkbox" name="hangkhongco" id="hangkhongco" value="1" <?= isset($_GET['hangkhongco']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="hangkhongco">
                Chỉ hiện hàng chưa bán
            </label>
        </div>
    </form>
</div>
<div class="container mt-4">
    
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Hình ảnh</th>
                <th>Tên giày</th>
                <th>Số lượng tồn kho</th>
            <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $where = "WHERE 1";
            if (!empty($_GET['search'])) {
                $search = mysqli_real_escape_string($conn, $_GET['search']);
                $where .= " AND tengiay LIKE '%$search%'";
            }
            if (isset($_GET['hangkhongco']) && $_GET['hangkhongco'] == 1) {
                $where .= " AND magiay NOT IN (SELECT ma_giay FROM chitietdonhang)";
            }

            // Xử lý sắp xếp theo magiay
            $orderBy = "ORDER BY soluongtonkho DESC";
            if (!empty($_GET['sort_magiay'])) {
                $sort = strtolower($_GET['sort_magiay']) == 'asc' ? 'ASC' : 'DESC';
                $orderBy = "ORDER BY magiay $sort";
            }

            $sql = "SELECT * FROM giay $where $orderBy";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>
                        <img src="anhgiay/<?= htmlspecialchars($row['anhminhhoa']) ?>" alt="<?= htmlspecialchars($row['tengiay']) ?>" style="width:70px; height:auto;">
                    </td>
                    <td><?= htmlspecialchars($row['tengiay']) ?></td>
                    <td><?= (int)$row['soluongtonkho'] ?></td>
                    <td>
                        <a href="themhang.php?magiay=<?= (int)$row['magiay'] ?>" class="btn btn-success">Cộng thêm</a>
                        <form action="chucnang/chucnang_xoagiay.php" method="POST" class="d-inline form-no-border">
                  <button type="submit" name="xoa_giay" value="<?= $row['magiay']; ?>" class="btn btn-danger btn-sm"onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xoá</button>
                </form>
                    </td>
                </tr>
            <?php endwhile; ?>
           
        </tbody>
    </table>
</div>
</body>
</html>