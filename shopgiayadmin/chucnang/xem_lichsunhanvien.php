
<?php
include "connectdb.php";


// Lấy danh sách nhân viên
$nhanvien_sql = "SELECT ma_nhanvien, ten_nhanvien FROM nhanvien";
$nhanvien_rs = $conn->query($nhanvien_sql);

// Xử lý lọc
$filter = isset($_GET['ma_nhanvien']) ? intval($_GET['ma_nhanvien']) : 0;
$where = $filter ? "WHERE l.ma_nhanvien = $filter" : "";

$sql = "SELECT l.*, n.ten_nhanvien FROM lichsunhanvien l 
        JOIN nhanvien n ON l.ma_nhanvien = n.ma_nhanvien $where ORDER BY l.thoigian DESC";
$rs = $conn->query($sql);
?>

<h2>Lịch sử nhân viên</h2>
<form method="get">
    <label for="ma_nhanvien">Lọc theo nhân viên:</label>
    <select name="ma_nhanvien" id="ma_nhanvien">
        <option value="0">-- Tất cả --</option>
        <?php while($nv = $nhanvien_rs->fetch_assoc()): ?>
            <option value="<?= $nv['ma_nhanvien'] ?>" <?= $filter == $nv['ma_nhanvien'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($nv['ten_nhanvien']) ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button type="submit">Lọc</button>
</form>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>STT</th>
        <th>Nhân viên</th>
        <th>Nội dung</th>
        <th>Thời gian</th>
    </tr>
    <?php $i = 1; while($row = $rs->fetch_assoc()): ?>
    <tr>
        <td><?= $i++ ?></td>
        <td><?= htmlspecialchars($row['ten_nhanvien']) ?></td>
        <td><?= htmlspecialchars($row['noidung']) ?></td>
        <td><?= $row['thoigian'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php
$conn->close();
?>