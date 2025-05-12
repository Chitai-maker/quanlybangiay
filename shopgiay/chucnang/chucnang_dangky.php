<?php
include_once("connectdb.php");
if($_SERVER["REQUEST_METHOD"]==="POST") {
    $TEN_KHACHHANG =  $_POST["tenkhachhang"];
    $EMAIL =  $_POST["email"];
    $SDT =  $_POST["sdt"];
    $DIACHI =  $_POST["diachi"];
    $PASS =  $_POST["psw"];

    //dang ky
    $pass_dangky =  $PASS;//khong luu tru sao database
    //hash passward bang password_hash cua php
    $option= ['cost' => 12];
    $hash_pass=password_hash($pass_dangky,PASSWORD_BCRYPT,$option);

            $query = "INSERT INTO `khachhang`(`ten_khachhang`, `email`,`sdt`,`diachi`, `matkhau`) VALUES ('$TEN_KHACHHANG','$EMAIL','$SDT','$DIACHI','$hash_pass')";
            if(mysqli_query($conn, $query)){
                header("Location: index.php");
            }
        }
