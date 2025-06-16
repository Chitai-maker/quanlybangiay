<?php
// filepath: c:\xampp\htdocs\quanlybangiay\shopgiayadmin\thongke.php
include "header.php";
include_once("chucnang/connectdb.php");
if($_SESSION['quyen'] > 0){
    header("location:dangnhap_quyencaohon.php");     
} 

// Xử lý lọc theo thời gian
$from = isset($_GET['from']) ? $_GET['from'] : '';
$to = isset($_GET['to']) ? $_GET['to'] : '';

// Lọc theo loại giày
$maloaigiay = isset($_GET['maloaigiay']) ? intval($_GET['maloaigiay']) : 0;

// Lọc theo thương hiệu
$mathuonghieu = isset($_GET['mathuonghieu']) ? intval($_GET['mathuonghieu']) : 0;

// Lấy danh sách loại giày
$loaigiay_result = mysqli_query($conn, "SELECT * FROM loaigiay");

// Lấy danh sách thương hiệu
$thuonghieu_result = mysqli_query($conn, "SELECT * FROM thuonghieu");

// Truy vấn thống kê sản phẩm bán chạy theo thời gian, loại và thương hiệu
$where = [];
if ($from && $to) {
    $where[] = "dh.ngaydat BETWEEN '$from' AND '$to'";
} elseif ($from) {
    $where[] = "dh.ngaydat >= '$from'";
} elseif ($to) {
    $where[] = "dh.ngaydat <= '$to'";
}
if ($maloaigiay > 0) {
    $where[] = "g.maloaigiay = $maloaigiay";
}
if ($mathuonghieu > 0) {
    $where[] = "g.mathuonghieu = $mathuonghieu";
}
$where_sql = '';
if (count($where) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

$query = "
SELECT 
    g.tengiay,
    SUM(ct.soluong) AS tong_soluong,
    g.anhminhhoa
FROM chitietdonhang ct
JOIN donhang dh ON ct.ma_donhang = dh.ma_donhang
JOIN giay g ON ct.ma_giay = g.magiay
$where_sql
GROUP BY ct.ma_giay
ORDER BY tong_soluong DESC
LIMIT 10
";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Thống kê sản phẩm bán chạy nhất</h2>
    <form method="get" class="mb-4 row g-3 justify-content-center">
        <div class="col-auto">
            <label for="from" class="form-label">Từ ngày:</label>
            <input type="date" id="from" name="from" class="form-control" value="<?= htmlspecialchars($from) ?>">
        </div>
        <div class="col-auto">
            <label for="to" class="form-label">Đến ngày:</label>
            <input type="date" id="to" name="to" class="form-control" value="<?= htmlspecialchars($to) ?>">
        </div>
        <div class="col-auto">
            <label for="maloaigiay" class="form-label">Loại giày:</label>
            <select name="maloaigiay" id="maloaigiay" class="form-control">
                <option value="0">Tất cả</option>
                <?php while($row = mysqli_fetch_assoc($loaigiay_result)): ?>
                    <option value="<?= $row['maloaigiay'] ?>" <?= ($maloaigiay == $row['maloaigiay']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['tenloaigiay']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-auto">
            <label for="mathuonghieu" class="form-label">Thương hiệu:</label>
            <select name="mathuonghieu" id="mathuonghieu" class="form-control">
                <option value="0">Tất cả</option>
                <?php while($row = mysqli_fetch_assoc($thuonghieu_result)): ?>
                    <option value="<?= $row['mathuonghieu'] ?>" <?= ($mathuonghieu == $row['mathuonghieu']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['tenthuonghieu']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-auto align-self-end">
            <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
    </form>
        

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ảnh sản phẩm</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng bán</th>
                <th>Tổng doanh thu</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php $stt = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $stt++; ?></td>
                        <td>
                            <img src="../shopgiayadmin/anhgiay/<?= htmlspecialchars($row['anhminhhoa']) ?>" width="80" height="80" style="object-fit:cover;border-radius:8px;">
                        </td>
                        <td><?= htmlspecialchars($row['tengiay']) ?></td>
                        <td><?= $row['tong_soluong'] ?></td>
                        <td>
                            <?php
                            // Tính tổng doanh thu cho sản phẩm
                            $tengiay = mysqli_real_escape_string($conn, $row['tengiay']);
                            $giay_query = "SELECT giaban FROM giay WHERE tengiay = '$tengiay' LIMIT 1";
                            $giay_result = mysqli_query($conn, $giay_query);
                            $giaban = 0;
                            if ($giay_result && $giay_row = mysqli_fetch_assoc($giay_result)) {
                                $giaban = $giay_row['giaban'];
                            }
                            $tong_doanhthu = $giaban * $row['tong_soluong'];
                            echo number_format($tong_doanhthu, 0, ',', '.') . " đ";
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Không có dữ liệu thống kê.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>