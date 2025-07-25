<?php
session_start();
if (!isset($_SESSION['name']))
    header("location:login.php");

include "sidebar.php";
include "chucnang/connectdb.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Thêm Coupon</title>
</head>
<body>
    <div class="container mt-5">
        <form action="chucnang/chucnang_themcoupon.php" method="post">
            <h2>Thêm Coupon</h2>
            <div class="form-group mt-4">
                <label for="ten_coupon">Tên coupon:</label>
                <input type="text" class="form-control" name="ten_coupon" id="ten_coupon" required>
            </div>
            <div class="form-group mt-4">
                <label for="giatri">Giá trị giảm (%):</label>
                <input type="number" class="form-control" name="giatri" id="giatri" min="1" max="100" required>
            </div>
            <div class="form-group mt-4">
                <label for="ngaybatdau">Ngày bắt đầu:</label>
                <input type="datetime-local" class="form-control" name="ngaybatdau" id="ngaybatdau" required>
            </div>
            <div class="form-group mt-4">
                <label for="ngayketthuc">Ngày kết thúc:</label>
                <input type="datetime-local" class="form-control" name="ngayketthuc" id="ngayketthuc" required>
            </div>
            <input class="btn btn-primary mt-4" type="submit" value="Thêm" name="submit">
        </form>
    </div>
    <?php include "chucnang/chucnang_xemcoupon.php"; ?>
</body>
</html>