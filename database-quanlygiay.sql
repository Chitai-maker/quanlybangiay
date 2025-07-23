-- Tạo cơ sở dữ liệu quanlybangiay
CREATE DATABASE quanlybangiay;
-- Sử dụng cơ sở dữ liệu quanlybangiay
USE quanlybangiay;
-- Tạo bảng LOAIGIAY
CREATE TABLE loaigiay (
    maloaigiay INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tenloaigiay VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Tạo bảng THUONGHIEU
CREATE TABLE thuonghieu (
    mathuonghieu INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tenthuonghieu VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Tạo bảng MAUGIAY
CREATE TABLE maugiay (
    mamaugiay INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tenmaugiay VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- tạo bảng SIZE
CREATE TABLE sizegiay (
    masize INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tensize VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Tạo bảng MATHANG
CREATE TABLE giay (
    magiay INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tengiay VARCHAR(100) NOT NULL UNIQUE,
    maloaigiay INT NOT NULL,
    mathuonghieu INT NOT NULL,
    mamaugiay INT NOT NULL,
    masize INT ,
    donvitinh VARCHAR(20) NOT NULL,
    giaban int NOT NULL,
    anhminhhoa VARCHAR(200),
    mota TEXT,
    soluongtonkho INT NOT NULL DEFAULT 0,
    FOREIGN KEY (maloaigiay) REFERENCES loaigiay(maloaigiay),
    FOREIGN KEY (mathuonghieu) REFERENCES thuonghieu(mathuonghieu),
    FOREIGN KEY (mamaugiay) REFERENCES maugiay(mamaugiay),
    FOREIGN KEY (masize) REFERENCES sizegiay(masize)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Tạo bảng nhanvien
CREATE TABLE nhanvien (
    ma_nhanvien INT AUTO_INCREMENT PRIMARY KEY,
    ten_nhanvien VARCHAR(100) NOT NULL,
    hash VARCHAR(255) NOT NULL , -- dùng để lưu mật khẩu đã được hash (mã hóa)
    email VARCHAR(100) NOT NULL UNIQUE,
    sdt VARCHAR(100) NOT NULL UNIQUE,
    diachi VARCHAR(100) NOT NULL UNIQUE,
    gioitinh VARCHAR(10) NOT NULL,
    ngaysinh DATE NOT NULL,
    luong int NOT NULL,
    quyen INT NOT NULL -- 0: admin, 1: nhân viên bán hàng, 2: nhân viên kho
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;-- Tạo bảng khách hàng
CREATE TABLE khachhang (
    ma_khachhang INT AUTO_INCREMENT PRIMARY KEY,
    ten_khachhang VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    sdt VARCHAR(100) NOT NULL UNIQUE,
    diachi VARCHAR(100) NOT NULL UNIQUE,
    matkhau VARCHAR(255) NOT NULL, -- dùng để lưu mật khẩu đã được hash (mã hóa)
    diemthanhvien INT DEFAULT 0, -- điểm thành viên để tích lũy
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_expire DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- tạo bảng đơn hàng
CREATE TABLE donhang (
    ma_donhang INT AUTO_INCREMENT PRIMARY KEY,
    ma_khachhang INT NOT NULL,
    ngaydat DATETIME NOT NULL,
    trangthai VARCHAR(100) NOT NULL,
    tongtien INT NOT NULL,
    hinhthucthanhtoan VARCHAR(100) NOT NULL,
    FOREIGN KEY (ma_khachhang) REFERENCES khachhang(ma_khachhang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- tạo bảng chi tiết đơn hàng
CREATE TABLE chitietdonhang (
    ma_chitietdonhang INT AUTO_INCREMENT PRIMARY KEY,
    ma_donhang INT NOT NULL,
    ma_giay INT NOT NULL,
    soluong INT NOT NULL,
    giaban INT,
    FOREIGN KEY (ma_donhang) REFERENCES donhang(ma_donhang),
    FOREIGN KEY (ma_giay) REFERENCES giay(magiay)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- tạo bảng sanphamhot
CREATE TABLE sanphamhot (
    ma_sanphamyeuthich INT AUTO_INCREMENT PRIMARY KEY, 
    magiay INT NOT NULL,
    giakhuyenmai INT CHECK (giakhuyenmai BETWEEN 1 and 100),
    FOREIGN KEY (magiay) REFERENCES giay(magiay)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- tạo bảng danhgia
CREATE TABLE danhgia (
    ma_danhgia INT AUTO_INCREMENT PRIMARY KEY,
    ma_khachhang INT NOT NULL,
    magiay INT NOT NULL,
    danhgia INT CHECK (danhgia BETWEEN 1 and 5),
    binhluan TEXT,
    ngaydanhgia DATETIME NOT NULL,
    FOREIGN KEY (ma_khachhang) REFERENCES khachhang(ma_khachhang),
    FOREIGN KEY (magiay) REFERENCES giay(magiay)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- tạo bảng bình luận đánh giá
CREATE TABLE binhluandanhgia (
    ma_binhluan INT AUTO_INCREMENT PRIMARY KEY,
    ma_danhgia INT NOT NULL,
    ma_nhanvien INT,
    ma_khachhang INT,
    noidung TEXT NOT NULL,
    thoigian DATETIME NOT NULL,
    FOREIGN KEY (ma_danhgia) REFERENCES danhgia(ma_danhgia),
    FOREIGN KEY (ma_nhanvien) REFERENCES nhanvien(ma_nhanvien),
    FOREIGN KEY (ma_khachhang) REFERENCES khachhang(ma_khachhang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- tạo bảng chatbox
CREATE TABLE chatbox (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ma_khachhang INT(11) NOT NULL,
    noidung TEXT NOT NULL,
    nguoigui ENUM('khach', 'shop') NOT NULL,
    thoigian DATETIME NOT NULL,
    trang_thai ENUM('chua_doc', 'da_doc') DEFAULT 'chua_doc',
    FOREIGN KEY (ma_khachhang) REFERENCES khachhang(ma_khachhang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- tạo bảng coupon
CREATE TABLE coupon (
    ma_coupon  INT AUTO_INCREMENT PRIMARY KEY,
    ten_coupon VARCHAR(100) NOT NULL UNIQUE,
    giatri INT NOT NULL CHECK (giatri BETWEEN 1 and 100),
    ngaybatdau DATETIME NOT NULL,
    ngayketthuc DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--  tạp bảng bannner ad
CREATE TABLE banner (
    ma_banner INT AUTO_INCREMENT PRIMARY KEY,
    ten_banner VARCHAR(100) NOT NULL UNIQUE,
    link_banner VARCHAR(200) NOT NULL,
    anh_banner VARCHAR(200) NOT NULL,
    trang_thai BOOLEAN DEFAULT TRUE -- TRUE: hiển thị, FALSE: ẩn
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- tạo bảng lịch sử thanh toán lương
CREATE TABLE lichsuthanhtoanluong (
    ma_lichsuthanhtoan INT AUTO_INCREMENT PRIMARY KEY,
    ma_nhanvien INT NOT NULL,
    ngaythanhtoan DATETIME NOT NULL,
    luong INT NOT NULL,
    FOREIGN KEY (ma_nhanvien) REFERENCES nhanvien(ma_nhanvien)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- tạo bảng thông tin ngân hàng
CREATE TABLE thongtinnganhang (
    ma_thongtinnganhang INT AUTO_INCREMENT PRIMARY KEY,
    ma_nhanvien INT NOT NULL,
    ten_chutaikhoan VARCHAR(100) NOT NULL,
    so_taikhoan VARCHAR(100) NOT NULL UNIQUE,
    ma_nganhang VARCHAR(100) NOT NULL ,
    FOREIGN KEY (ma_nhanvien) REFERENCES nhanvien(ma_nhanvien)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- tạo bảng lịch sử nhân viên
CREATE TABLE lichsunhanvien (
    ma_lichsunhanvien INT AUTO_INCREMENT PRIMARY KEY,
    ma_nhanvien INT NOT NULL,
    noidung TEXT NOT NULL,
    thoigian DATETIME NOT NULL,
    FOREIGN KEY (ma_nhanvien) REFERENCES nhanvien(ma_nhanvien)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- dử liệu cho bảng banner
INSERT INTO banner (ten_banner, link_banner, anh_banner, trang_thai) VALUES
('Banner 1', 'https://example.com', 'ad.jpg', 1),
('Banner 2', 'https://example.com', 'ad2.jpg', 1),
('Banner 3', 'https://example.com', 'ad3.jpg', 1),
('Banner 4', 'https://example.com', 'ad4.jpg', 1);
-- dử liệu cho bảng nhanvien
INSERT INTO `nhanvien` (`ma_nhanvien`, `ten_nhanvien`, `hash`, `email`, `sdt`, `diachi`, `gioitinh`, `ngaysinh`, `luong`, `quyen`) VALUES
(1, 'Tài', '$2y$12$08D4RMoZWcjhPpTyItV0..m4xc4Ofp4kn/LDZZuLPdcw8UvGqNJkq', 'Admin@admin.com', '09113001001', '123123123', 'Nam', '0000-00-00', 1200000000, 0),
(2, 'Dương Văn Trí', '$2y$12$FkGKGz7gySPX4shJQSfS7O8zY1/RHPWDxiq32eAVDsI/iCosaSjU.', 'tridv221@gmail.com', '0997232111', '112 Hùng Vương, TpHCM', 'Nam', '2025-05-15', 7000000, 0),
(3, 'Phan Thu Hương', '$2y$12$U07XbyUJd/Klp8GvVCQv9OVenYRFd5aD5w1Fx2RBYUcHkVttZn.rO', 'huongPT212@gmail.com', '0764356441', '28 Phan Bội Châu,Bình Định', 'Nữ', '2000-05-03', 5000000, 2),
(4, 'Nguyễn Hiền Trang', '$2y$12$GU18hw/uofKa6Bl6ZXeKf.V9HBdF54M8zT7jwYwFfTzK8yw3vqnbm', 'trangNH434@gmail.com', '0903262717', '55/1 Ba Đình,Hà Nội', 'Nữ', '2001-01-10', 5000000, 2),
(5, 'Lê Đức Trí', '$2y$12$YvnLinunznC.5nHGiPNXUe.9sAq2reBOgLmTOTXf5Ta3RfhkoGz8i', 'triLD134@gmail.com', '0880333657', '50 Hùng Vương,Nha Trang', 'Nam', '1994-10-20', 6000000, 1),
(6, 'Trần Thu Trinh', '$2y$12$qJm0N48lmOd0HcC..NQMX.HbeGmwPbCA/Yngh8svjkGan3It10HIC', 'trinhTT661@gmail.com', '854111373', '33 Lê Lợi ,Đà Nẵng', 'Nữ', '1999-09-19', 6000000, 1),
(7, 'Phan Tấn Vũ', '$2y$12$MyLgiiraok8Iz0Jq8PbJXehhaPvC9tJqkqp7fGYoWHZcR5ORB65d2', 'VuPT999@gmail.com', '0912555676', '29/3 Phan Văn Trị,TPHCM', 'Nam', '2004-04-20', 5000000, 2);
-- dử liệu cho bảng khách hàng
INSERT INTO `khachhang` (`ma_khachhang`, `ten_khachhang`, `email`, `sdt`, `diachi`, `matkhau`) VALUES
(1, 'Trần Văn Nam', 'namtv123@gmail.com', '980221332', '228 Lê Lợi, TpHCM', '$2y$12$PXM..oBcbDiOq1V8r3MK4eZ5h.MW1Ulb.OXjW39Af./WiePMjqQzS'),
(2, 'Lê Tấn Vương', 'vuonglt991@gmail.com', '0876555342', '45 Phan Đình Phùng,Bình Định', '$2y$12$0fvcdB1CKap3SUYq1vCVoeH7bXYORg2wGfs6JE/kMcwVTNJ/fh6ae'),
(3, 'Nguyễn Thu Dương', 'Duongnt223@gmail.com', '0671323477', '25/5 Ba Đình,Hà Nội', '$2y$12$FI1L2L6x8QyyDlirrToEkOcrjMN4Ppxb7y4sAE/971W2v/f/G420G'),
(4, 'Trần Hoài Đức', 'DucTH212@gmail.com', '0981224353', '92 Yersin,Nha Trang', '$2y$12$KaQQG4xX5cL.6KuCOcb.AuaWiQKsUOPqhdH/7G/lqYjzgFK.iFFWi'),
(5, 'Lê Thu Ngọc ', 'NgocLT111@gmail.com', '0700336578', '321 Hùng Vương ,Đà Nẵng', '$2y$12$FPHQ8ujiT1H0BeYS/zsADucgEwggu1BXgniDTZ2YFAo//HJ2bkyyG');
-- dử liệu cho bảng loaigiay
INSERT INTO loaigiay (tenloaigiay) VALUES 
('Sneaker'),
('Sandal'),
('Dép'),
('Tất');
-- dử dữ liệu cho bảng thuonghieu
INSERT INTO thuonghieu (tenthuonghieu) VALUES 
('Vans'),
('Adidas'),
('Balenciaga'),
('Nike');
-- dử liệu cho bảng maugiay
INSERT INTO maugiay (tenmaugiay) VALUES 
('Đen'),
('Trắng'),
('Hồng'),
('Xanh dương'),
('Xanh lá'),
('Đỏ');
-- dử liệu cho bảng sizegiay
INSERT INTO sizegiay (tensize) VALUES 
('36'),
('37'),
('38'),
('39'),
('40'),
('41'),
('42'),
('43'),
('44'),
('45');
-- dử liệu cho bảng giay
INSERT INTO giay( tengiay, maloaigiay, mathuonghieu, mamaugiay, masize, donvitinh, giaban, anhminhhoa, mota) VALUES
('Giày Gazelle Indoor', 1,2,2,1,'đôi',36000000,'giay_gazelle_indoor.jpg',
'Đôi giày tập trong nhà classic có thân giày bằng vải dệt lót da.\n
Mang trong mình dòng chảy di sản adidas Originals đích thực, đôi giày Gazelle Indoor này sẽ cho bạn phong cách vintage mang vibe casual phóng khoáng. Lấy cảm hứng từ mẫu giày tập trong nhà nguyên bản, phiên bản này khác biệt hơn với chất liệu vải lưới đơn sắc với lỗ thông khí to hơn. Các chi tiết phủ ngoài toe cap, viền gót giày và dây giày tạo nên cấu trúc, cùng lớp lót bằng da mượt mà để mỗi lần xỏ giày vào đều là trải nghiệm xứng đáng. Thành đế bằng cao su gum trong mờ theo đúng thiết kế gốc, cùng vân bám hình logo Ba Lá giúp đôi chân bạn luôn vững vàng.'),
('Giày Samba OG', 1,2,2,2,'đôi',2700000,'giay_samba_og_trang.jpg',
'Giày Samba trở lại với thiết kế đơn sắc gọn gàng.\n
Với bề dày lịch sử suốt hơn 70 năm qua, giày Samba OG là một biểu tượng. Thân giày bằng da mượt mà và đế ngoài bằng cao su cho phong cách vừa thời thượng vừa đa năng. Bất kể đi chơi tối hay ra đường cuối tuần như bao ngày, đôi giày này sẽ là điểm nhấn tôn lên mọi outfit. Thiết kế thanh thoát và thuôn gọn mang đậm dấu ấn lịch sử, từ sân bóng đá đến sân trượt ván, khẳng định vị thế đẳng cấp của logo Ba Lá. Đã qua rồi thời còn là đôi giày tập trong nhà — ngày nay dòng giày Originals này tiếp tục viết thêm chương mới trong văn hóa và giới streetwear.'),
('Giày Superstar II', 1,2,5,3,'đôi',2600000,'giay_superstar_ii_mau_xanh_la_ji3076.jpg',
'Đôi giày mũi vỏ sò huyền thoại mang sắc màu tươi tắn.\n
Dù trên sân hay ngoài đời, đôi giày adidas Superstar II này sẽ luôn đồng hành cùng bạn. Thân giày bằng da mượt mà và mũi giày vỏ sò biểu tượng không thể nhầm lẫn, tôn vinh lịch sử hơn 50 năm tạo dấu ấn trong giới thể thao và thời trang streetwear. Đế ngoài bằng cao su bám đáp ứng những chuyến đi thường ngày, cùng lớp lót bằng vải dệt giúp đôi chân luôn thoải mái nguyên cả ngày. Nếu muốn gây ấn tượng, phối màu tươi sáng cùng charm dây giày in logo Ba Lá sẽ khiến mọi ánh mắt phải ngoái nhìn theo. Là biểu tượng trong giới streetwear và lifestyle, giày Superstar II cho bạn sải bước đầy tự tin qua mọi không gian.'),
('Giày Handball Spezial', 1,2,4,4,'đôi',2500000,'giay_handball_spezial_mau_xanh_da_troi_bd7633.jpg',
'Đôi giày được ưa chuộng bởi các fan bóng đá cũng như tín đồ thời trang, làm từ da lộn.\n
Ra mắt lần đầu vào năm 1979 dành cho các cầu thủ bóng ném hàng đầu, đôi giày này giờ đây được yêu thích bởi phong cách classic. Phiên bản này có thân giày bằng da lộn cho cảm giác mềm dẻo. Đế gum mềm mại trung thành với thiết kế vintage.'),
('Dép Racer TR', 3,2,1,5,'đôi',700000,'dep_racer_tr_djen_g58170.jpg',
'Đôi dép nhanh khô với thiết kế thanh thoát.\n
Ra mắt vào năm 1972, đôi dép adidas adilette rất được yêu thích này dù đã bị "copy" rất nhiều lần nhưng vẫn chưa bao giờ thất thế. Với thiết kế phù hợp ở cả bãi biển lẫn phòng thay đồ và cả khi ra phố giải quyết công chuyện, phiên bản này có quai dép bằng EVA mềm mại cùng lòng dép ôm chân thoải mái.'),
('Dép adilette Flow', 3,2,5,6,'đôi',750000,'dep_adilette_flow_mau_xanh_la_ig6865.jpg',
'Đôi dép êm ái với thiết kế cực cool.\n
Tận hưởng cảm giác thoải mái tuyệt đối mọi lúc mọi nơi. Bất kể bạn đang đi dạo nhẹ nhàng hay trên đường về nhà sau giờ bơi, đôi dép adidas này sẽ nâng niu đôi chân bạn nhờ thiết kế đúc nguyên khối. Kết cấu slip-on thanh thoát giúp bạn dễ dàng xỏ vào và cởi ra. Với thiết kế siêu nhẹ mà bền chắc, đôi dép này chắc chắn sẽ trở thành lựa chọn hàng đầu của bạn cho những hành trình phiêu lưu mùa nóng.'),
('Dép adilette 22', 3,2,2,7,'đôi',1400000,'dep_adilette_22_trang_hq4672.jpg',
'Đôi dép đậm chất tương lai, có nguồn gốc tự nhiên.\n
Để làm ra thiết kế cho đôi dép adidas này, chúng tôi đã tìm kiếm bản đồ địa hình minh họa những chuyến du hành tới sao Hoả và những giai đoạn không gian khác nhau của một hành tinh mới. Nguồn cảm hứng đến từ tương lai không chỉ dừng lại ở đó. Dép còn được làm từ chất liệu sử dụng cây mía, đây là bước tiến hướng đến một tương lai bền vững hơn. Hãy đi đôi dép này trên nhiều loại địa hình từ ướt đến khô./n
Đôi dép này làm từ chất liệu tự nhiên và có thể tái tạo, là một phần trong hành trình của chúng tôi hướng tới chấm dứt sử dụng tài nguyên hữu hạn và góp phần loại bỏ rác thải nhựa.'),
('Dép Znsory', 3,2,3,8,'đôi',1000000,'dep_znsory_hong_js2847.jpg',
'Đôi dép êm ái cho cảm giác thoải mái, êm ái suốt cả ngày.\n
Hãy mang dép và lên đường. Đôi dép êm ái này có phần lòng dép chạm khắc nâng niu bàn chân bạn và đế giữa cho cảm giác thoải mái. Bất kể bạn đang thư giãn bên hồ bơi hay dạo bước trên bờ biển, đôi dép này sẽ mang đến phong cách laid-back hoàn hảo cho những hành trình phiêu lưu mùa nóng.'),
('ADI 24 SOCK', 4,2,4,9,'đôi',245000,'adi_24_sock_mau_xanh_da_troi_im8924_01_02_hover_standard.jpg',
'VỚ THỂ THAO ADIDAS ADI 24 SOCK.\n
Tăng cường hiệu suất với vớ thể thao Adidas Adi 24 Sock, trang bị công nghệ AEROREADY và đệm mềm mại, giữ chân bạn khô ráo và thoải mái. Thiết kế dài đến đầu gối bảo vệ tối ưu và giảm thiểu sự di chuyển của vớ. Đặc biệt, sản phẩm làm từ ít nhất 50% vật liệu tái chế, giúp bảo vệ môi trường.'),
('Bộ 1 Đôi Tất RUNxADIZERO', 4,2,1,10,'đôi',415000,'bo_1_djoi_tat_runxadizero_djen_jc6463_03_standard.jpg',
'Đôi tất chạy bộ sử dụng công nghệ CLIMACOOL giúp bạn luôn mát mẻ, khô ráo và sẵn sàng.\n
Từ thói quen trước giờ thi đấu đến đôi tất phù hợp, từng chi tiết nhỏ nhất đều quan trọng. Đôi tất chạy bộ adidas này mang lại cảm giác nhẹ nhàng cho đôi chân bạn nhờ lớp đệm thoải mái ở phần mũi chân. Công nghệ CLIMACOOL thoát ẩm và đánh bay mồ hôi cho cảm giác mát mẻ, khô ráo và không chút phân tâm, đồng thời thiết kế nâng đỡ vòm bàn chân thông thoáng giúp đôi chân luôn sảng khoái.\n
Công nghệ CLIMACOOL giúp bạn kiểm soát mồ hôi nhờ chất liệu thoát mồ hôi nhanh chóng. Chất vải nhanh khô cho cảm giác tươi mới.\n
Sản phẩm này làm từ tối thiểu 50% chất liệu tái chế. Bằng cách tái sử dụng các chất liệu đã được tạo ra, chúng tôi góp phần giảm thiểu lãng phí và hạn chế phụ thuộc vào các nguồn tài nguyên hữu hạn, cũng như giảm phát thải từ các sản phẩm mà chúng tôi sản xuất.'),
('Tất Running x 4D HEAT.RDY', 4,2,2,1,'đôi',315000,'tat_running_x_4d_heat.rdy_trang_hy0680_03_standard.jpg',
'Đôi tất chạy bộ siêu thoáng khí có sử dụng sợi Parley Ocean Plastic.\n
Kể cả khi bạn đã có đôi giày chạy bộ tốt nhất thế giới, nhưng nếu không có tất phù hợp thì chẳng khác gì bạn đang chạy chân trần. Lấy cảm hứng từ công nghệ cải tiến 4D, đôi tất adidas này có lớp đệm cao cấp tập trung ở các vùng bàn chân tiếp đất mạnh nhất. Thiết kế nén cơ kết hợp các vùng lưới mang lại cảm giác thoáng khí, nâng đỡ.\n
Sản phẩm này làm từ sợi dệt có chứa 50% chất liệu Parley Ocean Plastic – rác thải nhựa tái chế thu gom từ các vùng đảo xa, bãi biển, khu dân cư ven biển và đường bờ biển, nhằm ngăn chặn ô nhiễm đại dương. Sản phẩm này có chứa tổng cộng tối thiểu 70% thành phần tái chế.'),
('Tất Chạy Bộ UB23 HEAT.RDY', 4,2,3,2,'đôi',270000,'tat_chay_bo_ub23_heat.rdy_hong_in2370_03_standard.jpg',
'Đôi tất chạy bộ thoáng khí có sử dụng chất liệu tái chế.\n
Là một runner, bạn đòi hỏi rất nhiều từ đôi chân của mình. Hãy chăm sóc chúng thật tốt với đôi tất chạy bộ adidas này. Bạn sẽ luôn thấy mát mẻ trong suốt nhiều km nhờ công nghệ HEAT.RDY và lớp vải lưới thoáng khí. Công nghệ FORMOTION ôm chân linh hoạt và hỗ trợ ở những vị trí cần thiết, còn đường may phẳng ở đầu ngón chân đảm bảo không có phồng rộp xảy ra. Đệm lót gót chân nâng niu từng sải bước.\n
Làm từ một loạt chất liệu tái chế và có chứa tối thiểu 40% thành phần tái chế, sản phẩm này đại diện cho một trong số rất nhiều các giải pháp của chúng tôi hướng tới chấm dứt rác thải nhựa.'),
('Nike Air Max Dn8', 1,4,1,3,'đôi',5589000,'airmaxdn8.jpg',
'Nhiều không khí hơn, ít cồng kềnh hơn. Dn8 sử dụng hệ thống Dynamic Air của chúng tôi và cô đọng nó thành một gói kiểu dáng đẹp, cấu hình thấp. Được cung cấp bởi tám ống khí có áp suất, nó mang lại cho bạn cảm giác nhạy bén với mỗi bước chân. Bước vào trải nghiệm chuyển động không thực.\n
Đệm thực tế\n
Hệ thống Dynamic Air của chúng tôi có các ống buồng kép với mức áp suất được điều chỉnh. Điều này có nghĩa là luồng không khí thay đổi trong mỗi bộ ống để đáp ứng với nén, mang lại trải nghiệm đệm tùy chỉnh.\n
Chuyển động không có thật\n
Trải dài từ gót chân đến chân, hệ thống Dynamic Air nằm ngay trên đế ngoài bằng cao su để giúp bạn gần mặt đất nhất có thể để chuyển đổi mượt mà hơn. Bọt sang trọng được đặt dưới chân để tạo thêm sự thoải mái.\n
Dòng chảy thực tế\n
Phần trên được điêu khắc được làm từ lưới nhẹ ở phía trên, trong khi các đường gạch chéo ở hai bên để lộ lưới hở để thoáng khí hơn. Các đường nét thiết kế giúp tỏa năng lượng trong suốt vẻ ngoài của bạn.'),
('Nike Shox R4', 1,4,6,4,'đôi',4409000,'nikeshoxr4.jpg',
'Một phiên bản được làm lại của biểu tượng đầu những năm 2000, Nike Shox R4 tái tạo bản gốc với các đường nét thiết kế tương lai và đệm giống như hiệu suất. Các cột Nike Shox ở gót chân phân phối trọng lượng để tối đa hóa sự thoải mái đồng thời mang lại vẻ ngoài táo bạo trên đường phố.'),
('Nike Air Force 1', 1,4,2,5,'đôi',3239000,'airforce107.jpg',
'Thoải mái, bền bỉ và vượt thời gian — đó là số 1 là có lý do. Cấu trúc của thập niên 80 kết hợp với màu sắc cổ điển cho phong cách theo dõi cho dù bạn đang ở trên sân hay khi đang di chuyển.'),
('Nike Mercurial Superfly 10 Elite',1,4,4,7,'đôi',8059000,'nike_mercurial_superfly_10_elite.jpg',
'Bị ám ảnh bởi tốc độ? Những ngôi sao lớn nhất của trò chơi cũng vậy. Đó là lý do tại sao chúng tôi tạo ra chiếc bốt Elite này với bộ phận Air Zoom dài 3/4 cải tiến. Nó mang lại cho bạn và những người chơi nhanh nhất của môn thể thao cảm giác thúc đẩy cần thiết để vượt qua tuyến sau. Kết quả là Mercurial phản hồi nhanh nhất mà chúng tôi từng tạo ra, bởi vì bạn đòi hỏi sự tuyệt vời từ bản thân và giày dép của mình.\n
Cảm ứng đặc biệt\n
Nike Gripknit là một vật liệu dính mang lại cảm giác tốt hơn cho quả bóng. Nó chiếm nhiều diện tích bề mặt hơn, vì vậy bạn có nhiều không gian hơn để kiểm soát bóng khi rê bóng ở tốc độ cao hoặc kết thúc một trận đấu bằng một cú sút trúng khung thành. Nó phù hợp với hình dạng bàn chân của bạn và mang lại cho bạn độ bám ngang bằng trong điều kiện ẩm ướt hoặc khô ráo để có vẻ vừa vặn như đinh điêu khắc. Kết cấu đúc vi mô hoạt động với Gripknit để giúp tạo hình cho bàn chân của bạn để có những cú đánh tốt hơn nữa.\n
Lực kéo nhanh\n
Mô hình lực kéo giống như sóng là một loạt các đinh tán xếp tầng, vì vậy nó thu hút nhiều diện tích bề mặt hơn của thiết bị Zoom không khí đồng thời cung cấp độ bám thích hợp. Đinh tán lớn nhất có cùng chiều cao với đinh tán giữa truyền thống của chúng tôi, vì vậy lực kéo không bị ảnh hưởng. Chúng tôi kết hợp lực kéo giống như sóng với đinh tán mũi tên và lưỡi dao phát triển để giúp bạn dừng lại khi thực hiện các vết cắt nhanh.\n
Vừa vặn\n
Phần trên Flyknit đầy đủ được thiết kế để tăng tốc độ. AtomKnit, một Flyknit cực kỳ nhẹ và mạnh mẽ, ở hai bên giảm trọng lượng và hỗ trợ. Gripknit, AtomKnit và Flyknit kết hợp để tạo thành phần trên Mercurial mỏng nhất cho đến nay, đưa bạn đến gần hơn với quả bóng và giảm thời gian đột nhập. Cổ áo Dynamic Fit quấn mắt cá chân của bạn trong chất liệu vải mềm mại, co giãn để tạo cảm giác an toàn.'),
('Nike Calm Mule', 3,4,1,6,'đôi',1479000,'nike_calm_mule.jpg',
'Tận hưởng trải nghiệm yên tĩnh, thoải mái — bất cứ nơi nào bạn đưa ngày nghỉ. Được làm từ bọt mềm mại nhưng hỗ trợ, thiết kế tối giản giúp những slide này dễ dàng tạo kiểu khi có hoặc không có tất. Và họ có một đế chân có kết cấu để giúp giữ chân của bạn ở đúng vị trí.'),
('Nike Victori One', 3,4,4,7,'đôi',889000,'nikevictoryoneslide.jpg',
'SỰ THOẢI MÁI HUYỀN THOẠI, ĐƯỢC LÀM LẠI.\n
Từ bãi biển đến khán đài, Victori One là cầu trượt không thể bỏ qua cho các hoạt động hàng ngày. Những cập nhật tinh tế nhưng đáng kể như dây đeo rộng hơn và bọt mềm hơn giúp bạn dễ dàng thư giãn. Hãy tiếp tục — tận hưởng sự thoải mái vô tận cho đôi chân của bạn.'),
('Nike ReactX Rejuven8', 3,4,2,8,'đôi',1759000,'nikereactxrejuven8slide.jpg',
'Cho đôi chân của bạn nghỉ ngơi. Được làm từ bọt ReactX mềm mại và nhạy bén, Rejuven8 sử dụng một số công nghệ tốt nhất của chúng tôi để tạo ra các slide phục hồi mà bạn mong muốn mặc.'),
('Nike Calm SE', 3,4,4,9,'đôi',1407199,'nike_calm_se.jpg',
'Tận hưởng trải nghiệm yên tĩnh, thoải mái — bất cứ nơi nào bạn đưa ngày nghỉ. Được làm từ bọt mềm mại nhưng hỗ trợ, thiết kế tối giản giúp những slide này dễ dàng tạo kiểu khi có hoặc không có tất. Và họ có một đế chân có kết cấu để giúp giữ chân của bạn ở đúng vị trí. Trên hết, phiên bản đặc biệt này có lớp hoàn thiện óng ánh.'),
('Nike Vista', 2,4,1,10,'đôi',1609000,'nikevistasandal.jpg',
'Với tất cả các yếu tố cần thiết bạn cần cho một ngày đi chơi (như đệm sang trọng và màu sắc dễ tạo kiểu), Nike Vista mang lại ít hơn là nhiều hơn. Trọng lượng nhẹ, thoáng mát và hỗ trợ, nó cho phép bạn kết nối cuộc sống thành phố và thiên nhiên chỉ bằng dây đeo.'),
('Nike Calm', 3,4,1,1,'đôi',2349000,'nikecalmslide.jpg',
'Tận hưởng trải nghiệm yên tĩnh, thoải mái — bất cứ nơi nào bạn đưa ngày nghỉ. Được làm từ bọt mềm mại nhưng hỗ trợ, thiết kế tối giản giúp những đôi dép này dễ dàng tạo kiểu khi có hoặc không có tất. Và dây đai siêu sang trọng tạo ra vẻ ngoài quá khổ, với dây đeo trên cùng có thể điều chỉnh để mang lại cho bạn sự vừa vặn hoàn hảo.'),
('Nike Air Max Sol', 2,4,1,2,'đôi',2349000,'nikeairmaxsolsandal.jpg',
'Phong cách Air Max được tăng cường vitamin D. Những đôi xăng đan nắng này vẫn giữ được bộ phận Nike Air có thể nhìn thấy và nhiều đệm trong khi cho phép những ngón chân đó được tự do. Thêm vào đó, phần trên bằng lưới giúp bạn luôn mát mẻ cả về vẻ ngoài và cảm giác. Thắt dây an toàn — đã đến lúc vui vẻ.'),
('Nike Everyday Essential', 3,4,2,3,'đôi',459000,'nike_everyday_essential.jpg',
'THOẢI MÁI GIẢN DỊ.\n
Vớ Nike Everyday Essential là những đôi tất nhẹ, linh hoạt, có thể được mang cho nhiều hoạt động khác nhau. Chúng có cảm giác mềm mại với chất liệu vải dệt kim thoáng khí mang lại sự thoải mái cả ngày.'),
('Nike Everyday Plus Cushioned', 3,4,1,4,'đôi',509000,'nike_everyday_plus_cushioned.jpg',
'HỖ TRỢ THOẢI MÁI CỘNG VỚI\n
Vớ đệm Nike Everyday Plus mang lại sự thoải mái cho quá trình tập luyện của bạn với đệm bổ sung dưới gót chân và bàn chân trước và dây vòm vừa khít, hỗ trợ. Khả năng thấm mồ hôi và khả năng thoáng khí ở phía trên giúp giữ cho bàn chân của bạn khô ráo và mát mẻ để giúp bạn vượt qua bộ phụ đó.'),
('Vans Old Skool Classic Sport', 1,1,2,5,'đôi',1750000,'vans_old_skool_classic_sport_black_true_white_vn0a5krf93u_3.jpg',
'Vans Old Skool Classic sport Với thiết kế màu sắc và họa tiết đặc trưng, phần lượn sóng viền màu Trắng sẽ làm cho người đi có cảm giác khỏe khắn và họa tiết Bắt mắt luôn cuốn giới trẻ.'),
('VANS CANVAS OLD SKOOL CLASSIC TRUE WHITE', 1,1,2,6,'đôi',1575000,'vans_old_skool_classic_true_white_vn000d3hw00_2.jpg',
'Lại là một phiên bản Best Seller của VANS mọi thời đại, đôi giày chỉ với một màu trắng tinh này đã mang đến cho thương hiệu khá nhiều lợi nhuận dù khá kén người chọn. Là phiên bản được VANS sử dụng chủ yếu trong các sự kiện Custom giày, được các fan hâm mộ của VANS vẽ ra rất nhiều kiểu dáng khác nhau và luôn được hãng VANS ủng hộ và thậm chí là tài trợ để có những phiên bản Custom đặc sắc.'),
('VANS SK8-HI CLASSIC NAVY', 1,1,4,6,'đôi',1850000,'van_sk8_hi_classic_navywhite_vn000d5invy_2.jpg',
'Dòng VANS CLASSIC SK8-HI được xem là đôi giày kinh điển và đặt nền móng đầu tiên để VANS có thể phát triển huy hoàng đến ngày hôm nay. Tổng quan, đôi giày được thiết kế khá đơn giản và không cầu kỳ nhưng không mất đi tính năng động của nó, đây là điểm thu hút nhất đối với giới trẻ, đặc biệt là các bạn yêu giày.'),
('VANS OLD SKOOL CLASSIC BLACK', 1,1,1,7,'đôi',1665000,'vans_old_skool_black_white_vn000d3hy28_2.jpg',
'VANS - một thương hiệu giày thể thao vang tầm thế giới với các sản phẩm đi đôi cùng tâm huyết, chất lượng và đẳng cấp thời trang toàn cầu. Với kinh nghiệm hoạt động trong nghề cực kỳ phong phú với hơn nửa thế kỷ cống hiến hết mình, VANS chính thức đi vào hoạt động và cho ra mắt những sản phẩm đầu tiên gây chấn động thị trường Sneaker vào ngày 16 tháng 3 năm 1966 tại số 704E Broadway, Anaheim, California. Sau 11 năm hoạt động vào năm 1977 cùng với những thành công nhất định, VANS chưa một lần ngủ quên trong chiến thẳng của mình mà tiếp tục cống hiến đến các VANSAHOLIC một huyền thoại tiếp theo mang tên VANS OLD SKOOL.'),
('Giày VANS STYLE 36 CLASSIC SUEDE DRESS BLUES', 1,1,4,8,'đôi',1750000,'giay_vans_style_36_classic_suede_dress_blues_marsh_vn0a3dz3rfl_2.jpg',
'Giày Vans UA Style 36 màu Dress Blues/Marsh được làm từ 40.44% da và 59.56% vải dệt, đôi giày này không chỉ bền bỉ mà còn mang lại cảm giác thoải mái. Màu xanh đen tạo điểm nhấn tinh tế và dễ dàng phối hợp với nhiều trang phục khác nhau.'),
('VANS AUTHENTIC CLASSIC COMFYCUSH WHITE', 1,1,2,9,'đôi',1750000,'giay_vans_authentic_classic_comfycush_white_vn0a3wm7vng_3.jpg',
'Vans Authentic Comfycush All White ra mắt công nghệ đế lót ly Fresh Fresh ComfyCush ™. Nhẹ hơn, thoải mái hơn.'),
('Giày VANS OLD SKOOL PIG SUEDE VINTAGE INDIGO', 1,1,4,10,'đôi',1750000,'giay_vans_old_skool_pig_suede_vintage_indigo_vn0005ufahu_3.jpg',
'Sở hữu kiểu dáng Old Skool cổ điển với phối màu rất chill, Vans Pig Suede Vintage Indigo Old Skool sở hữu tone xanh dương mang lại cảm giác ấm áp với phong cách thời thượng. Siêu phẩm sở hữu nhiều tính năng vô cùng nổi bật với Upper bao phủ toàn bộ 100% chất liệu da lộn mềm mại tựa như nhung. Ngoài ra thiết kế da lộn được nâng cấp với công nghệ HeiQ Eco Dry tạo nên điểm khác biệt cho BST với khả năng chống thấm cực tốt.'),
('VANS SLIP-ON CLASSIC COLOR THEORY CHECKERBOARD ICEBERG GREEN', 2,1,5,5,'đôi',1450000,'giay_vans_slip_on_classic_color_theory_checkerboard_iceberg_green_vn000bvzcjl_3.jpg',
'Dòng giày VANS SLIP-ON CLASSIC COLOR THEORY CHECKERBOARD ICEBERG GREEN mang đến một cách tiếp cận mới mẻ và táo bạo trong việc sử dụng màu sắc, khiến cho đôi giày trở nên nổi bật và thu hút mọi ánh nhìn.'),
('VANS CLASSIC SLIP-ON JOYFUL DENIM LIGHT PINK', 2,1,3,1,'đôi',1029500,'giay_vans_classic_slip_on_joyful_denim_light_pink_vn000ct5ltp_2.jpg',
'Đôi giày Vans Classic Slip-On màu Joyful Denim Light Pink với chất liệu 100% vải dệt, mang đến sự nhẹ nhàng và đơn giản trong phong cách. Màu sắc hồng cùng chi tiết trắng tinh tế tạo nên sự tương phản và bắt mắt, phù hợp để phối cùng nhiều loại trang phục khác nhau, từ casual đến semi-formal.'),
('VANS CLASSIC SLIP-ON BLACK/WHITE', 2,1,1,2,'đôi',1450000,'vans_slip_on_classic_black_vn000eyeblk_2.jpg',
'Slip-on hay Việt Nam gọi là giày lười, là thiết kế đặc biệt thiên về thời trang nhiều hơn của VANS, nhưng vẫn phù hợp với những môn thể thao như trượt ván, BMX, v.v... mặc dù thiết kế không thể bảo vệ được những phần quan trọng của chân như mắt cá, nhưng đối với những vận động viên trình độ cao thì đây vẫn là sự lựa chọn không thể thiếu trong bộ sưu tập của họ.'),
('VANS SLIP-ON CLASSIC TRUE WHITE', 2,1,2,3,'đôi',1450000,'vans_slip_on_classic_true_white_vn000eyew00_2.jpg',
'VANS Classic Slip-on luôn có một lượng fan đông đảo trung thành, kiểu dáng đơn giản chỉ là một đôi giày lười nhưng thiết kế lại dành cho vận động viên trượt ván, BMX và nhiều môn thể thao mạo hiểm khác, điều đó khiến cho VANS Classic Slip-on trở nên đặc biệt khi ẩn chứa một nội lực đáng kể trong vẻ bề ngoài đơn giản.'),
('VANS SLIP-ON MULE CLASSIC CHECKERBOARD', 2,1,2,4,'đôi',1550000,'giay_vans_slip_on_mule_classic_checkerboard_vn0004kteo1_2.jpg',
'Vans Mule Classix Slip On Checkerbroad  với họa tiết checker đặc trưng làm bạn có thể dễ dàng kết hợp với nhiều phụ kiện và trang phục khác nhau để tạo nên một set đồ đẹp, thích hợp mang trong nhiều môi trường khác nhau như đi chơi, đi làm hay vận động ngoài trời.'),
('VANS ANAHEIM FACTORY 98 DX SLIP-ON SPIDER WEB', 2,1,1,5,'đôi',1710000,'vans_anaheim_factory_98_dx_slip_on_spider_web_vn0a3jex1jj_2.jpg',
'Là sản phẩm nằm trong pack Anaheim Factory nhằm vinh danh nhà máy đầu tiên của VANS tại vùng đất Anaheim, bang California. Những sản phẩm đầu tiên từ nhà máy này là những sản phẩm mang tính giá trị cao nhất, vì chúng làm nên tên tuổi cũng như sức mạnh của VANS cho đến thời điểm hiện nay. Những sản phẩm giày xuất phát từ bộ sưu tập này sử dụng bộ khung của những dòng giày truyền thống cùng công nghệ UltraCush độc quyền từ VANS - sẽ giúp đôi giày trở nên nhẹ và êm hơn. Bên cạnh đó, đôi giày vẫn sẽ giữ được những yếu tố được cho là signature của pack Anaheim như vải canvas dày với trọng lương 10 oz/yard vuông, lớp lót ở gót giày được đục lỗ, phần dây cao su bao quanh mid-sole cũng được làm to hơn và bóng hơn.'),
('Giày Balenciaga Defender Trainers', 1,3,1,6,'đôi',27500000,'giay_balenciaga_defender_trainers.jpg',
'Nâng cao phong cách đường phố của bạn với giày Balenciaga Defender Sneaker màu đen. Những đôi giày này không chỉ có thiết kế hiện đại mà còn cung cấp sự thoải mái cần thiết cho việc sử dụng cả ngày dài.\n
Với sự kết hợp hoàn hảo giữa tính năng đa dụng và phong cách bắt mắt, giày Balenciaga Defender là sự lựa chọn tuyệt vời để làm nổi bật bất kỳ bộ trang phục nào. Từ những buổi đi chơi đến những dịp casual, những đôi giày này sẽ giúp bạn thể hiện phong cách riêng biệt và cuốn hút. Hãy sẵn sàng tỏa sáng với Balenciaga Defender Sneaker!'),
('Giày Balenciaga x Adidas Triple S ', 1,3,3,7,'đôi',26500000,'giay_balenciaga_x_adidas_triple_s.jpg',
'Giới thiệu mẫu giày adidas x Balenciaga Triple S Sneaker với tông màu Hồng Neon nổi bật, được thiết kế đặc biệt dành cho phái đẹp. Sự hợp tác giữa adidas và Balenciaga mang đến sự kết hợp hoàn hảo giữa thể thao và thời trang, tạo ra một đôi giày sneaker táo bạo và phong cách.\n
Thiết kế Triple S sở hữu kiểu dáng chunky cùng các chất liệu cao cấp và chi tiết tinh xảo. Những đôi giày này sẽ giúp bạn nổi bật giữa đám đông, thể hiện phong cách với sự kết hợp hoàn hảo giữa sự thoải mái và thẩm mỹ thời trang cao cấp.\n
Đây là lựa chọn hoàn hảo cho những ai muốn thêm phần nổi bật và năng động cho bộ sưu tập sneaker của mình với một chút sắc màu neon!'),
('Giày Balenciaga Triple S Trainer Sneakers', 1,3,2,8,'đôi',26900000,'giay_balenciaga_triple_s_trainer_sneakers.jpg',
'Giày Balenciaga Triple S Sneaker White Multi (WMNS) là một món đồ thời trang nổi bật dành cho phái đẹp. Với thiết kế màu trắng đa sắc nổi bật, đôi giày này mang đến một điểm nhấn sống động cho bất kỳ bộ trang phục nào.\n
Được sản xuất bởi thương hiệu nổi tiếng Balenciaga, những đôi giày này không chỉ thời thượng mà còn đảm bảo sự thoải mái và độ bền. Với kiểu dáng này, bạn có thể dễ dàng kết hợp với trang phục hàng ngày và tạo nên một tuyên ngôn thời trang mạnh mẽ.\n
Hãy để Balenciaga Triple S Sneaker White Multi là lựa chọn hoàn hảo cho những ai yêu thích sự cá tính và phong cách trong từng bước đi!'),
('Giày Balenciaga Triple S Sneaker Bred', 1,3,6,9,'đôi',21000000,'giay_balenciaga_triple_s_sneaker_bred.jpg',
'Nâng cao phong cách của bạn với đôi giày Balenciaga Triple S Sneaker ‘Bred’ 516440-W09O7-6576. Những đôi sneaker biểu tượng này nổi bật với bảng màu độc đáo gồm đen, đỏ và trắng, thể hiện sự sang trọng và phong cách đường phố hiện đại.\n
Với thiết kế đế ba lớp, chúng mang lại sự thoải mái và hỗ trợ vượt trội. Hình dáng lớn cùng với logo Balenciaga đã tạo nên một tuyên ngôn thời trang mạnh mẽ. Đây là sự lựa chọn hoàn hảo cho những tín đồ đam mê sneaker, tìm kiếm sự kết hợp giữa thiết kế đương đại và kỹ thuật thủ công cao cấp.\n
Hãy để Balenciaga Triple S Sneaker ‘Bred’ trở thành điểm nhấn nổi bật trong bộ sưu tập giày của bạn, khẳng định phong cách cá nhân và tạo dựng ấn tượng mạnh mẽ trong mọi dịp!'),
('Giày Balenciaga x Adidas 3xl Trainers', 1,3,1,10,'đôi',26100000,'giay_balenciaga_x_adidas_3xl_trainers.jpg',
'Xin giới thiệu giày Balenciaga 3XL Sneaker Worn-Out – Đen Trắng. Đôi giày thời thượng và thanh lịch này từ Balenciaga là bổ sung hoàn hảo cho bộ sưu tập giày dép của bạn. Với thiết kế đen trắng đã qua sử dụng, sản phẩm mang đến một cái nhìn độc đáo và hợp thời trang.\n
Được làm từ các vật liệu chất lượng cao, những đôi giày này không chỉ bền bỉ mà còn cực kỳ thoải mái, giúp bạn dễ dàng sử dụng hàng ngày. Hãy nâng tầm phong cách của bạn với Balenciaga 3XL Sneaker Worn-Out – Black White, sự lựa chọn tuyệt vời cho những ai yêu thích sự mới mẻ và phong cách!'),
('Giày Balenciaga Speed Trainer Mid', 1,3,1,1,'đôi',21500000,'giay_balenciaga_speed_trainer_mid.jpg',
'Giới thiệu mẫu giày Balenciaga Speed Trainer Mid màu Đen. Đôi giày sneaker thanh lịch và phong cách này đến từ thương hiệu nổi tiếng Balenciaga, là sự lựa chọn hoàn hảo cho những ai đề cao sự thoải mái và thời trang.\n
Với thiết kế toàn bộ màu đen, những đôi giày này rất đa dụng và dễ dàng phối hợp với bất kỳ trang phục nào. Nâng tầm phong cách sneaker của bạn với mẫu Balenciaga Speed Trainer Mid màu Đen, mang đến nét quyến rũ và chất riêng cho mỗi bước đi.\n
Hãy để chúng trở thành sự lựa chọn hàng đầu trong bộ sưu tập giày của bạn!'),
('Giày Balenciaga Paris Mules', 1,3,2,2,'đôi',22900000,'giay_balenciaga_paris_mules.jpg',
'Trải nghiệm sự thoải mái tuyệt đối với Nike Flex Experience Run 9 trong màu xám Particle Grey đầy phong cách. Đôi giày chạy này có thiết kế linh hoạt, giúp tiếp xúc tự nhiên và phản hồi tốt với từng bước chân của bạn. Phần trên bằng lưới giữ cho đôi chân luôn thoáng mát, trong khi đế ngoài bền bỉ cung cấp độ bám chắc chắn trên mọi bề mặt.\n
Dù bạn là một vận động viên kỳ cựu hay mới bắt đầu, Nike Flex Experience Run 9 hứa hẹn mang đến một trải nghiệm chạy êm ái và hỗ trợ, giúp bạn chinh phục mọi mục tiêu. Hãy làm mới hành trình chạy bộ của bạn ngay hôm nay!'),
('Giày Balenciaga x Adidas Triple S Blue', 1,3,4,3,'đôi',23500000,'giay_balenciaga_x_adidas_triple_s_blue.jpg',
'Giày adidas x Balenciaga Triple S Blue White mang đến lựa chọn phong cách và thoải mái cho các tín đồ sneaker. Với phối màu xanh và trắng, những đôi giày này rất dễ phối với nhiều trang phục khác nhau, trở thành món phụ kiện lý tưởng cho phong cách hàng ngày.\n
Sự hợp tác giữa adidas và Balenciaga đảm bảo chất lượng thủ công cao cấp cùng với thiết kế thời thượng. Đôi giày này không chỉ là biểu tượng của phong cách mà còn mang lại cảm giác dễ chịu cho đôi chân của bạn. Hãy thêm ngay đôi giày adidas x Balenciaga Triple S vào bộ sưu tập của bạn để nâng tầm phong cách và thể hiện bản thân!'),
('Giày Balenciaga Track Sneaker', 1,3,2,4,'đôi',24900000,'giay_balenciaga_track_sneaker.jpg',
'Chiếc giày Balenciaga Track Sneaker màu trắng là lựa chọn phong cách và thời thượng từ thương hiệu nổi tiếng Balenciaga. Được thiết kế với màu trắng cổ điển, đôi giày này hoàn hảo cho mọi dịp thường ngày hoặc thể thao.\n
Sản phẩm được làm từ các vật liệu cao cấp, mang đến sự thoải mái và độ bền cho việc sử dụng lâu dài. Hãy nâng cấp bộ sưu tập giày dép của bạn với những đôi sneaker trắng thời thượng này từ Balenciaga. Với Balenciaga Track Sneaker, bạn sẽ tự tin thể hiện phong cách cá nhân trong mọi hoạt động!'),
('Giày Balenciaga Triple S Sneaker Clear Sole White Green', 1,3,5,5,'đôi',29000000,'giay_balenciaga_triple_s_sneaker_clear_sole_white_green.jpg',
'Khám phá phong cách tiên phong với đôi giày Balenciaga Triple S Sneaker ‘Clear Sole – White Green’. Mẫu giày biểu tượng này kết hợp tỷ lệ táo bạo với đế cao su trong suốt, tạo ra một ấn tượng thị giác nổi bật.\n
Được chế tác từ da mềm mại và lưới, sản phẩm sở hữu phần mũi giày lưới nhiều lớp và các tiện ích mang thương hiệu, mang lại một chút tinh tế cho thiết kế. Đế chunky, điêu khắc không chỉ nâng tầm phong cách của bạn mà còn màu sắc nổi bật góp phần tạo nên sự vui tươi cho bất kỳ trang phục nào.\n
Nâng cao phong cách streetwear của bạn với đôi giày này, một tuyên ngôn thời trang đầy ấn tượng và độc đáo dành cho những tín đồ yêu thích phong cách cá tính!'),
('Giày Balenciaga Runner Trainers ', 1,3,4,6,'đôi',32000000,'giay_balenciaga_runner_trainers.jpg',
'Nâng cấp bộ sưu tập giày sneaker của bạn với mẫu Balenciaga Runner Sneaker màu “Blue” (677403W3RB34912). Kết hợp thiết kế avant-garde với độ thoải mái hàng đầu, đôi giày này nổi bật với sắc xanh nổi bật và những chi tiết tinh xảo, khiến mọi bộ trang phục trở nên cuốn hút hơn.\n
Chúng hoàn hảo cho những ai yêu thích phong cách thời thượng, với cấu trúc đa vật liệu độc đáo, đế cao su bền bỉ và logo đặc trưng của thương hiệu. Dù là để đi chơi hàng ngày hay thể hiện phong cách đường phố, những đôi sneaker Balenciaga này sẽ giúp bạn nổi bật một cách dễ dàng.\n
Trải nghiệm sự sang trọng vô song và tạo nên phong cách ấn tượng với Balenciaga Runner Sneaker màu xanh, một lựa chọn không thể thiếu cho những tín đồ thời trang.'),
('Giày Balenciaga Track.2 Trainer Khaki', 1,3,5,7,'đôi',29000000,'giay_balenciaga_track.2_trainer_khaki.jpg',
'Giới thiệu mẫu giày Balenciaga Track.2 Sneaker trong tông màu Khaki Đen. Đôi giày thời thượng này của Balenciaga sở hữu sự kết hợp độc đáo giữa màu xanh khaki và màu đen, là lựa chọn hoàn hảo cho bất kỳ ai hướng tới phong cách thời trang đậm chất streetwear.\n
Với thiết kế hiện đại và vừa vặn thoải mái, những đôi sneakers này chắc chắn sẽ nâng tầm phong cách của bạn. Đừng bỏ lỡ cơ hội thêm mẫu giày không thể thiếu này vào bộ sưu tập của bạn.\n
Hãy để Balenciaga Track.2 Khaki Black trở thành điểm nhấn cho trang phục của bạn và thể hiện cá tính thời trang độc đáo của bản thân!'),
('Dép Balenciaga x Adidas Speed 2.0 Recycle', 3,3,3,8,'đôi',27900000,'dep_balenciaga_x_adidas_speed_2.0_recycle.jpg',
'Nâng tầm phong cách thể thao của bạn với đôi giày Balenciaga Speed 2.0 ‘Fluorescent Pink’ đầy nổi bật dành cho nữ. Mẫu giày này nổi bật với gam màu hồng phát quang mắt bắt, cùng với dây giày đen tương phản và logo Balenciaga đặc trưng trên lưỡi gà và gót giày.\n
Được làm từ chất liệu lưới thoáng khí và đế cao su bền bỉ, đôi giày này vừa mang lại sự thoải mái trong từng bước đi, vừa đảm bảo độ bền lâu dài. Bước vào tâm điểm ánh nhìn với những đôi giày nổi bật này, sẽ là điểm nhấn hoàn hảo để làm mới tủ đồ của bạn và thu hút mọi ánh nhìn ở bất kỳ đâu bạn đi qua!\n
Hãy để Balenciaga Speed 2.0 ‘Fluorescent Pink’ trở thành biểu tượng phong cách thể thao của bạn và khẳng định sự tự tin trong từng hoạt động!');

-- dử liêu cho bảng sản phẩm hot
INSERT INTO `sanphamhot` (`magiay`,`giakhuyenmai`) VALUES
(47,20),
(33,40),
(18,40),
(7,50),
(50,10),
(21,20),
(43,30),
(10,40),
(6,60),
(14,10);