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

<div class="container mt-4">
    <form method="get" action="index.php" class="d-flex justify-content-center">
        <input type="text" name="search" class="form-control w-50" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
    </form>
</div>
  
    <form method="GET" action="" style="border: none;">
    <h3>Loại giày</h3>
        <!-- Nút lọc theo loại giày -->
        <button type="submit" name="maloaigiay" value="1" class="btn btn-primary" style="border: none;">Sneaker</button>
        <button type="submit" name="maloaigiay" value="4" class="btn btn-secondary" style="border: none;">Tất</button>
        <button type="submit" name="maloaigiay" value="3" class="btn btn-success" style="border: none;">Dép</button>
        <button type="submit" name="maloaigiay" value="2" class="btn btn-warning" style="border: none;">Sandal</button>
    </form>


    <form method="GET" action="" style="border: none;">
    <h3>size</h3>
        <!-- Nút lọc theo size giày -->
        <button type="submit" name="sizegiay" value="1" class="btn btn-info" style="border: none;">Size 36</button>
        <button type="submit" name="sizegiay" value="2" class="btn btn-info" style="border: none;">Size 37</button>
        <button type="submit" name="sizegiay" value="3" class="btn btn-info" style="border: none;">Size 38</button>
        <button type="submit" name="sizegiay" value="4" class="btn btn-info" style="border: none;">Size 39</button>
        <button type="submit" name="sizegiay" value="5" class="btn btn-info" style="border: none;">Size 40</button>
    </form>



<?php
// Lấy giá trị $maloaigiay từ tham số GET
$maloaigiay = isset($_GET['maloaigiay']) ? intval($_GET['maloaigiay']) : null;

// Lấy giá trị $sizegiay từ tham số GET
$sizegiay = isset($_GET['sizegiay']) ? intval($_GET['sizegiay']) : null;

include("chucnang/chucnang_xemkhogiay.php");
?>
</body>

</html>