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
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-info'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị
}
if(isset($_GET['masize'])) {
    $masize = mysqli_real_escape_string($conn, $_GET['masize']);
    $query = "SELECT * FROM sizegiay WHERE masize='$masize'";
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) > 0) {
        $size = mysqli_fetch_array($query_run);
        ?>
        <form action="chucnang/chucnang_editsize.php" method="post" >
            <label>id size</label>
            <input type="number" name="masize" value="<?=$size['masize'];?>" class="form-control" readonly >
            <label>Tên size</label>
            <input type="text" name="tensize" value="<?=$size['tensize'];?>" class="form-control">
            <button type="submit" name="submit" class="btn btn-primary"> Update</button>
        </form>
        <?php
    } else {
        echo "<p class='text-danger'>Không tìm thấy size.</p>";
    }
} else {
    echo "<p class='text-danger'>Không có mã size được cung cấp.</p>";
}
?>
    
</div>
</body>
</html>