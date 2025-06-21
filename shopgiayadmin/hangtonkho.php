<?php

session_start();
if (!isset($_SESSION['name']))
  header("location:login.php");
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
<div class="container mt-4 d-flex justify-content-center align-items-center" style="border:none; box-shadow:none;">
    <h2 class="mb-4">Danh sách tồn kho giày</h2>
    <form method="get" action="hangtonkho.php" class="d-flex flex-grow-1 justify-content-center" style="border:none; box-shadow:none;">
        <input type="text" name="search" class="form-control w-50" placeholder="Tìm kiếm sản phẩm..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
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
            $sql = "SELECT * FROM giay $where ORDER BY soluongtonkho DESC";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>
                        <img src="anhgiay/<?= htmlspecialchars($row['anhminhhoa']) ?>" alt="<?= htmlspecialchars($row['tengiay']) ?>" style="width:70px; height:auto;">
                    </td>
                    <td><?= htmlspecialchars($row['tengiay']) ?></td>
                    <td><?= (int)$row['soluongtonkho'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>