-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 12:00 PM
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
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `BranchID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `City` varchar(100) NOT NULL,
  `CitySide` varchar(50) DEFAULT NULL,
  `ContactNumber` varchar(50) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Operational` tinyint(1) NOT NULL DEFAULT 1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`BranchID`, `Name`, `City`, `CitySide`, `ContactNumber`, `Address`, `Operational`, `CreatedAt`, `UpdatedAt`) VALUES
(1, 'Main Branch', 'Lilongwe', 'North', '0999123456', '123 Main St, Lilongwe', 1, '2025-05-22 08:25:48', '2025-05-22 08:25:48'),
(2, 'City Center', 'Blantyre', 'South', '0888765432', '456 City Rd, Blantyre', 1, '2025-05-22 08:25:48', '2025-05-22 08:25:48'),
(3, 'Downtown', 'Mzuzu', 'Central', '0999876543', '789 Downtown Ave, Mzuzu', 0, '2025-05-22 08:25:48', '2025-05-22 08:47:39'),
(4, 'kudya', 'Blantyre', 'Central', '0999876543', '789 Downtown Ave, Mzuzu', 1, '2025-05-22 09:40:55', '2025-05-22 09:40:55');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `DeliveryID` int(11) NOT NULL,
  `ParcelID` varchar(50) NOT NULL,
  `DriverID` int(11) NOT NULL,
  `AssignedBranchID` int(11) NOT NULL,
  `DeliveryStatusID` int(11) NOT NULL,
  `AssignedAt` datetime DEFAULT current_timestamp(),
  `UpdatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DeliveredAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`DeliveryID`, `ParcelID`, `DriverID`, `AssignedBranchID`, `DeliveryStatusID`, `AssignedAt`, `UpdatedAt`, `DeliveredAt`) VALUES
(1, 'PARC-202505220001', 1, 1, 1, '2025-05-22 11:00:00', '2025-05-22 11:30:00', NULL),
(2, 'PARC-202505220002', 1, 2, 2, '2025-05-22 10:30:00', '2025-05-22 11:35:00', NULL),
(3, 'PARC-202505220003', 1, 1, 2, '2025-05-22 11:10:00', '2025-05-22 11:38:00', NULL),
(4, 'PARC-202505210001', 1, 2, 3, '2025-05-21 14:30:00', '2025-05-22 08:00:00', '2025-05-22 08:00:00'),
(5, 'PARC-202505210002', 1, 1, 4, '2025-05-21 15:30:00', '2025-05-22 09:30:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deliverystatus`
--

