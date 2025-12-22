-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2025 at 05:15 PM
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
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `stock` int(11) NOT NULL DEFAULT 0,
  `rating` float NOT NULL DEFAULT 0,
  `rating_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `category`, `description`, `price`, `image`, `created_at`, `stock`, `rating`, `rating_count`) VALUES
(3, 'Baju Casual Pria', 'Fashion', 'baju keren', 75000.00, 'src/img/products/prod_1764852148.jpg', '2025-12-04 12:42:28', 0, 0, 0),
(4, 'Salada Buah', 'Food', 'Salad buah ini banyak manfaat nya, yang dimana menjadi bugar dan segar badan', 15000.00, 'src/img/products/prod_1765029502.jpeg', '2025-12-06 13:58:22', 0, 0, 0),
(5, 'Kalung Bintang', 'Aksesoris', 'Kalung bintang keren', 7500.00, 'src/img/products/prod_1765030178.jpg', '2025-12-06 14:09:38', 0, 0, 0),
(7, 'blablbalbal', 'Fashion', 'blablalbla', 123456789.00, 'src/img/products/prod_1766322235.jpg', '2025-12-21 13:03:55', 0, 0, 0),
(8, 'blalvlalvas', 'Fashion', 'baugs', 12345654.00, 'src/img/products/prod_1766325762.jpeg', '2025-12-21 14:02:42', 0, 0, 0),
(9, 'sfsadas', 'Fashion', 'ini produk terkeren', 10000.00, 'src/img/products/prod_1766331623.jpeg', '2025-12-21 15:40:23', 0, 0, 0),
(10, 'ailalislaidlasd', 'Fashion', 'ail ganteng', 20000.00, 'src/img/products/prod_1766332891.png', '2025-12-21 16:01:31', 0, 0, 0);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@spiritguide.com', '0192023a7bbd73250516f069df18b500', 'admin', '2025-10-23 20:51:01'),
(2, 'ail', 'ail@spiritguide.com', '$2y$10$d5P93dhmMmDsjxRSx2giKe3uyer/nH.Q9ZadRT2z4/RDgO3cNuBx2', 'customer', '2025-10-23 20:53:57'),
(3, 'alif', 'alif@spiritguide.com', '$2y$10$2vheo8f5kfviRYCnCZ6.o.yiERwPXkOjHKIpL1gKx7KcR9BX.n2Ey', 'customer', '2025-12-03 02:16:18'),
(4, 'joel', 'joel@spiritguide.com', '$2y$10$V52ak164WwVvA9pra8tubexkBUJA20ItJeqIbCaOzQd1xyR2Jl.xi', 'customer', '2025-12-04 10:55:37');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
