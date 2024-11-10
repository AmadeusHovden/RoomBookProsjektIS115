-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 10. Nov, 2024 14:05 PM
-- Tjener-versjon: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `room_booking_system`
--

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `room_id` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `adults` int(11) NOT NULL,
  `children` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `loyalty`
--

CREATE TABLE `loyalty` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bookings_count` int(11) DEFAULT 0,
  `discount_percent` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_type` enum('suite','family','double','single') NOT NULL,
  `floor` int(11) NOT NULL,
  `near_elevator` tinyint(1) DEFAULT NULL,
  `max_adults` int(11) NOT NULL,
  `max_children` int(11) NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `availability` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dataark for tabell `rooms`
--

INSERT INTO `rooms` (`id`, `room_type`, `floor`, `near_elevator`, `max_adults`, `max_children`, `price_per_night`, `availability`) VALUES
(1, 'suite', 2, 1, 5, 2, 3000.00, 1),
(2, 'suite', 2, 0, 5, 2, 3000.00, 1),
(3, 'suite', 2, 1, 5, 2, 3000.00, 1),
(4, 'family', 2, 1, 4, 2, 2000.00, 1),
(5, 'family', 1, 0, 4, 2, 2000.00, 1),
(6, 'family', 2, 0, 4, 2, 2000.00, 1),
(7, 'family', 1, 1, 4, 2, 2000.00, 1),
(8, 'family', 2, 0, 4, 2, 2000.00, 1),
(9, 'double', 1, 1, 2, 1, 1500.00, 1),
(10, 'double', 2, 0, 2, 1, 1500.00, 1),
(11, 'double', 1, 0, 2, 1, 1500.00, 1),
(12, 'double', 2, 1, 2, 1, 1500.00, 1),
(13, 'double', 1, 1, 2, 1, 1500.00, 1),
(14, 'double', 2, 0, 2, 1, 1500.00, 1),
(15, 'double', 1, 0, 2, 1, 1500.00, 1),
(16, 'double', 2, 1, 2, 1, 1500.00, 1),
(17, 'double', 1, 1, 2, 1, 1500.00, 1),
(18, 'double', 2, 0, 2, 1, 1500.00, 1),
(19, 'single', 1, 1, 1, 0, 1000.00, 1),
(20, 'single', 2, 0, 1, 0, 1000.00, 1),
(21, 'single', 1, 0, 1, 0, 1000.00, 1),
(22, 'single', 2, 1, 1, 0, 1000.00, 1),
(23, 'single', 1, 1, 1, 0, 1000.00, 1),
(24, 'single', 2, 0, 1, 0, 1000.00, 1),
(25, 'single', 1, 1, 1, 0, 1000.00, 1);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('guest','loyalty','admin') DEFAULT 'guest',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `loyalty`
--
ALTER TABLE `loyalty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loyalty`
--
ALTER TABLE `loyalty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Begrensninger for dumpede tabeller
--

--
-- Begrensninger for tabell `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Begrensninger for tabell `loyalty`
--
ALTER TABLE `loyalty`
  ADD CONSTRAINT `loyalty_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
