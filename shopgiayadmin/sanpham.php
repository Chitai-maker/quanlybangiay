<!-- filepath: c:\xampp\htdocs\shopgiay\sanpham.php -->
<?php
include "sidebar.php";
include "chucnang/connectdb.php";


// Kiểm tra xem `masanpham` có được truyền qua URL không
if (isset($_GET['masanpham'])) {
    $magiay = $_GET['masanpham'];

    // Truy vấn thông tin sản phẩm từ cơ sở dữ liệu
    $query = "SELECT * FROM giay WHERE magiay = '$magiay'";
    $result = mysqli_query($conn, $query);

    // Kiểm tra nếu sản phẩm tồn tại
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Truy vấn giá khuyến mãi từ bảng sanphamhot
        $query_discount = "SELECT giakhuyenmai FROM sanphamhot WHERE magiay = '$magiay'";
        $result_discount = mysqli_query($conn, $query_discount);
        $discount = 0;

        if (mysqli_num_rows($result_discount) > 0) {
            $discount_row = mysqli_fetch_assoc($result_discount);
            $discount = $discount_row['giakhuyenmai'];
        }

        // Tính giá sau khi giảm
        $original_price = $row['giaban'];
        $final_price = $discount > 0 ? $original_price * (1 - $discount / 100) : $original_price;
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Chi Tiết Sản Phẩm</title>
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        </head>

        <body>
            <div class="container mt-5">
                <div class="row">
                    <!-- Hình ảnh sản phẩm -->
                    <div class="col-md-6">
                        <img src="../shopgiayadmin/anhgiay/<?php echo $row['anhminhhoa']; ?>" class="img-fluid" alt="<?php echo $row['tengiay']; ?>" style="border-radius: 10px;">
                    </div>

                    <!-- Thông tin sản phẩm -->
                    <div class="col-md-6">
                        <h2><?php echo $row['tengiay']; ?></h2>
                        <h4 class="text-danger">
                            <?php 
                            if ($discount > 0) {
                                echo "<del>" . number_format($original_price, 0, ',', '.') . " VND</del> ";
                            }
                            echo number_format($final_price, 0, ',', '.') . " VND"; 
                            ?>
                        </h4>
                        <p><?php echo $row['mota']; ?></p>

                        <!-- Form thêm vào giỏ hàng -->
                        
                    </div>
                </div>
            </div>
        </body>

        </html>
        <?php
    } else {
        echo "<div class='container mt-5'><h3>Sản phẩm không tồn tại.</h3></div>";
    }
} else {
    echo "<div class='container mt-5'><h3>Không tìm thấy sản phẩm.</h3></div>";
}
?>
