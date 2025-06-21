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
    <form method="get" action="kho.php" class="d-flex flex-grow-1 justify-content-center" style="border:none; box-shadow:none;">
        <input type="text" name="search" class="form-control w-50" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
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
           $sql = "SELECT tengiay, anhminhhoa, soluongtonkho FROM giay ORDER BY soluongtonkho DESC";
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