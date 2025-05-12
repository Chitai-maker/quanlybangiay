<div class="container mt-5">
    <table>
      <tr>
        <th>Ảnh sản phẩm</th>
        <th>Tên Sản phẩm</th>
        <th>Giá</th>
        <th>Đơn vị tính</th>
        <th>Loại</th>
        <th>Thương hiệu</th>
        <th>Màu</th>
        <th>Size</th>
        <th>Chức năng</th>
      </tr>
      <?php
      include_once("chucnang/connectdb.php");

      // Xây dựng truy vấn SQL
      $query = "SELECT 
        giay.magiay,
        giay.tengiay,
        loaigiay.tenloaigiay AS loaigiay,
        thuonghieu.tenthuonghieu AS thuonghieu,
        maugiay.tenmaugiay AS maugiay,
        sizegiay.tensize AS size,
        giay.donvitinh,
        giay.giaban,
        giay.anhminhhoa,
        giay.mota
        FROM giay
        JOIN loaigiay ON giay.maloaigiay = loaigiay.maloaigiay
        JOIN thuonghieu ON giay.mathuonghieu = thuonghieu.mathuonghieu
        JOIN maugiay ON giay.mamaugiay = maugiay.mamaugiay
        JOIN sizegiay ON giay.masize = sizegiay.masize";

      // Thêm điều kiện lọc theo loại giày
      if (isset($maloaigiay)) {
          $query .= " WHERE giay.maloaigiay = $maloaigiay";
      }

      // Thêm điều kiện lọc theo size giày
      if (isset($sizegiay)) {
          $query .= isset($maloaigiay) ? " AND" : " WHERE";
          $query .= " giay.masize = $sizegiay";
      }

      // Thêm điều kiện tìm kiếm theo tên giày
      if (isset($_GET['search']) && !empty($_GET['search'])) {
          $search = mysqli_real_escape_string($conn, $_GET['search']);
          $query .= (isset($maloaigiay) || isset($sizegiay)) ? " AND" : " WHERE";
          $query .= " giay.tengiay LIKE '%$search%'";
      }

      $result = mysqli_query($conn, $query);

      // Hiển thị sản phẩm
      if (mysqli_num_rows($result) > 0) {
        foreach ($result as $GIAY_SLQ) {
          // Lấy ảnh
          $ANH = $GIAY_SLQ["anhminhhoa"];
          $Url_ANH = "../shopgiayadmin/anhgiay/" . $ANH;
          // Format cột giá
          $GIABAN = number_format($GIAY_SLQ["giaban"], 0, ',', '.'); ?>
          <tr>
            <td><?php echo "<img src='$Url_ANH' class='small-img'>"; ?></td>
            <td><a href="sanpham.php?masanpham=<?php echo $GIAY_SLQ['magiay']; ?>"> <?= $GIAY_SLQ['tengiay']; ?></a></td>
            <td><?php echo "$GIABAN"; ?> đ</td>
            <td><?= $GIAY_SLQ['donvitinh']; ?></td>
            <td><?= $GIAY_SLQ['loaigiay']; ?></td>
            <td><?= $GIAY_SLQ['thuonghieu']; ?></td>
            <td><?= $GIAY_SLQ['maugiay']; ?></td>
            <td><?= $GIAY_SLQ['size']; ?></td>
            <td>
              <div class="button-group">
                <a href="editgiay.php?magiay=<?= $GIAY_SLQ['magiay']; ?>" class="btn btn-success btn-sm">Edit</a>
                <form action="chucnang/chucnang_xoagiay.php" method="POST" class="d-inline form-no-border">
                  <button type="submit" name="xoa_giay" value="<?= $GIAY_SLQ['magiay']; ?>" class="btn btn-danger btn-sm">Xoá</button>
                </form>
              </div>
            </td>
          </tr>
      <?php
        }
      } else {
        echo "<h5>Không tìm thấy dữ liệu</h5>";
      }
      ?>
    </table>
  </div>