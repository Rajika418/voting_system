-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2024 at 09:18 AM
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
-- Database: `school_ms`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE `candidate` (
  `id` int(11) NOT NULL,
  `nomination_id` int(11) NOT NULL,
  `total_votes` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`id`, `nomination_id`, `total_votes`) VALUES
(1, 2, 6),
(2, 6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

CREATE TABLE `elections` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `election_name` varchar(255) NOT NULL,
  `nom_start_date` date NOT NULL,
  `nom_end_date` date NOT NULL,
  `ele_start_date` date NOT NULL,
  `ele_end_date` date NOT NULL,
  `image` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `elections`
--

INSERT INTO `elections` (`id`, `year`, `election_name`, `nom_start_date`, `nom_end_date`, `ele_start_date`, `ele_end_date`, `image`) VALUES
(4, 2024, 'School Parliament Election', '2024-10-15', '2024-10-16', '2024-10-20', '2024-10-21', 0x36373064396139346230633936362e34303630373232382e6a7067);

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `grade_id` int(11) NOT NULL,
  `grade_name` varchar(255) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`grade_id`, `grade_name`, `teacher_id`, `year`) VALUES
(7, '1A', NULL, NULL),
(8, '1B', NULL, NULL),
(9, '1C', NULL, NULL),
(10, '1D', NULL, NULL),
(11, '2A', NULL, NULL),
(12, '2B', NULL, NULL),
(13, '2C', NULL, NULL),
(14, '2D', NULL, NULL),
(15, '3A', NULL, NULL),
(16, '3B', NULL, NULL),
(17, '3C', NULL, NULL),
(18, '3D', NULL, NULL),
(19, '4A', NULL, NULL),
(20, '4B', NULL, NULL),
(21, '4C', NULL, NULL),
(22, '4D', NULL, NULL),
(23, '5A', NULL, NULL),
(24, '5B', NULL, NULL),
(25, '5C', NULL, NULL),
(26, '5D', NULL, NULL),
(27, '6A', NULL, NULL),
(28, '6B', NULL, NULL),
(29, '6C', NULL, NULL),
(30, '6D', NULL, NULL),
(31, '7A', NULL, NULL),
(32, '7B', NULL, NULL),
(33, '7C', NULL, NULL),
(34, '7D', NULL, NULL),
(35, '8A', NULL, NULL),
(36, '8B', NULL, NULL),
(37, '8C', NULL, NULL),
(38, '8D', NULL, NULL),
(39, '9A', NULL, NULL),
(40, '9B', NULL, NULL),
(41, '9C', NULL, NULL),
(42, '9D', NULL, NULL),
(43, '10A', NULL, NULL),
(44, '10B', NULL, NULL),
(45, '10C', NULL, NULL),
(46, '10D', NULL, NULL),
(47, '11A', NULL, 11),
(48, '11B', NULL, 11),
(49, '11C', NULL, 11),
(50, '11D', NULL, 11),
(51, '12A', NULL, 12),
(52, '13A', NULL, 13),
(53, '13B', NULL, 13),
(54, '13C', NULL, 13);

-- --------------------------------------------------------

--
-- Table structure for table `nomination`
--

