<!-- filepath: c:\xampp\htdocs\shopgiay\sanpham.php -->
<?php
session_start();
// Xử lý thêm vào giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    if (!isset($_SESSION['makhachhang'])) {
        // Chưa đăng nhập, chuyển hướng sang trang đăng nhập
        header("Location: login.php?msg=login_required");
        exit();
    }
    // Đã đăng nhập, xử lý thêm vào giỏ hàng như bình thường
    // ... (code thêm vào giỏ hàng của bạn ở đây)
}
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
                        <img src="../shopgiayadmin/anhgiay/<?php echo $row['anhminhhoa']; ?>" class="img-fluid" alt="<?php echo $row['tengiay']; ?>" style="border-radius: 10px; width: 550px; height: auto;">
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
                            <!-- Form thêm vào giỏ hàng -->
                            <form method="post" action="sanpham.php?action=add&magiay=<?php echo $row['magiay']; ?>">
                                <div class="form-group">
                                    <label for="soluong">Số lượng:</label>
                                    <input type="number" name="soluong" id="soluong" class="form-control-sm w-2" value="1" min="1" max="<?php echo $row['soluongtonkho']; ?>">
                                </div>
                                <input type="hidden" name="1_tengiay" value="<?php echo $row['tengiay']; ?>">
                                <input type="hidden" name="1_giaban" value="<?php echo $final_price; ?>">
                                <button type="submit" name="add" class="btn btn-success">Thêm vào giỏ hàng</button>
                            </form>
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
                                <!-- Nút đánh giá -->
                                <a href="danhgia.php?masanpham=<?php echo $row['magiay']; ?>" class="btn btn-primary mt-2">Đánh giá sản phẩm</a>
                            <?php
                            } else {
                                echo "<span class='badge bg-info mt-2'>Bạn đã đánh giá sản phẩm này</span>";
                            }
                            ?>
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

                                    // Truy vấn phản hồi của người bán cho đánh giá này
                                    $madanhgia = $bl['ma_danhgia'];
                                    $reply_sql = "SELECT b.*, n.ten_nhanvien FROM binhluandanhgia b
                                                LEFT JOIN nhanvien n ON b.ma_nhanvien = n.ma_nhanvien
                                                WHERE b.ma_danhgia = '$madanhgia'
                                                ORDER BY b.thoigian ASC";
                                    $reply_result = mysqli_query($conn, $reply_sql);
                                    if (mysqli_num_rows($reply_result) > 0) {
                                        while ($reply = mysqli_fetch_assoc($reply_result)) {
                                            echo '<div class="mt-2 p-2" style="background:#f8f9fa;border-radius:5px;">';
                                            echo '<strong>Phản hồi của người bán</strong><br>';
                                            echo '<span style="color:#333;">' . nl2br(htmlspecialchars($reply['noidung'])) . '</span>';
                                            echo '<br><small class="text-muted">(' . htmlspecialchars($reply['ten_nhanvien'] ?? 'Admin') . ' - ' . $reply['thoigian'] . ')</small>';
                                            echo '</div>';
                                        }
                                    }

                                    echo '</div>';
                                }
                            } else {
                                echo '<div class="text-muted">Chưa có bình luận nào cho sản phẩm này.</div>';
                            }
                            ?>
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