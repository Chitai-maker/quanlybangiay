<?php

session_start();
if (!isset($_SESSION['name']))
    header("location:login.php");
  if($_SESSION['quyen'] > 0){
    header("location:dangnhap_quyencaohon.php");
  
  }   
include "sidebar.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nhân Viên</title>
</head>
<body>
<h1 class="text-center mt-5">Danh Sách Nhân Viên</h1>
<div class="text-center mb-3">
  <button class="btn btn-success" onclick="window.location.href='themnhanvien.php'">Thêm Nhân Viên</button>
</div>
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
  include_once("chucnang/chucnang_xemnhanvien.php");
  ?>
  <script>
function filterNhanVien() {
  const input = document.getElementById("searchNhanVien");
  const filter = input.value.toLowerCase();
  const table = document.querySelector("table");
  const tr = table.getElementsByTagName("tr");

  for (let i = 1; i < tr.length; i++) { // Bỏ dòng tiêu đề
    const td = tr[i].getElementsByTagName("td")[0]; // Cột tên nhân viên
    if (td) {
      const textValue = td.textContent || td.innerText;
      tr[i].style.display = textValue.toLowerCase().includes(filter) ? "" : "none";
    }
  }
}
</script>

</body>
</html>