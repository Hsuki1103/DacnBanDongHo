-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th12 28, 2024 lúc 08:34 AM
-- Phiên bản máy phục vụ: 8.3.0
-- Phiên bản PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `donghoshop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitiet_donhang`
--

DROP TABLE IF EXISTS `chitiet_donhang`;
CREATE TABLE IF NOT EXISTS `chitiet_donhang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `madh` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tensp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hinhanhsp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `giasp` decimal(10,2) NOT NULL,
  `soluong` int NOT NULL,
  `tonggia` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dh_ctdh` (`order_id`),
  KEY `fk_sach_ctdh` (`madh`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitiet_giohang`
--

DROP TABLE IF EXISTS `chitiet_giohang`;
CREATE TABLE IF NOT EXISTS `chitiet_giohang` (
  `id_chitiet` int NOT NULL AUTO_INCREMENT,
  `id_giohang` int NOT NULL,
  `id_dongho` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `soluong` int NOT NULL,
  `price` decimal(30,2) NOT NULL,
  PRIMARY KEY (`id_chitiet`),
  KEY `fk_gh_ctgh` (`id_giohang`),
  KEY `fk_sach_ctgh` (`id_dongho`)
) ENGINE=InnoDB AUTO_INCREMENT=282 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitiet_giohang`
--

INSERT INTO `chitiet_giohang` (`id_chitiet`, `id_giohang`, `id_dongho`, `soluong`, `price`) VALUES
(281, 139, 'rl001', 1, 0.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitiet_hoadon`
--

DROP TABLE IF EXISTS `chitiet_hoadon`;
CREATE TABLE IF NOT EXISTS `chitiet_hoadon` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mahd` int NOT NULL,
  `masp` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `soluongsp` int NOT NULL,
  `dongia` decimal(30,2) NOT NULL,
  `tongtien` decimal(30,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cthd_hoadon` (`mahd`),
  KEY `fk_s_cthd` (`masp`)
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitiet_hoadon`
--

INSERT INTO `chitiet_hoadon` (`id`, `mahd`, `masp`, `soluongsp`, `dongia`, `tongtien`) VALUES
(196, 133, 'og003', 11, 98000000.00, 1078000000.00),
(197, 134, 'og003', 4, 98000000.00, 392000000.00),
(198, 134, 'og002', 1, 129000000.00, 129000000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dongho`
--

DROP TABLE IF EXISTS `dongho`;
CREATE TABLE IF NOT EXISTS `dongho` (
  `madh` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tendh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gia` int NOT NULL,
  `mota` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hinhanh` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `so_luong` int NOT NULL,
  `mahang` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `soseri` text COLLATE utf8mb4_general_ci NOT NULL,
  `ngaythem` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`madh`),
  KEY `fk_danhmuc_sach` (`mahang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `dongho`
--

INSERT INTO `dongho` (`madh`, `tendh`, `gia`, `mota`, `hinhanh`, `so_luong`, `mahang`, `soseri`, `ngaythem`) VALUES
('cs001', 'Casio MTP-1374L-1AVDF', 1702000, 'Xuất xứ: Nhật Bản\r\nĐối tượng: Nam\r\nDòng sản phẩm: Casio MTP\r\nKháng nước: 5atm\r\nLoại máy: Pin/Quartz\r\nChất liệu kính: Kính Khoáng\r\nChất liệu dây: Dây Da\r\nSize mặt: 43.5mm\r\nĐộ dầy: 10.4mm\r\nSeries: Casio MTP 1374\r\nTiện ích: Dạ quang, Lịch thứ, Lịch ngày, Giờ, phút, giây, Lịch 24 giờ', 't1.jpg', 10, 'mh001', '1XXXX', '2024-12-15 03:56:33'),
('cs002', 'Casio AE-1200WHD-1AVDF', 1129000, 'Xuất xứ: Nhật Bản\r\nĐối tượng: Nam\r\nKháng nước: 10atm\r\nLoại máy: Pin/Quartz\r\nChất liệu kính: Kính Nhựa\r\nChất liệu dây: Dây Thép Không Gỉ\r\nSize mặt: 45 x 42.1 mm\r\nĐộ dầy: 12.5mm\r\nSeries: Casio AE1200\r\nTiện ích: World Time, Báo thức, Đồng hồ bấm giờ, La bàn, Lịch thứ, Lịch ngày, Giờ, phút, giây', 't2.jpg', 10, 'mh001', '2XXXX', '2024-12-15 03:58:47'),
('cs003', 'Casio LTP-1274D-7BDF', 862000, 'Xuất xứ: Nhật Bản\r\nĐối tượng: Nữ\r\nDòng sản phẩm: Casio LTP\r\nKháng nước: 3atm\r\nLoại máy: Pin/Quartz\r\nChất liệu kính: Kính Khoáng\r\nChất liệu dây: Dây Kim Loại\r\nSize mặt: 22mm\r\nĐộ dầy: 8mm\r\nTiện ích: Giờ, phút, giây', 't3.jpg', 10, 'mh001', '3XXXX', '2024-12-15 04:00:07'),
('cs004', 'Casio MTP-VT01GL-2BUDF', 862000, 'Xuất xứ: Nhật Bản\r\nĐối tượng: Nam\r\nDòng sản phẩm: Casio MTP\r\nKháng nước: 3atm\r\nLoại máy: Pin/Quartz\r\nChất liệu kính: Kính Khoáng\r\nChất liệu dây: Dây Da\r\nSize mặt: 40mm\r\nĐộ dầy: 8.2mm\r\nSeries: Casio MTP VT01\r\nTiện ích: Giờ, phút, giây', 't4.jpg', 10, 'mh001', '4XXXX', '2024-12-15 04:00:51'),
('og001', 'Omega 210.30.42.20.01.001', 126000000, 'Xuất xứ: Thụy Sỹ\r\nĐối tượng: Nam\r\nDòng sản phẩm: Omega Seamaster\r\nKháng nước: 30atm\r\nLoại máy: Cơ tự động\r\nChất liệu kính: Kính Sapphire\r\nChất liệu dây: Dây Kim Loại\r\nSize mặt: 42mm\r\nĐộ dầy: 11mm\r\nKhoảng trữ cót: 55 tiếng\r\nTiện ích: Dạ quang, Lịch ngày, Giờ, phút, giây, Chronometer', 't11.jpg', 10, 'mh004', '5XXXX', '2024-12-17 02:25:53'),
('og002', 'Omega 210.30.42.20.03.001', 129000000, 'Xuất xứ: Thụy Sỹ\r\nĐối tượng: Nam\r\nKháng nước: 30atm\r\nLoại máy: Cơ tự động\r\nChất liệu kính: Kính Sapphire\r\nChất liệu dây: Dây Kim Loại\r\nSize mặt: 42mm\r\nĐộ dầy: 13.5mm\r\nKhoảng trữ cót: 55 tiếng\r\nTiện ích: Dạ quang, Lịch ngày, Giờ, phút, giây', 't12.jpg', 10, 'mh004', '6XXXX', '2024-12-17 02:26:48'),
('og003', 'Omega 424.20.37.20.03.002', 98000000, 'Xuất xứ: Thụy Sỹ\r\nĐối tượng: Unisex\r\nDòng sản phẩm: Omega De ville\r\nKháng nước: 3atm\r\nLoại máy: Cơ tự động\r\nChất liệu kính: Kính Sapphire\r\nChất liệu dây: Dây Vàng & thép không gỉ\r\nSize mặt: 36.8mm\r\nĐộ dầy: 9mm\r\nKhoảng trữ cót: 48 tiếng\r\nTiện ích: Lịch ngày, Giờ, phút, giây, Chronometer', 't13.jpg', 10, 'mh004', '7XXXX', '2024-12-17 02:27:37'),
('rl001', 'Rolex M126333-0012', 427500000, 'Xuất xứ: Thụy Sỹ\r\nĐối tượng: Nam\r\nDòng sản phẩm: Rolex Datejust\r\nKháng nước: 10atm\r\nLoại máy: Cơ tự động\r\nChất liệu kính: Kính Sapphire\r\nChất liệu dây: Dây Vàng & thép không gỉ\r\nSize mặt: 41mm\r\nKhoảng trữ cót: 70 tiếng\r\nBộ máy: Calibre 3235\r\nKiểu mặt: Guilloche , Đính đá\r\nTiện ích: Lịch ngày, Giờ, phút, giây, Chronometer', 't9.jpg', 10, 'mh003', '8XXXX', '2024-12-17 02:23:15'),
('rl002', 'Rolex M126334-0026', 308750000, 'Xuất xứ: Thụy Sỹ\r\nĐối tượng: Nam\r\nDòng sản phẩm: Rolex Datejust\r\nKháng nước: 10atm\r\nLoại máy: Cơ tự động\r\nChất liệu kính: Kính Sapphire\r\nChất liệu dây: Dây Thép Không Gỉ\r\nSize mặt: 41mm\r\nKhoảng trữ cót: 70 tiếng\r\nBộ máy: Calibre 3235\r\nKiểu mặt: Guilloche\r\nTiện ích: Lịch ngày, Giờ, phút, giây, Chronometer', 't10.jpg', 10, 'mh003', '9XXXX', '2024-12-17 02:24:16'),
('rl003', 'Rolex M128345RBR-0044', 760000000, 'Xuất xứ: Thụy Sỹ\r\nĐối tượng: Nam\r\nDòng sản phẩm: Rolex Day-Date\r\nKháng nước: 10atm\r\nLoại máy: Cơ tự động\r\nChất liệu kính: Kính Sapphire\r\nChất liệu dây: Vàng Hồng 18K\r\nSize mặt: 36mm\r\nKhoảng trữ cót: 70 tiếng\r\nBộ máy: Calibre 3255\r\nKiểu mặt: Guilloche , Đính đá\r\nTiện ích: Lịch thứ, Lịch ngày, Giờ, phút, giây', 't14.jpg', 10, 'mh003', '10XXX', '2024-12-17 02:29:00'),
('sk001', 'Seiko SRPD53K1', 595000, 'Xuất xứ: Nhật Bản\r\nĐối tượng: Nam\r\nDòng sản phẩm: Seiko 5 Sport\r\nKháng nước: 10atm\r\nLoại máy: Cơ tự động\r\nChất liệu kính: Hardlex Crystal\r\nChất liệu dây: Dây Kim Loại\r\nSize mặt: 42.5mm\r\nĐộ dầy: 13.4mm\r\nKhoảng trữ cót: 41 tiếng\r\nBộ máy: 4R36A\r\nKiểu mặt: Guilloche\r\nTiện ích: Dạ quang, Lịch thứ, Lịch ngày, Giờ, phút, giây', 't1.jpg', 10, 'mh002', '11XXX', '2024-12-15 04:01:30'),
('sk002', 'Seiko SNKE04K1	', 5700000, 'Xuất xứ: Nhật Bản\r\nĐối tượng: Nam\r\nKháng nước: 5atm\r\nLoại máy: Cơ tự động\r\nChất liệu kính: Hardlex Crystal\r\nChất liệu dây: Dây Kim Loại\r\nSize mặt: 38mm\r\nĐộ dầy: 11mm\r\nKhoảng trữ cót: 40 tiếng\r\nTiện ích: Dạ quang, Lịch thứ, Lịch ngày, Giờ, phút, giây', 't6.jpg', 10, 'mh002', '12XXX', '2024-12-15 04:02:48'),
('sk003', 'Seiko SNKK11K1', 3825000, 'Xuất xứ: Nhật Bản\r\nĐối tượng: Nam\r\nDòng sản phẩm: Seiko 5\r\nKháng nước: 3atm\r\nLoại máy: Cơ tự động\r\nChất liệu kính: Hardlex Crystal\r\nChất liệu dây: Dây Kim Loại\r\nSize mặt: 38mm\r\nĐộ dầy: 11mm\r\nKhoảng trữ cót: 40 tiếng\r\nTiện ích: Dạ quang, Lịch thứ, Lịch ngày, Giờ, phút, giây', 't7.jpg', 10, 'mh002', '13XXX', '2024-12-17 02:12:29'),
('sk004', 'Seiko SSA377J1', 13125000, 'Xuất xứ: Nhật Bản\r\nĐối tượng: Nam\r\nDòng sản phẩm: Seiko Presage\r\nKháng nước: 3atm\r\nLoại máy: Cơ tự động\r\nChất liệu kính: Kính Sapphire\r\nChất liệu dây: Dây Kim Loại\r\nSize mặt: 42mm\r\nĐộ dầy: 12mm\r\nKhoảng trữ cót: 40 tiếng\r\nKiểu mặt: Số La Mã\r\nTiện ích: Giờ, phút, giây', 't8.jpg', 10, 'mh002', '14XXX', '2024-12-17 02:20:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhang`
--

DROP TABLE IF EXISTS `donhang`;
CREATE TABLE IF NOT EXISTS `donhang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `makh` int NOT NULL,
  `mahd` int NOT NULL,
  `tonggia` decimal(10,2) NOT NULL,
  `soluong` int NOT NULL,
  `trangthai_vanchuyen` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ngaydat` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dh_kh` (`makh`),
  KEY `fk_dh_hd` (`mahd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giohang`
--

DROP TABLE IF EXISTS `giohang`;
CREATE TABLE IF NOT EXISTS `giohang` (
  `id_giohang` int NOT NULL AUTO_INCREMENT,
  `madh` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `session_id` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `soluong_gh` int NOT NULL,
  `id_taikhoan` int DEFAULT NULL,
  PRIMARY KEY (`id_giohang`),
  UNIQUE KEY `id_taikhoan` (`id_taikhoan`),
  KEY `fk_kh_gh` (`id_taikhoan`)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `giohang`
--

INSERT INTO `giohang` (`id_giohang`, `madh`, `session_id`, `soluong_gh`, `id_taikhoan`) VALUES
(139, '', '', 0, 18);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hangdh`
--

DROP TABLE IF EXISTS `hangdh`;
CREATE TABLE IF NOT EXISTS `hangdh` (
  `mahang` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tenhang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`mahang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hangdh`
--

INSERT INTO `hangdh` (`mahang`, `tenhang`) VALUES
('mh001', 'Casio'),
('mh002', 'Seiko'),
('mh003', 'Rolex'),
('mh004', 'Omega');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoadon`
--

DROP TABLE IF EXISTS `hoadon`;
CREATE TABLE IF NOT EXISTS `hoadon` (
  `mahd` int NOT NULL AUTO_INCREMENT,
  `tenkh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `emailkh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sdt` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `diachi` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tongsoluong` decimal(10,0) NOT NULL,
  `tongtienhd` decimal(30,0) NOT NULL,
  `ngaylaphd` datetime NOT NULL,
  `trangthai` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`mahd`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hoadon`
--

INSERT INTO `hoadon` (`mahd`, `tenkh`, `emailkh`, `sdt`, `diachi`, `tongsoluong`, `tongtienhd`, `ngaylaphd`, `trangthai`) VALUES
(87, '1', '0@gmail.com', '0398491113', '1234567', 2, 2023000, '2024-12-14 10:20:02', 'Đang xử lý'),
(88, '1', 'hsukihsuki001@gmail.com', '0398491113', '1234567', 12, 11316000, '2024-12-14 19:59:16', 'Đang xử lý'),
(104, '1', 'hsukihsuki04@gmail.com', '1', '1', 1, 98032000, '2024-12-19 10:46:02', 'Đang xử lý'),
(122, '1', 'hsukihsuki04@gmail.com', '1', '1', 2, 858032000, '2024-12-19 13:38:13', 'Đang xử lý'),
(123, '1', 'hsukihsuki04@gmail.com', '1', '1', 1, 129032000, '2024-12-19 13:42:52', 'Đang xử lý'),
(124, '1', 'hsukihsuki04@gmail.com', '1', '1', 6, 678032000, '2024-12-19 13:59:56', 'Đang xử lý'),
(125, '1', 'hsukihsuki04@gmail.com', '1', '1', 3, 6922000, '2024-12-19 14:16:14', 'Đang xử lý'),
(126, '1', 'hsukihsuki04@gmail.com', '1', '1', 5, 1898032000, '2024-12-19 14:17:09', 'Đang xử lý'),
(127, '1', 'hsukihsuki04@gmail.com', '1', '1', 5, 1898032000, '2024-12-19 14:17:15', 'Đang xử lý'),
(133, '1', 'hsukihsuki04@gmail.com', '1', '1', 11, 1078032000, '2024-12-20 07:52:29', 'Đang xử lý'),
(134, '1', 'hsukihsuki04@gmail.com', '1', '1', 5, 521032000, '2024-12-20 18:24:01', 'Đang xử lý');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachhang`
--

DROP TABLE IF EXISTS `khachhang`;
CREATE TABLE IF NOT EXISTS `khachhang` (
  `makh` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hotenkh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sdt` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `diachi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`makh`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khachhang`
--

INSERT INTO `khachhang` (`makh`, `email`, `password`, `hotenkh`, `sdt`, `diachi`, `avatar`) VALUES
(8, 'nguyenhieushanley2210@gmail.com', '$2y$10$F6sAisUeUbhF0SMvHpspkObHlBDzaA1/BAZpKuYJMQxA9oZ2vteni', 'Nguyễn Trung Hiếu 1', '0326780829', 'Hưng lễ 1', '../account/avatar/default_avatar.jpg'),
(18, '0@gmail.com', '$2y$10$1uZMBPdP1MJJn1bjl67VPu/1GasgG0/joMKLqgBilsfUx2/sL3Ocq', 'GT', '1', '12', '../account/avatar/t1.jpg');

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitiet_donhang`
--
ALTER TABLE `chitiet_donhang`
  ADD CONSTRAINT `fk_dh_ctdh` FOREIGN KEY (`order_id`) REFERENCES `donhang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sach_ctdh` FOREIGN KEY (`madh`) REFERENCES `dongho` (`madh`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `chitiet_giohang`
--
ALTER TABLE `chitiet_giohang`
  ADD CONSTRAINT `fk_gh_ctgh` FOREIGN KEY (`id_giohang`) REFERENCES `giohang` (`id_giohang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sach_ctgh` FOREIGN KEY (`id_dongho`) REFERENCES `dongho` (`madh`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `chitiet_hoadon`
--
ALTER TABLE `chitiet_hoadon`
  ADD CONSTRAINT `fk_cthd_hoadon` FOREIGN KEY (`mahd`) REFERENCES `hoadon` (`mahd`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_s_cthd` FOREIGN KEY (`masp`) REFERENCES `dongho` (`madh`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `dongho`
--
ALTER TABLE `dongho`
  ADD CONSTRAINT `fk_danhmuc_sach` FOREIGN KEY (`mahang`) REFERENCES `hangdh` (`mahang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `fk_dh_hd` FOREIGN KEY (`mahd`) REFERENCES `hoadon` (`mahd`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dh_kh` FOREIGN KEY (`makh`) REFERENCES `khachhang` (`makh`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `fk_kh_gh` FOREIGN KEY (`id_taikhoan`) REFERENCES `khachhang` (`makh`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
