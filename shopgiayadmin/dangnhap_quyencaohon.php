<?php
include "chucnang/chucnang_dangnhap.php";
include "sidebar.php";
?>
<div class="container">
        <h1>Đăng nhập vào quyền cao hơn</h1>
        <?php
        $disp_email = !empty($email) ? $email : (isset($_COOKIE['cookie_email']) ? $_COOKIE['cookie_email'] : "");

        $checked = !empty($rem) ? "checked" : (isset($_COOKIE['cookie_rem']) ? "checked" : "");
        ?>
        <form action="" method="post">
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="<?=$disp_email?>" placeholder="Nhập Email" />
                <div class="text-danger"><?= $email_err?></div>
            </div>
            <div class="mb-3">
                <label for="pwd" class="form-label fw-bold">Password</label>
                <input type="password" class="form-control" name="pwd" id="pwd" placeholder="Nhập Password" />
                <div class="text-danger"><?= $pwd_err?></div>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="" name="remember" <?= $checked?>/>
                <label class="form-check-label" for=""> Remember Me </label>
            </div>
            
            <div class="register-btn text-center">
                <button type="submit" class="btn btn-success" name="submit">Login</button>
            </div>
        </form>
    </div>
</body>
</html>