-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2026 at 01:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spiritguide_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `katalog`
--

CREATE TABLE `katalog` (
  `id` int(11) NOT NULL,
  `nama_katalog` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `kategori` enum('fashion','food','aksesoris') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `katalog`
--

INSERT INTO `katalog` (`id`, `nama_katalog`, `deskripsi`, `gambar`, `kategori`) VALUES
(1, 'Jaket Hoodie Premium', 'Hoodie bahan fleece, nyaman dan hangat.', 'jaket1.jpg', 'fashion'),
(2, 'Kaos Oversize Unisex', 'Kaos oversize streetwear kekinian.', 'kaos1.jpg', 'fashion'),
(3, 'Celana Cargo High Quality', 'Celana cargo kuat dan nyaman untuk aktivitas.', 'cargo1.jpg', 'fashion'),
(4, 'Kemeja Flannel Import', 'Kemeja flannel tebal cocok untuk daily style.', 'flannel1.jpg', 'fashion'),
(5, 'Crewneck Minimalist', 'Crewneck bahan premium, style simple namun elegan.', 'crewneck1.jpg', 'fashion'),
(6, 'Salad Buah Segar', 'Campuran buah segar dengan topping yoghurt.', 'salad1.jpg', 'food'),
(7, 'Bolu Coklat Premium', 'Bolu lembut dengan cita rasa coklat strong.', 'bolu1.jpg', 'food'),
(8, 'Mie Pedas Spirit', 'Mie pedas khas Spirit Guide dengan bumbu rahasia.', 'mie1.jpg', 'food'),
(9, 'Es Kopi Susu Gula Aren', 'Kopi susu creamy dengan gula aren premium.', 'kopi1.jpg', 'food'),
(10, 'Roti Bakar Coklat Keju', 'Roti bakar crispy topping coklat keju melimpah.', 'rotibakar1.jpg', 'food'),
(11, 'Topi Baseball Premium', 'Topi premium bahan halus dan breathable.', 'topi1.jpg', 'aksesoris'),
(12, 'Kalung Titanium', 'Kalung titanium anti karat dan elegan.', 'kalung1.jpg', 'aksesoris'),
(13, 'Gelang Magnetic', 'Gelang kesehatan modern dengan desain stylish.', 'gelang1.jpg', 'aksesoris'),
(14, 'Jam Tangan Casual', 'Jam tangan simple cocok dipakai daily.', 'jam1.jpg', 'aksesoris'),
(15, 'Kacamata Retro', 'Frame retro kekinian untuk fashion harian.', 'kacamata1.jpg', 'aksesoris');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(15,2) DEFAULT 0.00,
  `name` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `payment_method` varchar(225) DEFAULT NULL,
  `status` enum('pending','verifying','shipping','success','canceled') DEFAULT 'pending',
  `proof_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `user_id`, `total_price`, `name`, `phone`, `address`, `total`, `payment_method`, `status`, `proof_image`, `created_at`) VALUES
(46, '', NULL, 0.00, 'testinglagi', 'testinglagi', 'testinglagi\r\n', 7500.00, 'QRIS', 'verifying', 'proof_46_1766959538.jpg', '2025-12-28 22:05:24'),
(47, '', NULL, 0.00, 'Ailum', 'tes lagiii', 'tes lagii', 30000.00, 'QRIS', 'success', 'proof_47_1767005750.jpg', '2025-12-29 10:55:22'),
(48, '', 5, 0.00, 'testbelanja', 'testbelanja ajah ', 'testbelanja', 125000.00, 'QRIS', 'success', 'proof_48_1767636892.png', '2026-01-05 18:14:31'),
(49, '', 2, 0.00, 'testdiskon', 'testdiskon', 'testdiskon', 125000.00, 'QRIS', 'success', 'proof_49_1767641797.jpeg', '2026-01-05 19:36:28'),
(50, '', NULL, 0.00, 'tescheckout', 'tescheckout', 'tescheckout', 200000.00, 'QRIS', 'verifying', 'proof_50_1767820376.jpeg', '2026-01-07 21:12:48'),
(51, 'ORD-20260107-534', 2, 125000.00, 'ail', '08971566371', 'jl. cilengkrang 1, kp cigagak, kec.cibiru, kel.cisurupan, rt 5 / rw 7', NULL, 'QRIS', 'success', '', '2026-01-07 21:58:42');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `qty`, `price`) VALUES
(54, 46, 5, 1, 7500.00),
(55, 47, 4, 2, 15000.00),
(56, 48, 11, 1, 125000.00),
(57, 49, 11, 1, 125000.00),
(58, 50, 11, 1, 200000.00),
(59, 51, 11, 1, 125000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `category` enum('Fashion','Food','Aksesoris','Other') DEFAULT 'Other',
  `description` text DEFAULT NULL,
  `price` decimal(12,2) DEFAULT 0.00,
  `member_price` decimal(15,2) DEFAULT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_flash_sale` tinyint(1) DEFAULT 0,
  `flash_sale_end` datetime DEFAULT NULL,
  `rating` float NOT NULL DEFAULT 0,
  `rating_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `category`, `description`, `price`, `member_price`, `original_price`, `image`, `created_at`, `stock`, `is_flash_sale`, `flash_sale_end`, `rating`, `rating_count`) VALUES
(3, 'Baju Casual Pria', 'Fashion', 'baju keren', 175000.00, 135000.00, 175000.00, 'assets/uploads/products/prod_1767657154_537.jpg', '2025-12-04 12:42:28', 10, 1, NULL, 0, 0),
(4, 'Salad Buah', 'Food', 'Salad buah ini banyak manfaat nya, yang dimana menjadi bugar dan segar badan', 15000.00, 0.00, 0.00, 'assets/uploads/products/prod_1767657106_706.jpg', '2025-12-06 13:58:22', 0, 0, NULL, 0, 0),
(5, 'Kalung Bintang', 'Aksesoris', 'Kalung bintang keren', 7500.00, 5000.00, 7500.00, 'assets/uploads/products/prod_1767657085_366.jpg', '2025-12-06 14:09:38', 5, 1, NULL, 0, 0),
(8, 'makaroni basah', 'Food', '...', 14500.00, 0.00, 0.00, 'assets/uploads/products/prod_1767657076_239.jpeg', '2025-12-21 14:02:42', 10, 0, NULL, 0, 0),
(9, 'spagetti', 'Food', '', 10000.00, 0.00, 0.00, 'assets/uploads/products/prod_1767657062_390.jpeg', '2025-12-21 15:40:23', 10, 0, NULL, 0, 0),
(10, 'hoddie hitam', 'Fashion', 'ail ganteng', 150000.00, NULL, 0.00, 'assets/uploads/products/prod_1767655468_358.jpg', '2025-12-21 16:01:31', 10, 0, NULL, 0, 0),
(11, 'tes barang diskon', 'Fashion', '', 200000.00, 125000.00, 200000.00, 'assets/uploads/products/prod_1767661257_605.jpg', '2026-01-05 17:14:43', 3, 1, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_id`, `user_id`, `username`, `rating`, `comment`, `created_at`) VALUES
(1, 10, NULL, 'Guest', 5, 'tes doang', '2025-12-21 20:21:14'),
(2, 4, NULL, 'Guest', 5, 'enak banget salad buah nya', '2025-12-21 20:23:13'),
(3, 5, NULL, 'Guest', 5, 'kalung nya keren ', '2025-12-21 20:46:21'),
(4, 9, NULL, 'Guest', 5, 'enajjjjkkkk bgt', '2025-12-21 21:07:32'),
(5, 5, NULL, 'Guest', 5, 'waw keren\r\n', '2025-12-28 18:49:47'),
(6, 5, NULL, 'Guest', 5, 'udah murah, sesuai dengan pict dan juga berkualitas \r\n', '2025-12-29 11:04:18'),
(7, 11, 5, 'ail2', 5, 'test komen', '2026-01-05 19:55:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer','user') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `reset_token`, `reset_expiry`, `phone`, `address`, `avatar`) VALUES
(1, 'admin', 'admin@spiritguide.com', '0192023a7bbd73250516f069df18b500', 'admin', '2025-10-23 20:51:01', NULL, NULL, NULL, NULL, 'default.jpg'),
(2, 'ail', 'ail@spiritguide.com', '13b37f91dbd438199129b6552e06cd83', 'customer', '2025-10-23 20:53:57', NULL, NULL, '08971566371', 'jl. cilengkrang 1, kp cigagak, kec.cibiru, kel.cisurupan, rt 5 / rw 7', '1767823007_ailum.jpeg'),
(3, 'alif', 'alif@spiritguide.com', '$2y$10$2vheo8f5kfviRYCnCZ6.o.yiERwPXkOjHKIpL1gKx7KcR9BX.n2Ey', 'customer', '2025-12-03 02:16:18', NULL, NULL, NULL, NULL, 'default.jpg'),
(4, 'joel', 'joel@spiritguide.com', 'c52331dd6fae697dbfa3954c00600b46', 'customer', '2025-12-04 10:55:37', NULL, NULL, NULL, NULL, 'default.jpg'),
(5, 'ail2', 'ail2@spiritguide.com', '$2y$10$3NlgrRK64BKq1mIqiNKZGeSSR0mn6wPSRqi4.Xog2Pg.l31FyfdqS', 'customer', '2025-12-27 04:32:15', NULL, NULL, NULL, NULL, 'default.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `katalog`
--
ALTER TABLE `katalog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `katalog`
--
ALTER TABLE `katalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
