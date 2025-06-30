<?php
session_start();
if (!isset($_SESSION['name']))
  header("location:login.php");
if($_SESSION['quyen'] > 1){
  header("location:dangnhap_quyencaohon.php");
}
include "sidebar.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đơn hàng</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
  <h1 class="text-center mt-5">Danh Sách Đơn hàng</h1>
  <?php
  // Display session message if set
  if (isset($_SESSION['message'])) {
    echo "<div class='session-message text-center'>";
    echo "<div class='alert alert-success alert-dismissible fade show d-inline-block' role='alert'>";
    echo $_SESSION['message'];
    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
    echo "</div>";
    echo "</div>";
    unset($_SESSION['message']);
  }
  ?>

  <!-- Form lọc theo trạng thái -->
  <div class="container mb-3" style="border:none; box-shadow:none;">
    <form method="get" class="row g-3 justify-content-center" style="border:none; box-shadow:none;">
      <div class="col-auto">
        <label for="trangthai" class="form-label">Lọc theo trạng thái:</label>
        <select name="trangthai" id="trangthai" class="form-control">
          <option value="">Tất cả</option>
          <option value="Chờ xác nhận thanh toán QR" <?= (isset($_GET['trangthai']) && $_GET['trangthai'] == 'Chờ xác nhận thanh toán QR') ? 'selected' : '' ?>>Chờ xác nhận thanh toán QR</option>
          <option value="Chờ xác nhận" <?= (isset($_GET['trangthai']) && $_GET['trangthai'] == 'Chờ xác nhận') ? 'selected' : '' ?>>Chờ xác nhận</option>
          <option value="Đang giao hàng" <?= (isset($_GET['trangthai']) && $_GET['trangthai'] == 'Đang giao hàng') ? 'selected' : '' ?>>Đang giao hàng</option>
          <option value="Hoàn thành" <?= (isset($_GET['trangthai']) && $_GET['trangthai'] == 'Hoàn thành') ? 'selected' : '' ?>>Hoàn thành</option>
          <option value="Đã hủy" <?= (isset($_GET['trangthai']) && $_GET['trangthai'] == 'Đã hủy') ? 'selected' : '' ?>>Đã hủy</option>
        </select>
      </div>
      <div class="col-auto align-self-end">
        <button type="submit" class="btn btn-primary">Lọc</button>
      </div>
    </form>
  </div>

  <?php
  // Truyền biến lọc trạng thái cho file xem đơn hàng
  $trangthai_filter = isset($_GET['trangthai']) ? $_GET['trangthai'] : '';
  include_once("chucnang/chucnang_xemdonhang.php");
  ?>

</body>
</html>