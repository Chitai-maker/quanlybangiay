<?php
//tạo kết nối database
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "quanlybangiay";
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
if (!$conn) {
    die("something went wrong");
}
?>