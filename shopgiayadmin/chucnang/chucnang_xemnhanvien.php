<div class="container mt-5">
  <input type="text" id="searchNhanVien" class="form-control mb-3" placeholder="Tìm tên nhân viên..." onkeyup="filterNhanVien()">
  <table>
    <tr>
      <th>Tên Nhân Viên</th>
      
      
      <th>Quyền</th>
      <th>Ngân hàng</th>
      <th>Lương</th>
      <th>Chi tiết</th>
      <th></th>
    </tr>
    <?php
    include_once("connectdb.php");
    $query = "SELECT * FROM `nhanvien`;";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
      foreach ($result as $NHANVIEN_SLQ) { ?>
        <tr>
          <td><?= $NHANVIEN_SLQ['ten_nhanvien']; ?></td>
          
          <td><?= $NHANVIEN_SLQ['quyen'] == 0 ? "Admin" : ($NHANVIEN_SLQ['quyen'] == 1 ? "Nhân viên kho" : "Nhân viên bán hàng"); ?></td>
          <td>
            <?php
            // Kiểm tra xem nhân viên đã có thông tin ngân hàng chưa
            $query_check_bank = "SELECT 1 FROM thongtinnganhang WHERE ma_nhanvien = " . $NHANVIEN_SLQ['ma_nhanvien'] . " LIMIT 1;";
            $result_check_bank = mysqli_query($conn, $query_check_bank);
            $hasBankInfo = mysqli_num_rows($result_check_bank) > 0;
            if ($hasBankInfo) { ?> 
            <!-- nút sửa ngân hàng -->
             <form action="suathongtinnganhang.php" method="GET" class="d-inline form-no-border">
                <input type="hidden" name="ma_nhanvien" value="<?= $NHANVIEN_SLQ['ma_nhanvien']; ?>">
                <input type="hidden" name="ten_nhanvien" value="<?= htmlspecialchars($NHANVIEN_SLQ['ten_nhanvien']); ?>">
                <button type="submit" class="btn btn-warning btn-sm">Sửa ngân hàng</button>
                    </form>
             <?php } else { ?>
              <!-- nút thêm ngân hàng -->
              <form action="themnganhang.php" method="GET" class="d-inline form-no-border">
                <input type="hidden" name="ma_nhanvien" value="<?= $NHANVIEN_SLQ['ma_nhanvien']; ?>">
                <input type="hidden" name="ten_nhanvien" value="<?= htmlspecialchars($NHANVIEN_SLQ['ten_nhanvien']); ?>">
                <button type="submit" class="btn btn-primary btn-sm">Thêm ngân hàng</button>
              </form> <?php
            }
            ?>
          </td>
          <td>
            <!-- nút thanh toán lương -->
            <!-- ẩn nút thanh toán lương -->
            <div class="button-group">
              <?php
              $query_check = "SELECT 1
              FROM lichsuthanhtoanluong
              WHERE ma_nhanvien =" . $NHANVIEN_SLQ['ma_nhanvien'] . " 
              AND MONTH(ngaythanhtoan) = MONTH(CURRENT_DATE())
              AND YEAR(ngaythanhtoan) = YEAR(CURRENT_DATE())
              LIMIT 1;";
              $result_check = mysqli_query($conn, $query_check);
              $disabled = mysqli_num_rows($result_check) > 0 ? "disabled" : "";
              if ($disabled) { ?>
                <button class="btn btn-secondary btn-sm" disabled>Đã thanh toán</button>
              <?php } else { ?>
                <form action="thanhtoanluong.php" method="POST" class="d-inline form-no-border">
                  <input type="hidden" name="ma_nhanvien" value="<?= $NHANVIEN_SLQ['ma_nhanvien']; ?>">
                  <input type="hidden" name="ten_nhanvien" value="<?= htmlspecialchars($NHANVIEN_SLQ['ten_nhanvien']); ?>">
                  <input type="hidden" name="tongtien" value="<?= $NHANVIEN_SLQ['luong']; ?>">
                  <button type="submit" name="thanhtoanluong" class="btn btn-success btn-sm">Thanh toán lương</button>
                </form>
              <?php } ?>
            
            </div>
          </td>
         
          <td>
            
            <!-- nút xem chi tiết -->
            <form action="chitietnhanvien.php" method="POST" class="d-inline form-no-border">
              <input type="hidden" name="nhanvien" value="<?= $NHANVIEN_SLQ['ma_nhanvien']; ?>">
              <button type="submit" class="btn btn-info btn-sm">Xem chi tiết</button>
            </form>
              
          </td>
           <td>
            <!-- nút Xoá -->
            <div class="button-group">
              <form action="chucnang/chucnang_xoanhanvien.php" method="POST" class="d-inline form-no-border">
                <button type="submit" name="xoa_nhanvien" value="<?= $NHANVIEN_SLQ['ma_nhanvien']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này này?');">Xoá</button>
              </form>
            </div>
          </td>
        </tr>
    <?php
      }
    } else {
      echo "<h5> không tìm thấy dử liệu</h5>";
    }
    ?>
  </table>
</div>