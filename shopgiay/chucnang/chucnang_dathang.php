<?php
                ///nut dat hang
                if (!empty($_SESSION["giohang"])) {
                    $total = 0;
                    foreach ($_SESSION["giohang"] as $key => $value) {
                        $total += $value["soluong"] * $value["giaban"];
                    }
                ?>
                    
                    
                    <!-- filepath: c:\xampp\htdocs\shopgiay\giohang.php -->
                    <?php
                    // đạt hàng
                    if (isset($_POST['dat_hang'])) {
                        $makhachhang = $_SESSION['makhachhang'];
                        $ngaydat = date("Y-m-d");
                        $trangthai = "Đang xử lý";

                        // Thêm đơn hàng vào bảng `donhang`
                        $query_donhang = "INSERT INTO donhang (ma_khachhang, ngaydat, trangthai) VALUES ('$makhachhang', '$ngaydat', '$trangthai')";
                        mysqli_query($conn, $query_donhang);

                        // Lấy mã đơn hàng vừa thêm
                        $ma_donhang = mysqli_insert_id($conn);

                        // Thêm chi tiết đơn hàng vào bảng `chitietdonhang`
                        foreach ($_SESSION["giohang"] as $key => $value) {
                            $magiay = $value["magiay"];
                            $soluong = $value["soluong"];
                            $query_chitiet = "INSERT INTO chitietdonhang (ma_donhang, ma_giay, soluong) VALUES ('$ma_donhang', '$magiay', '$soluong')";
                            mysqli_query($conn, $query_chitiet);
                        }

                        // Xóa giỏ hàng sau khi đặt hàng
                        unset($_SESSION["giohang"]);

                        // Thông báo và chuyển hướng
                        $_SESSION['message'] = "Đặt hàng thành công!";
                        echo '<script>window.location="giohang.php"</script>';
                        
                    }
                    ?>

                <?php
                }
                ?>