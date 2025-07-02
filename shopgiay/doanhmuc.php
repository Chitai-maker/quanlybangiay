<?php
include "header.php";
include "chucnang/connectdb.php";

// Lấy danh sách thương hiệu
$thuonghieu_query = mysqli_query($conn, "SELECT * FROM thuonghieu ORDER BY tenthuonghieu ASC");
// Lấy danh sách size
$size_query = mysqli_query($conn, "SELECT * FROM sizegiay ORDER BY tensize ASC");
// Lấy danh sách màu
$mau_query = mysqli_query($conn, "SELECT * FROM maugiay ORDER BY tenmaugiay ASC");
// Lấy danh sách loại giày
$loaigiay_query = mysqli_query($conn, "SELECT * FROM loaigiay ORDER BY tenloaigiay ASC");

// Lọc theo thương hiệu nếu có
$mathuonghieu = isset($_GET['mathuonghieu']) ? intval($_GET['mathuonghieu']) : 0;
// Lọc theo size nếu có
$masize = isset($_GET['masize']) ? intval($_GET['masize']) : 0;
// Lọc theo màu nếu có
$mamaugiay = isset($_GET['mamaugiay']) ? intval($_GET['mamaugiay']) : 0;
// Lọc theo loại nếu có
$maloaigiay = isset($_GET['maloaigiay']) ? intval($_GET['maloaigiay']) : 0;

