<?php
//giao diện Thêm sản phẩm
// Connect to database
include_once("connectdb.php");
$sql_loai = "SELECT maloaigiay, tenloaigiay FROM loaigiay";
$sql_thuonghieu = "SELECT mathuonghieu, tenthuonghieu FROM thuonghieu";
$sql_mau = "SELECT mamaugiay, tenmaugiay FROM maugiay";
$sql_size = "SELECT masize, tensize FROM sizegiay";
$result_loai = $conn->query($sql_loai);
$result_thuonghieu = $conn->query($sql_thuonghieu);   
$result_mau = $conn->query($sql_mau);
$result_size = $conn->query($sql_size);