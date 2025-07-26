<div class="container mt-5">
    <h2>Danh sách sản phẩm khuyến mãi</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã sản phẩm</th>
                <th>Tên sản phẩm</th>
                <th>Giá gốc</th>
                <th>Giá khuyến mãi</th>
                <th>% khuyến mãi</th>
                <th>Chức năng</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT sanphamhot.ma_sanphamyeuthich, giay.magiay, giay.tengiay, giay.giaban, sanphamhot.giakhuyenmai
                      FROM sanphamhot 
                      JOIN giay ON sanphamhot.magiay = giay.magiay
                      ORDER BY giay.tengiay ASC";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $giaban = $row['giaban'];
                    $phantram = $row['giakhuyenmai'];
                    $giakm = $giaban * (100 - $phantram) / 100;
            ?>
                    <tr>
                        <td><?php echo $row['ma_sanphamyeuthich']; ?></td>
                        <td><a href="sanpham.php?masanpham=<?php echo $row['magiay']; ?>"> <?= $row['tengiay']; ?></a></td>
                        <td><?php echo number_format($giaban, 0, ',', '.') . ' VNĐ'; ?></td>
                        <td><?php echo number_format($giakm, 0, ',', '.') . ' VNĐ'; ?></td>
                        <td><?php echo $phantram . " %"; ?></td>
                        <td>
                            <form action='chucnang/chucnang_xoasanphamhot.php' method='post' class='d-inline form-no-border'>
                                <input type='hidden' name='ma_sanphamyeuthich' value='<?php echo $row['ma_sanphamyeuthich'] ?>'>
                                <button type='submit' class='btn btn-danger btn-sm' onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">Xóa</button>
                            </form>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>Không có sản phẩm hot</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>