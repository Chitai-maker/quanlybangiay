<?php 
include 'connectdb.php';
// Chỉ gọi session_start() nếu chưa có session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION["giohang"])) {
    $_SESSION["giohang"] = array();
}

if(isset($_POST["add"])){
    if(isset($_SESSION["giohang"])){
        $item_array_id = array_column($_SESSION["giohang"],"magiay");
        if(!in_array($_GET["magiay"],$item_array_id)){
            $count = count($_SESSION["giohang"]);
            $item_array = array(
                'magiay' => $_GET["magiay"],
                'tengiay' => $_POST["1_tengiay"],
                'giaban' => $_POST["1_giaban"],
                'soluong' => $_POST["soluong"],
            );
            if (!isset($_SESSION['makhachhang' ])){
                unset($_SESSION["giohang"]);
                header("location:login.php");
            }
            else{
                $_SESSION["giohang"][$count] = $item_array;
                $_SESSION['message'] = "Thêm sản phẩm vào giỏ hàng thành công!";
                // Chuyển hướng về trang sản phẩm
                $masanpham = $_GET["magiay"];
                echo "<script>window.location='sanpham.php?masanpham=$masanpham';</script>";
            }
        }else{
            if (!isset($_SESSION['makhachhang' ])){
                unset($_SESSION["giohang"]);
                header("location:login.php");
            }
            else{
                $_SESSION['message'] = "Sản phẩm đã có trong giỏ hàng!";
                // Chuyển hướng về trang sản phẩm
                $masanpham = $_GET["magiay"];
                echo "<script>window.location='sanpham.php?masanpham=$masanpham';</script>";
            }
        }
    }else{
        $item_array = array(
            'magiay' => $_GET["magiay"],
            'tengiay' => $_POST["1_tengiay"],
            'giaban' => $_POST["1_giaban"],
            'soluong' => $_POST["soluong"],
        );
        $_SESSION["giohang"][0] = $item_array;
        $_SESSION['message'] = "Thêm sản phẩm vào giỏ hàng thành công!";
        // Chuyển hướng về trang sản phẩm
        $masanpham = $_GET["magiay"];
        echo "<script>window.location='sanpham.php?masanpham=$masanpham';</script>";
    }
}

// Xử lý cập nhật số lượng trong giỏ hàng
if (isset($_POST['capnhat']) && isset($_POST['magiay']) && isset($_POST['soluong'])) {
    $magiay = $_POST['magiay'];
    $soluong = max(1, intval($_POST['soluong']));
    foreach ($_SESSION["giohang"] as $key => $value) {
        if ($value["magiay"] == $magiay) {
            $_SESSION["giohang"][$key]["soluong"] = $soluong;
            $_SESSION['message'] = "Cập nhật số lượng thành công!";
            break;
        }
    }
    echo '<script>window.location="../giohang.php";</script>';
    exit;
}

if(isset($_GET["action"])){
    if($_GET["action"] == "delete"){
        foreach($_SESSION["giohang"] as $keys => $value){
            if($value["magiay"] == $_GET["magiay"]){
                unset($_SESSION["giohang"][$keys]);
                $_SESSION['message'] = "Xoá sản phẩm thành công";
                echo '<script>window.location="../giohang.php"</script>';
            }
        }
    }
}