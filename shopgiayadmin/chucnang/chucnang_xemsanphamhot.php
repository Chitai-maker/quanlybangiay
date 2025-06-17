<div class="container mt-5">
    <h2>Danh sách sản phẩm khuyến mãi</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã sản phẩm</th>
                <th>Tên sản phẩm</th>
                <th>% khuyến mãi</th>
                <th>Chức năng</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT sanphamhot.ma_sanphamyeuthich, giay.magiay, giay.tengiay ,sanphamhot.giakhuyenmai
                      FROM sanphamhot 
                      JOIN giay ON sanphamhot.magiay = giay.magiay
                      order BY giay.tengiay ASC";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                    <td> <?php echo $row['ma_sanphamyeuthich']; ?> </td>
                    <td><a href="sanpham.php?masanpham=<?php echo $row['magiay']; ?>"> <?= $row['tengiay']; ?></a></td>                                       
                    <td><?php echo $row['giakhuyenmai'] ." %" ?></td>
                    <td>
                            <form action='chucnang/chucnang_xoasanphamhot.php' method='post' class='d-inline form-no-border'>
                                <input type='hidden' name='ma_sanphamyeuthich' value='<?php echo $row['ma_sanphamyeuthich'] ?>'>
                                <button type='submit' class='btn btn-danger btn-sm'onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">Xóa</button>
                            </form>
                          </td>
                    
                    </tr><?php
                }
            } else {
                echo "<tr><td colspan='3' class='text-center'>Không có sản phẩm hot</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>