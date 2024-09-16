-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2024 at 09:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dh`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_data`
--

CREATE TABLE `user_data` (
  `id` int(11) NOT NULL,
  `user_info` text NOT NULL,
  `doctor` text NOT NULL,
  `patient` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_data`
--

INSERT INTO `user_data` (`id`, `user_info`, `doctor`, `patient`) VALUES
(1, 'Name: what is your name? , Email: what is your email? , Phone: what is your number? ', '', ''),
(2, 'Q1: q1, Q2: q2, Q3: q5, Q4: q5, Q5: q5', 'Q1: q1, Q2: q2, Q3: q5, Q4: q5, Q5: q5', ''),
(3, 'Q1: what is q1? , Q2: what is q2? , Q3: what is q5? , Q4: what is q5? , Q5: what is q5? ', 'Q1: what is q1? , Q2: what is q2? , Q3: what is q5? , Q4: what is q5? , Q5: what is q5? ', ''),
(4, 'Q1: answer1, Q2: answer1, Q3: answer1, Q4: answer1, Q5: answer1', '', 'A1: answer1, A2: answer2, A3: answer3, A4: answer4, A5: answer5'),
(5, '', '{\"Q1\":\"qqqqq1\",\"Q2\":\"qqqqq2\",\"Q3\":\"qqqqq3\",\"Q4\":\"qqqqq4\",\"Q5\":\"qqqqq5\"}', '{\"qqqqq1\":\"aaa1\",\"qqqqq2\":\"aaaa2\",\"qqqqq3\":\"aaa3\",\"qqqqq4\":\"aaaa4\",\"qqqqq5\":\"aaaa5\"}');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_data`
--
ALTER TABLE `user_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
