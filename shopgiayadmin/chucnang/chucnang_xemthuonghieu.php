<div class="container mt-5">
    <table>
      <tr>
        <th>Tên thương hiệu</th>
        <th>Chức năng</th> 
      </tr>
      <?php
      include_once("chucnang/connectdb.php");
      $query = "SELECT * FROM thuonghieu ;";
      $result = mysqli_query($conn, $query);
      //bảng
      if (mysqli_num_rows($result) > 0) {
        foreach ($result as $THUONGHIEU_SLQ) {?>
          <tr>
            <td><?= $THUONGHIEU_SLQ['tenthuonghieu']; ?></td>
            <td>
              <div class="button-group">
                <a href="edit_thuonghieu.php?mathuonghieu=<?= $THUONGHIEU_SLQ['mathuonghieu']; ?>" class="btn btn-success btn-sm">Edit</a>
                <form action="chucnang/chucnang_xoathuonghieu.php" method="POST" class="d-inline form-no-border">
                  <button type="submit" name="xoa_thuonghieu" value="<?= $THUONGHIEU_SLQ['mathuonghieu']; ?>" class="btn btn-danger btn-sm">Xoá</button>
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