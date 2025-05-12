<div class="container mt-5">
    <table>
      <tr>
        <th>Tên size</th> 
        <th>Chức năng</th> 
      </tr>
      <?php
      include_once("chucnang/connectdb.php");
      $query = "SELECT * FROM sizegiay ;";
      $result = mysqli_query($conn, $query);
      //bảng
      if (mysqli_num_rows($result) > 0) {
        foreach ($result as $SIZE_SLQ) {?>
          <tr>
            <td><?= $SIZE_SLQ['tensize']; ?></td>
            <td>
              <div class="button-group">
                <a href="edit_size.php?masize=<?= $SIZE_SLQ['masize']; ?>" class="btn btn-success btn-sm">Edit</a>
                <form action="chucnang/chucnang_xoasize.php" method="POST" class="d-inline form-no-border">
                  <button type="submit" name="xoa_size" value="<?= $SIZE_SLQ['masize']; ?>" class="btn btn-danger btn-sm">Xoá</button>
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