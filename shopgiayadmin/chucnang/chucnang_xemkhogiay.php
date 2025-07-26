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

      // Xây dựng mảng điều kiện
      $whereArr = [];
      if (isset($maloaigiay) && $maloaigiay) {
          $whereArr[] = "giay.maloaigiay = $maloaigiay";
      }
      if (isset($sizegiay) && $sizegiay) {
          $whereArr[] = "giay.masize = $sizegiay";
      }
      if (isset($mathuonghieu) && $mathuonghieu) {
          $whereArr[] = "giay.mathuonghieu = $mathuonghieu";
      }
      if (isset($_GET['search']) && !empty($_GET['search'])) {
          $search = mysqli_real_escape_string($conn, $_GET['search']);
          $whereArr[] = "giay.tengiay LIKE '%$search%'";
      }
      if (!empty($whereArr)) {
          $query .= " WHERE " . implode(" AND ", $whereArr);
      }
      if (isset($_GET['sort_price']) && $_GET['sort_price'] == 'asc') {
          $query .= " ORDER BY giay.giaban ASC";
      } elseif (isset($_GET['sort_price']) && $_GET['sort_price'] == 'desc') {
          $query .= " ORDER BY giay.giaban DESC";
      } else {
          $query .= " ORDER BY giay.magiay DESC";
      }

      $result = mysqli_query($conn, $query);
      if ($result === false) {
          // Hiển thị lỗi SQL để debug
          echo "<div class='alert alert-danger'>Lỗi truy vấn: " . mysqli_error($conn) . "</div>";
      } else {
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
                      <button type="submit" name="xoa_giay" value="<?= $GIAY_SLQ['magiay']; ?>" class="btn btn-danger btn-sm"onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xoá</button>
                    </form>
                    <a href="../shopgiayadmin/khuyenmai.php?magiay=<?= $GIAY_SLQ['magiay']; ?>" class="btn btn-warning btn-sm ms-1">
                        Thêm khuyến mãi
                    </a>
                  </div>
                </td>
              </tr>
          <?php
            }
          } else {
            echo "<div class='alert alert-warning'>Không có dữ liệu.</div>";
          }
      }
      ?>
    </table>
  </div>