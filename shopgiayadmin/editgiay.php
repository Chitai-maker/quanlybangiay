<?php
session_start();
if (!isset($_SESSION['name']))
  header("location:login.php");
include "sidebar.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Thêm giầy</title>
</head>
<body>

<?php
include_once("chucnang/chucnang_showFK.php");
if(isset($_GET['magiay']))
        $magiay = mysqli_real_escape_string($conn, $_GET['magiay']);
        $query = "SELECT * FROM giay WHERE magiay='$magiay' ";
        $query_run = mysqli_query($conn, $query);

        if(mysqli_num_rows($query_run) > 0)
        {
            $giay = mysqli_fetch_array($query_run);
            ?>
            <form action="chucnang/chucnang_editgiay.php" method="post" enctype="multipart/form-data">
            <button type="submit" name="submit" class="btn btn-primary"> Edit</button>
                
                <input type="number" name="magiay" value="<?=$giay['magiay'];?>" class="form-control" readonly >
                <label>Tên sản phẩm</label>
                <input type="text" name="tengiay" value="<?=$giay['tengiay'];?>" class="form-control">
                <label>Ảnh sản phẩm</label>
                <input class="form-control mt-4" type="file" name="anhminhhoa" id=""value="<?=$giay['anhminhhoa'];?>">
                <label>Giá</label>
                <input type="number" name="giaban" value="<?=$giay['giaban'];?>" class="form-control">
                <label>Đơn vị tính</label>
                <input type="text" name="donvitinh" value="<?=$giay['donvitinh'];?>" class="form-control">
                <label>Mô tả</label>
                <textarea name="mota" class="form-control" style="resize: none; overflow: hidden;" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px';"><?=$giay['mota'];?></textarea>
                <label>Loại</label>
                <select class="form-control mt-4" name="loaigiay">
                    <option value="<?=$giay['maloaigiay'];?>">loai giay</option>
                        <?php while($row = $result_loai->fetch_assoc()): ?>
                    <option value="<?php echo $row['maloaigiay']; ?>"><?php echo $row['tenloaigiay']; ?></option>
                        <?php endwhile; ?>
                </select>
                <label>Màu</label>
                <select class="form-control mt-4" name="maugiay">
                    <option value="<?=$giay['mamaugiay'];?>">-- Chọn màu --</option>
                        <?php while($row = $result_mau->fetch_assoc()): ?>
                    <option value="<?php echo $row['mamaugiay']; ?>"><?php echo $row['tenmaugiay']; ?></option>
                        <?php endwhile; ?>
                </select>
                <label>Thương hiệu</label>
                <select class="form-control mt-4" name="thuonghieu">
                    <option value="<?=$giay['mathuonghieu'];?>">-- Chọn thương hiệu --</option>
                        <?php while($row = $result_thuonghieu->fetch_assoc()): ?>
                <option value="<?php echo $row['mathuonghieu']; ?>"><?php echo $row['tenthuonghieu']; ?></option>
                <?php endwhile; ?>
                
                </form>
        <?php }else {
    echo "<p>No product found with the given ID.</p>";
}?>         
</body>
</html>