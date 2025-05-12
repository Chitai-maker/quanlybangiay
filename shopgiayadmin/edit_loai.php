<?php
session_start();
if (!isset($_SESSION['name']))
  header("location:login.php");
include "header.php"; 
include "chucnang/connectdb.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Edit thương hiệu</title>
</head>
<body>
<div class="container mt-5">
<?php 
if(isset($_GET['maloaigiay'])) {
    $maloaigiay = mysqli_real_escape_string($conn, $_GET['maloaigiay']);
    $query = "SELECT * FROM loaigiay WHERE maloaigiay='$maloaigiay'";
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) > 0) {
        $loaigiay = mysqli_fetch_array($query_run);
        ?>
        <form action="chucnang/chucnang_editloai.php" method="post" >
            <label>id loại giầy</label>
            <input type="number" name="maloaigiay" value="<?=$loaigiay['maloaigiay'];?>" class="form-control" readonly >
            <label>Tên loại giầy</label>
            <input type="text" name="tenloaigiay" value="<?=$loaigiay['tenloaigiay'];?>" class="form-control">
            <button type="submit" name="submit" class="btn btn-primary"> Update</button>
        </form>
        <?php
    } else {
        echo "<p class='text-danger'>Không tìm thấy loại giầy.</p>";
    }
} else {
    echo "<p class='text-danger'>Không có mã loại giầy được cung cấp.</p>";
}
 
?>
</div>
</body>
</html>