<?php
include "chucnang/chucnang_dangky.php";
include "header.php";
?>
<style>
    form {
    width: 50%;
    margin: auto;
    border: 1px solid #cababa;
    padding: 20px;
    border-radius: 10px;    
}
</style>
<body>
  <div class="container">
    <h1>Đăng Ký</h1>
          <form action="" style="border:1px solid #ccc"  method="post" enctype="multipart/form-data">       
          <div class="mb-3">
                <label for="tenkhachhang" class="form-label fw-bold">Tên</label>
                <input type="text" class="form-control" name="tenkhachhang" id="tenkhachhang" value="" placeholder="Enter Tên" />
                
            </div>
            <div class="mb-3">
                <label for="sdt" class="form-label fw-bold">Số điện thoại</label>
                <input type="text" class="form-control" name="sdt" id="sdt" value="" placeholder="Enter Số điện thoại" />
            </div>
            <div class="mb-3">
                <label for="diachi" class="form-label fw-bold">Địa chỉ</label>
                <input type="text" class="form-control" name="diachi" id="diachi" value="" placeholder="Enter Địa chỉ" />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="" placeholder="Enter Email" />
            </div>
            <div class="mb-3">
                <label for="pwd" class="form-label fw-bold">Password</label>
                <input type="password" class="form-control" name="psw" id="psw" placeholder="Enter Password" />
                
            </div>
            <div class="register-btn text-center">
                <button type="submit" class="btn btn-success" name="submit">Đăng ký</button>
            </div>
</form>
  </div>
</body>
</html>
