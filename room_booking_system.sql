-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 01. Des, 2024 19:54 PM
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
  `guest_name` varchar(100) DEFAULT NULL,
  `guest_email` varchar(100) DEFAULT NULL,
  `room_id` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `adults` int(11) NOT NULL,
  `children` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `preferred_floor` int(11) DEFAULT NULL,
  `near_elevator` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dataark for tabell `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `guest_name`, `guest_email`, `room_id`, `check_in`, `check_out`, `adults`, `children`, `total_price`, `preferred_floor`, `near_elevator`, `created_at`) VALUES
(1, NULL, 'Amadeus Hovden', 'hovdenamadeus@gmail.com', 2, '2024-12-01', '2024-12-08', 1, 0, 21000.00, NULL, NULL, '2024-12-01 15:19:30'),
(5, NULL, 'Sander Halvorsen', 'sander@gmail.com', 3, '2024-12-01', '2024-12-03', 1, 0, 6000.00, NULL, NULL, '2024-12-01 15:23:39'),
(6, NULL, 'Kjersti Lunde', 'kjersti@gmail.com', 4, '2024-12-01', '2024-12-10', 1, 0, 18000.00, NULL, NULL, '2024-12-01 18:05:28'),
(7, 4, 'Regular User', NULL, 1, '2024-12-13', '2024-12-11', 1, 0, -6000.00, NULL, NULL, '2024-12-01 18:42:40'),
(8, 4, 'Regular User', NULL, 1, '2024-12-31', '2025-01-08', 1, 0, 24000.00, NULL, NULL, '2024-12-01 18:47:30'),
(9, NULL, 'Sindre Norbom', 'sindre@gmail.com', 1, '2024-12-04', '2024-12-11', 1, 0, 21000.00, NULL, NULL, '2024-12-01 18:48:46');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `loyalty_program`
--

CREATE TABLE `loyalty_program` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bookings_count` int(11) DEFAULT 0,
  `discount_percent` decimal(5,2) DEFAULT 0.00,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `room_type` enum('suite','family','double','single') NOT NULL,
  `floor` int(11) NOT NULL,
  `near_elevator` tinyint(1) DEFAULT 0,
  `max_adults` int(11) NOT NULL,
  `max_children` int(11) NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `availability` tinyint(1) DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dataark for tabell `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `room_type`, `floor`, `near_elevator`, `max_adults`, `max_children`, `price_per_night`, `availability`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Kongen', 'suite', 2, 1, 5, 2, 3000.00, 1, 'A luxurious suite fit for royalty.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(2, 'Dronningen', 'suite', 2, 0, 5, 2, 3000.00, 1, 'Elegant suite with stunning views.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(3, 'Prinsen', 'suite', 2, 1, 5, 2, 3000.00, 1, 'Spacious suite with premium amenities.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(4, 'Familie 1', 'family', 2, 1, 4, 2, 2000.00, 1, 'Ideal for family stays with multiple beds.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(5, 'Familie 2', 'family', 1, 0, 4, 2, 2000.00, 1, 'Family room with easy ground-floor access.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(6, 'Familie 3', 'family', 2, 0, 4, 2, 2000.00, 1, 'Quiet family room away from the elevator.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(7, 'Familie 4', 'family', 1, 1, 4, 2, 2000.00, 1, 'Convenient family room near the elevator.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(8, 'Familie 5', 'family', 2, 0, 4, 2, 2000.00, 1, 'Comfortable family room with modern decor.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(9, 'Dobbel 1', 'double', 1, 1, 2, 1, 1500.00, 1, 'Cozy double room near the elevator.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(10, 'Dobbel 2', 'double', 2, 0, 2, 1, 1500.00, 1, 'Spacious double room on a quiet floor.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(11, 'Dobbel 3', 'double', 1, 0, 2, 1, 1500.00, 1, 'Affordable double room for couples.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(12, 'Dobbel 4', 'double', 2, 1, 2, 1, 1500.00, 1, 'Bright double room with modern amenities.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(13, 'Dobbel 5', 'double', 1, 1, 2, 1, 1500.00, 1, 'Comfortable room near main facilities.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(14, 'Dobbel 6', 'double', 2, 0, 2, 1, 1500.00, 1, 'Quiet room with beautiful views.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(15, 'Dobbel 7', 'double', 1, 0, 2, 1, 1500.00, 1, 'Affordable and conveniently located.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(16, 'Dobbel 8', 'double', 2, 1, 2, 1, 1500.00, 1, 'Great room for short stays.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(17, 'Dobbel 9', 'double', 1, 1, 2, 1, 1500.00, 1, 'Accessible and welcoming.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(18, 'Dobbel 10', 'double', 2, 0, 2, 1, 1500.00, 1, 'Modern design for comfortable living.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(19, 'Enkel 1', 'single', 1, 1, 1, 0, 1000.00, 1, 'Cozy single room near the elevator.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(20, 'Enkel 2', 'single', 2, 0, 1, 0, 1000.00, 1, 'Quiet single room on the top floor.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(21, 'Enkel 3', 'single', 1, 0, 1, 0, 1000.00, 1, 'Budget-friendly single room.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(22, 'Enkel 4', 'single', 2, 1, 1, 0, 1000.00, 1, 'Convenient single room with modern decor.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(23, 'Enkel 5', 'single', 1, 1, 1, 0, 1000.00, 1, 'Compact room for solo travelers.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(24, 'Enkel 6', 'single', 2, 0, 1, 0, 1000.00, 1, 'Private room on the quieter floor.', '2024-12-01 10:08:27', '2024-12-01 10:08:27'),
(25, 'Enkel 7', 'single', 1, 1, 1, 0, 1000.00, 1, 'Simple room with necessary amenities.', '2024-12-01 10:08:27', '2024-12-01 10:08:27');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `unavailable_dates`
--

CREATE TABLE `unavailable_dates` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dataark for tabell `unavailable_dates`
--

INSERT INTO `unavailable_dates` (`id`, `room_id`, `start_date`, `end_date`, `reason`) VALUES
(1, 1, '2024-12-01', '2024-12-03', 'Renovation by admin user ID 3');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','gjest','bruker') DEFAULT 'gjest',
  `failed_attempts` int(11) DEFAULT 0,
  `lock_time` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dataark for tabell `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `failed_attempts`, `lock_time`, `created_at`) VALUES
(3, 'Admin User', 'admin@example.com', '$2y$10$kTuEsPghAO7WULKWaBwxiuFrOx.11NKYx0plk/XjCryAcdmXa6Y6i', 'admin', 0, NULL, '2024-11-30 14:34:09'),
(4, 'Regular User', 'user@example.com', '$2y$10$27q5NATOXiqSP11cfNL7ZuJnv.Enquq27vwt1Fs8e7BU9N3VyvDBS', 'bruker', 0, NULL, '2024-11-30 14:36:57');

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
-- Indexes for table `loyalty_program`
--
ALTER TABLE `loyalty_program`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unavailable_dates`
--
ALTER TABLE `unavailable_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `loyalty_program`
--
ALTER TABLE `loyalty_program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `unavailable_dates`
--
ALTER TABLE `unavailable_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Begrensninger for tabell `loyalty_program`
--
ALTER TABLE `loyalty_program`
  ADD CONSTRAINT `loyalty_program_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Begrensninger for tabell `unavailable_dates`
--
ALTER TABLE `unavailable_dates`
  ADD CONSTRAINT `unavailable_dates_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
