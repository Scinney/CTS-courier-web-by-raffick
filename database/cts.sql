-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2025 at 05:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cts`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `city` enum('Lilongwe','Blantyre','Mzuzu') NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `is_operational` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `city`, `contact_number`, `address`, `is_operational`, `created_at`, `updated_at`) VALUES
(1, 'Main Lilongwe Branch', 'Lilongwe', '0999555666', 'Lilongwe City Centre', 1, '2025-05-01 06:00:00', '2025-05-10 21:59:24'),
(2, 'Lilongwe North Branch', 'Lilongwe', '0999555777', 'Lilongwe North', 0, '2025-05-01 07:00:00', '2025-05-10 21:59:49'),
(3, 'Central Blantyre Branch', 'Blantyre', '0999666777', 'Blantyre Commercial Area', 1, '2025-05-02 07:00:00', '2025-05-10 22:00:01'),
(4, 'Blantyre South Branch', 'Blantyre', '0999666888', 'Blantyre kudya branch', 1, '2025-05-02 08:00:00', '2025-05-10 22:01:05'),
(5, 'Mzuzu Downtown Branch', 'Mzuzu', '0999777888', 'Mzuzu Central', 1, '2025-05-03 08:00:00', '2025-05-10 22:00:21'),
(6, 'Mzuzu East Branch', 'Mzuzu', '0999777999', 'Mzuzu East', 0, '2025-05-03 09:00:00', '2025-05-10 22:00:30'),
(7, 'kayaku', 'Blantyre', '0999777999', 'limbe iwe', 1, '2025-05-10 22:14:39', '2025-05-10 22:14:39');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `user_id`, `email`) VALUES
(1, 1, 'mkanda2011@hotmail.com'),
(2, 2, 'dhdfshdf@gmail.com'),
(3, 3, 'mkaffnda2011@hotmail.com'),
(4, 4, 'sfgsgmkanda2011@hotmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parcels`
--

CREATE TABLE `parcels` (
  `id` int(11) NOT NULL,
  `parcel_id` varchar(50) NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `sender_branch_id` int(11) NOT NULL,
  `sender_contact` varchar(15) NOT NULL,
  `receiver_name` varchar(100) NOT NULL,
  `receiver_branch_id` int(11) NOT NULL,
  `receiver_contact` varchar(15) NOT NULL,
  `status` enum('In Transit','Out for Delivery','Delivered','Returned','archived','deleted') NOT NULL DEFAULT 'In Transit',
  `weight` float NOT NULL,
  `declared_value` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parcels`
--

INSERT INTO `parcels` (`id`, `parcel_id`, `sender_name`, `sender_branch_id`, `sender_contact`, `receiver_name`, `receiver_branch_id`, `receiver_contact`, `status`, `weight`, `declared_value`, `created_at`, `updated_at`) VALUES
(1, 'CTS-001', 'John Banda', 1, '0999123456', 'Mary Phiri', 3, '0888765432', 'In Transit', 2.5, 1500.00, '2025-05-01 06:00:00', '2025-05-01 06:00:00'),
(2, 'CTS-002', 'Alice Mbewe', 5, '0999234567', 'Peter Nkhoma', 1, '0888876543', 'Out for Delivery', 1.8, 800.00, '2025-05-02 07:30:00', '2025-05-03 12:20:00'),
(3, 'CTS-003', 'David Kamanga', 3, '0999345678', 'Sarah Zulu', 5, '0888987654', 'Delivered', 3.2, 2500.00, '2025-05-03 09:15:00', '2025-05-05 14:45:00'),
(4, 'CTS-004', 'Grace Chisi', 1, '0999456789', 'James Mwale', 7, '0888098765', 'Returned', 0.9, 500.00, '2025-05-04 11:00:00', '2025-05-10 22:16:04'),
(5, 'CTS-005', 'Michael Tembo', 5, '0999567890', 'Linda Kachala', 1, '0888109876', 'archived', 4, 3000.00, '2025-05-05 05:45:00', '2025-05-08 10:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `second_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `role` enum('admin','user','employee','manager') DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `second_name`, `email`, `gender`, `role`, `password`, `phone_number`, `address`, `created_at`) VALUES
(1, 'huggett', 'Mtafya', 'mkanda2011@hotmail.com', 'Male', 'admin', '$2y$10$OqjiTPxMLoj52ssEzQLyJuJ9optb6XKcSou.SQ3lO0sxIfUJox.Xu', '0996424313', 'post office box 24, Karonga', '2025-03-11 17:37:06'),
(2, 'fdghd', 'dhd', 'dhdfshdf@gmail.com', 'Male', 'user', '$2y$10$oZhuaFP2BQ4oZoN7js7P0uGV0mxN.OrXwqL2mQ47l4Qmk91CoVoOu', '0996424313', 'post office box 24, Karonga', '2025-03-14 15:02:51'),
(3, 'huggett', 'Mtafya', 'mkaffnda2011@hotmail.com', 'Male', '', '$2y$10$ZcuXh2oH93LiML1gnoOyIezpV2Ric9O3J91C6hyctbRVecyg/PZ7C', '0996424313', 'post office box 24, Karonga', '2025-03-15 11:03:11'),
(4, 'huggett', 'Mtafya', 'sfgsgmkanda2011@hotmail.com', 'Male', 'user', '$2y$10$fpEQvXB2MomAhnsxJa2iv.BtPGITLOvU8amB2amlnDpiKAsHtsR7O', '0996424313', 'post office box 24, Karonga', '2025-03-15 11:04:35');

