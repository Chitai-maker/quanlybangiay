<?php
session_start();
if (!isset($_SESSION['name']))
  header("location:login.php");
include "header.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sản phẩm</title>
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

<div class="container mt-4 d-flex justify-content-center align-items-center">
    <form method="get" action="index.php" class="d-flex flex-grow-1 justify-content-center">
        <input type="text" name="search" class="form-control w-50" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
    </form>
    <a href="themgiay.php" class="btn btn-success ms-3" title="Thêm sản phẩm mới" style="font-size: 1.5rem; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
        +
    </a>
</div>

<!-- Nút lọc theo thương hiệu -->
<?php
include_once("chucnang/connectdb.php");
$mathuonghieu = isset($_GET['mathuonghieu']) ? intval($_GET['mathuonghieu']) : 0;
$thuonghieu_query = "SELECT * FROM thuonghieu";
$thuonghieu_result = mysqli_query($conn, $thuonghieu_query);
?>
<form method="GET" action="" style="border: none;">
    <h3>Thương hiệu</h3>
    <?php while($row = mysqli_fetch_assoc($thuonghieu_result)): ?>
        <button type="submit" name="mathuonghieu" value="<?= $row['mathuonghieu'] ?>" class="btn btn-outline-primary" style="border: none;<?= ($mathuonghieu == $row['mathuonghieu']) ? 'font-weight:bold;background:#d1e7fd;' : '' ?>">
            <?= htmlspecialchars($row['tenthuonghieu']) ?>
        </button>
    <?php endwhile; ?>
</form>
<!-- Nút lọc theo loại giày -->
 <?php
include_once("chucnang/connectdb.php");
$maloaigiay = isset($_GET['maloaigiay']) ? intval($_GET['maloaigiay']) : 0;
$loaigiay_query = "SELECT * FROM loaigiay";
$loaigiay_result = mysqli_query($conn, $loaigiay_query);
?>
 <form method="GET" action="" style="border: none;">
    <h3>Loại giày</h3>
    <?php while($row = mysqli_fetch_assoc($loaigiay_result)): ?>
        <button type="submit" name="maloaigiay" value="<?= $row['maloaigiay'] ?>" class="btn btn-outline-primary" style="border: none;<?= ($maloaigiay == $row['maloaigiay']) ? 'font-weight:bold;background:#d1e7fd;' : '' ?>">
            <?= htmlspecialchars($row['tenloaigiay']) ?>
        </button>
    <?php endwhile; ?>
</form>

<!-- Nút lọc theo size giày -->
<?php
include_once("chucnang/connectdb.php");
$sizegiay = isset($_GET['sizegiay']) ? intval($_GET['sizegiay']) : 0;
$sizegiay_query = "SELECT * FROM sizegiay";
$sizegiay_result = mysqli_query($conn, $sizegiay_query);
?>
<form method="GET" action="" style="border: none;">
<h3>Size giày</h3>
    <?php while($row = mysqli_fetch_assoc($sizegiay_result)): ?>
        <button type="submit" name="sizegiay" value="<?= $row['masize'] ?>" class="btn btn-outline-primary" style="border: none;<?= ($sizegiay == $row['masize']) ? 'font-weight:bold;background:#d1e7fd;' : '' ?>">
            <?= htmlspecialchars($row['tensize']) ?>
        </button>
    <?php endwhile; ?>
</form>
<?php
// Lấy giá trị $maloaigiay từ tham số GET
$maloaigiay = isset($_GET['maloaigiay']) ? intval($_GET['maloaigiay']) : null;

// Lấy giá trị $sizegiay từ tham số GET
$sizegiay = isset($_GET['sizegiay']) ? intval($_GET['sizegiay']) : null;

// Lấy giá trị $mathuonghieu từ tham số GET
$mathuonghieu = isset($_GET['mathuonghieu']) ? intval($_GET['mathuonghieu']) : null;

include("chucnang/chucnang_xemkhogiay.php");
?>
</body>

</html>