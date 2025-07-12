<div class="container mt-5">
  <form method="GET"  class="text-center  mb-3">
  <label for="sort">Sắp xếp theo:</label>
  <select name="sort" id="sort" class="form-select w-auto d-inline">
    <option value="newest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : '' ?>>Mới nhất</option>
    <option value="oldest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'oldest') ? 'selected' : '' ?>>Cũ nhất</option>
  </select>
  <button type="submit" class="btn btn-primary btn-sm">Lọc</button>
</form>
  <input type="text" id="searchInput" class="form-control mb-3" placeholder="Tìm tên khách hàng..." onkeyup="searchCustomer()">
  
  <table>
    <tr>
      <th>Tên khách hàng</th>
      <th>Email</th>
      <th>SDT</th>
      <th>Địa chỉ</th>
      <th>Điểm thành viên</th>
      <th>Doanh thu</th>
      <th>Thao tác</th>
    </tr>
    <?php
    include_once("connectdb.php");
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
    $orderBy = ($sort === 'oldest') ? 'ASC' : 'DESC';
    $query = "SELECT * FROM `khachhang` ORDER BY ma_khachhang $orderBy;";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
      foreach ($result as $KHACHHANG_SLQ) { ?>
        <tr>
          
          <td><a href="chatbox.php?ma_khachhang=<?= $KHACHHANG_SLQ['ma_khachhang']; ?>"><?= $KHACHHANG_SLQ['ten_khachhang']; ?></a></td>
          <td><?= $KHACHHANG_SLQ['email']; ?></td>
          <td><?= $KHACHHANG_SLQ['sdt']; ?></td>
          <td><?= $KHACHHANG_SLQ['diachi']; ?></td>
          <td><?= $KHACHHANG_SLQ['diemthanhvien']; ?></td>
          <td><?php
          $query_doanhthu = "SELECT SUM(tongtien) AS tongdoanhthu FROM donhang WHERE ma_khachhang = '" . $KHACHHANG_SLQ['ma_khachhang'] . "' AND trangthai = 'Hoàn thành'";
          $result_doanhthu = mysqli_query($conn, $query_doanhthu);
          $row_doanhthu = mysqli_fetch_assoc($result_doanhthu);
          echo isset($row_doanhthu['tongdoanhthu']) ? number_format($row_doanhthu['tongdoanhthu'], 0, ',', '.') . ' VNĐ' : '0 VNĐ';
          ?></td>
          <td>
            <!-- Nút xóa -->
            <div class="button-group">
              <form action="chucnang/chucnang_xoakhachhang.php" method="POST" class="d-inline form-no-border">
                <button type="submit" name="xoa_khachhang" value="<?= $KHACHHANG_SLQ['ma_khachhang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?');">Xoá</button>
              </form>
            <!-- Nút lịch sử mua hàng -->
              <a href="lichsumuahang.php?ma_khachhang=<?= $KHACHHANG_SLQ['ma_khachhang']; ?>" class="btn btn-info btn-sm">Lịch sử mua hàng</a>  
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