<?php
session_start();
if (!isset($_SESSION['name']))
    header("location:login.php");
include "header.php";
include_once("chucnang/connectdb.php");

// Đếm số đơn hàng theo trạng thái
$query = "SELECT COUNT(*) AS total FROM donhang WHERE trangthai = 'Đang xử lý'";
$query2 = "SELECT COUNT(*) AS total FROM donhang WHERE trangthai = 'Đang giao hàng'";
$query3 = "SELECT COUNT(*) AS total FROM donhang WHERE trangthai = 'Hoàn thành'";
$query4 = "SELECT COUNT(*) AS total FROM donhang WHERE trangthai = 'Đã hủy'";
$result = mysqli_query($conn, $query);
$result2 = mysqli_query($conn, $query2);
$result3 = mysqli_query($conn, $query3);
$result4 = mysqli_query($conn, $query4);
$row = mysqli_fetch_assoc($result);
$row2 = mysqli_fetch_assoc($result2);
$row3 = mysqli_fetch_assoc($result3);
$row4 = mysqli_fetch_assoc($result4);
$donhang_chuaduyet = $row['total'];
$donhang_danggiaohang = $row2['total'];
$donhang_hoanthanh = $row3['total'];
$donhang_huy = $row4['total'];
// đếm số khách hàng
$query5 = "SELECT COUNT(*) AS total FROM khachhang";
$result5 = mysqli_query($conn, $query5);
$row5 = mysqli_fetch_assoc($result5);
$khachhang_count = $row5['total'];

?>
<h1>My Dashboard</h1>
<div style="margin: 20px 0;">
    <a href="donhang.php?trangthai=Đang+xử+lý"><h3>Số đơn hàng chưa duyệt: <span style="color: red;"><?= $donhang_chuaduyet ?></span></h3></a>
    <a href="donhang.php?trangthai=Đang+giao+hàng"><h3>Số đơn hàng đang giao hàng: <span style="color: red;"><?= $donhang_danggiaohang ?></span></h3></a>
    <a href="donhang.php?trangthai=Hoàn+thành"><h3>Số đơn hàng đã hoàn thành: <span style="color: red;"><?= $donhang_hoanthanh ?></span></h3></a>
    <a href="donhang.php?trangthai=Đã+hủy"><h3>Số đơn hàng đã hủy: <span style="color: red;"><?= $donhang_huy ?></span></h3></a>
    <a href="khachhang.php"><h3>Số khách hàng: <span style="color: red;"><?= $khachhang_count ?></span></h3></a>
    <a href="thongke.php"><h3>Thống kê sản phẩm bán chạy nhất</h3></a>
</div>
</body>
</html>
