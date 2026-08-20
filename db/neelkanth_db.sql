-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2026 at 11:41 AM
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
-- Database: `neelkanth_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `message`, `created_at`) VALUES
(1, 'abc', 'abc@gmail.com', '9876543211', 'demo', '2026-08-19 12:11:13');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'COD',
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `address`, `city`, `pincode`, `payment_method`, `status`, `created_at`) VALUES
(1, 2, 1200.00, 'junagadh', 'junagadh', '362100', 'COD', 'Pending', '2026-08-19 10:55:22'),
(2, 2, 150.00, 'jamnagar', 'jamnagar', '361001', 'COD', 'Pending', '2026-08-19 11:01:11'),
(3, 2, 5000.00, 'jamnagar', 'jamnagar', '361001', 'COD', 'Delivered', '2026-08-19 11:26:02'),
(4, 2, 1200.00, 'jamnagr', 'jam', '361001', 'COD', 'Confirmation', '2026-08-19 11:45:25'),
(5, 3, 5000.00, 'jam', 'jam', '361001', 'COD', 'Confirmation', '2026-08-19 12:07:21'),
(6, 3, 12400.00, 'jamnagar', 'jamnagar', '361001', '', 'Pending', '2026-08-20 09:32:25'),
(7, 3, 1200.00, 'jam', 'jam', '361001', '', 'Pending', '2026-08-20 09:32:40'),
(8, 3, 5000.00, 'jam', 'jam', '361001', 'COD', 'Pending', '2026-08-20 09:34:48'),
(9, 4, 1200.00, 'jam', 'jsm', '361001', 'COD', 'Pending', '2026-08-20 09:38:58');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`) VALUES
(1, 1, 2, NULL, 1, 1200.00),
(2, 2, 1, NULL, 1, 150.00),
(3, 3, 3, NULL, 1, 5000.00),
(4, 4, 2, NULL, 1, 1200.00),
(5, 5, 3, NULL, 1, 5000.00),
(6, 6, 2, NULL, 2, 1200.00),
(7, 6, 3, NULL, 2, 5000.00),
(8, 7, 2, NULL, 1, 1200.00),
(9, 8, 3, NULL, 1, 5000.00),
(10, 9, 2, NULL, 1, 1200.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `description`, `price`, `image`, `created_at`) VALUES
(1, 'LED Bulb 12W', NULL, 'High quality energy saving LED bulb', 150.00, 'bulb.jpg', '2026-08-19 10:11:26'),
(2, 'Copper Wire 1.5sqmm', 'Mixer Grinder', 'Fire resistant pure copper wire roll', 1200.00, '1787137890_image (5).png', '2026-08-19 10:11:26'),
(3, 'tv', 'Blender', 'tv', 5000.00, '1787137843_image (5).png', '2026-08-19 11:10:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `password`, `created_at`) VALUES
(1, NULL, 'admin', 'admin@gmail.com', NULL, '$2y$10$4q9v/s5C3eB5iL4M2V0Uu.mC3yB3I9Jg5L6u0O3K2N1M4P5Q6R7S8', '2026-08-19 10:18:53'),
(2, 'kishan', '', 'kishan@gmail.com', '9876543211', '$2y$10$R3Ir.aSB1A8XE5yDh6Ic8uiP5uU9jGb6vWuGqTIst6aIIvtVdmlN.', '2026-08-19 10:54:19'),
(3, NULL, 'kishan', 'kisan@gmail.com', NULL, '$2y$10$tY3OZNiC/tkFzkA37GDLIe2YAtkEmB0xOwtxANModlRwK5o70JJOa', '2026-08-19 11:59:32'),
(4, 'khushal', '', 'khushal@gmail.com', '9876543211', '$2y$10$mhVhBgANvpaNtWxOtogfseoL1OlOobZqPy6AA2rtwEniYI4oHrGFm', '2026-08-20 09:38:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