CREATE TABLE `deliverystatus` (
  `DeliveryStatusID` int(11) NOT NULL,
  `StatusName` enum('Pending','In Transit','Delivered','Failed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deliverystatus`
--

INSERT INTO `deliverystatus` (`DeliveryStatusID`, `StatusName`) VALUES
(1, 'Pending'),
(2, 'In Transit'),
(3, 'Delivered'),
(4, 'Failed');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `DriverID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `ContactNumber` varchar(20) NOT NULL,
  `AssignedBranchID` int(11) NOT NULL,
  `IsActive` tinyint(4) DEFAULT 1,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  `UpdatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`DriverID`, `FirstName`, `LastName`, `ContactNumber`, `AssignedBranchID`, `IsActive`, `CreatedAt`, `UpdatedAt`) VALUES
(1, 'John', 'Doe', '0999123456', 1, 1, '2025-05-22 11:11:49', '2025-05-22 11:11:49');

-- --------------------------------------------------------

--
-- Table structure for table `parcels`
--

CREATE TABLE `parcels` (
  `ParcelID` varchar(50) NOT NULL,
  `Sender` varchar(100) NOT NULL,
  `SenderBranchID` int(11) NOT NULL,
  `SenderContact` varchar(50) DEFAULT NULL,
  `Receiver` varchar(100) NOT NULL,
  `ReceiverBranchID` int(11) NOT NULL,
  `ReceiverContact` varchar(50) DEFAULT NULL,
  `WeightKg` decimal(10,2) NOT NULL,
  `DeclaredValueMWK` decimal(15,2) DEFAULT NULL,
  `PaymentStatusID` int(11) NOT NULL DEFAULT 2,
  `DeliveryStatusID` int(11) NOT NULL DEFAULT 1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parcels`
--

INSERT INTO `parcels` (`ParcelID`, `Sender`, `SenderBranchID`, `SenderContact`, `Receiver`, `ReceiverBranchID`, `ReceiverContact`, `WeightKg`, `DeclaredValueMWK`, `PaymentStatusID`, `DeliveryStatusID`, `CreatedAt`, `UpdatedAt`) VALUES
('PARC-202505210001', 'Grace Chilima', 2, '0888456789', 'Henry Tembo', 1, '0999567890', 3.20, 18000.00, 1, 3, '2025-05-21 12:00:00', '2025-05-22 06:00:00'),
('PARC-202505210002', 'Isaac Kamanga', 1, '0999678901', 'Jane Manda', 2, '0888789012', 4.00, 20000.00, 2, 4, '2025-05-21 13:00:00', '2025-05-22 07:30:00'),
('PARC-202505220001', 'Alice Mbewe', 1, '0999123456', 'Bob Phiri', 2, '0888765432', 2.50, 15000.00, 2, 1, '2025-05-22 07:00:00', '2025-05-22 07:00:00'),
('PARC-202505220002', 'Charles Banda', 2, '0888123456', 'Diana Kumwenda', 1, '0999876543', 1.80, 8000.00, 1, 2, '2025-05-22 08:00:00', '2025-05-22 08:30:00'),
('PARC-202505220003', 'Esther Nkhoma', 1, '0999234567', 'Frank Mwale', 2, '0888345678', 5.00, 25000.00, 2, 2, '2025-05-22 09:00:00', '2025-05-22 09:15:00');

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
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `PaymentStatusID` int(11) NOT NULL,
  `StatusName` enum('Paid','Not Paid') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`PaymentStatusID`, `StatusName`) VALUES
(1, 'Paid'),
(2, 'Not Paid');

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
(2, 'uriah', 'Lone', 'scinneylone@gmail.com', '$2y$10$iemjbG/LxSGOBSLc0qFxGu8pJ8bXLlYlif2ddi3vUhDirOG6cnYxa', 'user', '2025-05-13 19:06:57', '0992135373', 'active'),
(3, 'dan', 'reen', 'reeniel@cts.mw', '$2y$10$kP/bOTTYjJuswJIKFMNu/.4.15NsQ.4hDp88tbhLV5ssmbuTA7eNW', 'user', '2025-05-19 10:14:18', '+265880285862', 'active'),
(4, 'daniel', 'ndovie', 'dandovie@cts.mw', '$2y$10$cFxkR0nkG0AvIAtgFD67EONTM.YLvBvIi37qFkYz.8DUZAAIn46tC', 'user', '2025-05-19 10:19:48', '0880285862', 'deleted'),
(5, 'art', '47', 'arthonykanjira444@gmail.com', '$2y$10$1/0kiRs2abHPC9Bw8Cw7s.bOmp6jx5uGtmAV3dMQTiU1trsSSWjre', 'branch-admin', '2025-05-19 10:57:06', '+2659921345676', 'suspended'),
(6, 'test', 'test', 'test12@cts.mw', '$2y$10$WI8QNqBYIUSPQ2yuWBrCKesZbzuDGcYPMK5H1TEWwuIinP9z1Pnea', 'user', '2025-05-19 15:24:57', '0992135373', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`BranchID`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`DeliveryID`),
  ADD KEY `ParcelID` (`ParcelID`),
  ADD KEY `DriverID` (`DriverID`),
  ADD KEY `AssignedBranchID` (`AssignedBranchID`),
  ADD KEY `DeliveryStatusID` (`DeliveryStatusID`);

--
-- Indexes for table `deliverystatus`
--
ALTER TABLE `deliverystatus`
  ADD PRIMARY KEY (`DeliveryStatusID`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`DriverID`),
  ADD KEY `AssignedBranchID` (`AssignedBranchID`);

--
-- Indexes for table `parcels`
--
ALTER TABLE `parcels`
  ADD PRIMARY KEY (`ParcelID`),
  ADD KEY `SenderBranchID` (`SenderBranchID`),
  ADD KEY `ReceiverBranchID` (`ReceiverBranchID`),
  ADD KEY `PaymentStatusID` (`PaymentStatusID`),
  ADD KEY `DeliveryStatusID` (`DeliveryStatusID`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `token` (`token`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`PaymentStatusID`);

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
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `BranchID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `DeliveryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `deliverystatus`
--
ALTER TABLE `deliverystatus`
  MODIFY `DeliveryStatusID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `DriverID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `PaymentStatusID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_ibfk_1` FOREIGN KEY (`ParcelID`) REFERENCES `parcels` (`ParcelID`) ON DELETE CASCADE,
  ADD CONSTRAINT `deliveries_ibfk_2` FOREIGN KEY (`DriverID`) REFERENCES `drivers` (`DriverID`),
  ADD CONSTRAINT `deliveries_ibfk_3` FOREIGN KEY (`AssignedBranchID`) REFERENCES `branches` (`BranchID`),
  ADD CONSTRAINT `deliveries_ibfk_4` FOREIGN KEY (`DeliveryStatusID`) REFERENCES `deliverystatus` (`DeliveryStatusID`);

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_ibfk_1` FOREIGN KEY (`AssignedBranchID`) REFERENCES `branches` (`BranchID`);

--
-- Constraints for table `parcels`
--
ALTER TABLE `parcels`
  ADD CONSTRAINT `parcels_ibfk_1` FOREIGN KEY (`SenderBranchID`) REFERENCES `branches` (`BranchID`),
  ADD CONSTRAINT `parcels_ibfk_2` FOREIGN KEY (`ReceiverBranchID`) REFERENCES `branches` (`BranchID`),
  ADD CONSTRAINT `parcels_ibfk_3` FOREIGN KEY (`PaymentStatusID`) REFERENCES `payment` (`PaymentStatusID`),
  ADD CONSTRAINT `parcels_ibfk_4` FOREIGN KEY (`DeliveryStatusID`) REFERENCES `deliverystatus` (`DeliveryStatusID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
