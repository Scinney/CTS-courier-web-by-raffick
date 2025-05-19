-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 02:36 PM
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
-- Database: `cts_courier`
--

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`) VALUES
(1, 'admin@cts.mw', '45e361770a37ea6191814f0d0258cb2c5feb5bbd34bfe55dd0f34e98a8988854', '2025-05-19 11:30:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin','branch-admin','receptionist','driver') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone_number` varchar(15) NOT NULL,
  `status` enum('active','suspended','deleted') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `password`, `role`, `created_at`, `phone_number`, `status`) VALUES
(1, 'Admin', 'admin', 'admin@cts.mw', '$2y$10$mx/8G5/5kirtuRHa3knquOpZfEqLwvHaKDmszBA5ln3vLOUmBVG3q', 'admin', '2025-05-13 18:10:45', '+265992135373', 'active'),
(2, 'uriah', 'Lone', 'scinneylone@gmail.com', '$2y$10$iemjbG/LxSGOBSLc0qFxGu8pJ8bXLlYlif2ddi3vUhDirOG6cnYxa', 'user', '2025-05-13 19:06:57', '0992135373', 'suspended'),
(3, 'dan', 'reen', 'reeniel@cts.mw', '$2y$10$kP/bOTTYjJuswJIKFMNu/.4.15NsQ.4hDp88tbhLV5ssmbuTA7eNW', 'user', '2025-05-19 10:14:18', '+265880285862', 'active'),
(4, 'daniel', 'ndovie', 'dandovie@cts.mw', '$2y$10$cFxkR0nkG0AvIAtgFD67EONTM.YLvBvIi37qFkYz.8DUZAAIn46tC', 'user', '2025-05-19 10:19:48', '0880285862', 'deleted'),
(5, 'art', '47', 'arthonykanjira444@gmail.com', '$2y$10$1/0kiRs2abHPC9Bw8Cw7s.bOmp6jx5uGtmAV3dMQTiU1trsSSWjre', 'branch-admin', '2025-05-19 10:57:06', '+2659921345676', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `token` (`token`);

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
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
