<?php
include "sidebar.php";
include_once("chucnang/connectdb.php");
if (!isset($_SESSION['name']))
  header("location:login.php");
if ($_SESSION['quyen'] > 0) {
    header("location:dangnhap_quyencaohon.php");
}

// Xử lý lọc theo ngày
$from = isset($_GET['from']) ? $_GET['from'] : '';
$to = isset($_GET['to']) ? $_GET['to'] : '';

$where = [];
if ($from) $where[] = "dh.ngaydat >= '$from'";
if ($to) $where[] = "dh.ngaydat <= '$to'";
$where[] = "dh.trangthai = 'Hoàn thành'";
$where_sql = count($where) ? "WHERE " . implode(" AND ", $where) : "";

// Lấy doanh thu theo khách hàng (dựa trên tổng tongtien của đơn hàng hoàn thành)
$query = "
SELECT 
    kh.email, 
    SUM(dh.tongtien) AS doanhthu
FROM donhang dh
JOIN khachhang kh ON dh.ma_khachhang = kh.ma_khachhang
$where_sql
GROUP BY kh.email
ORDER BY doanhthu DESC
";
$result = mysqli_query($conn, $query);

$labels = [];
$data = [];
$max = null;
$min = null;
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $labels[] = $row['email'];
        $data[] = $row['doanhthu'];
        if ($max === null || $row['doanhthu'] > $max['doanhthu']) $max = $row;
        if ($min === null || $row['doanhthu'] < $min['doanhthu']) $min = $row;
    }
}
?>

<div class="container mt-4">
    <h2 class="mb-4 text-center"><i class="fa fa-users"></i> Báo Cáo Doanh Thu Theo Khách Hàng</h2>
    <form method="get" class="row g-3 align-items-center mb-3">
        <div class="col-auto">
            <label for="from" class="form-label">Từ ngày:</label>
            <input type="date" id="from" name="from" class="form-control" value="<?= htmlspecialchars($from) ?>">
        </div>
        <div class="col-auto">
            <label for="to" class="form-label">Đến ngày:</label>
            <input type="date" id="to" name="to" class="form-control" value="<?= htmlspecialchars($to) ?>">
        </div>
        <div class="col-auto align-self-end">
            <button type="submit" class="btn btn-primary"><i class="fa fa-chart-bar"></i> Thống kê</button>
        </div>
        <div class="col-auto align-self-end">
            <a href="dashboard.php" class="btn btn-secondary">← Quay lại</a>
        </div>
    </form>

    <?php if ($max): ?>
        <div class="alert alert-success">
            <b>Khách hàng mua nhiều nhất:</b> <?= htmlspecialchars($max['email']) ?> – <?= number_format($max['doanhthu'], 0, ',', '.') ?> VND
        </div>
    <?php endif; ?>
    <?php if ($min && $min['doanhthu'] > 0): ?>
        <div class="alert alert-warning">
            <b>Ít nhất:</b> <?= htmlspecialchars($min['email']) ?> – <?= number_format($min['doanhthu'], 0, ',', '.') ?> VND
        </div>
    <?php endif; ?>

    <canvas id="doanhthuChart" height="120"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('doanhthuChart').getContext('2d');
const doanhthuChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Doanh thu (VND)',
            data: <?= json_encode($data) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            borderRadius: 8,
            maxBarThickness: 40
        }]
    },
    options: {
        indexAxis: 'y',
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN');
                    }
                }
            }
        },
        plugins: {
            legend: { display: true }
        }
    }
});
</script>