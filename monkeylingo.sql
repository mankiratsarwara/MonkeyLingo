-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2022 at 05:13 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `monkeylingo`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE `client` (
  `username` varchar(30) NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `password_hash` text NOT NULL,
  `api_key` text NOT NULL,
  `license_number` text NOT NULL,
  `license_start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `license_end_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`username`, `first_name`, `last_name`, `password_hash`, `api_key`, `license_number`, `license_start_date`, `license_end_date`) VALUES
('fet', 'Mankirat Singh', 'Sarwara', '$2y$10$Vzm9OPwH4Su64ijESYP2Je8pFcMoZzv8jTozd.E.l/9r.6vsdrurS', '625e074b9337a', '625e074b9337c', '2022-04-19 00:50:19', '2022-10-19 00:50:19');

-- --------------------------------------------------------

--
-- Table structure for table `detect`
--

DROP TABLE IF EXISTS `detect`;
CREATE TABLE `detect` (
  `detect_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `original_string` text NOT NULL,
  `detected_language` text NOT NULL,
  `detect_date` timestamp NULL DEFAULT NULL,
  `detect_completed_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `translate`
--

DROP TABLE IF EXISTS `translate`;
CREATE TABLE `translate` (
  `translate_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `original_string` text NOT NULL,
  `converted_string` text NOT NULL,
  `original_language` text NOT NULL,
  `converted_language` text NOT NULL,
  `translate_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `translate_completed_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `detect`
--
ALTER TABLE `detect`
  ADD PRIMARY KEY (`detect_id`);

--
-- Indexes for table `translate`
--
ALTER TABLE `translate`
  ADD PRIMARY KEY (`translate_id`),
  ADD KEY `username_fk` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detect`
--
ALTER TABLE `detect`
  MODIFY `detect_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `translate`
--
ALTER TABLE `translate`
  MODIFY `translate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `translate`
--
ALTER TABLE `translate`
  ADD CONSTRAINT `username_fk` FOREIGN KEY (`username`) REFERENCES `client` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
