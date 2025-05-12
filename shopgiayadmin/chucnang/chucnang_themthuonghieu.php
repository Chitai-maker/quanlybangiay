<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
if ($_POST["submit"]) {
    $TENTHUONGHIEU = $_POST["tenthuonghieu"];
            $query = "INSERT INTO thuonghieu (tenthuonghieu) VALUES ('$TENTHUONGHIEU')";
            if(mysqli_query($conn, $query)){
                header("Location: ../themthuonghieu.php");
            }
        }
?>