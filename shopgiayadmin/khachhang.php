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
  <title>Khách hàng</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
  <h1 class="text-center mt-5">Danh Sách Khách Hàng</h1>

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
  include_once("chucnang/chucnang_xemkhachhang.php");
  ?>
  <script>
function searchCustomer() {
  let input = document.getElementById("searchInput");
  let filter = input.value.toLowerCase();
  let table = document.querySelector("table");
  let tr = table.getElementsByTagName("tr");

  for (let i = 1; i < tr.length; i++) {
    let td = tr[i].getElementsByTagName("td")[0]; // Tên khách hàng nằm ở cột đầu
    if (td) {
      let textValue = td.textContent || td.innerText;
      tr[i].style.display = textValue.toLowerCase().includes(filter) ? "" : "none";
    }
  }
}
</script>
</body>

</html>