<?php
include "chucnang/chucnang_dangky.php";
include "sidebar.php";
if (!isset($_SESSION['name'])) {
    header("location:login.php");
}
if($_SESSION['quyen'] > 0){
    header("location:dangnhap_quyencaohon.php");     
} 
?>
<body>
  <div class="container">
    <h1>Thêm Nhân Viên</h1>
          <form action="" style="border:1px solid #ccc"  method="post" enctype="multipart/form-data">       
          <div class="mb-3">
                <label for="tennhanvien" class="form-label fw-bold">Tên</label>
                <input type="text" class="form-control" name="tennhanvien" id="tennhanvien" value="" placeholder="Enter Tên" />
                
            </div>
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="" placeholder="Enter Email" />
                
            </div>
            <div class="mb-3">
                <label for="pwd" class="form-label fw-bold">Password</label>
                <input type="password" class="form-control" name="psw" id="psw" placeholder="Enter Password" />
                
            </div>
            <div class="mb-3">
                <label for="sdt" class="form-label fw-bold">Số Điện Thoại</label>
                <input type="text" class="form-control" name="sdt" id="sdt" value="" placeholder="Enter Số Điện Thoại" />
            </div>
            <div class="mb-3">
                <label for="diachi" class="form-label fw-bold">Địa Chỉ</label>
                <input type="text" class="form-control" name="diachi" id="diachi" value="" placeholder="Enter Địa Chỉ" />
            </div>
            <div class="mb-3">
                <label for="ngaysinh" class="form-label fw-bold">Ngày Sinh</label >
                
                <input type="date" class="form-control" name="ngaysinh" id="ngaysinh" value="" placeholder="Enter Ngày Sinh" />
            </div>
            <div class="mb-3">
                <label for="gioitinh" class="form-label fw-bold">Giới Tính</label>
                <select name="gioitinh" id="gioitinh" class="form-control">
                    <option value="">Chọn Giới Tính</option>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="luong" class="form-label fw-bold">Lương</label>
                <input type="text" class="form-control" name="luong" id="luong" value="" placeholder="Enter Lương" />
            </div>
            <div class="mb-3">
                <label for="quyen" class="form-label fw-bold">Chức Vụ</label>
                <select name="quyen" id="quyen" class="form-control">
                    <option value="">Chọn Chức Vụ</option>
                    <option value="0">admin</option>
                    <option value="2">Nhân Viên bán hàng</option>
                    <option value="1">nhân viên kho</option>
                </select>
            </div>   
            <div class="register-btn text-center">
                <button type="submit" class="btn btn-success" name="submit">Thêm</button>
            </div>
</form>
  </div>
</body>
</html>