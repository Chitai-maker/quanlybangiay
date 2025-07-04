<?php
include "header.php";
include "chucnang/connectdb.php";
?>
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sản phẩm hot</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
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

        /* Đổi màu nền và màu mũi tên của nút chuyển banner */
        .carousel-control-prev-icon,
        .carousel-control-prev,
        .carousel-control-next {
            width: 60px; /* Tăng vùng bấm nếu muốn */
            opacity: 1;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(1); /* Màu mũi tên trắng */
        }
    </style>
</head>

<body>
    <?php
    // Lấy banner đang hiển thị
    $banner_query = mysqli_query($conn, "SELECT * FROM banner WHERE trang_thai = 1 ORDER BY ma_banner ASC");
    $banners = [];
    while ($row = mysqli_fetch_assoc($banner_query)) {
        $banners[] = $row;
    }
    if (count($banners) > 0):
    ?>
        <div id="bannerCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($banners as $i => $banner): ?>
                    <div class="carousel-item <?= $i == 0 ? 'active' : '' ?>">
                        <a href="<?= htmlspecialchars($banner['link_banner']) ?>" target="_blank">
                            <img src="anh/<?= htmlspecialchars($banner['anh_banner']) ?>"
                                class="d-block mx-auto"
                                style="max-width:100%;height: 400px;;object-fit:contain;"
                                alt="<?= htmlspecialchars($banner['ten_banner']) ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($banners) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php
    // Display session message if set
    if (isset($_SESSION['message'])) {
        echo "<div class='session-message text-center'>"; // Add a wrapper with a class
        echo "<div class='alert alert-success alert-dismissible fade show d-inline-block' role='alert'>";
        echo $_SESSION['message'];
        echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
        echo "</div>";
        echo "</div>";
        unset($_SESSION['message']); // Clear the message after displaying it
    }
    ?>

    <div class="container" style="margin-top: 50px;">
        <?php
        // Kiểm tra nếu có tham số tìm kiếm
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            echo "<h2 class='text-center mt-4'>Kết quả tìm kiếm cho: <span style='color: red;'>" . htmlspecialchars($_GET['search']) . "</span></h2>";
            // Lấy giá trị tìm kiếm từ tham số GET
            $search = mysqli_real_escape_string($conn, $_GET['search']);
            $query = "SELECT * FROM giay WHERE tengiay LIKE '%$search%' ORDER BY magiay ASC";
        } else { ?>
            <h2 class="text-center mt-4">Sản phẩm hot <img src="anh/fire.gif" alt="HTML tutorial" style="width:42px;height:42px;"> </h2>
            <?php
            // Nếu không có tìm kiếm, hiển thị tất cả sản phẩm trong bảng sanphamhot
            $query = "SELECT g.*, SUM(ct.soluong) AS tongban
    FROM giay g
    INNER JOIN chitietdonhang ct ON g.magiay = ct.ma_giay
    GROUP BY g.magiay
    ORDER BY tongban DESC
    LIMIT 12";
        }

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                // Lấy giá khuyến mãi nếu có
                $magiay = $row['magiay'];
                $query_discount = "SELECT giakhuyenmai FROM sanphamhot WHERE magiay = $magiay";
                $result_discount = mysqli_query($conn, $query_discount);
                $discount = 0;

                if (mysqli_num_rows($result_discount) > 0) {
                    $discount_row = mysqli_fetch_assoc($result_discount);
                    $discount = $discount_row['giakhuyenmai'];
                }

                // Tính giá sau khi giảm
                $original_price = $row["giaban"];
                $final_price = $discount > 0 ? $original_price * (1 - $discount / 100) : $original_price;
            ?>
                <div class="col-md-3" style="float: left;">
                    <div class="product">
                        <a href="sanpham.php?masanpham=<?php echo $row['magiay']; ?>">
                            <img src="../shopgiayadmin/anhgiay/<?php echo $row["anhminhhoa"]; ?>" width="190px" height="200px" style="border-radius: 10px;">
                        </a>
                        <span class="product-name"><?php echo $row["tengiay"]; ?></span>
                        <h6 class="text-danger">
                            <a href="sanpham.php?masanpham=<?php echo $row['magiay']; ?>" style="text-decoration: none; color: #ff4d4d;">
                                <?php
                                if ($discount > 0) {
                                    echo "<del>" . number_format($original_price, 0, ',', '.') . " đ</del> ";
                                }
                                echo number_format($final_price, 0, ',', '.') . " đ";
                                ?>
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

</body>