<?php
include "header.php";
include "chucnang/connectdb.php";
?>
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shopping Cart</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</head>

<body>
    <!-- lọc màu -->
    <div class="container mt-5">
        <h3 class="text-center">Lọc sản phẩm theo thương hiệu</h3>
        <div class="d-flex justify-content-center mb-4">
            <?php
            // Truy vấn danh sách thương hiệu từ bảng `thuonghieu`
            $query_brands = "SELECT * FROM thuonghieu";
            $result_brands = mysqli_query($conn, $query_brands);

            if (mysqli_num_rows($result_brands) > 0) {
                while ($brand = mysqli_fetch_assoc($result_brands)) {
            ?>
                    <form method="get" action="thuonghieu.php" class="mx-2">
                        <input type="hidden" name="mathuonghieu" value="<?php echo $brand['mathuonghieu']; ?>">
                        <button type="submit" class="btn btn-outline-primary">
                            <?php echo $brand['tenthuonghieu']; ?>
                        </button>
                    </form>
            <?php
                }
            }
            ?>
        </div>
    </div>
<div class="container" style="margin-top: 50px;">
    <?php
    // Kiểm tra nếu có tham số `mathuonghieu` trong URL
    if (isset($_GET['mathuonghieu'])) {
        $mathuonghieu = $_GET['mathuonghieu'];
        $query = "SELECT * FROM giay WHERE mathuonghieu = '$mathuonghieu'";
    } else {
        $query = "SELECT * FROM giay";
    }
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) { ?>
            <div class="col-md-3" style="float: left;">
                        <div class="product">
                            <a href="sanpham.php?masanpham=<?php echo $row['magiay']; ?>">
                                <img src="../shopgiayadmin/anhgiay/<?php echo $row["anhminhhoa"]; ?>" width="190px" height="200px" class="img-responsive" style="border-radius: 10px;">
                            </a>
                            <?php echo $row["tengiay"]; ?>
                            <h6 class="text-danger">
                                <a href="sanpham.php?masanpham=<?php echo $row['magiay']; ?>" style="text-decoration: none; color: #ff4d4d;">                                   
                                    <?php echo number_format($row["giaban"], 0, ',', '.'); ?> đ
                                </a>
                            </h6>
                        </div>
                </div>
        <?php }
    } else {
        echo "<h4 class='text-center'>Không tìm thấy sản phẩm nào.</h4>";
    }
    ?>
</div>