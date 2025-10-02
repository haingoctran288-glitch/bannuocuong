-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 18, 2025 lúc 05:53 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `gecafe`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`) VALUES
(1, 14),
(2, 20),
(4, 23),
(5, 25),
(6, 26);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_item`
--

CREATE TABLE `cart_item` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart_item`
--

INSERT INTO `cart_item` (`cart_item_id`, `cart_id`, `product_id`, `quantity`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 5, 1),
(25, 3, 1, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `name_category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`category_id`, `name_category`) VALUES
(1, 'trà sữa'),
(2, 'coffe'),
(3, 'trà hoa quả đặt biệt'),
(4, 'trà olong'),
(5, 'sữa tươi'),
(6, 'trà trái cây'),
(7, 'món nóng'),
(8, 'đá xay'),
(9, 'trà'),
(10, 'cà phê phin'),
(11, 'cà phê hạt'),
(12, 'Olong');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `admin_reply` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`comment_id`, `product_id`, `user_id`, `comment_text`, `admin_reply`, `created_at`) VALUES
(1, 1, 20, 'ngon ', 'Cảm ơn bạn', '2024-12-08 06:56:25'),
(2, 2, 20, 'ngon', NULL, '2024-12-08 06:58:45'),
(3, 5, 20, 'ngon', NULL, '2024-12-08 06:59:37'),
(6, 28, 20, 'Ngon', NULL, '2024-12-09 07:19:08'),
(7, 7, 20, 'ngon', NULL, '2024-12-10 06:15:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int(11) NOT NULL,
  `invoice_date` date NOT NULL,
  `payment_status` varchar(25) NOT NULL,
  `total_amount` decimal(10,3) NOT NULL,
  `due_date` date NOT NULL,
  `billing_address` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `invoice`
--

INSERT INTO `invoice` (`invoice_id`, `invoice_date`, `payment_status`, `total_amount`, `due_date`, `billing_address`, `user_id`, `recipient_name`, `phone`, `notes`) VALUES
(87, '2024-12-09', 'Thanh toán thành công', 30.000, '2024-12-16', 'Đà Nẵng', 20, 'Tiến Đạt', '0973793847', ''),
(89, '2024-12-09', 'Thanh toán thành công', 30.000, '2024-12-16', 'Đà Nẵng', 20, 'Tiến Đạt', '0973793847', ''),
(90, '2024-12-09', 'Thanh toán thành công', 176.000, '2024-12-16', 'Quảng Nam', 20, 'Tiến Đạt', '0973793847', ''),
(91, '2024-12-10', 'Chưa Thanh Toán', 58.000, '2024-12-17', 'Đà Nẵng', 20, 'Tiến Đạt', '0973793847', ''),
(92, '2025-07-18', 'Thanh toán thành công', 90.000, '2025-07-25', 'Đà Nẵng', 25, 'Nguyễn Hoàng Phi Hồng', '247878975389', 'sadas'),
(93, '2025-07-18', 'Thanh toán thành công', 291.000, '2025-07-25', 'đâs', 26, 'sdadas', 'dsadas', 'áddasd');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `invoice_detail`
--

CREATE TABLE `invoice_detail` (
  `invoice_detail_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,3) NOT NULL,
  `total_price` decimal(10,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `invoice_detail`
--

INSERT INTO `invoice_detail` (`invoice_detail_id`, `invoice_id`, `product_id`, `quantity`, `price`, `total_price`) VALUES
(35, 87, 7, 1, 30.000, 30.000),
(37, 89, 5, 1, 30.000, 30.000),
(38, 90, 34, 8, 22.000, 176.000),
(39, 91, 1, 1, 28.000, 28.000),
(40, 91, 7, 1, 30.000, 30.000),
(41, 92, 51, 1, 90.000, 90.000),
(42, 93, 28, 3, 30.000, 90.000),
(43, 93, 5, 4, 30.000, 120.000),
(44, 93, 8, 3, 27.000, 81.000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name_product` varchar(50) NOT NULL,
  `price` decimal(10,3) NOT NULL,
  `description` text NOT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`product_id`, `category_id`, `name_product`, `price`, `description`, `address`) VALUES
(1, 1, 'Trà Đào Sữa', 28.000, 'Trà Đào Sữa', 'uploadFiles/1732821097_Tra-Dao-Sua.png'),
(2, 1, 'Trà Sữa Thái Xanh', 36.000, 'Trà Sữa Bee Thái Xanh', 'uploadFiles/1732821176_Tra-Sua-Bee-Thai-Xanh.png'),
(5, 1, 'Trà Sữa Bee Truyền Thống', 30.000, 'Trà Sữa Bee Truyền Thống', 'uploadFiles/1732821282_Tra-Sua-Bee-Truyen-Thong.png'),
(7, 1, 'Trà Sữa Truyền Thống', 30.000, 'Trà Sữa Truyền Thống Đặc Biệt', 'uploadFiles/1732821504_Tra-Sua-Dac-Biet-Truyen-Thong.png'),
(8, 1, 'Trà Sữa Khoai Môn', 27.000, 'Trà Sữa Khoai Môn', 'uploadFiles/Tra-Sua-Khoai-Mon-Tran-Chau.png'),
(9, 1, 'Trà Sữa SOCOLA', 27.000, 'Trà Sữa SOCOLA', 'uploadFiles/Tra-Sua-Socola-Tran-Chau.png'),
(10, 2, 'Bạc Xỉu', 22.000, 'Bạc Xỉu', 'uploadFiles/Bac-Xiu.png'),
(11, 2, 'Cà Phê Dừa', 30.000, 'Cà Phê Dừa', 'uploadFiles/Ca-Phe-Dua.png'),
(13, 2, 'Cà Phê Đen SG', 20.000, 'Cà Phê Đen Sài Gòn', 'uploadFiles/Ca-Phe-Den-Sai-Gon.png'),
(14, 2, 'Cà Phê Kem Trứng', 28.000, 'Cà Phê Kem Trứng', 'uploadFiles/Ca-Phe-Kem-Trung-Nuong.png'),
(15, 2, 'Cà Phê Sữa Máy', 18.000, 'Cà Phê Sữa Máy', 'uploadFiles/Ca-Phe-Sua-May.png'),
(16, 3, 'Trà Cam Đào Dâu Tây', 38.000, 'Trà Cam Đào Dâu Tây', 'uploadFiles/Tra-Cam-Dao-Dau-Tay.png'),
(17, 3, 'Trà Chanh Dây', 38.000, 'Trà Chanh Dây', 'uploadFiles/Tra-Chanh-Day-Nhiet-Doi.png'),
(18, 3, 'Trà Hoa Atiso', 30.000, 'Trà Hoa Atiso', 'uploadFiles/Tra-Hoa-Lac-Than-Hat-Chia.png'),
(19, 3, 'Trà Mãng Cầu', 35.000, 'Trà Mãng Cầu', 'uploadFiles/Tra-Mang-Cau.png'),
(20, 3, 'Trà Matcha Xoài', 38.000, 'Trà Matcha Xoài', 'uploadFiles/Tra-Matcha-Xoai.png'),
(21, 3, 'Trà Ổi Hồng', 38.000, 'Trà Ổi Hồng Nam Vương', 'uploadFiles/Tra-Oi-Hong-Nam-Duong.png'),
(22, 3, 'Trà RuBy Cam Đào', 38.000, 'Trà RuBy Cam Đào', 'uploadFiles/Tra-RuBy-Cam-Dao.png'),
(23, 3, 'Trà Xoài', 38.000, 'Trà Xoài Anh Quốc', 'uploadFiles/Tra-Xoai-Anh-Quoc.png'),
(24, 4, 'OLong Gạo', 40.000, 'OLong Gạo Rang Nhật Trân Châu ', 'uploadFiles/Olong-Gao-Rang-Nhat-Tran-Chau.png'),
(25, 4, 'OLong Hạt Sen', 32.000, 'OLong Hạt Sen Vàng Kem Sữa', 'uploadFiles/Olong-Gao-Rang-Sen-Vang-Kem-Sua.png'),
(26, 4, 'OLong Nhài', 40.000, 'OLong Nhài Sữa Trân Châu', 'uploadFiles/Olong-Nhai-Sua-Tran-Chau.png'),
(27, 4, 'OLong Sữa', 40.000, 'OLong Sữa Bee Trân Châu', 'uploadFiles/Olong-Sua-Bee-Tran-Chau.png'),
(28, 5, 'Matcha Latte Dâu', 30.000, 'Matcha Latte Dâu', 'uploadFiles/Matcha-Latte-Dau.png'),
(29, 5, 'Matcha Latte Xoài', 30.000, 'Matcha Latte Xoài', 'uploadFiles/Matcha-Latte-Xoai.png'),
(30, 5, 'SOCOLa Sữa ', 30.000, 'SOCOLa Sữa  Trân Châu Đường Đen', 'uploadFiles/Socola-Sua-Tran-Chau-Duong-Den.png'),
(31, 5, 'Sữa Tươi Sương Sáo', 28.000, 'Sữa Tươi Sương Sáo Đường Đen', 'uploadFiles/Sua-Tuoi-Suong-Sao-Duong-Den.png'),
(32, 5, 'Sữa Tươi Kem Trứng', 35.000, 'Sữa Tươi Trân Châu Đường Đen Kem Trứng Nướng', 'uploadFiles/Sua-Tuoi-Tran-Chau-Duong-Den-Kem-Trung-Nuong.png'),
(33, 6, 'Trà Chanh Mật Ong', 22.000, 'Trà Chanh Mật Ong Đào Gừng', 'uploadFiles/Tra-Chanh-Mat-Ong-Dao-Gung.png'),
(34, 6, 'Trà Chanh Nha Đam', 22.000, 'Trà Chanh Nha Đam', 'uploadFiles/Tra-Chanh-Nha-Dam.png'),
(35, 6, 'Trà Chanh Trân Châu', 22.000, 'Trà Chanh Trân Châu', 'uploadFiles/Tra-Chanh-Tran-Chau.png'),
(36, 6, 'Trà Đào', 27.000, 'Trà Đào', 'uploadFiles/Tra-Dao.png'),
(37, 6, 'Trà Ổi Hồng', 27.000, 'Trà ổi Hồng', 'uploadFiles/Tra-Oi-Hong.png'),
(38, 6, 'Trà Vải', 27.000, 'Trà Vải', 'uploadFiles/Tra-Vai-1.png'),
(39, 6, 'Trà Xoài Chanh Leo', 35.000, 'Trà Xoài Chanh Leo', 'uploadFiles/Tra-Xoai-Chanh-Leo.png'),
(40, 7, 'Trà Đào Kim Quất', 30.000, '30,000', 'uploadFiles/Tra-Dao-Kim-Quat.png'),
(41, 7, 'Trà Gừng', 22.000, 'Trà Gừng', 'uploadFiles/Tra-Gung.png'),
(42, 7, 'Trà Gừng Kim Quất', 30.000, 'Trà Gừng Kim Quất', 'uploadFiles/Tra-Gung-Kim-Quat.png'),
(43, 8, 'Chanh Dây Đá Xay', 28.000, 'Chanh Dây Đá Xay', 'uploadFiles/Chanh-Day-Da-Xay.png'),
(44, 8, 'KiWi Đá Xay', 28.000, 'KiWi Đá Xay', 'uploadFiles/Kiwi-Da-Xay.png'),
(46, 8, 'SOCOLA Đá Xay', 30.000, 'SOCOLA Đá Xay', 'uploadFiles/Socola-Da-Xay.png'),
(47, 8, 'Việt Quất Đá Xay', 28.000, 'Việt Quất Đá Xay', 'uploadFiles/Viet-Quat-Da-Xay.png'),
(48, 9, 'Bột Sữa Bee', 90.000, 'Bột Sữa Bee', 'uploadFiles/Bot-Sua-Bee.png'),
(49, 10, 'Cà Phê Phin Đen', 95.000, 'Cà Phê Phin Đen', 'uploadFiles/Dong-Ca-Phe-Phin-1.png'),
(50, 9, 'Lục Trà Bee', 80.000, 'Lục Trà Bee', 'uploadFiles/Luc-Tra-Bee.png'),
(51, 9, 'Hồng Trà Bee', 90.000, 'Hồng Trà Bee', 'uploadFiles/Hong-Tra-Bee-Thuong-Hang.png'),
(53, 11, 'Cà phê Hạt Rang Bee', 100.000, 'Cà phê Hạt Rang Bee', 'uploadFiles/Dong-Ca-Phe-Hat-Rang-1.png'),
(54, 12, 'Olong Nhài Bee', 120.000, 'Olong Nhài Bee', 'uploadFiles/Olong-Hoa-Nhai-1.png'),
(55, 12, 'Olong Bee Gạo Rang', 12.000, 'Olong Bee Gạo Rang', 'uploadFiles/Olong-Bee-Gao-Rang-Nhat.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `productdetail`
--

CREATE TABLE `productdetail` (
  `product_detail_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `ice_level` enum('Ít','Bình thường','Nhiều') NOT NULL,
  `sweetness_level` enum('Ít','Bình thường','Nhiều') NOT NULL,
  `size` enum('M','L','XL') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `productdetail`
--

INSERT INTO `productdetail` (`product_detail_id`, `product_id`, `ice_level`, `sweetness_level`, `size`) VALUES
(3, 1, 'Bình thường', 'Bình thường', 'L'),
(4, 1, 'Bình thường', 'Bình thường', 'L'),
(5, 1, 'Bình thường', 'Bình thường', 'L'),
(6, 1, 'Bình thường', 'Bình thường', 'L'),
(7, 1, 'Bình thường', 'Bình thường', 'L'),
(8, 1, 'Bình thường', 'Bình thường', 'L'),
(9, 1, 'Bình thường', 'Bình thường', 'L'),
(10, 1, 'Bình thường', 'Bình thường', 'L'),
(11, 2, 'Bình thường', 'Bình thường', 'L'),
(12, 5, 'Bình thường', 'Bình thường', 'L'),
(13, 1, 'Bình thường', 'Bình thường', 'L'),
(14, 2, 'Bình thường', 'Bình thường', 'L'),
(15, 2, 'Bình thường', 'Bình thường', 'L'),
(16, 5, 'Bình thường', 'Bình thường', 'L'),
(19, 1, 'Bình thường', 'Bình thường', 'L'),
(20, 1, 'Bình thường', 'Bình thường', 'L'),
(21, 1, 'Bình thường', 'Bình thường', 'L'),
(22, 1, 'Bình thường', 'Bình thường', 'L'),
(23, 2, 'Bình thường', 'Bình thường', 'L'),
(24, 1, 'Bình thường', 'Bình thường', 'L'),
(25, 1, 'Nhiều', 'Nhiều', 'M'),
(26, 1, 'Bình thường', 'Bình thường', 'L'),
(27, 1, 'Nhiều', 'Nhiều', 'M'),
(28, 2, 'Nhiều', 'Nhiều', 'L'),
(29, 2, 'Bình thường', 'Bình thường', 'L'),
(31, 7, 'Bình thường', 'Bình thường', 'L'),
(32, 2, 'Bình thường', 'Bình thường', 'L'),
(33, 1, 'Bình thường', 'Bình thường', 'L'),
(34, 1, 'Bình thường', 'Bình thường', 'L'),
(35, 5, 'Bình thường', 'Bình thường', 'L'),
(36, 1, 'Bình thường', 'Bình thường', 'L'),
(37, 1, 'Bình thường', 'Bình thường', 'L'),
(38, 1, 'Bình thường', 'Bình thường', 'L'),
(39, 5, 'Bình thường', 'Bình thường', 'L'),
(40, 5, 'Bình thường', 'Bình thường', 'L'),
(41, 2, 'Bình thường', 'Bình thường', 'L'),
(42, 1, 'Bình thường', 'Bình thường', 'L'),
(43, 1, 'Bình thường', 'Bình thường', 'L'),
(44, 1, 'Bình thường', 'Bình thường', 'L'),
(45, 1, 'Bình thường', 'Bình thường', 'L'),
(46, 2, 'Bình thường', 'Bình thường', 'L'),
(47, 1, 'Bình thường', 'Bình thường', 'L'),
(48, 1, 'Bình thường', 'Bình thường', 'L'),
(49, 5, 'Bình thường', 'Bình thường', 'L'),
(50, 1, 'Bình thường', 'Bình thường', 'L'),
(51, 1, 'Bình thường', 'Bình thường', 'L'),
(52, 5, 'Bình thường', 'Bình thường', 'L'),
(53, 8, 'Bình thường', 'Bình thường', 'L'),
(54, 2, 'Bình thường', 'Bình thường', 'L'),
(55, 5, 'Bình thường', 'Bình thường', 'L'),
(56, 1, 'Bình thường', 'Bình thường', 'L'),
(57, 5, 'Bình thường', 'Bình thường', 'L'),
(58, 7, 'Bình thường', 'Bình thường', 'L'),
(59, 1, 'Bình thường', 'Bình thường', 'L'),
(60, 1, 'Bình thường', 'Bình thường', 'L'),
(61, 2, 'Bình thường', 'Bình thường', 'L'),
(62, 7, 'Bình thường', 'Bình thường', 'L'),
(63, 2, 'Bình thường', 'Bình thường', 'L'),
(64, 1, 'Bình thường', 'Bình thường', 'L'),
(65, 5, 'Bình thường', 'Bình thường', 'L'),
(66, 2, 'Bình thường', 'Bình thường', 'L'),
(67, 1, 'Bình thường', 'Bình thường', 'L'),
(68, 2, 'Bình thường', 'Bình thường', 'L'),
(69, 1, 'Bình thường', 'Bình thường', 'L'),
(70, 2, 'Bình thường', 'Bình thường', 'L'),
(71, 7, 'Bình thường', 'Bình thường', 'L'),
(72, 1, 'Bình thường', 'Bình thường', 'L'),
(73, 2, 'Bình thường', 'Bình thường', 'L'),
(74, 1, 'Bình thường', 'Bình thường', 'L'),
(75, 2, 'Bình thường', 'Bình thường', 'L'),
(76, 8, 'Bình thường', 'Bình thường', 'L'),
(77, 1, 'Bình thường', 'Bình thường', 'L'),
(78, 1, 'Bình thường', 'Bình thường', 'L'),
(79, 2, 'Bình thường', 'Bình thường', 'L'),
(80, 2, 'Bình thường', 'Bình thường', 'L'),
(81, 2, 'Bình thường', 'Bình thường', 'L'),
(82, 1, 'Bình thường', 'Bình thường', 'L'),
(83, 1, 'Bình thường', 'Bình thường', 'L'),
(84, 2, 'Bình thường', 'Bình thường', 'L'),
(85, 1, 'Bình thường', 'Bình thường', 'L'),
(86, 1, 'Bình thường', 'Bình thường', 'L'),
(87, 5, 'Bình thường', 'Bình thường', 'L'),
(88, 25, 'Bình thường', 'Bình thường', 'L'),
(89, 1, 'Bình thường', 'Bình thường', 'L'),
(90, 7, 'Bình thường', 'Bình thường', 'L'),
(91, 2, 'Bình thường', 'Bình thường', 'L'),
(92, 5, 'Bình thường', 'Bình thường', 'L'),
(93, 1, 'Bình thường', 'Bình thường', 'L'),
(94, 5, 'Bình thường', 'Bình thường', 'L'),
(95, 2, 'Bình thường', 'Bình thường', 'L'),
(96, 29, 'Bình thường', 'Bình thường', 'L'),
(97, 2, 'Bình thường', 'Bình thường', 'L'),
(98, 34, 'Bình thường', 'Bình thường', 'L'),
(99, 1, 'Bình thường', 'Bình thường', 'L'),
(100, 51, 'Bình thường', 'Bình thường', 'L'),
(101, 28, 'Bình thường', 'Bình thường', 'L'),
(102, 5, 'Bình thường', 'Bình thường', 'L'),
(103, 8, 'Bình thường', 'Bình thường', 'L');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `permissions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `role`
--

INSERT INTO `role` (`role_id`, `role_name`, `permissions`) VALUES
(1, 'Admin', NULL),
(2, 'Nhân viên', NULL),
(3, 'Người dùng', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(12) NOT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`user_id`, `role_id`, `username`, `email`, `password`, `phone_number`, `address`) VALUES
(20, 3, 'Lê Đình Nghĩa', 'datnltpd10663@gmail.com', 'c76fa734099e3c5cba89d1d176e40e64ba69e3dc0fb83c05ec8d4fc3a210eb4e', '0973793847', 'Đà Nẵng'),
(23, 1, 'Nguyễn Lê Tiến Đạt', 'nguyenletiendat63@gmail.com', '4297f44b13955235245b2497399d7a93', '0973793847', 'Đà Nẵng'),
(25, 1, 'Nguyễn Hoàng Phi Hồng', 'tiendatcv123@gmail.com', '036e0860f72e47ccfebfa8db3a0bb4ddd15be4d54ed29c4f1dd033fc1fbabc64', '0365164064', 'Đà nẵng'),
(26, 3, 'Abu', 'nn0017275@gmail.com', '036e0860f72e47ccfebfa8db3a0bb4ddd15be4d54ed29c4f1dd033fc1fbabc64', '0365164064', '9 Hà Văn Tính, Hoà Khánh Nam, Liên Chiểu, Đà Nẵng 550000');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `invoice_detail`
--
ALTER TABLE `invoice_detail`
  ADD PRIMARY KEY (`invoice_detail_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `productdetail`
--
ALTER TABLE `productdetail`
  ADD PRIMARY KEY (`product_detail_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT cho bảng `invoice_detail`
--
ALTER TABLE `invoice_detail`
  MODIFY `invoice_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT cho bảng `productdetail`
--
ALTER TABLE `productdetail`
  MODIFY `product_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT cho bảng `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Các ràng buộc cho bảng `invoice_detail`
--
ALTER TABLE `invoice_detail`
  ADD CONSTRAINT `invoice_detail_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`invoice_id`),
  ADD CONSTRAINT `invoice_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Các ràng buộc cho bảng `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
