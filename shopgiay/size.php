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
<style>
        .product {
            width: 230px;
            height: 340px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            margin: 16px auto;
            padding: 16px 8px 8px 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            transition: box-shadow 0.2s;
        }

        .product img {
            width: 190px;
            height: 200px;
            object-fit: contain;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .product h6,
        .product a,
        .product span {
            text-align: center;
            width: 100%;
            margin: 0;
        }

        .product-name {
            display: block;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
            font-weight: 500;
            margin-bottom: 8px;
            min-height: 24px;
        }
    </style>
<body>
    <!-- lọc màu -->
    <div class="container mt-5">
        <h3 class="text-center">Lọc sản phẩm theo size</h3>
        <div class="d-flex justify-content-center mb-4">
            <?php
            // Truy vấn danh sách thương hiệu từ bảng `sizegiay`
            $query_brands = "SELECT * FROM sizegiay";
            $result_brands = mysqli_query($conn, $query_brands);

            if (mysqli_num_rows($result_brands) > 0) {
                while ($brand = mysqli_fetch_assoc($result_brands)) {
            ?>
                    <form method="get" action="size.php" class="mx-2">
                        <input type="hidden" name="masize" value="<?php echo $brand['masize']; ?>">
                        <button type="submit" class="btn btn-outline-primary">
                            <?php echo $brand['tensize']; ?>
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
    // Kiểm tra nếu có tham số `masize` trong URL
    if (isset($_GET['masize'])) {
        $masize = $_GET['masize'];
        $query = "SELECT * FROM giay WHERE masize = '$masize'";
    } else {
        $query = "SELECT * FROM giay";
    }
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            // Lấy giá khuyến mãi nếu có
            $magiay = $row['magiay'];
            $query_discount = "SELECT giakhuyenmai FROM sanphamhot WHERE magiay = $magiay";
            $result_discount = mysqli_query($conn, $query_discount);
            $discount = 0;

            if (mysqli_num_rows($result_discount) > 0) {
                $discount_row = mysqli_fetch_assoc($result_discount);
                $discount = $discount_row['giakhuyenmai'];
            }

            // Tính giá sau khi giảm
            $original_price = $row["giaban"];
            $final_price = $discount > 0 ? $original_price * (1 - $discount / 100) : $original_price;
    ?>
            <div class="col-md-3" style="float: left;">
                <div class="product">
                    <a href="sanpham.php?masanpham=<?php echo $row['magiay']; ?>">
                        <img src="../shopgiayadmin/anhgiay/<?php echo $row["anhminhhoa"]; ?>" width="190px" height="200px" class="img-responsive" style="border-radius: 10px;">
                    </a>
                    <span class="product-name"><?php echo $row["tengiay"]; ?></span>
                    <h6 class="text-danger">
                        <a href="sanpham.php?masanpham=<?php echo $row['magiay']; ?>" style="text-decoration: none; color: #ff4d4d;">
                            <?php 
                            if ($discount > 0) {
                                echo "<del>" . number_format($original_price, 0, ',', '.') . " đ</del> ";
                            }
                            echo number_format($final_price, 0, ',', '.') . " đ"; 
                            ?>
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