-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2025 at 02:33 PM
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
-- Database: `kenanginkopi`
--

-- --------------------------------------------------------

--
-- Table structure for table `coffee`
--

CREATE TABLE `coffee` (
  `CoffeeID` char(5) NOT NULL,
  `CoffeeName` varchar(50) NOT NULL,
  `CoffeePrice` int(11) NOT NULL,
  `CoffeeDesc` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coffee`
--

INSERT INTO `coffee` (`CoffeeID`, `CoffeeName`, `CoffeePrice`, `CoffeeDesc`) VALUES
('C0001', 'Latte', 25000, 'Smooth espresso with milk'),
('C0002', 'Americano', 18000, 'Black coffee rich in aroma'),
('C0003', 'Caramel Macchiato', 32000, 'Sweet caramel layered coffee');

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `StoreID` char(5) NOT NULL,
  `StoreName` varchar(50) NOT NULL,
  `StoreLocation` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`StoreID`, `StoreName`, `StoreLocation`) VALUES
('S0001', 'Kenangin Kopi Pusat', 'Jakarta Barat'),
('S0002', 'Kenangin Kopi Cabang 1', 'Jakarta Selatan'),
('S0003', 'Kenangin Kopi Cabang 2', 'Tangerang');

-- --------------------------------------------------------

--
-- Table structure for table `storecoffee`
--

CREATE TABLE `storecoffee` (
  `StoreID` char(5) NOT NULL,
  `CoffeeID` char(5) NOT NULL,
  `Price` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `storecoffee`
--

INSERT INTO `storecoffee` (`StoreID`, `CoffeeID`, `Price`) VALUES
('S0001', 'C0001', 26000.00),
('S0001', 'C0002', 19000.00),
('S0001', 'C0003', 33000.00),
('S0002', 'C0001', 25500.00),
('S0002', 'C0002', 18500.00),
('S0002', 'C0003', 32500.00),
('S0003', 'C0001', 25000.00),
('S0003', 'C0002', 17500.00),
('S0003', 'C0003', 31000.00);

-- --------------------------------------------------------

--
-- Table structure for table `transactiondetails`
--

CREATE TABLE `transactiondetails` (
  `TransactionID` char(5) NOT NULL,
  `CoffeeID` char(5) NOT NULL,
  `Qty` int(11) NOT NULL,
  `SubTotal` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `TransactionID` char(5) NOT NULL,
  `UserID` char(5) NOT NULL,
  `StoreID` char(5) NOT NULL,
  `TransactionDate` date NOT NULL,
  `TotalPrice` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` char(5) NOT NULL,
  `FullName` varchar(50) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `UserEmail` varchar(50) NOT NULL,
  `UserPassword` varchar(100) NOT NULL,
  `UserRole` enum('Admin','User') NOT NULL DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FullName`, `UserName`, `UserEmail`, `UserPassword`, `UserRole`) VALUES
('U0001', 'user account', 'User', 'user@gmail.com', '$2y$10$MLlcit7qm64Hx/6gwQZbQ.lQzQhnBnx0FtXC3zQaLun7GJ5B1dPHS', 'User'),
('U0002', 'admin account', 'Admin', 'admin@gmail.com', '$2y$10$1ckZpBBtQ9wwD.JiAQLFKOd/uOpHtZQaGYe3wgOrFk0ybb43oXPSq', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coffee`
--
ALTER TABLE `coffee`
  ADD PRIMARY KEY (`CoffeeID`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`StoreID`);

--
-- Indexes for table `storecoffee`
--
ALTER TABLE `storecoffee`
  ADD KEY `StoreID` (`StoreID`),
  ADD KEY `CoffeeID` (`CoffeeID`);

--
-- Indexes for table `transactiondetails`
--
ALTER TABLE `transactiondetails`
  ADD KEY `TransactionID` (`TransactionID`),
  ADD KEY `CoffeeID` (`CoffeeID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`TransactionID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `StoreID` (`StoreID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD UNIQUE KEY `UserEmail` (`UserEmail`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `storecoffee`
--
ALTER TABLE `storecoffee`
  ADD CONSTRAINT `storecoffee_ibfk_1` FOREIGN KEY (`StoreID`) REFERENCES `store` (`StoreID`) ON DELETE CASCADE,
  ADD CONSTRAINT `storecoffee_ibfk_2` FOREIGN KEY (`CoffeeID`) REFERENCES `coffee` (`CoffeeID`) ON DELETE CASCADE;

--
-- Constraints for table `transactiondetails`
--
ALTER TABLE `transactiondetails`
  ADD CONSTRAINT `transactiondetails_ibfk_1` FOREIGN KEY (`TransactionID`) REFERENCES `transactions` (`TransactionID`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactiondetails_ibfk_2` FOREIGN KEY (`CoffeeID`) REFERENCES `coffee` (`CoffeeID`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`StoreID`) REFERENCES `store` (`StoreID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
