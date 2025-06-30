<?php
include "sidebar.php";
include_once("chucnang/connectdb.php");
if (!isset($_SESSION['name']))
  header("location:login.php");
if ($_SESSION['quyen'] > 0) {
    header("location:dangnhap_quyencaohon.php");
}

// Lấy năm từ form hoặc mặc định là năm hiện tại
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Truy vấn thống kê doanh thu từng tháng trong năm (dựa trên tongtien của donhang)
$query = "
SELECT 
    MONTH(ngaydat) AS thang,
    SUM(tongtien) AS doanhthu
FROM donhang
WHERE YEAR(ngaydat) = $year AND trangthai = 'Hoàn thành'
GROUP BY thang
ORDER BY thang ASC
";
$result = mysqli_query($conn, $query);

// Chuẩn bị mảng doanh thu 12 tháng, mặc định 0
$doanhthu_thang = array_fill(1, 12, 0);
while ($row = mysqli_fetch_assoc($result)) {
    $doanhthu_thang[intval($row['thang'])] = $row['doanhthu'];
}

// Tìm tháng có doanh thu cao nhất
$maxMonth = null;
$maxDoanhThu = 0;
foreach ($doanhthu_thang as $thang => $doanhthu) {
    if ($doanhthu > $maxDoanhThu) {
        $maxDoanhThu = $doanhthu;
        $maxMonth = $thang;
    }
}

// Sắp xếp mảng theo doanh thu giảm dần, giữ lại số tháng
$doanhthu_sorted = [];
foreach ($doanhthu_thang as $thang => $doanhthu) {
    $doanhthu_sorted[] = ['thang' => $thang, 'doanhthu' => $doanhthu];
}
usort($doanhthu_sorted, function($a, $b) {
    return $b['doanhthu'] <=> $a['doanhthu'];
});
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Thống kê doanh thu từng tháng năm <?= $year ?></h2>
    <form method="get" class="mb-4 row g-3 justify-content-center">
        <div class="col-auto">
            <label for="year" class="form-label">Chọn năm:</label>
            <input type="number" min="2000" max="<?= date('Y') ?>" id="year" name="year" class="form-control" value="<?= $year ?>">
        </div>
        <div class="col-auto align-self-end">
            <button type="submit" class="btn btn-primary">Xem thống kê</button>
        </div>
    </form>
    <?php if ($maxMonth): ?>
        <div class="alert alert-success text-center">
            <b>Tháng <?= $maxMonth ?></b> có doanh thu cao nhất: <b><?= number_format($maxDoanhThu, 0, ',', '.') ?> VND</b>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            Không có dữ liệu doanh thu cho năm này.
        </div>
    <?php endif; ?>

    <table class="table table-bordered text-center mt-4">
        <thead>
            <tr>
                <th>Tháng</th>
                <th>Doanh thu</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($doanhthu_sorted as $item): ?>
                <tr <?= ($item['thang'] == $maxMonth) ? 'style="background:#d1e7dd;font-weight:bold;"' : '' ?>>
                    <td><?= $item['thang'] ?></td>
                    <td><?= number_format($item['doanhthu'], 0, ',', '.') ?> VND</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>