<?php
session_start();
if (!isset($_SESSION['name']))
    header("location:login.php");
if ($_SESSION['quyen'] > 1) {
    header("location:dangnhap_quyencaohon.php");
}
include "header.php";
include "chucnang/connectdb.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Thêm sản phẩm hot</title>
</head>
<body>
    <div class="container mt-5">
        <form action="chucnang/chucnang_themsanphamhot.php" method="post">
            <h2>Thêm sản phẩm hot</h2>
            <div class="form-group mt-4">
                <label for="magiay">Chọn sản phẩm:</label>
                <select class="form-control" name="magiay" id="magiay" required>
                    <option value="">-- Chọn sản phẩm --</option>
                    <?php
                    // Lấy danh sách giày từ bảng giay
                    $query = "SELECT magiay, tengiay FROM giay";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['magiay'] . "'>" . htmlspecialchars($row['tengiay']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>Không có sản phẩm</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group mt-4">
                <label for="giakhuyenmai">Giá khuyến mãi (%):</label>
                <input type="number" class="form-control" name="giakhuyenmai" id="giakhuyenmai" min="1" max="100" required>
            </div>
            <input class="btn btn-primary mt-4" type="submit" value="Thêm sản phẩm hot" name="submit">
        </form>
    </div>
    <?php include "chucnang/chucnang_xemsanphamhot.php"; ?>
</body>
</html>