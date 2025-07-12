<?php
session_start();
$email_err = $pwd_err = $email = "";
// database connection
require "connectdb.php";

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $pwd = trim($_POST['pwd']);

    if (empty($email)) {
        $email_err = "Please Enter the Email";
    } elseif (empty($pwd)) {
        $pwd_err = "Please Enter the Password";
    } else {
        // process the inputs
        $sql = "select * from nhanvien where email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // email is correct
            $row = $result->fetch_assoc();
            $db_pwd = $row['hash'];
            if (password_verify($pwd, $db_pwd)) {
                // password is correct, login successful
                // Use PHP session 
                $_SESSION['name'] = $row['ten_nhanvien'];
                $_SESSION['quyen'] = $row['quyen'];
                $_SESSION['ma_nhanvien'] = $row['ma_nhanvien'];
                // viết vào bảng lịch sử nhân viên
                $ma_nhanvien = $row['ma_nhanvien'];
                $noidung = "Đăng nhập";

                $sql_lichsu = "INSERT INTO lichsunhanvien (ma_nhanvien, noidung, thoigian) VALUES (?, ?, now())";
                $stmt_lichsu = $conn->prepare($sql_lichsu);
                $stmt_lichsu->bind_param("is", $ma_nhanvien, $noidung);
                $stmt_lichsu->execute();

                $stmt_lichsu->close();
                if (isset($_POST['remember'])) {
                    $rem = $_POST['remember'];
                    // create two cookies
                    setcookie("cookie_email", $email, time() + 60 * 60 * 24 * 30, '/');
                    setcookie("cookie_rem", $rem, time() + 60 * 60 * 24 * 30, '/');
                } else {
                    if (isset($_COOKIE['cookie_email'])) {
                        setcookie("cookie_email", $email, time() - 60 * 60 * 24 * 30, '/');
                    }
                    if (isset($_COOKIE['cookie_rem'])) {
                        setcookie("cookie_rem", $rem, time() - 60 * 60 * 24 * 30, '/');
                    }
                }
                if ($_SESSION['quyen'] > 0) {
                    header("location:index.php");
                } else {
                    header("location:dashboard.php");
                }
            } else {
                $pwd_err = "Incorrect Password";
            }
        } else {
            $email_err = "Email is not registered";
        }
    }
}
