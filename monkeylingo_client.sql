-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2022 at 08:22 PM
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
-- Database: `monkeylingo_client`
--
CREATE DATABASE IF NOT EXISTS `monkeylingo_client` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `monkeylingo_client`;

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
  `license_end_date` timestamp NULL DEFAULT NULL,
  `token` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`username`, `first_name`, `last_name`, `password_hash`, `api_key`, `license_number`, `license_start_date`, `license_end_date`, `token`) VALUES
('fet', 'Mankirat Singh', 'Sarwara', '$2y$10$Vzm9OPwH4Su64ijESYP2Je8pFcMoZzv8jTozd.E.l/9r.6vsdrurS', '625e074b9337a', '625e074b9337c', '2022-04-19 00:50:19', '2022-10-19 00:50:19', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2F1dGhjb250cm9sbGVyL2F1dGgiLCJhdWQiOiJodHRwOi8vbG9jYWxob3N0L3dlYmNsaWVudC9kZXRlY3QiLCJpYXQiOjE2NTExMTE2NTMsImV4cCI6MTY2Njg4MTY1M30.XRP0vAXvB8ZF2CAtgtroFR7EmawmRlY9wY1lRw3t-hE');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
