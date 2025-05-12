<div class="container mt-3">
    <table>
      <tr>
        <th>Tên Loại</th> 
        <th>Chức năng</th> 
      </tr>
      <?php
      include_once("chucnang/connectdb.php");
      $query = "SELECT * FROM loaigiay ;";
      $result = mysqli_query($conn, $query);
      //bảng
      if (mysqli_num_rows($result) > 0) {
        foreach ($result as $LOAI_SLQ) {?>
          <tr>
            <td><?= $LOAI_SLQ['tenloaigiay']; ?></td>
            <td>
              <div class="button-group">
                <a href="edit_loai.php?maloaigiay=<?= $LOAI_SLQ['maloaigiay']; ?>" class="btn btn-success btn-sm">Edit</a>
                <form action="chucnang/chucnang_xoaloai.php" method="POST" class="d-inline form-no-border">
                  <button type="submit" name="xoa_loai" value="<?= $LOAI_SLQ['maloaigiay']; ?>" class="btn btn-danger btn-sm">Xoá</button>
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