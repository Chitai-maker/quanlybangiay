<!-- filepath: c:\xampp\htdocs\shopgiay\sanpham.php -->
<?php
include "header.php";
include "chucnang/connectdb.php";
include "chucnang/chucnang_giohang.php";



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
            <!-- ...existing code... -->
        </head>

        <body>
            <?php
        // Display session message if set
        if (isset($_SESSION['message'])) {
            echo "<div class='session-message text-center'>"; // Add a wrapper with a class
            echo "<div class='alert alert-success alert-dismissible fade show d-inline-block' role='alert'>";
            echo $_SESSION['message'];
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            echo "</div>";
            echo "</div>";
            unset($_SESSION['message']); // Clear the message after displaying it
        }
        ?>
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
                        <form method="post" action="sanpham.php?action=add&magiay=<?php echo $row['magiay']; ?>">
                            <div class="form-group">
                                <label for="soluong">Số lượng:</label>
                                <input type="number" name="soluong" id="soluong" class="form-control" value="1" min="1">
                            </div>
                            <input type="hidden" name="1_tengiay" value="<?php echo $row['tengiay']; ?>">
                            <input type="hidden" name="1_giaban" value="<?php echo $final_price; ?>">
                            <button type="submit" name="add" class="btn btn-success">Thêm vào giỏ hàng</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="container mt-5">
                <h3 class="text-center">Sản phẩm liên quan</h3>
                <div class="row">
                    <?php
                    // Truy vấn sản phẩm liên quan dựa trên maloaigiay
                    $related_query = "SELECT * FROM giay WHERE maloaigiay = '" . $row['maloaigiay'] . "' AND magiay != '" . $magiay . "' LIMIT 4";
                    $related_result = mysqli_query($conn, $related_query);

                    if (mysqli_num_rows($related_result) > 0) {
                        while ($related_row = mysqli_fetch_assoc($related_result)) {
                            ?>
                            <div class="col-md-3" style="float: left;">
                                <div class="product">
                                    <a href="sanpham.php?masanpham=<?php echo $related_row['magiay']; ?>">
                                        <img src="../shopgiayadmin/anhgiay/<?php echo $related_row['anhminhhoa']; ?>" width="190px" height="200px" class="img-responsive" style="border-radius: 10px;">
                                    </a>
                                    <h6><?php echo $related_row['tengiay']; ?></h6>
                                    <h6 class="text-danger">
                                        <?php echo number_format($related_row['giaban'], 0, ',', '.'); ?> đ
                                    </h6>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p class='text-center'>Không có sản phẩm liên quan.</p>";
                    }
                    ?>
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