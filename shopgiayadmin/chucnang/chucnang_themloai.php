<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
if ($_POST["submit"]) {
    $TENLOAIGIAY = $_POST["tenloaigiay"];
            $query = "INSERT INTO loaigiay (tenloaigiay) VALUES ('$TENLOAIGIAY')";
            if(mysqli_query($conn, $query)){
                header("Location: ../themloaigiay.php");
            }
        }
?>