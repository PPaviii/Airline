-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 18, 2019 at 10:10 PM
-- Server version: 10.1.40-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s264970`
--

-- --------------------------------------------------------

--
-- Table structure for table `Seat`
--

DROP TABLE IF EXISTS `Seat`;
CREATE TABLE `Seat` (
  `Seat` varchar(5) NOT NULL,
  `Status` int(2) NOT NULL,
  `Username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Seat`
--

INSERT INTO `Seat` (`Seat`, `Status`, `Username`) VALUES
('A4', 0, 'u1@p.it'),
('B2', 1, 'u2@p.it'),
('B3', 1, 'u2@p.it'),
('B4', 1, 'u2@p.it'),
('D4', 0, 'u1@p.it'),
('F4', 0, 'u2@p.it');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`Username`, `Password`) VALUES
('u1@p.it', '$2y$10$1UzNBiLuu8iqUc0lAlyYY.Emmnm2rY1M/o57sFprL0MZNMuW.nWAO'),
('u2@p.it', '$2y$10$WSolJxuCleDDnu0X3x65KOOZRUwV31v1K1z.TsmvVpS0BP0RD7NaW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Seat`
--
ALTER TABLE `Seat`
  ADD PRIMARY KEY (`Seat`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`Username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
