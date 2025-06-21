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
    <title>Danh sách sắp hết hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4 d-flex justify-content-center align-items-center" style="border:none; box-shadow:none;">
        <h2 class="mb-4">Danh sách sắp hết hàng</h2>
    </div>
    <div class="container mt-4">

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tên giày</th>
                    <th>Số lượng tồn kho</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM giay wHERE soluongtonkho < 5 and soluongtonkho > 0 ORDER BY soluongtonkho ASC";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <img src="anhgiay/<?= htmlspecialchars($row['anhminhhoa']) ?>" alt="<?= htmlspecialchars($row['tengiay']) ?>" style="width:70px; height:auto;">
                        </td>
                        <td><?= htmlspecialchars($row['tengiay']) ?></td>
                        <td><?= (int)$row['soluongtonkho'] ?></td>
                        <td>
                            <a href="themhang.php?magiay=<?= htmlspecialchars($row['magiay']) ?>" class="btn btn-primary">Thêm hàng</a>
                            
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>