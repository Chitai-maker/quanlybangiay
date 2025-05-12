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
    <!--thanh tìm kiếm -->
<div class="container mt-4">
    <form method="get" action="index.php" class="d-flex justify-content-center">
        <input type="text" name="search" class="form-control w-50" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
    </form>
</div>
<h2 class="text-center mt-4">Sản Phẩm Giảm Giá<img src="anh/fire.gif" alt="HTML tutorial" style="width:42px;height:42px;"> </h2>
<div class="container" style="margin-top: 50px;">
    <?php
    // Kiểm tra nếu có tham số tìm kiếm
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = mysqli_real_escape_string($conn, $_GET['search']);
        $query = "SELECT * FROM giay WHERE tengiay LIKE '%$search%' ORDER BY magiay ASC";
    } else {
        $query = "SELECT giay.* 
          FROM giay 
          INNER JOIN sanphamhot ON giay.magiay = sanphamhot.magiay 
          ORDER BY giay.magiay ASC";
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
                <?php echo $row["tengiay"]; ?>
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
        <?php
    }
} else {
    echo "<h4 class='text-center'>Không tìm thấy sản phẩm nào.</h4>";
}
    ?>
</div>
</body>