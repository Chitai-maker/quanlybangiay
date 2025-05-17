<?php
include_once("connectdb.php");
$error = ""; // Thêm biến lỗi
if($_SERVER["REQUEST_METHOD"]==="POST") {
    $TEN_KHACHHANG =  $_POST["tenkhachhang"];
    $EMAIL =  $_POST["email"];
    $SDT =  $_POST["sdt"];
    $DIACHI =  $_POST["diachi"];
    $PASS =  $_POST["psw"];

    // Kiểm tra trùng email
    $check_email = "SELECT * FROM khachhang WHERE email = '$EMAIL'";
    $result = mysqli_query($conn, $check_email);
    if(mysqli_num_rows($result) > 0) {
        $error = "Email đã tồn tại. Vui lòng chọn email khác!";
    } else {
        $option= ['cost' => 12];
        $hash_pass=password_hash($PASS,PASSWORD_BCRYPT,$option);
        $query = "INSERT INTO `khachhang`(`ten_khachhang`, `email`,`sdt`,`diachi`, `matkhau`) VALUES ('$TEN_KHACHHANG','$EMAIL','$SDT','$DIACHI','$hash_pass')";
        if(mysqli_query($conn, $query)){
            header("Location: index.php");
            exit();
        }
    }
}
?>
