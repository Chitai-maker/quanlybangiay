<?php

session_start();
if (!isset($_SESSION['name']))
    header("location:login.php");
  if($_SESSION['quyen'] > 0){
    header("location:dangnhap_quyencaohon.php");
  
  }
include "header.php"; ?>
<!DOCTYPE html>