<div class="container mt-5">
    <table>
      <tr>
        <th>Tên Nhân Viên</th>
        <th>Email</th>
        <th>SDT</th>
        <th>Địa chỉ</th>
        <th>Ngày sinh</th>
        <th>Giới tính</th>
        <th>Lương</th>
        <th>Quyền</th>
        <th>Thao tác</th>
      </tr>
      <?php
      include_once("connectdb.php");
      $query = "SELECT * FROM `nhanvien`;";
      $result = mysqli_query($conn, $query);
      if (mysqli_num_rows($result) > 0) {
        foreach ($result as $NHANVIEN_SLQ) {?>
          <tr>
            <td><?= $NHANVIEN_SLQ['ten_nhanvien']; ?></td>
            <td><?= $NHANVIEN_SLQ['email']; ?></td>
            <td><?= $NHANVIEN_SLQ['sdt']; ?></td>
            <td><?= $NHANVIEN_SLQ['diachi']; ?></td>
            <td><?= $NHANVIEN_SLQ['ngaysinh']; ?></td>
            <td><?= $NHANVIEN_SLQ['gioitinh']; ?></td>
            <td><?= $NHANVIEN_SLQ['luong']; ?></td>
            <td><?= $NHANVIEN_SLQ['quyen'] == 0 ? "Admin" : ($NHANVIEN_SLQ['quyen'] == 1 ? "Nhân viên kho" : "Nhân viên bán hàng"); ?></td>
            <td>
              <div class="button-group">
                <form action="chucnang/chucnang_xoanhanvien.php" method="POST" class="d-inline form-no-border">
                <button type="submit" name="xoa_nhanvien" value="<?= $NHANVIEN_SLQ['ma_nhanvien']; ?>" class="btn btn-danger btn-sm"onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này này?');">Xoá</button>
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