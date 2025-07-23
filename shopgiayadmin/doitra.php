<?php

session_start();
if (!isset($_SESSION['name']))
    header("location:login.php");
if ($_SESSION['quyen'] > 1) {
    header("location:dangnhap_quyencaohon.php");
}
include "sidebar.php";
include "chucnang/connectdb.php";
$conn->set_charset("utf8mb4");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng yêu cầu đổi trả</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <h1 class="text-center mt-5">Danh Sách Đơn hàng Yêu Cầu Đổi Trả</h1>
    <div class="container mt-5">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã đổi trả</th>
                    
                   
                    
                    <th>Trạng thái</th>
                    <th>Thời gian</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT doitrahang.*, khachhang.ten_khachhang, donhang.trangthai AS trangthai_donhang
                        FROM doitrahang
                        JOIN donhang ON doitrahang.ma_donhang = donhang.ma_donhang
                        JOIN khachhang ON donhang.ma_khachhang = khachhang.ma_khachhang
                        ORDER BY doitrahang.ma_doitrahang DESC";
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['ma_doitrahang']}</td>
                            <td>{$row['trangthai']}</td>
                            <td>{$row['thoigian']}</td>
                            <td>
                                <a href='chitietdoitra.php?ma_doitrahang={$row['ma_doitrahang']}' class='btn btn-info btn-sm'>Xem chi tiết</a>
                                <!-- Thêm nút xử lý đổi trả nếu cần -->
                                
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center text-danger'>Không có đơn đổi trả nào</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>