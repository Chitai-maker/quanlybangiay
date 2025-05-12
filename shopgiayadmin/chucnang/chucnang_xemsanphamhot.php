<div class="container mt-5">
    <h2>Danh sách sản phẩm hot</h2>
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
            $query = "SELECT sanphamhot.ma_sanphamyeuthich, giay.tengiay ,sanphamhot.giakhuyenmai
                      FROM sanphamhot 
                      JOIN giay ON sanphamhot.magiay = giay.magiay";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['ma_sanphamyeuthich'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['tengiay']) . "</td>";
                    echo "<td>" .htmlspecialchars($row['giakhuyenmai']) . "</td>";
                    echo "<td>
                            <form action='chucnang/chucnang_xoasanphamhot.php' method='post' class='d-inline form-no-border'>
                                <input type='hidden' name='ma_sanphamyeuthich' value='" . $row['ma_sanphamyeuthich'] . "'>
                                <button type='submit' class='btn btn-danger btn-sm'>Xóa</button>
                            </form>
                          </td>";
                    
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3' class='text-center'>Không có sản phẩm hot</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>