<!-- filepath: c:\xampp\htdocs\shopgiay\thongtin.php -->
<?php
session_start();
include "sidebar.php";
include "chucnang/connectdb.php";



// Lấy thông tin nhân viên từ cơ sở dữ liệu
$manhanvien = $_POST['nhanvien'] ;
$query = "SELECT * FROM nhanvien WHERE ma_nhanvien = '$manhanvien'";
$result = mysqli_query($conn, $query);

// Kiểm tra nếu tìm thấy thông tin nhân viên
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "<script>alert('Không tìm thấy thông tin nhân viên!'); window.location.href='index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin nhân viên</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="text-center mt-4">
        <div class="item">
            <h1 class="text-center">Thông tin nhân viên : #<?php echo $row['ma_nhanvien'] ?> </h1>
            <h3><strong>Họ tên:</strong> <?php echo $row['ten_nhanvien']; ?></h3>
            <h3><strong>Email:</strong> <?php echo $row['email']; ?></h3>
            <h3><strong>Số điện thoại:</strong> <?php echo $row['sdt']; ?></h3>
            <h3><strong>Địa chỉ:</strong> <?php echo $row['diachi']; ?></h3>
            <h3><strong>Ngày sinh:</strong> <?php echo $row['ngaysinh']; ?></h3>
            <h3><strong>Giới tính:</strong> <?php echo $row['gioitinh']; ?></h3>
            <h3><strong>Lương:</strong> <?php echo number_format($row['luong'], 0, ',', '.') . " đ"; ?></h3>
            <h3><strong>Quyền:</strong> <?php echo $row['quyen'] == 0 ? "Admin" : ($row['quyen'] == 1 ? "Nhân viên kho" : "Nhân viên bán hàng"); ?></h3> 
            
        </div>
        <div class="mt-4">
            <h2>Thông tin ngân hàng</h2>
            <?php
            // Lấy thông tin ngân hàng từ cơ sở dữ liệu
            $query_bank = "SELECT * FROM thongtinnganhang WHERE ma_nhanvien = '$manhanvien'";
            $result_bank = mysqli_query($conn, $query_bank);
            // lấy tên ngân hàng từ API VietQR
            $ch = curl_init("https://api.vietqr.io/v2/banks");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($response, true);
            $banks = [];
            if (isset($data['data']) && is_array($data['data'])) {
                $banks = $data['data'];
            }

            if (mysqli_num_rows($result_bank) > 0) {
                $bank_info = mysqli_fetch_assoc($result_bank);

                // Tìm tên ngân hàng theo mã
                $ten_nganhang = $bank_info['ma_nganhang'];
                foreach ($banks as $bank) {
                    if ($bank['bin'] == $bank_info['ma_nganhang']) {
                        $ten_nganhang = $bank['shortName'] . " - " . $bank['name'];
                        break;
                    }
                }

                echo "<p><strong>Tên chủ tài khoản:</strong> " . htmlspecialchars($bank_info['ten_chutaikhoan']) . "</p>";
                echo "<p><strong>Số tài khoản:</strong> " . htmlspecialchars($bank_info['so_taikhoan']) . "</p>";
                echo "<p><strong>Ngân hàng:</strong> " . htmlspecialchars($ten_nganhang) . "</p>";
            } else {
                echo "<p>Chưa có thông tin ngân hàng.</p>";
            }
            ?>
            <div class="text-center mt-4">
                <a href="nhanvien.php" class="btn btn-primary">Quay lại trang chủ</a>
                <a href="suathongtin.php?ma_nhanvien=<?= $row['ma_nhanvien'] ?>" class="btn btn-secondary">Sửa thông tin</a>
            </div>
    </div>
</body>
</html>