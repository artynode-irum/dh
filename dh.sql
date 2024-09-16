-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2024 at 04:37 PM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` text NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `profile_picture` varchar(255) NOT NULL DEFAULT 'default.png',
  `hpassword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `username`, `password`, `profile_picture`, `hpassword`) VALUES
(1, 'admin@admin.com', 'admin', '$2y$10$uwxGoVB0zvrGC7rCfATv1.OtvhPd/5.VjR5EJW7YDkm', 'profile.png', '123'),
(8, 'test@test.com', 'TestAdmin', '12345678', 'default.png', '$2y$10$AxpqxSZzIt8CFK45m/kNi.vx4GtkiaxQqKh0NTKg0AjcZ91OTuamu');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `status` enum('pending','approved','assigned','disapproved') DEFAULT 'pending',
  `video_link` varchar(255) DEFAULT NULL,
  `prescription` text DEFAULT NULL,
  `request_date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `signature` varchar(255) NOT NULL,
  `medicare_number` varchar(255) NOT NULL DEFAULT 'Not provided',
  `medicare_expiration_date` varchar(255) NOT NULL,
  `payment` text NOT NULL,
  `title` text NOT NULL,
  `name` text NOT NULL,
  `gender` text NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `dob` text NOT NULL,
  `address` text NOT NULL,
  `card_number` int(11) NOT NULL,
  `security_code` int(11) NOT NULL,
  `expiration_date` text NOT NULL,
  `country` text NOT NULL,
  `appointment_type` text NOT NULL,
  `appointment_day` varchar(255) NOT NULL,
  `appointment_time` varchar(255) NOT NULL,
  `created_date_2` date DEFAULT NULL,
  `notes` text NOT NULL,
  `referral_comment` text NOT NULL,
  `addressee_fname` text NOT NULL,
  `addressee_address` text NOT NULL,
  `addressee_phone` text NOT NULL,
  `provider_number` text NOT NULL,
  `tests_required` text NOT NULL,
  `clinic_notes` text NOT NULL,
  `city_code` varchar(60) DEFAULT NULL,
  `postcode` varchar(20) DEFAULT NULL,
  `state_code` varchar(20) DEFAULT NULL,
  `area_timeZone` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `description`, `status`, `video_link`, `prescription`, `request_date_time`, `created_date`, `signature`, `medicare_number`, `medicare_expiration_date`, `payment`, `title`, `name`, `gender`, `email`, `phone`, `dob`, `address`, `card_number`, `security_code`, `expiration_date`, `country`, `appointment_type`, `appointment_day`, `appointment_time`, `created_date_2`, `notes`, `referral_comment`, `addressee_fname`, `addressee_address`, `addressee_phone`, `provider_number`, `tests_required`, `clinic_notes`, `city_code`, `postcode`, `state_code`, `area_timeZone`) VALUES
(132, 7004, NULL, '', 'pending', NULL, 'Not defined yet', '2024-09-09 14:00:21', '2024-09-09 19:00:21', '', '', '2032-07-25', '', 'Miss', 'Rana Carver', 'Female', 'qutygabi@teleg.eu', '40', '2007-09-04', '', 0, 0, '', 'American Samoa', 'Telehealth', '2024-09-12', '05:00', NULL, '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL),
(139, 1, 1, 'assign', 'assigned', '13123', 'dxa', '2024-09-13 14:20:51', '2024-09-10 11:06:51', '', 'acscac', '2039-08-18', '', 'Mrs', 'Christopher Guzman', 'Unknown', 'pyjapaqu@cyclelove.cc', '39', '2007-09-04', 'erfwecfw', 0, 0, '', 'Angola', 'Telehealth', '2024-09-12', '02:00', NULL, '{\"Symptoms\":\"Voluptas et ea magna\",\"Clinical Findings\":null,\"Diagnosis\":\"Atque fugiat dignis\",\"Plan\":\"Excepturi eaque unde\",\"File Paths\":[\"uploads\\/Ultra HD, 4K, Landscape, 35874556.jpg\",\"uploads\\/Ultra HD, 4K, Landscape, 43760389.jpg\"]}', '12', '21', '21', '21', '21', 'a', 'a', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `medical_certificate_id` int(11) DEFAULT NULL,
  `sender` enum('doctor','patient','admin') DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `medical_certificate_id`, `sender`, `message`, `timestamp`) VALUES
(1, 29, 'doctor', 'heyyy', '2024-08-19 08:08:14'),
(2, 29, 'patient', 'heyyy from patient', '2024-08-19 08:14:15'),
(3, 27, 'doctor', 'doctor wants to know your phone number and email? ', '2024-08-19 08:15:09'),
(4, 27, 'patient', 'my email is patient@patient.com.\r\n', '2024-08-19 08:16:02'),
(5, 29, 'admin', 'i am admin', '2024-08-19 08:22:22'),
(6, 37, 'admin', 'hey', '2024-08-19 08:46:32'),
(7, 37, 'doctor', 'hey from doctor', '2024-08-19 08:46:53'),
(8, 37, 'patient', 'hey from patient\r\n', '2024-08-19 08:47:23'),
(9, 37, 'doctor', 'jhdcshdb cshdb sd c', '2024-08-24 07:56:27'),
(10, 37, 'doctor', 'kdchsbc sbc ', '2024-08-24 07:57:13'),
(11, 37, 'admin', 'sdhbcjshdbc jsdb c', '2024-08-24 07:57:36'),
(12, 37, 'doctor', 'kdchsbc sbc ', '2024-08-24 07:57:39'),
(13, 37, 'doctor', 'kdchsbc sbc ', '2024-08-24 07:57:53'),
(14, 37, 'doctor', 'hey doctor here', '2024-08-24 07:58:42'),
(15, 37, 'admin', 'hey admin here', '2024-08-24 07:59:04'),
(16, 37, 'patient', 'hey patient here', '2024-08-24 07:59:17'),
(17, 1, 'admin', 'hi', '2024-08-28 14:08:05'),
(18, 52, 'patient', 'hey', '2024-08-29 10:54:30'),
(19, 52, 'patient', 'jhsdchsdbcs', '2024-08-29 10:57:53'),
(20, 52, 'patient', 'skjdhjksvkjdcn', '2024-08-29 10:58:02'),
(21, 52, 'patient', 'avdhjsdabvabdchjbahdbc', '2024-08-29 10:58:23'),
(22, 52, 'patient', 'sdchasbdc dcsa', '2024-08-29 10:58:54'),
(23, 54, 'patient', 'hello', '2024-08-31 11:43:38'),
(24, 54, 'patient', 'rejected\r\n', '2024-08-31 11:44:54'),
(25, 58, 'patient', 'sjkdfsdbc', '2024-08-31 11:45:37'),
(26, 58, 'admin', 'ksehfckjsdbc', '2024-08-31 11:47:44'),
(27, 54, 'doctor', 'kjsdckjsdbc', '2024-08-31 11:47:56'),
(28, 59, 'patient', 'hey', '2024-08-31 18:32:54'),
(29, 59, 'admin', 'hey from admin\r\n\r\n', '2024-08-31 18:33:34'),
(30, 59, 'doctor', 'hey from doctor\r\n', '2024-08-31 18:34:41'),
(31, 110, 'patient', 'avdasdcsda', '2024-09-09 11:21:48'),
(32, 110, 'admin', 'afv asd', '2024-09-09 11:22:00');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'DoctorHelp', 'doctorHelp@doctorhelp.cpm', 'Contact form text.', '2024-08-06 20:01:08');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `doctor_name` text NOT NULL,
  `password` varchar(50) NOT NULL,
  `hpassword` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL DEFAULT 'default.png',
  `signature` varchar(255) NOT NULL DEFAULT 'signature.png',
  `aphra` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `email`, `username`, `doctor_name`, `password`, `hpassword`, `profile_picture`, `signature`, `aphra`) VALUES
(1, 'doctor@doctor.com', 'doctor', 'raghu', '123', '$2y$10$Npg64hVDWOWie5zRv2htJepABkJsncVaz1/QIiuWMuy49GIs5pSNG', '3255382.jpg', 'signature.png', '11'),
(9, 'dd@dd.dd', 'doctor2', '', 'jkl', '$2y$10$nDLga.7St9qvM9flrMUabOVv61aVKOvh.jzHtAGJB75xpbF0gU7wW', 'default.png', '', '22');

-- --------------------------------------------------------

--
-- Table structure for table `medical_certificate`
--

CREATE TABLE `medical_certificate` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `status` enum('pending','approved','assigned','disapproved') DEFAULT 'pending',
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `prescription` text DEFAULT NULL,
  `signature` varchar(255) NOT NULL,
  `request_date_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment` text DEFAULT NULL,
  `certificate_type` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `illness_description` text DEFAULT NULL,
  `title` text NOT NULL,
  `name` text DEFAULT NULL,
  `gender` text NOT NULL,
  `email` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `dob` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `card_number` int(11) DEFAULT NULL,
  `security_code` int(11) DEFAULT NULL,
  `expiration_date` text DEFAULT NULL,
  `country` text DEFAULT NULL,
  `document_path` varchar(255) NOT NULL,
  `created_date_2` date NOT NULL DEFAULT current_timestamp(),
  `notes` text NOT NULL,
  `disapproval_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_certificate`
--

INSERT INTO `medical_certificate` (`id`, `patient_id`, `doctor_id`, `description`, `status`, `from_date`, `to_date`, `prescription`, `signature`, `request_date_time`, `created_date`, `payment`, `certificate_type`, `reason`, `illness_description`, `title`, `name`, `gender`, `email`, `phone`, `dob`, `address`, `card_number`, `security_code`, `expiration_date`, `country`, `document_path`, `created_date_2`, `notes`, `disapproval_reason`) VALUES
(1, 1, 1, 'medical certificate req.', 'approved', '2024-08-12', '2024-08-13', 'medi certi.', '', '2024-08-12 08:53:08', '2024-08-12 08:53:08', '', NULL, NULL, '', '', '', '', '', '0', '', '', 0, 0, '', '', '', '2024-08-23', '', NULL),
(113, 7013, 1, '', 'approved', '2024-09-20', '2024-09-24', NULL, '', '2024-09-12 12:59:02', '2024-09-12 12:59:02', '0', 'work', 'Headache', 'nil', '315890000', 'DexterJohn john', '1', 'kofuzy@pelagius.net', '0412345678', '2007-09-01', '', 0, 0, '', '', 'localhost/doctorhelp/dh/patient/patient_doc/', '2024-09-12', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `patient_name` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `hpassword` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT 'default.jpg',
  `title` text NOT NULL,
  `name` text NOT NULL,
  `last_name` text NOT NULL,
  `phone` text NOT NULL,
  `address` text DEFAULT NULL,
  `dob` text NOT NULL,
  `gender` text NOT NULL,
  `medicare_detail` text NOT NULL,
  `expiry_year` text NOT NULL,
  `expiry_month` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `email_verify` varchar(20) NOT NULL,
  `token` varchar(225) NOT NULL,
  `token_expire` datetime DEFAULT NULL,
  `medirecord_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `email`, `username`, `patient_name`, `password`, `hpassword`, `profile_picture`, `title`, `name`, `last_name`, `phone`, `address`, `dob`, `gender`, `medicare_detail`, `expiry_year`, `expiry_month`, `created_date`, `email_verify`, `token`, `token_expire`, `medirecord_id`) VALUES
