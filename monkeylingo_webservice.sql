-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2022 at 08:23 PM
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
-- Database: `monkeylingo_webservice`
--
CREATE DATABASE IF NOT EXISTS `monkeylingo_webservice` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `monkeylingo_webservice`;

-- --------------------------------------------------------

--
-- Table structure for table `detect`
--

DROP TABLE IF EXISTS `detect`;
CREATE TABLE `detect` (
  `detect_id` int(11) NOT NULL,
  `username` text NOT NULL,
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
  `username` text NOT NULL,
  `original_string` text NOT NULL,
  `converted_string` text NOT NULL,
  `original_language` text NOT NULL,
  `converted_language` text NOT NULL,
  `translate_date` timestamp NULL DEFAULT NULL,
  `translate_completed_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detect`
--
ALTER TABLE `detect`
  ADD PRIMARY KEY (`detect_id`);

--
-- Indexes for table `translate`
--
ALTER TABLE `translate`
  ADD PRIMARY KEY (`translate_id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