-- --------------------------------------------------------

--
-- Table structure for table `users2`
--

CREATE TABLE `users2` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `role` enum('admin','staff','customer') NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','suspended','deleted') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users2`
--

INSERT INTO `users2` (`id`, `first_name`, `last_name`, `email`, `role`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Himothy', 'Lone', 'scinneylone@gmail.com', 'admin', '$2y$10$u.O2ue5o.PurRYRC4GMaI.po9AUTXpqVTjcFhKhQJahqcJ.eIrqcC', 'active', '2025-05-10 19:59:45', '2025-05-10 20:42:30'),
(2, 'Rafick', 'Lamson', 'talia04@gmail.com', 'admin', '$2y$10$sTkoACzdFPBl.BuS8oUZduP3rDmqAThjOZq6PQeQUBr1.g48XqaJi', 'active', '2025-05-10 20:04:09', '2025-05-10 20:43:42'),
(3, 'Himothy', 'doe', 'hy@gmail.com', 'customer', '$2y$10$GqvPhKZnhJYclp03Y3tKOeXa0Gy9AoXxCmhgwLnJqxAMQJr.a.MHe', 'deleted', '2025-05-10 20:29:07', '2025-05-10 20:29:25'),
(4, 'test', 'account', 'testaccount@gmail.com', 'admin', '$2y$10$dCljvjaaL3ZCLP1E4M7xDOyDHOz5bHvCFL6Kod4pT3y7S0liSoUq.', 'suspended', '2025-05-10 20:41:23', '2025-05-10 20:43:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_branch_name_city` (`name`,`city`),
  ADD KEY `idx_city` (`city`),
  ADD KEY `idx_operational` (`is_operational`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `parcels`
--
ALTER TABLE `parcels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parcel_id` (`parcel_id`),
  ADD KEY `sender_branch_id` (`sender_branch_id`),
  ADD KEY `receiver_branch_id` (`receiver_branch_id`),
  ADD KEY `idx_parcel_id` (`parcel_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users2`
--
ALTER TABLE `users2`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manager`
--
ALTER TABLE `manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parcels`
--
ALTER TABLE `parcels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users2`
--
ALTER TABLE `users2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `manager`
--
ALTER TABLE `manager`
  ADD CONSTRAINT `manager_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `parcels`
--
ALTER TABLE `parcels`
  ADD CONSTRAINT `parcels_ibfk_1` FOREIGN KEY (`sender_branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `parcels_ibfk_2` FOREIGN KEY (`receiver_branch_id`) REFERENCES `branches` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