(1, 'patient@patient.com', 'patient', 'adam', '123', '123', '7104754.jpg', '', '', '', '', '', '', '', '', '', '', '2024-08-19 10:49:17', '1', '', NULL, NULL),
(7004, 'qutygabi@teleg.eu', 'Emi Cobb', '', '$2y$10$G2iR1aHu13LkJXsNln6/EuROvRg3/TdQvrpLRT8mE9EcItIjHHKua', 'cddce5b89ea61f929754', 'default.jpg', '', '', '', '', '', '', '', '', '', '', '2024-09-09 08:13:59', '1', '7942433469116182735c08e5e9c09db3e760c4b2bbdd8ca6cc463c5159ec4a6a805c0db05ff73e0156319ae364d07488e1d6', '2024-09-09 15:29:31', NULL),
(7013, 'kofuzy@pelagius.net', '1', '', '$2y$10$qEa.e.X3xMqxRWzrcsw61OcBH.YYuRQX/lNoWcDpAhB8iKj5S41c6', '16ca38a63737ed44ea8d', 'default.jpg', '', '', '', '', '', '', '', '', '', '', '2024-09-12 12:43:17', '1', '', NULL, '45c976ed-ce24-42c3-ab3f-c735935cd799'),
(7016, 'pyjapaqu@cyclelove.cc', 'dasdcsd', '', '123', '$2y$10$AcsdSXGnflTxStt.SJFJw.BQjMlY55qbBujsDkRlTXI/Dra6xTsVu', 'default.jpg', '', '', '', '', NULL, '', '', '', '', '', '2024-09-13 13:33:45', '1', '', NULL, '1241d21f-32a6-44df-88b5-8559957b0873'),
(7017, 'rizihem@mailinator.com', 'TaShya Pugh', '', '$2y$10$wUrtK2HBOpyJrKa.7qFHLeLYIrfgKp7GNbdqPQL6SqxL65Ja.c2Hi', 'dac3c41c048b8bc49421', 'default.jpg', '', '', '', '', NULL, '', '', '', '', '', '2024-09-13 13:48:02', '1', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `status` enum('pending','approved','assigned','disapproved') DEFAULT 'pending',
  `prescribe` text DEFAULT NULL,
  `request_date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `signature` varchar(255) NOT NULL,
  `payment` text DEFAULT NULL,
  `title` text NOT NULL,
  `name` text NOT NULL,
  `gender` text NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `dob` text NOT NULL,
  `address` text NOT NULL,
  `card_number` int(11) NOT NULL,
  `security_code` int(11) NOT NULL,
  `expiration_date` text NOT NULL,
  `country` text NOT NULL,
  `treatment` text NOT NULL,
  `prescriptionafter` text NOT NULL,
  `dosage` text NOT NULL,
  `previously_taken_medi` varchar(255) NOT NULL,
  `currentlyppb` text NOT NULL,
  `health_condition` text NOT NULL,
  `known_allergies` varchar(255) NOT NULL,
  `reason_known_allergies_yes` text NOT NULL,
  `over_the_counter_drugs` varchar(255) NOT NULL,
  `healthcare_provider_person_recently` varchar(255) NOT NULL,
  `specific_medication_seeking` varchar(255) NOT NULL,
  `known_nill_allergies` text NOT NULL,
  `medication_used_previously` varchar(255) NOT NULL,
  `plan_schedule` varchar(255) NOT NULL,
  `appointment_type` varchar(255) NOT NULL,
  `appointment_day` text NOT NULL,
  `appointment_time` text NOT NULL,
  `created_date_2` date DEFAULT current_timestamp(),
  `adverse_reactions` text NOT NULL,
  `document_path` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  `city_code` varchar(60) DEFAULT NULL,
  `postcode` varchar(20) DEFAULT NULL,
  `state_code` varchar(20) DEFAULT NULL,
  `area_timeZone` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`id`, `patient_id`, `doctor_id`, `description`, `status`, `prescribe`, `request_date_time`, `created_date`, `signature`, `payment`, `title`, `name`, `gender`, `email`, `phone`, `dob`, `address`, `card_number`, `security_code`, `expiration_date`, `country`, `treatment`, `prescriptionafter`, `dosage`, `previously_taken_medi`, `currentlyppb`, `health_condition`, `known_allergies`, `reason_known_allergies_yes`, `over_the_counter_drugs`, `healthcare_provider_person_recently`, `specific_medication_seeking`, `known_nill_allergies`, `medication_used_previously`, `plan_schedule`, `appointment_type`, `appointment_day`, `appointment_time`, `created_date_2`, `adverse_reactions`, `document_path`, `notes`, `city_code`, `postcode`, `state_code`, `area_timeZone`) VALUES
(148, 1, 1, '', 'assigned', NULL, '2024-09-12 10:21:28', '2024-09-10 14:05:16', '', '0', 'Miss', 'Matthew Kemp', 'Male', 'xejet@mailinator.com', '29', '2007-09-04', 'Aut consequatur asp', 0, 0, '', 'Anguilla', 'Morning Sickness', 'Zofran Zydis Wafers 8mg (4 wafers)', '', 'Yes', 'None', 'Voluptate perferendi', 'Yes', 'sdcsc', 'Esse earum voluptate', 'Yes', '', '', '', '', 'Telehealth', '2024-09-17', '07:00', '2024-09-10', 'Yes', 'http://localhost/dh/frontend/null', '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `status` enum('pending','approved','assigned','disapproved') DEFAULT 'pending',
  `prescription` text DEFAULT NULL,
  `request_date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `signature` varchar(255) NOT NULL,
  `created_date_2` date DEFAULT current_timestamp(),
  `payment` text NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `dob` text NOT NULL,
  `card_number` text NOT NULL,
  `security_code` text NOT NULL,
  `country` text NOT NULL,
  `address` text NOT NULL,
  `gender` text NOT NULL,
  `title` text NOT NULL,
  `appointment_type` text NOT NULL,
  `appointment_day` text NOT NULL,
  `appointment_time` text NOT NULL,
  `medicare_number` text NOT NULL,
  `medicare_expiration_date` text NOT NULL,
  `other_details` text NOT NULL,
  `referral_provider` text NOT NULL,
  `referral_type` text NOT NULL,
  `notes` text NOT NULL,
  `city_code` varchar(60) DEFAULT NULL,
  `postcode` varchar(20) DEFAULT NULL,
  `state_code` varchar(20) DEFAULT NULL,
  `area_timeZone` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referrals`
--

INSERT INTO `referrals` (`id`, `patient_id`, `doctor_id`, `description`, `status`, `prescription`, `request_date_time`, `created_date`, `signature`, `created_date_2`, `payment`, `name`, `email`, `phone`, `dob`, `card_number`, `security_code`, `country`, `address`, `gender`, `title`, `appointment_type`, `appointment_day`, `appointment_time`, `medicare_number`, `medicare_expiration_date`, `other_details`, `referral_provider`, `referral_type`, `notes`, `city_code`, `postcode`, `state_code`, `area_timeZone`) VALUES
(116, 7017, NULL, 'hfnjfjh', 'pending', NULL, '2024-09-13 13:48:02', '2024-09-13 18:48:02', '', '2024-09-13', '39', 'TaShya Pugh', 'rizihem@mailinator.com', '28', '2007-09-06', '', '', 'Gabon', 'Ut temporibus id dol', 'Male', 'Dr', 'Telehealth', '2024-09-19', '02:00', '878', '2024-06-06', '', 'Larissa Sexton', 'Pathology', '', NULL, NULL, NULL, NULL),
(117, 7016, NULL, '', 'pending', NULL, '2024-09-13 14:33:04', '2024-09-13 19:33:04', '', '2024-09-13', '39', 'Judith EstesNora Dominguez', 'pyjapaqu@cyclelove.cc', '0412345678', '2007-09-13', '', '', 'American Samoa', 'Quo modi dolor adipi', '3', '315890004', 'Telehealth', '2024-09-13', '06:00', '', '2027-02-24', '', 'Shelby English', 'Specialist', '', '', '', '', ''),
(118, 7016, 1, '', 'assigned', NULL, '2024-09-13 14:34:31', '2024-09-13 19:34:31', '', '2024-09-13', '39', 'ChristopherGuzman', 'pyjapaqu@cyclelove.cc', '39', '2007-09-04', '', '', '', 'erfwecfw', '1', '315890000', 'Telehealth', '2024-09-28', '02:00', '', '-', '', '', 'Specialist', '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medical_certificate_id` (`medical_certificate_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `medical_certificate`
--
ALTER TABLE `medical_certificate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `medical_certificate`
--
ALTER TABLE `medical_certificate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7018;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`);

--
-- Constraints for table `medical_certificate`
--
ALTER TABLE `medical_certificate`
  ADD CONSTRAINT `medical_certificate_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `medical_certificate_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
