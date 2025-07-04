<?php
include "../shopgiay/chucnang/connectdb.php";


// Lấy danh sách banner
$result = mysqli_query($conn, "SELECT * FROM banner ORDER BY ma_banner DESC");
?>

<div class="container mt-4">
    <h2 class="mb-3" style="font-weight:700;">Danh sách banner</h2>
    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>Tên banner</th>
                <th>Ảnh banner</th>
                <th>Link</th>
                <th>Trạng thái</th>
                <th>Chức năng</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['ten_banner']) ?></td>
                    <td>
                        <img src="../shopgiay/anh/<?= htmlspecialchars($row['anh_banner']) ?>" alt="" style="max-width:120px;max-height:60px;">
                    </td>
                    <td>
                        <a href="<?= htmlspecialchars($row['link_banner']) ?>" target="_blank"><?= htmlspecialchars($row['link_banner']) ?></a>
                    </td>
                    <td>
                        <?= $row['trang_thai'] ? '<span class="badge bg-success">Hiển thị</span>' : '<span class="badge bg-secondary">Ẩn</span>' ?>
                    </td>
                    <td>
                        <a href="banner.php?edit=<?= $row['ma_banner'] ?>" class="btn btn-success btn-sm">Edit</a>
                        <a href="?delete=<?= $row['ma_banner'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa banner này?')">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>