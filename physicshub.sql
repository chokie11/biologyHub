-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 21, 2026 at 09:34 AM
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
-- Database: `physicshub`
--

-- --------------------------------------------------------

--
-- Table structure for table `lesson_progress`
--

CREATE TABLE `lesson_progress` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `lesson_name` varchar(100) DEFAULT NULL,
  `slide_number` int(11) DEFAULT 1,
  `completed` tinyint(4) DEFAULT 0,
  `quiz_score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_progress`
--

INSERT INTO `lesson_progress` (`id`, `student_id`, `lesson_name`, `slide_number`, `completed`, `quiz_score`) VALUES
(1, 10, 'The_Cell', 46, 1, 3),
(264, 10, 'Cell_cycle', 39, 1, 1),
(267, 8, 'The_Cell', 3, 0, 0),
(268, 10, 'Cell_Requirements', 4, 0, NULL),
(269, 10, 'Photosynthesis', 3, 0, NULL),
(270, 10, 'Transport_Mechanisms', 2, 0, NULL),
(271, 10, 'Metabolism_respiration', 3, 0, NULL),
(272, 10, 'Plant_Tissues', 3, 0, NULL),
(273, 11, 'The_Cell', 46, 1, 2),
(274, 11, 'Cell_cycle', 2, 0, NULL),
(275, 11, 'Cell_Requirements', 26, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp` varchar(10) DEFAULT NULL,
  `verified` int(11) DEFAULT 0,
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `password`, `created_at`, `otp`, `verified`, `otp_code`, `otp_expire`) VALUES
(8, 'chokie', 'darkmassterlabatiao@gmail.com', '$2y$10$/APpLLeym8PmRoBDIgZ8VO0tsGNxD2G4UFMCuMWXpslHo8.msXNmC', '2026-02-15 09:17:34', '578035', 1, NULL, '2026-02-15 10:19:38'),
(9, 'dj', '2022308224@pampangastateu.edu.ph', '$2y$10$VUOX43H1.pnd1VJa3h/VGubNnaMRvq/yxgtTMCKA2ERiiFG.Rom8m', '2026-02-15 09:55:35', '916785', 0, NULL, NULL),
(10, 'dj', 'darkmadsasterlabatiao@gmail.com', '$2y$10$KhIicoCfEMQaGaDyR8iuf.RqV6eWrS7uC2wDa6v/P6dT3dYCRuTD2', '2026-02-15 09:56:38', '292090', 0, NULL, NULL),
(11, 'Deejay', 'darkmasterlabatiao@gmail.com', '$2y$10$t8RKlriB/aR/EiUKhYg5w.FcQpZnanu9.S23nRRkbwAQXXqJJP/ra', '2026-02-18 09:13:01', '', 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_progress` (`student_id`,`lesson_name`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=276;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
