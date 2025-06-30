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
            <!-- ...existing code... -->
        </head>

        <body>
            <?php
        
        
        ?>
            <div class="container mt-5">
                <div class="row">
                    <!-- Hình ảnh sản phẩm -->
                    <div class="col-md-6">
                        <img src="../shopgiayadmin/anhgiay/<?php echo $row['anhminhhoa']; ?>"  alt="<?php echo $row['tengiay']; ?>" style="border-radius: 10px; width: 500px; height: auto;">
                    </div>

                    <!-- Thông tin sản phẩm -->
                    <div class="col-md-6">
                        <h2><?php echo $row['tengiay']; ?></h2>
                        <?php
                        // Lấy số sao trung bình
                        $avg_sql = "SELECT AVG(danhgia) as avg_star, COUNT(*) as total FROM danhgia WHERE magiay = '$magiay'";
                        $avg_result = mysqli_query($conn, $avg_sql);
                        $avg_data = mysqli_fetch_assoc($avg_result);
                        $avg_star = $avg_data['avg_star'] ? round($avg_data['avg_star'], 1) : 0;
                        $total = $avg_data['total'];
                        ?>
                        <p>
                            
                            <?php
                            if ($total > 0) {
                                // Hiển thị sao bằng icon
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= floor($avg_star)) {
                                        echo '<span style="color: gold;">&#9733;</span>'; // sao đầy
                                    } elseif ($i - $avg_star < 1) {
                                        echo '<span style="color: gold;">&#9734;</span>'; // sao rỗng
                                    } else {
                                        echo '<span style="color: #ccc;">&#9734;</span>'; // sao rỗng xám
                                    }
                                }
                                echo " ({$avg_star}/5 từ {$total} đánh giá)";
                            } else {
                                echo "Chưa có đánh giá";
                            }
                            ?>
                        </p>
                        <h4 class="text-danger">
                            <?php 
                            if ($discount > 0) {
                                echo "<del>" . number_format($original_price, 0, ',', '.') . " VND</del> ";
                            }
                            echo number_format($final_price, 0, ',', '.') . " VND"; 
                            ?>
                        </h4>
                        <h5><?php echo $row['soluongtonkho'] ?> sản phẩm có sẵn</h5>
                        <!-- Nút mua ngay hoặc thông báo hết hàng -->
                        <?php if ($row['soluongtonkho'] > 0): ?>
        
        
    <?php else: ?>
        <span class="badge bg-danger">Hết hàng</span>
        <?php
        // Kiểm tra nếu khách đã đăng nhập và đã đánh giá chưa
        $show_danhgia_btn = true;
        if (isset($_SESSION['makhachhang'])) {
            $makhachhang = mysqli_real_escape_string($conn, $_SESSION['makhachhang']);
            $check_sql = "SELECT * FROM danhgia WHERE magiay = '$magiay' AND ma_khachhang = '$makhachhang'";
            $check_result = mysqli_query($conn, $check_sql);
            if (mysqli_num_rows($check_result) > 0) {
                $show_danhgia_btn = false;
            }
        }
        if ($show_danhgia_btn) {
        ?>
            <!-- Nút đánh giá vẫn hiển thị -->
            <a href="danhgia.php?masanpham=<?php echo $row['magiay']; ?>" class="btn btn-primary mt-2">Đánh giá sản phẩm</a>
        <?php
        } else {
            echo "<span class='badge bg-info mt-2'>Bạn đã đánh giá sản phẩm này</span>";
        }
        ?>
    <?php endif; ?>
    <p><?php echo $row['mota']; ?></p>

<!-- Hiển thị bình luận -->
<div class="mt-4">
    <h5>Bình luận của khách hàng</h5>
    <?php
    $bl_query = "SELECT d.*, k.ten_khachhang FROM danhgia d 
                 LEFT JOIN khachhang k ON d.ma_khachhang = k.ma_khachhang 
                 WHERE d.magiay = '$magiay' ORDER BY d.ngaydanhgia DESC";
    $bl_result = mysqli_query($conn, $bl_query);
    if (mysqli_num_rows($bl_result) > 0) {
        while ($bl = mysqli_fetch_assoc($bl_result)) {
            echo '<div class="border rounded p-2 mb-2 bg-light">';
            echo '<strong>' . htmlspecialchars($bl['ten_khachhang']) . '</strong> ';
            // Hiển thị số sao
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $bl['danhgia']) {
                    echo '<span style="color: gold; font-size:16px;">&#9733;</span>';
                } else {
                    echo '<span style="color: #ccc; font-size:16px;">&#9733;</span>';
                }
            }
            echo '<br>';
            echo nl2br(htmlspecialchars($bl['binhluan']));
            echo '</div>';
        }
    } else {
        echo '<div class="text-muted">Chưa có bình luận nào cho sản phẩm này.</div>';
    }
    ?>
</div>
</div>
               
        </html>
<?php
    } else {
        echo "<div class='container mt-5'><h3>Sản phẩm không tồn tại.</h3></div>";
    }
} else {
    echo "<div class='container mt-5'><h3>Không tìm thấy sản phẩm.</h3></div>";
}
?>