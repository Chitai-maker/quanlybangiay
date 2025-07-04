<div class="container mt-5">
    <table>
      <tr>
        <th>Tên khách hàng</th>
        <th>Email</th>
        <th>SDT</th>
        <th>Địa chỉ</th>
        <th>Điểm thành viên</th>
        <th>Thao tác</th>
      </tr>
      <?php
      include_once("connectdb.php");
      $query = "SELECT * FROM `khachhang`;";
      $result = mysqli_query($conn, $query);
      if (mysqli_num_rows($result) > 0) {
        foreach ($result as $KHACHHANG_SLQ) {?>
          <tr>
            <td><?= $KHACHHANG_SLQ['ten_khachhang']; ?></td>
            <td><?= $KHACHHANG_SLQ['email']; ?></td>
            <td><?= $KHACHHANG_SLQ['sdt']; ?></td>
            <td><?= $KHACHHANG_SLQ['diachi']; ?></td>
            <td><?= $KHACHHANG_SLQ['diemthanhvien']; ?></td>
            <td>
              <div class="button-group">
                <form action="chucnang/chucnang_xoakhachhang.php" method="POST" class="d-inline form-no-border">
                <button type="submit" name="xoa_khachhang" value="<?= $KHACHHANG_SLQ['ma_khachhang']; ?>" class="btn btn-danger btn-sm"onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?');">Xoá</button>

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