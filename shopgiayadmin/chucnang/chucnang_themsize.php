<?php
require 'connectdb.php';
session_start();
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
if ($_POST["submit"]) {
    $TENSIZE = $_POST["tensize"];
            $query = "INSERT INTO sizegiay (tensize) VALUES ('$TENSIZE')";
            if(mysqli_query($conn, $query)){
                header("Location: ../themsize.php");
            }
        }
?>