$whereArr = [];
if ($mathuonghieu) $whereArr[] = "mathuonghieu = $mathuonghieu";
if ($masize) $whereArr[] = "masize = $masize";
if ($mamaugiay) $whereArr[] = "mamaugiay = $mamaugiay";
if ($maloaigiay) $whereArr[] = "maloaigiay = $maloaigiay";
$where = $whereArr ? "WHERE " . implode(" AND ", $whereArr) : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tất cả sản phẩm</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product {
            width: 230px;
            height: 340px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            margin: 16px auto;
            padding: 16px 8px 8px 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            transition: box-shadow 0.2s;
        }
        .product img {
            width: 190px;
            height: 200px;
            object-fit: contain;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .product h6,
        .product a,
        .product span {
            text-align: center;
            width: 100%;
            margin: 0;
        }
        .product-name {
            display: block;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
            font-weight: 500;
            margin-bottom: 8px;
            min-height: 24px;
        }
        .brand-list, .size-list, .color-list {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            padding: 18px 12px;
            margin-top: 24px;
        }
        .brand-list a, .size-list a, .color-list a {
            display: block;
            color: #1976d2;
            font-weight: 500;
            padding: 6px 0;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.15s;
        }
        .brand-list a.active, .brand-list a:hover,
        .size-list a.active, .size-list a:hover,
        .color-list a.active, .color-list a:hover {
            background: #e3f0ff;
            color: #0d47a1;
        }
    </style>
</head>
<body>
<div class="container" style="margin-top: 50px;">
    <div class="row">
        <!-- Bảng lọc thương hiệu, size, màu bên trái -->
        <div class="col-md-2">
            <div class="brand-list">
                <div class="mb-2" style="font-weight:bold;">Thương hiệu</div>
                <a href="doanhmuc.php<?= ($masize||$mamaugiay||$maloaigiay) ? '?' . http_build_query(array_filter(['masize'=>$masize,'mamaugiay'=>$mamaugiay,'maloaigiay'=>$maloaigiay])) : '' ?>" class="<?= $mathuonghieu==0 ? 'active' : '' ?>">Tất cả</a>
                <?php while($th = mysqli_fetch_assoc($thuonghieu_query)): ?>
                    <a href="doanhmuc.php?mathuonghieu=<?= $th['mathuonghieu'] ?><?= $masize ? '&masize='.$masize : '' ?><?= $mamaugiay ? '&mamaugiay='.$mamaugiay : '' ?><?= $maloaigiay ? '&maloaigiay='.$maloaigiay : '' ?>" class="<?= $mathuonghieu==$th['mathuonghieu'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($th['tenthuonghieu']) ?>
                    </a>
                <?php endwhile; ?>
            </div>
            <div class="size-list mt-4">
                <div class="mb-2" style="font-weight:bold;">Size</div>
                <a href="doanhmuc.php<?= ($mathuonghieu||$mamaugiay||$maloaigiay) ? '?' . http_build_query(array_filter(['mathuonghieu'=>$mathuonghieu,'mamaugiay'=>$mamaugiay,'maloaigiay'=>$maloaigiay])) : '' ?>" class="<?= $masize==0 ? 'active' : '' ?>">Tất cả</a>
                <?php while($sz = mysqli_fetch_assoc($size_query)): ?>
                    <a href="doanhmuc.php?masize=<?= $sz['masize'] ?><?= $mathuonghieu ? '&mathuonghieu='.$mathuonghieu : '' ?><?= $mamaugiay ? '&mamaugiay='.$mamaugiay : '' ?><?= $maloaigiay ? '&maloaigiay='.$maloaigiay : '' ?>" class="<?= $masize==$sz['masize'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($sz['tensize']) ?>
                    </a>
                <?php endwhile; ?>
            </div>
            <div class="color-list mt-4">
                <div class="mb-2" style="font-weight:bold;">Màu sắc</div>
                <a href="doanhmuc.php<?= ($mathuonghieu||$masize||$maloaigiay) ? '?' . http_build_query(array_filter(['mathuonghieu'=>$mathuonghieu,'masize'=>$masize,'maloaigiay'=>$maloaigiay])) : '' ?>" class="<?= $mamaugiay==0 ? 'active' : '' ?>">Tất cả</a>
                <?php while($mau = mysqli_fetch_assoc($mau_query)): ?>
                    <a href="doanhmuc.php?mamaugiay=<?= $mau['mamaugiay'] ?><?= $mathuonghieu ? '&mathuonghieu='.$mathuonghieu : '' ?><?= $masize ? '&masize='.$masize : '' ?><?= $maloaigiay ? '&maloaigiay='.$maloaigiay : '' ?>" class="<?= $mamaugiay==$mau['mamaugiay'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($mau['tenmaugiay']) ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
        <!-- Danh sách sản phẩm -->
        <div class="col-md-10">
            <!-- Thanh lọc loại giày -->
            <div class="mb-3 d-flex align-items-center" style="gap:10px;">
                <span style="font-weight:bold;">Loại:</span>
                <a href="doanhmuc.php<?= http_build_query(array_filter([
                    'mathuonghieu'=>$mathuonghieu,
                    'masize'=>$masize,
                    'mamaugiay'=>$mamaugiay
                ])) ? '?' . http_build_query(array_filter([
                    'mathuonghieu'=>$mathuonghieu,
                    'masize'=>$masize,
                    'mamaugiay'=>$mamaugiay
                ])) : '' ?>" class="btn btn-sm <?= $maloaigiay==0 ? 'btn-primary' : 'btn-outline-primary' ?>">Tất cả</a>
                <?php while($loai = mysqli_fetch_assoc($loaigiay_query)): ?>
                    <a href="doanhmuc.php?maloaigiay=<?= $loai['maloaigiay'] ?><?= $mathuonghieu ? '&mathuonghieu='.$mathuonghieu : '' ?><?= $masize ? '&masize='.$masize : '' ?><?= $mamaugiay ? '&mamaugiay='.$mamaugiay : '' ?>"
                       class="btn btn-sm <?= $maloaigiay==$loai['maloaigiay'] ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <?= htmlspecialchars($loai['tenloaigiay']) ?>
                    </a>
                <?php endwhile; ?>
            </div>
            <h2 class="text-center mt-4">Tất cả sản phẩm</h2>
            <div class="row">
            <?php
            $query = "SELECT * FROM giay $where ORDER BY magiay ASC";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $magiay = $row['magiay'];
                    $original_price = $row["giaban"];
                    ?>
                    <div class="col-md-3">
                        <div class="product">
                            <a href="sanpham.php?masanpham=<?php echo $row['magiay']; ?>">
                                <img src="../shopgiayadmin/anhgiay/<?php echo $row["anhminhhoa"]; ?>" width="190px" height="200px" class="img-responsive" style="border-radius: 10px;">
                            </a>
                            <span class="product-name"><?php echo $row["tengiay"]; ?></span>
                            <h6 class="text-danger">
                                <a href="sanpham.php?masanpham=<?php echo $row['magiay']; ?>" style="text-decoration: none; color: #ff4d4d;">
                                    <?php echo number_format($original_price, 0, ',', '.') . " đ"; ?>
                                </a>
                            </h6>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<h4 class='text-center'>Không tìm thấy sản phẩm nào.</h4>";
            }
            ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>