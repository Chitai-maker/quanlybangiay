<?php
session_start();
if (!isset($_SESSION['name']))
  header("location:login.php");
  if($_SESSION['quyen'] > 1){
    header("location:dangnhap_quyencaohon.php");
  
  }   
include "header.php"; ?>
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
    echo "<div class='session-message text-center'>"; // Add a wrapper with a class
    echo "<div class='alert alert-success alert-dismissible fade show d-inline-block' role='alert'>";
    echo $_SESSION['message'];
    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
    echo "</div>";
    echo "</div>";
    unset($_SESSION['message']); // Clear the message after displaying it
  }
  include_once("chucnang/chucnang_xemdonhang.php");
  ?>

</body>

</html>