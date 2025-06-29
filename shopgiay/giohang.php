<?php
include "header.php";
include "chucnang/connectdb.php";
include "chucnang/chucnang_giohang.php";


if (!isset($_SESSION['makhachhang']))
    header("location:login.php");

?>
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Giỏ hàng</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <style>


    </style>

</head>

<body>
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
        <div style="clear: both"></div>

        <h3 class="title2" style="text-transform: uppercase;">Giỏ Hàng</h3>
        <div class="table-responsive">
            <table class="table table-bordered" style="font-family: monospace;">
                <tr>
                    <th width="30%">Sản phẩm</th>
                    <th width="13%">Đơn giá</th>
                    <th width="10%">Số lượng</th>
                    <th width="10%">Số tiền</th>
                    <th width="17%">Thao tác</th>
                </tr>
                <?php
                // Kiểm tra nếu giỏ hàng không rỗng
                if (!empty($_SESSION["giohang"])) {
                    $total = 0;
                    foreach ($_SESSION["giohang"] as $key => $value) {
                        $sql = "SELECT * FROM giay WHERE magiay = '" . $value["magiay"] . "'";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_array($result);
                        // Kiểm tra nếu sản phẩm có trong bảng sanphamhot
                        $magiay = $value['magiay'];
                        $query_discount = "SELECT giakhuyenmai FROM sanphamhot WHERE magiay = '$magiay'";
                        $result_discount = mysqli_query($conn, $query_discount);
                        $discount = 0;

                        if (mysqli_num_rows($result_discount) > 0) {
                            $discount_row = mysqli_fetch_assoc($result_discount);
                            $discount = $discount_row['giakhuyenmai'];
                        }

                        // Tính giá sau khi giảm
                        $original_price =  $row["giaban"];
                        $final_price = $discount > 0 ? $original_price * (1 - $discount / 100) : $original_price;

                        // Tính tổng tiền
                        $subtotal = $value["soluong"] * $final_price;
                        $total += $subtotal;
                ?>
                        <tr>
                            <td><a href="sanpham.php?masanpham=<?php echo $value['magiay']; ?>"><?php echo $value["tengiay"]; ?></a></td>
                            <td>
                                <?php
                                if ($discount > 0) {
                                    echo "<del>" . number_format($original_price, 0, ',', '.') . " đ</del> ";
                                }
                                echo number_format($final_price, 0, ',', '.') . " đ";
                                ?>
                            </td>
                            <td>
                                <form method="post" action="chucnang/chucnang_giohang.php" class="d-flex align-items-center">
                                    <input type="hidden" name="magiay" value="<?php echo $value["magiay"]; ?>">
                                    <input type="number" name="soluong" min="1" max="<?php echo $row['soluongtonkho']; ?>" value="<?php echo $value["soluong"]; ?>" class="form-control form-control-sm" style="width:70px;">
                                    <button type="submit" name="capnhat" class="btn btn-sm btn-secondary ml-2">+/-</button>
                                </form>
                            </td>
                            <td><?php echo number_format($subtotal, 0, ',', '.'); ?> đ</td>
                            <td><a href="giohang.php?action=delete&magiay=<?php echo $value["magiay"]; ?>"><span class="text-danger">Xoá</span></a></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="3" align="right"><strong>Tổng cộng:</strong></td>
                        <td><?php echo number_format($total, 0, ',', '.'); ?> đ</td>
                        <td align="right">
                            <form method="post" action="">
                                <button type="submit" name="dat_hang" class="btn btn-primary">Đặt hàng</button>
                            </form>
                        </td>
                    </tr>
                <?php
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Giỏ hàng trống.</td></tr>";
                }
                ?>
                <?php
                include "chucnang/chucnang_dathang.php";
                ?>
            </table>
        </div>
    </div>