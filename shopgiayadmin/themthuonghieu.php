<?php
session_start();
if (!isset($_SESSION['name']))
header("location:login.php");
if($_SESSION['quyen'] > 1){
    header("location:dangnhap_quyencaohon.php");     
} 
include "sidebar.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Thêm màu giầy</title>
</head>
<body>
    <div class="container mt-5">
        <form action="chucnang/chucnang_themthuonghieu.php" method="post" enctype="multipart/form-data">
        <h2>Thêm thương hiệu</h2>
            <input class="form-control mt-4" type="text" name="tenthuonghieu" id="" placeholder="Nhập tên:">
            <input class="btn btn-primary mt-4" type="submit" value="Thêm thương hiệu" name="submit">
        </form>
    </div>
    <?php include "chucnang/chucnang_xemthuonghieu.php";?>
</body>
</html>