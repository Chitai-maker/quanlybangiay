<?php
session_start();
if (!isset($_SESSION['name']))
  header("location:login.php");
include "sidebar.php"; ?>
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


<div class="d-flex gap-3">
    <!-- Nút lọc theo thương hiệu -->
<?php
include_once("chucnang/connectdb.php");
$mathuonghieu = isset($_GET['mathuonghieu']) ? intval($_GET['mathuonghieu']) : 0;
$thuonghieu_query = "SELECT * FROM thuonghieu";
$thuonghieu_result = mysqli_query($conn, $thuonghieu_query);
?>
<form method="GET" action="" style="border: none; max-width:300px;">
    <h3>Thương hiệu</h3>
    <?php foreach($_GET as $key => $value): ?>
        <?php if($key != 'mathuonghieu'): ?>
            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
        <?php endif; ?>
    <?php endforeach; ?>
    <select name="mathuonghieu" class="form-select mb-2" onchange="this.form.submit()">
        <option value="0">Tất cả thương hiệu</option>
        <?php while($row = mysqli_fetch_assoc($thuonghieu_result)): ?>
            <option value="<?= $row['mathuonghieu'] ?>" <?= ($mathuonghieu == $row['mathuonghieu']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['tenthuonghieu']) ?>
            </option>
        <?php endwhile; ?>
    </select>
</form>
<!-- Nút lọc theo loại giày -->
 <?php
include_once("chucnang/connectdb.php");
$maloaigiay = isset($_GET['maloaigiay']) ? intval($_GET['maloaigiay']) : 0;
$loaigiay_query = "SELECT * FROM loaigiay";
$loaigiay_result = mysqli_query($conn, $loaigiay_query);
?>
<form method="GET" action="" style="border: none; max-width:300px;">
    <h3>Loại giày</h3>
    <?php foreach($_GET as $key => $value): ?>
        <?php if($key != 'maloaigiay'): ?>
            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
        <?php endif; ?>
    <?php endforeach; ?>
    <select name="maloaigiay" class="form-select mb-2" onchange="this.form.submit()">
        <option value="0">Tất cả loại giày</option>
        <?php while($row = mysqli_fetch_assoc($loaigiay_result)): ?>
            <option value="<?= $row['maloaigiay'] ?>" <?= ($maloaigiay == $row['maloaigiay']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['tenloaigiay']) ?>
            </option>
        <?php endwhile; ?>
    </select>
</form>

<!-- Nút lọc theo size giày -->
<?php
include_once("chucnang/connectdb.php");
$sizegiay = isset($_GET['sizegiay']) ? intval($_GET['sizegiay']) : 0;
$sizegiay_query = "SELECT * FROM sizegiay";
$sizegiay_result = mysqli_query($conn, $sizegiay_query);
?>
<form method="GET" action="" style="border: none; max-width:300px;">
    <h3>Size giày</h3>
    <!-- Giữ các tham số lọc khác -->
    <?php foreach($_GET as $key => $value): ?>
        <?php if($key != 'sizegiay'): ?>
            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
        <?php endif; ?>
    <?php endforeach; ?>
    <select name="sizegiay" class="form-select mb-2" onchange="this.form.submit()">
        <option value="0">Tất cả size</option>
        <?php while($row = mysqli_fetch_assoc($sizegiay_result)): ?>
            <option value="<?= $row['masize'] ?>" <?= ($sizegiay == $row['masize']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['tensize']) ?>
            </option>
        <?php endwhile; ?>
    </select>
</form>
</div>

<?php
// Lấy giá trị $maloaigiay từ tham số GET
$maloaigiay = isset($_GET['maloaigiay']) ? intval($_GET['maloaigiay']) : null;

// Lấy giá trị $sizegiay từ tham số GET
$sizegiay = isset($_GET['sizegiay']) ? intval($_GET['sizegiay']) : null;

// Lấy giá trị $mathuonghieu từ tham số GET
$mathuonghieu = isset($_GET['mathuonghieu']) ? intval($_GET['mathuonghieu']) : null;
?>
<div class="container mt-4 d-flex justify-content-center align-items-center" style="border:none; box-shadow:none;">
    <a href="themgiay.php"><img src="anh/add.webp" alt="them" style="width:50px;height:50px;"></a>
    <form method="get" action="index.php" class="d-flex flex-grow-1 justify-content-center"style="border:none; box-shadow:none;">
        <input type="text" name="search" class="form-control w-50" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
    </form>
    <form method="GET" action="" style="border: none; max-width:300px;">
    <h3>Lọc theo giá</h3>
    <?php foreach($_GET as $key => $value): ?>
        <?php if($key != 'sort_price'): ?>
            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
        <?php endif; ?>
    <?php endforeach; ?>
    <select name="sort_price" class="form-select mb-2" onchange="this.form.submit()">
        <option value="">-- Sắp xếp giá --</option>
        <option value="asc" <?= (isset($_GET['sort_price']) && $_GET['sort_price']=='asc') ? 'selected' : '' ?>>Giá tăng dần</option>
        <option value="desc" <?= (isset($_GET['sort_price']) && $_GET['sort_price']=='desc') ? 'selected' : '' ?>>Giá giảm dần</option>
    </select>
</form>
</div>
<?php
include("chucnang/chucnang_xemkhogiay.php");
?>
<!-- Nút lọc theo giá -->



</body>

</html>