
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
    <title>Thêm số lượng tồn kho</title>
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Thêm số lượng tồn kho</h1>
<?php 
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-info'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);
}
if(isset($_GET['magiay'])) {
    $magiay = mysqli_real_escape_string($conn, $_GET['magiay']);
    $query = "SELECT * FROM giay WHERE magiay='$magiay'";
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) > 0) {
        $giay = mysqli_fetch_array($query_run);
        ?>
        <form action="chucnang/chucnang_themsoluong.php" method="post">
            <label>ID giày</label>
            <input type="number" name="magiay" value="<?=$giay['magiay'];?>" class="form-control mb-2" readonly>
            <label>Tên giày</label>
            <input type="text" value="<?=$giay['tengiay'];?>" class="form-control mb-2" readonly>
            <label>Số lượng tồn kho hiện tại</label>
            <input type="number" value="<?=$giay['soluongtonkho'];?>" class="form-control mb-2" readonly>
            <label>Số lượng muốn thêm</label>
            <input type="number" name="soluong" class="form-control mb-3" min="1" required>
            <button type="submit" name="submit" class="btn btn-primary">Cộng thêm</button>
        </form>
        <?php
    } else {
        echo "<p class='text-danger'>Không tìm thấy giày.</p>";
    }
} else {
    echo "<p class='text-danger'>Không có mã giày được cung cấp.</p>";
}
?>
</div>
</body>
</html>