CREATE TABLE `nomination` (
  `id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `why` varchar(255) NOT NULL,
  `motive` varchar(255) NOT NULL,
  `what` varchar(255) NOT NULL,
  `isNominated` tinyint(1) NOT NULL,
  `isRejected` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nomination`
--

INSERT INTO `nomination` (`id`, `election_id`, `student_id`, `why`, `motive`, `what`, `isNominated`, `isRejected`) VALUES
(2, 4, 22, 'to get exprince', 'give good leadership', 'enhanse student power', 1, 0),
(6, 4, 24, 'dxhvjhbkj', 'jbkjnkjmk', 'vjbjn', 1, 0),
(8, 4, 23, 'jnkbjm;', 'bljnl', 'jlvkjld', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `result_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `result` varchar(10) NOT NULL,
  `exam_id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`result_id`, `subject_id`, `result`, `exam_id`) VALUES
(19, 1, 'A', 12),
(20, 2, 'A', 12),
(21, 30, 'A', 12),
(22, 31, 'A', 12),
(23, 4, 'A', 12),
(24, 8, 'A', 12),
(25, 11, 'A', 12),
(26, 15, 'A', 12),
(34, 46, 'A', 14),
(35, 47, 'A', 14),
(36, 48, 'A', 14),
(37, 35, 'A', 14),
(38, 36, 'A', 14),
(39, 45, 'C', 14),
(49, 1, 'B', 16),
(50, 2, 'B', 16),
(51, 8, 'B', 16),
(52, 30, 'B', 16),
(53, 31, 'B', 16),
(54, 4, 'B', 16),
(55, 11, 'A', 16),
(56, 15, 'A', 16),
(57, 26, 'B', 16),
(58, 46, 'A', 17),
(59, 47, 'A', 17),
(60, 48, 'A', 17),
(61, 35, 'A', 17),
(62, 37, 'B', 17),
(63, 36, 'B', 17);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'Teacher'),
(3, 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE `staffs` (
  `staff_id` int(11) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `leave_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staffs`
--

INSERT INTO `staffs` (`staff_id`, `staff_name`, `position`, `address`, `email`, `contact_number`, `image`, `join_date`, `leave_date`) VALUES
(1, 'K.Kumar', 'library assistent', 'bungalow divistion hopton', 'kumar@example.com', '1234567890', '/voting_system/uploads/school.jpeg', '2023-09-01', '2024-09-01'),
(3, 'K.kamal', 'library assistent', 'bungalow divistion hopton', 'kamal@example.com', '1234567890', 'C:/Users/SHANTHOS/Desktop/xampp/htdocs/voting_system/uploads/school.jpeg', '2023-09-01', '2024-09-01');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL,
  `address` text NOT NULL,
  `guardian` varchar(255) DEFAULT NULL,
  `contact_number` varchar(15) NOT NULL,
  `registration_number` varchar(20) NOT NULL,
  `join_date` date DEFAULT NULL,
  `leave_date` date DEFAULT NULL,
  `parents_image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `student_name`, `father_name`, `user_id`, `grade_id`, `address`, `guardian`, `contact_number`, `registration_number`, `join_date`, `leave_date`, `parents_image`) VALUES
(1, 'Raja', 'Kamal', 66, 7, 'Bungalow division Hopton', NULL, '077586942', '8256', '2018-11-13', NULL, NULL),
(21, 'nivithaya', 'kamal', 62, 52, '123 Main Street, Springfield', 'null', '1234567888', 'REG123463', '2024-09-06', NULL, NULL),
(22, 'kamalanathan', 'kamal', 63, 53, '123 Main Street, Springfield', NULL, '1234567889', 'REG123464', '2024-09-06', NULL, NULL),
(23, 'mary', 'Antony', 64, 49, '123 Main Street, Springfield', 'null', '1234567810', 'REG123465', '2024-09-06', NULL, NULL),
(24, 'Dthurai', 'Dhurai', 65, 50, '123 Main Street, Springfield', NULL, '1234567811', 'REG123467', '2024-09-06', NULL, NULL),
(25, 'Ramesh', 'Rajasuntharam', 69, 7, 'Bungalaow Division Hopton', NULL, '0764289365', '8257', '2018-06-20', NULL, NULL),
(26, 'Amala', 'Ramesh', 70, 8, 'Bulugala division hopton', NULL, '077894561', '4862', '2018-06-12', NULL, NULL),
(27, 'Arya', 'Kalyanam', 71, 8, 'Bulugala Division Hopton', NULL, '077562315', '1596', '2018-07-12', NULL, NULL),
(28, 'Bala', 'Balakirisnan', 72, 9, 'Bungalow division Hopton', NULL, '075142369', '4528', '2018-09-10', NULL, NULL),
(29, 'Babu', 'Prabu', 73, 9, 'Bulugala Division Hopton', NULL, '0754523698', '4253', '2018-10-17', NULL, NULL),
(30, 'Chithra', 'Rajesh', 74, 10, 'Bungalow division Hopton', NULL, '07854269', '7894', '2018-07-12', NULL, NULL),
(31, 'Chalsh', 'magesh', 75, 10, 'Bungalow division Hopton', NULL, '075861245', '1234', '2018-09-12', NULL, NULL),
(32, 'Dhenesh ', 'Tharmaraji', 76, 0, 'Bungalow division Hopton', NULL, '077895641', '1536', '2017-02-15', NULL, NULL),
(33, 'Dhenesh ', 'Tharmaraji', 77, 11, 'Bungalow division Hopton', NULL, '07746562', '12968', '2017-06-06', NULL, NULL),
(34, 'Dheluni', 'Ramesh', 78, 11, 'Bungalaow Division Hopton', NULL, '07564238', '1789', '2017-07-18', NULL, NULL),
(35, 'Dharman', 'Tharmabalan', 79, 12, 'Bulugala division hopton', NULL, '0785642', '1259', '2017-05-08', NULL, NULL),
(36, 'Dhilakshan', 'Dhilakshanan', 80, 12, 'Bulugala division hopton', NULL, '07546286', '1579', '2017-07-13', NULL, NULL),
(37, 'Malar', 'Dharman', 81, 13, 'Bungalaow Division Hopton', NULL, '077856423', '1789', '2017-06-13', NULL, NULL),
(38, 'Madhu', 'kamarai', 82, 13, 'Bulugala Division Hopton', NULL, '07596423', '1485', '2017-06-16', NULL, NULL),
(39, 'Thilakshan', 'Tharman', 83, 14, 'Bulugala Division Hopton', NULL, '07758862', '1769', '2017-02-22', NULL, NULL),
(40, 'Tharani', 'k.haranthan', 84, 14, 'Bungalaow Division Hopton', NULL, '07758623', '7895', '2017-06-28', NULL, NULL),
(41, 'Harshan', 'Rajasuntharam', 85, 15, 'Bungalaow Division Hopton', NULL, '077542698', '1296', '2017-08-11', NULL, NULL),
(42, 'Kokilan', 'tharani', 86, 16, 'Bungalaow Division Hopton', NULL, '0778563', '1452', '2017-01-02', NULL, NULL),
(43, 'Niroshan', 'tharan', 87, 18, 'Bulugala Division Hopton', NULL, '07856423', '1563', '2017-07-07', NULL, NULL),
(44, 'Mthavi', 'Dhurai', 90, 50, '123 Main Street, Springfield', NULL, '1234567811', 'REG122367', '2024-09-06', NULL, 'http://localhost/voting_system/uploads/67331beda0e7f_IMG_20241012_134934_460.jpg'),
(45, 'Saran', 'Sarathkumar', 91, 7, 'Bungalaow Division Hopton', NULL, '02456522', '178562', '2017-11-22', NULL, 'http://localhost/voting_system/uploads/67331ea19e52e_IMG_20241012_134934_460.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `student_exam`
--

CREATE TABLE `student_exam` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `exam_name` varchar(100) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `index_no` varchar(100) NOT NULL,
  `nic` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_exam`
--

INSERT INTO `student_exam` (`id`, `student_id`, `exam_name`, `year`, `index_no`, `nic`) VALUES
(12, 24, 'General Certificate of Education - Ordinary Level (G.C.E(O/L))', '2024', '12345', '123456789'),
(14, 21, 'General Certificate of Education - Advanced Level (G.C.E(A/L))', '2024', '5461', '7894562'),
(16, 23, 'General Certificate of Education - Ordinary Level (G.C.E(O/L))', '2024', '1478', '200060901020'),
(17, 22, 'General Certificate of Education - Advanced Level (G.C.E(A/L))', '2024', '2478', '200060901020');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `year` varchar(100) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `subject_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `year`, `section`, `subject_name`) VALUES
(1, 'o/l', '', 'English'),
(2, 'o/l', '', 'Mathematics'),
(4, 'o/l', 'Religions', 'Saivaneri'),
(6, 'o/l', 'Religions', 'Christianily'),
(7, 'o/l', 'Religions', 'Islam'),
(8, 'o/l', '', 'Tamil Language & Literature'),
(10, 'o/l', '1st Subject Group', 'Business & Accounting Studies'),
(11, 'o/l', '1st Subject Group', 'Geography'),
(14, 'o/l', '1st Subject Group', 'Civic Education'),
(15, 'o/l', '2nd Subject Group', 'Music (Carnatic)'),
(16, 'o/l', '2nd Subject Group', 'Art'),
(22, 'o/l', '3rd Subject Group', 'Information & Communication Technology'),
(26, 'o/l', '3rd Subject Group', 'Health & Physical Education'),
(29, 'o/l', '3rd Subject Group', 'Agriculture & Food Technology'),
(30, 'o/l', NULL, 'History'),
(31, 'o/l', NULL, 'Science'),
(35, 'A/L', 'Subject 1', 'Tami(A/L)'),
(36, 'A/L', 'Subject 1', 'Political Sceince'),
(37, 'A/L', 'Subject 1', 'Logic & Scientific Method'),
(38, 'A/L', 'Subject 1', 'Hindu Civilization'),
(42, 'A/L', 'Subject 1', 'Carnatic Music(A/L)'),
(45, 'A/L', 'Subject 1', 'Geography(A/L)'),
(46, 'A/L', NULL, 'General English'),
(47, 'A/L', NULL, 'General Knowladage'),
(48, 'A/L', NULL, 'GIT');

-- --------------------------------------------------------

--
-- Table structure for table `subject_teacher`
--

CREATE TABLE `subject_teacher` (
  `subject_teacher_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_teacher`
--

INSERT INTO `subject_teacher` (`subject_teacher_id`, `subject_id`, `teacher_id`) VALUES
(0, 11, 0);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `teacher_id` int(11) NOT NULL,
  `teacher_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `leave_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `teacher_name`, `address`, `contact_number`, `nic`, `user_id`, `join_date`, `leave_date`) VALUES
(0, 'M.Chanthrakumari', 'meethumpittity passara', '077586123', '19975243681', 88, '1996-06-26', NULL),
(1, 'Anusha', '123 Main Street, City, Colombo', '071234567', '987654321V', 12, '0000-00-00', '0000-00-00'),
(2, 'Pragash', ' Main Street, badulla, Sri Lanka', '0712345678', '200058426491', 13, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `password`, `email`, `role_id`, `image`) VALUES
(8, 'Raji', '$2y$10$gRWFH5I5GMz3PI37jRHJj.q/yZaJdNMORXcL5Ij0lrfuRq9HKb6ym', 'raji@gmail.com', 2, 'http://localhost/voting_system/uploads/66fcd4ecc08ca_vijay.jpg'),
(9, 'Rajika', '$2y$10$B9Dxn4jQdgd47Idp/56XuufICDiBXJ9c.Qmb4/0I9EdJ.QsanpSKK', 'rajikakumar18@gmail.com', 1, 'http://localhost/voting_system/uploads/66fcd4ecc08ca_vijay.jpg'),
(10, 'teacher', '$2y$10$HfD9i9ysA2XjTvfqp0YwEOAQXHH3pe/Wh6kXSFUlTGOlNtrZ5hVrO', 'teacher@example.com', 2, ''),
(11, 'student', '$2y$10$RgS2NmBonL.cXJ1ly/wmkOLTrx1VfViQXNKkqxhhmDGy2RTJIFC4K', 'student@example.com', 3, ''),
(12, 'anusha', '$2y$10$76dHSMUt/7cNnYVtgQF53uh7krWDKi9hSXaX1U7ipLlR.5CcWZEbe', 'anusha@example.com', 2, 'http://localhost/voting_system/uploads/6724c76b02527_IMG_20241012_134934_460.jpg'),
(13, 'pre', '$2y$10$3HiPsuOkwT5485KN3F53fezwNjGBq6jpCzVnjSECGdwihceJ1gGsW', 'pre@example.com', 2, 'http://localhost/voting_system/uploads/6724c7762c555_IMG_20241012_134934_460.jpg'),
(36, 'divishi', '$2y$10$ziveYIAu8LKYitlT6ddtcuIGndjdKIaFbc5A286wbgcaWfPy8zf3W', 'divishi@example.com', 2, ''),
(37, 'nimi', '$2y$10$ZKqjHE6JPm03kWq4vw8IWeRjunokICUdemUADNNXwZYOB/2lmChhu', 'nimi@example.com', 2, ''),
(38, 'nimi', '$2y$10$T72pfhoRTz6zT9LxJs1NuewgkYUKYrnZEMKMMffswf6Oa.M3haEUO', 'nimi@example.com', 2, ''),
(39, 'nimi', '$2y$10$CWfnu9gGhQ1TcvfwYWz.H.dOpcjl/dubRmSLPOTL6/3/n3POXKNpu', 'nimi@example.com', 2, ''),
(40, 'nimi', '$2y$10$xCLwTnfqQPieXUwtTZ7RCO4ny2sba4FJYLu2G/JMi/XiBb0XR3/Hi', 'nimi@example.com', 2, ''),
(41, 'nimi', '$2y$10$tUjJDQreur2nSd0EGlKFUukEUJgCZ8OYEvdjX1.I4U3Ndh9w77N1O', 'nimi@example.com', 2, ''),
(42, 'nimi', '$2y$10$xyth5JnySE9.PXu9W3BuieYoZuQIOGy./xDd5a5w.jjzEEd6aOR9m', 'nimi@example.com', 2, ''),
(62, 'niviya', '$2y$10$CFnsSZiB/2R98daJpIgprOQbqcc9jurTAx6chvklOpZuBuN3itX6m', 'nivi@example.com', 3, ''),
(63, 'kamal', '$2y$10$fnEdCmoRU8Z7OIAr3g30vO4koLsKGjA4.L5NKW/GIZgr7ZptXJolK', 'kamal@example.com', 3, 'http://localhost/voting_system/uploads/67235fb926948_IMG_20241012_134903_954.jpg'),
(64, 'mary', '$2y$10$//cSAYv4by27m/ECuhTZz.cB/AlV.GLTpqfMH5u6O55lzYZsxdW1q', 'mary@example.com', 3, 'http://localhost/voting_system/uploads/67235fc5b4251_IMG_20241012_134903_954.jpg'),
(65, 'dhurai', '$2y$10$aLQ6PT.UtcfOsYNaHRnUJ.6hSFD80acTK4B0w4zseqEj4bLeCI.zu', 'dhurai@example.com', 3, 'http://localhost/voting_system/uploads/67235fcd36752_IMG_20241012_134903_954.jpg'),
(66, 'Raja', '$2y$10$vcRiqU80W1j1zodcqUwQUuTPPijwhTzUa.H79k39B6yS1Nqkz1cNK', 'raja@gmail.com', 3, NULL),
(69, 'Ramesh', '$2y$10$VcAUvn61U8gfyiZXrrRj8.ElN1W6mDmVpKd87AhRYdcE.vP/ntrQK', 'ramesg@gmail.com', 3, NULL),
(70, 'Amala', '$2y$10$9qYyBCBfT0i/XluBJYZoTuKmGAiPieVLP.OvPHWCopHToMypA/bRW', 'amala@gmail.com', 3, NULL),
(71, 'Arya', '$2y$10$kbiung/Ip.UkChz8.H0H/uZCeJisTMEkXoFHO8Rki.6tA3sQbghly', 'arya@gmail.com', 3, NULL),
(72, 'Bala', '$2y$10$Rkp2IZbrr/WEsM15.pZvouc.YZc1OtohFxNIk9Q3u2by3she5Bcju', 'bala@gmail.com', 3, NULL),
(73, 'Babu', '$2y$10$4GgDY55fBkCM5yZuLVn9bOh3UfNb0gRv/CoxsXeVa7mrzweYSe.ue', 'babu@gmail.com', 3, NULL),
(74, 'Chithra', '$2y$10$k4xDfEJYskRWh/ZBkY.Zau7znz1ndBYJ0PkLEDuSOIaj5WkGn1lGW', 'chithra@gmail.com', 3, NULL),
(75, 'Chalsh', '$2y$10$bq1ENdusBoZ4/9rfT2cSHuyNwPZ/BArRQiwIHNGBy61n6QPGVJvma', 'chalsh@gmail.com', 3, NULL),
(76, 'Dhenesh', '$2y$10$aiydIOlbJVol2zqVE3PlNedRh49PnBul.u2smq5ngfi1afXJPc0rC', 'dhenesh@gmailcooom', 3, NULL),
(77, 'Dhenesh', '$2y$10$CgULD0IZFGXHiSXSKoZpOu/kj.uxVvSTKkhwZX/H6h8nHyLsc4.oi', 'dhenesg@gmail.com', 3, NULL),
(78, 'Dhiluni', '$2y$10$MWmAqBSDb3dLpI6T1p2ZFO0j/VnTb7rRStEG.bz61i251WgXkT8a6', 'dhiluni@gmail.com', 3, NULL),
(79, 'Dharman', '$2y$10$Sbe/D2.MzHxjyfSVkOHp/.b9Osu1zDlarq0qRWU9RNymWHEf/2ile', 'dharman@gmail.com', 3, NULL),
(80, 'Dhilakshan', '$2y$10$rtoqICmhuMrfSXgCObw4.uNlHcg7AbTVrtceAqOIro.T001YqLi7.', 'dhilakshan@gmail.com', 3, NULL),
(81, 'Malar', '$2y$10$.ytF0hdYYdOulKJ4P8KIH.AXm8z4gIzmGPEmtulpvonQ1yVaA.lom', 'malar@gmail.com', 3, NULL),
(82, 'Madhu', '$2y$10$7WMGc.huekHCukJwjOKA3uj/uVWgGdZqLtOiTybv2XsF4mM1ai3IG', 'madhu@gmail.com', 3, NULL),
(83, 'Thilakshan', '$2y$10$ycL5v7bggnlK9Oy5OuzoWuD2Uc8uSmy7FCQjm0qpbO8C04BH2cKaG', 'thilakshan@gmail.com', 3, NULL),
(84, 'Tharani', '$2y$10$83dKFUYZ0q9Vvb6h1DX0keKKNa.1ILj6DjBBS6s2Vsjd9XmSoF2i.', 'tharani@gmail.com', 3, NULL),
(85, 'Harshan', '$2y$10$17SDaHcTUhAb7XuhXFCMHuaOt6e9U.JRZ4qafV8XVnXcNkLh8gnr6', 'harshan@gmail.com', 3, NULL),
(86, 'Kokilan', '$2y$10$4A/P.sYyLPtG30jBmkgfH./lkOtsAWNnvAgVeaq34E0nhpAxtZeqK', 'kokilan@gmail.com', 3, NULL),
(87, 'Nirosha', '$2y$10$hxL3I3DgAd5lXAYaM4YsiOfPKpm6eZHNXRYrxMrTEVgfSTL3wAqmW', 'nirosha@gmail.com', 3, NULL),
(88, 'chanthra', '$2y$10$On0HT0IWc0GK4K/VTTKm3O5tCKKKxCpH40wepKBcNB2mAl/q3a58C', 'chanthra@gmail.com', 2, NULL),
(90, 'mathavi', '$2y$10$7k4W0KdkTyLzI8FeoJJn4eJjQDDWSK.h0AnvkWEfaEPaJxYNs1YkG', 'mathavi@example.com', 3, NULL),
(91, 'saran', '$2y$10$Fp1nmlShT0Lm4qzqrUg2TORkFEajfNzZJVGgYKwD9ASH9q2j3cc72', 'saran@gmail.com', 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_election`
--

CREATE TABLE `user_election` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `hasVoted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_election`
--

INSERT INTO `user_election` (`id`, `user_id`, `election_id`, `hasVoted`) VALUES
(1, 8, 4, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidate`
--
ALTER TABLE `candidate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nomination_id` (`nomination_id`);

--
-- Indexes for table `elections`
--
ALTER TABLE `elections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`grade_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `nomination`
--
ALTER TABLE `nomination`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `election_id` (`election_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `results_ibfk_3` (`exam_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `grade_id` (`grade_id`);

--
-- Indexes for table `student_exam`
--
ALTER TABLE `student_exam`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `subject_teacher`
--
ALTER TABLE `subject_teacher`
  ADD PRIMARY KEY (`subject_teacher_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_election`
--
ALTER TABLE `user_election`
  ADD PRIMARY KEY (`id`),
  ADD KEY `election_id` (`election_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidate`
--
ALTER TABLE `candidate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nomination`
--
ALTER TABLE `nomination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `user_election`
--
ALTER TABLE `user_election`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nomination`
--
ALTER TABLE `nomination`
  ADD CONSTRAINT `nomination_ibfk_1` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `user_election`
--
ALTER TABLE `user_election`
  ADD CONSTRAINT `user_election_ibfk_1` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`),
  ADD CONSTRAINT `user_election_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
