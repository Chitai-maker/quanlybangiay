<div class="container mt-5">
    <table>
      <tr>
        <th>Tên màu</th> 
        <th>Chức năng</th> 
      </tr>
      <?php
      include_once("chucnang/connectdb.php");
      $query = "SELECT * FROM maugiay ;";
      $result = mysqli_query($conn, $query);
      //bảng
      if (mysqli_num_rows($result) > 0) {
        foreach ($result as $MAU_SLQ) {?>
          <tr>
            <td><?= $MAU_SLQ['tenmaugiay']; ?></td>
            <td>
              <div class="button-group">
                <a href="edit_mau.php?mamaugiay=<?= $MAU_SLQ['mamaugiay']; ?>" class="btn btn-success btn-sm">Edit</a>
                <form action="chucnang/chucnang_xoamau.php" method="POST" class="d-inline form-no-border">
                  <button type="submit" name="xoa_mau" value="<?= $MAU_SLQ['mamaugiay']; ?>" class="btn btn-danger btn-sm"onclick="return confirm('Bạn có chắc chắn muốn xóa màu này?');">Xoá</button>
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