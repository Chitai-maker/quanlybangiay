<?php
session_start();
if (!isset($_SESSION['tenkhachhang']))
    header("location:login.php");
include "header.php";
?>
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
} ?>
<h1>Đơn hàng của bạn</h1>
<?php
include_once "chucnang/chucnang_xemdonhang.php" ?>
</body>

</html>