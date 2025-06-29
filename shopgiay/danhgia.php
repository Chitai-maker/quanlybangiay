<?php
include "header.php";
include "chucnang/connectdb.php";
// Thêm dòng này nếu chưa có

if (!isset($_SESSION['makhachhang'])) {
    // Nếu chưa đăng nhập, chuyển hướng hoặc thông báo
    echo "<div class='container mt-5'><div class='alert alert-warning'>Bạn cần <a href='login.php'>đăng nhập</a> để đánh giá sản phẩm.</div></div>";
    exit;
}

if (isset($_GET['masanpham'])) {
    $magiay = $_GET['masanpham'];
    $makhachhang = mysqli_real_escape_string($conn, $_SESSION['makhachhang']);

    // Kiểm tra khách hàng đã mua sản phẩm này chưa
    $check_mua_sql = "SELECT ctdh.*
                      FROM chitietdonhang ctdh
                      JOIN donhang dh ON ctdh.ma_donhang = dh.ma_donhang
                      WHERE dh.ma_khachhang = '$makhachhang' AND ctdh.ma_giay = '$magiay' AND dh.trangthai != 'Đã huỷ'";
    $check_mua_result = mysqli_query($conn, $check_mua_sql);

    if (mysqli_num_rows($check_mua_result) == 0) {
        echo "<div class='container mt-5'><div class='alert alert-warning'>Bạn chỉ có thể đánh giá sản phẩm khi đã mua sản phẩm này.</div></div>";
        exit;
    }

    // Kiểm tra khách hàng đã đánh giá sản phẩm này chưa
    $check_sql = "SELECT * FROM danhgia WHERE magiay = '$magiay' AND ma_khachhang = '$makhachhang'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<div class='container mt-5'><div class='alert alert-info'>Bạn đã đánh giá sản phẩm này rồi.</div></div>";
    } else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $binhluan = mysqli_real_escape_string($conn, $_POST['binhluan']);
            $sosao = intval($_POST['danhgia']);

            $sql = "INSERT INTO danhgia (magiay, ma_khachhang, binhluan, danhgia, ngaydanhgia) 
                    VALUES ('$magiay', '$makhachhang', '$binhluan', '$sosao', NOW())";
            if (mysqli_query($conn, $sql)) {
                // Chuyển hướng về trang sản phẩm sau khi đánh giá thành công
                header("Location: sanpham.php?masanpham=$magiay&danhgia=success");
                exit;
            } else {
                echo "<div class='container mt-5'><div class='alert alert-danger'>Lỗi: Không thể lưu đánh giá.</div></div>";
            }
        }
?>
    <div class="container mt-5">
        <h3>Đánh giá sản phẩm</h3>
        <form method="post">
            <div class="mb-3">
                <label for="danhgia" class="form-label">Số sao:</label>
                <select class="form-select" id="danhgia" name="danhgia" required>
                    <option value="5">5 sao</option>
                    <option value="4">4 sao</option>
                    <option value="3">3 sao</option>
                    <option value="2">2 sao</option>
                    <option value="1">1 sao</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="binhluan" class="form-label">Nội dung đánh giá:</label>
                <textarea class="form-control" id="binhluan" name="binhluan" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Gửi đánh giá</button>
            <a href="sanpham.php?masanpham=<?php echo $magiay; ?>" class="btn btn-secondary">Quay lại sản phẩm</a>
        </form>
    </div>
<?php
        }
    }
 else {
    echo "<div class='container mt-5'><h3>Không tìm thấy sản phẩm để đánh giá.</h3></div>";
}
?>