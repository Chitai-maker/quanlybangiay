<?php
session_start();
if (!isset($_SESSION['name']))
    header("location:login.php");
    if($_SESSION['quyen'] > 1){
        header("location:dangnhap_quyencaohon.php");     
    }   
include "header.php";
?>
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
?>
    <div class="container mt-5">
        <form action="chucnang/chucnang_themgiay.php" method="post" enctype="multipart/form-data">
        <h2>Thêm sản phẩm</h2>
            <label>Tên giầy</label>
            <input class="form-control mt-4" type="text" name="tengiay" id="" placeholder="Nhập tên:">
            <label>Đơn vị tính</label>
            <input class="form-control mt-4" type="text" name="donvitinh" id="" placeholder="Nhập tên:">
            <label>Giá</label>
            <input class="form-control mt-4" type="text" name="giaban" id="" placeholder="Nhập giá:(VND)">
            <label>Mô tả</label>
            <textarea name="mota" class="form-control" style="resize: none; overflow: hidden;" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px';"></textarea>
            <label>Ảnh sản phẩm</label>
            <input class="form-control mt-4" type="file" name="anhminhhoa" id="">
            <select class="form-control mt-4" name="loaigiay">
                    <option value="">-- Chọn loại giầy --</option>
                <?php while($row = $result_loai->fetch_assoc()): ?>
                    <option value="<?php echo $row['maloaigiay']; ?>"><?php echo $row['tenloaigiay']; ?></option>
                <?php endwhile; ?>
            </select>

            <select class="form-control mt-4" name="thuonghieu">
                    <option value="">-- Chọn thương hiệu --</option>
                <?php while($row = $result_thuonghieu->fetch_assoc()): ?>
                    <option value="<?php echo $row['mathuonghieu']; ?>"><?php echo $row['tenthuonghieu']; ?></option>
                <?php endwhile; ?>
            </select>

            <select class="form-control mt-4" name="maugiay">
                    <option value="">-- Chọn màu --</option>
                <?php while($row = $result_mau->fetch_assoc()): ?>
                    <option value="<?php echo $row['mamaugiay']; ?>"><?php echo $row['tenmaugiay']; ?></option>
                <?php endwhile; ?>
            </select>
            <select class="form-control mt-4" name="sizegiay">
                    <option value="">-- Chọn size --</option>
                <?php while($row = $result_size->fetch_assoc()): ?>
                    <option value="<?php echo $row['masize']; ?>"><?php echo $row['tensize']; ?></option>
                <?php endwhile; ?>
            </select>
            <input class="btn btn-primary mt-4" type="submit" value="Upload" name="submit">
        </form>
    </div>

</body>
</html>