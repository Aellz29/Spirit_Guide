-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 05:43 AM
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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `category` enum('Fashion','Food','Aksesoris','Other') DEFAULT 'Other',
  `description` text DEFAULT NULL,
  `price` decimal(12,2) DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
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
(3, 'alif', 'alif@spiritguide.com', '$2y$10$2vheo8f5kfviRYCnCZ6.o.yiERwPXkOjHKIpL1gKx7KcR9BX.n2Ey', 'customer', '2025-12-03 02:16:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `katalog`
--
ALTER TABLE `katalog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
