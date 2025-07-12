<?php
include_once("connectdb.php");
if($_SERVER["REQUEST_METHOD"]==="POST") {
    $TEN_NHANVIEN =  $_POST["tennhanvien"];
    $EMAIL =  $_POST["email"];
    $PASS =  $_POST["psw"];
    $SDT =  $_POST["sdt"];
    $DIACHI =  $_POST["diachi"];
    $NGAYSINH =  $_POST["ngaysinh"];
    $GIOITINH =  $_POST["gioitinh"];
    $LUONG =  $_POST["luong"];
    $QUYEN =  $_POST["quyen"];
    //kiem tra email da ton tai chua
    $query = "SELECT * FROM `nhanvien` WHERE email='$EMAIL'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        echo "<script>alert('Email đã tồn tại');</script>";
        echo "<script>window.location.href='signup.php';</script>";
        exit();
    }
    //kiem tra sdt da ton tai chua
    $query = "SELECT * FROM `nhanvien` WHERE sdt='$SDT'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        echo "<script>alert('Số điện thoại đã tồn tại');</script>";
        echo "<script>window.location.href='signup.php';</script>";
        exit();
    }
    //kiem tra dia chi da ton tai chua
    $query = "SELECT * FROM `nhanvien` WHERE diachi='$DIACHI'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        echo "<script>alert('Địa chỉ đã tồn tại');</script>";
        echo "<script>window.location.href='signup.php';</script>";
        exit();
    }
    //dang ky
    $pass_dangky =  $PASS;//khong luu tru sao database
    //hash passward bang password_hash cua php
    $option= ['cost' => 12];
    $hash_pass=password_hash($pass_dangky,PASSWORD_BCRYPT,$option);
    //insert du lieu vao database
    $query = "INSERT INTO `nhanvien`(`ten_nhanvien`, `email`, `sdt`, `diachi`, `ngaysinh`, `gioitinh`, `luong`, `quyen`, `hash`) VALUES ('$TEN_NHANVIEN','$EMAIL','$SDT','$DIACHI','$NGAYSINH','$GIOITINH','$LUONG','$QUYEN','$hash_pass')";
            //$query = "INSERT INTO `nhanvien`(`ten_nhanvien`, `email`, `hash`) VALUES ('$TEN_NHANVIEN','$EMAIL','$hash_pass')";
            if(mysqli_query($conn, $query)){
                
                // viết vào bảng lịch sử nhân viên
                session_start();
                $ma_nhanvien = $_SESSION['ma_nhanvien'];
                $noidung = "Thêm nhân viên mới: " . $TEN_NHANVIEN;
                $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
                $stmt_lichsu = $conn->prepare($sql_lichsu);
                $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
                $stmt_lichsu->execute();
                header("Location: nhanvien.php");
            }
        }
