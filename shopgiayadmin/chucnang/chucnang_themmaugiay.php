<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
if ($_POST["submit"]) {
    $TENMAUGIAY = $_POST["tenmaugiay"];
            $query = "INSERT INTO maugiay (tenmaugiay) VALUES ('$TENMAUGIAY')";
            if(mysqli_query($conn, $query)){
                header("Location: ../themmaugiay.php");
            }
        }
?>