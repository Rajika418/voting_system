-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2024 at 12:56 AM
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
-- Database: `voting_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE `candidate` (
  `candidate_id` int(11) NOT NULL,
  `nomination_id` int(11) DEFAULT NULL,
  `candidate_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `election`
--

CREATE TABLE `election` (
  `election_id` int(11) NOT NULL,
  `election_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `election_candidate`
--

CREATE TABLE `election_candidate` (
  `election_candidate_id` int(11) NOT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `election_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(50, '11D', NULL, 11);

-- --------------------------------------------------------

--
-- Table structure for table `grade_teacher`
--

CREATE TABLE `grade_teacher` (
  `grade_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade_teacher`
--

INSERT INTO `grade_teacher` (`grade_id`, `teacher_id`) VALUES
(18, 15);

-- --------------------------------------------------------

--
-- Table structure for table `nomination`
--

CREATE TABLE `nomination` (
  `nomination_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(10, 1, 'A', 2),
(11, 2, 'A', 2),
(12, 30, 'A', 2),
(13, 31, 'A', 2),
(14, 3, 'A', 2),
(15, 8, 'S', 2),
(16, 10, 'A', 2),
(17, 16, 'A', 2),
(18, 26, 'C', 2);

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
  `leave_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `student_name`, `father_name`, `user_id`, `grade_id`, `address`, `guardian`, `contact_number`, `registration_number`, `join_date`, `leave_date`) VALUES
(5, 'k.Doe join', 'null', 31, 47, '123 Main Street', 'Jane Doe', '555-1234', '2024001', '0000-00-00', NULL),
(6, 'k.Doe jone', 'null', 32, 48, '123 Main Street', 'Jane Doe', '555-1234', '2024001', '0000-00-00', NULL),
(7, 'T.Santhosh', NULL, 34, 22, 'bungalow divition hopton', 'Thiruselvam', '0775248369', 'RGE12458', '2011-10-18', NULL),
(8, 'K.DAYANI', NULL, 35, 21, 'bungalow divition hopton', 'Thiruselvagogam', '0775248369', 'RGE12453', '2023-08-10', NULL),
(9, 'rajikakumar', NULL, 50, 8, '123 Main Street, Springfield', 'ane Doe', '1234567890', 'REG123459', '2024-09-06', NULL),
(11, 'kavipriyadharsini', NULL, 52, 10, '123 Main Street, Springfield', 'kamaraj', '1234567888', 'REG123469', '2024-09-06', NULL),
(12, 'gjhjk', NULL, 53, 14, 'nkjnjn', 'fgj', '0775248369', 'RGE12458', '2024-09-12', NULL),
(13, 'nadee', NULL, 54, 20, 'nkjnjn', 'fgj', '0775248369', 'RGE12458', '2024-09-12', NULL),
(14, 'nadee', NULL, 55, 20, 'nkjnjn', 'fgj', '0775248369', 'RGE12458', '2024-09-12', NULL),
(15, 'nadeesha', NULL, 56, 17, 'bungalow divition hopton', 'Thiruselvam', '0775248369', 'RGE12453', '2024-09-14', NULL),
(16, 'nivitha', NULL, 57, 10, '123 Main Street, Springfield', 'kamaraj', '1234567888', 'REG123469', '2024-09-06', NULL),
(17, 'nivithaya', 'kamal', 58, 10, '123 Main Street, Springfield', NULL, '1234567888', 'REG123463', '2024-09-06', NULL),
(18, 'nivithaya', 'y.kamal', 59, 10, '123 Main Street, Springfield', 'null', '1234567888', 'REG123463', '0000-00-00', NULL),
(19, 'nivithaya', 'kamal', 60, 10, '123 Main Street, Springfield', NULL, '1234567888', 'REG123463', '2024-09-06', NULL),
(20, 'gjhjk', 'ewstryh', 61, 7, 'nkjnjn', NULL, '0775248369', 'RGE12453', '2024-09-13', NULL);

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
(2, 11, 'GCE O/L', '2024', '123456', '123456789V'),
(10, 5, 'ol', '2024', '5678', '123456789789'),
(11, 6, 'General Certificate of Education - Ordinary Level (G.C.E(O/L))', '2024', '4562', '123456789789');

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
(3, 'o/l', 'Religions', 'Buddhism'),
(4, 'o/l', 'Religions', 'Saivaneri'),
(5, 'o/l', 'Religions', 'Catholicism'),
(6, 'o/l', 'Religions', 'Christianily'),
(7, 'o/l', 'Religions', 'Islam'),
(8, 'o/l', 'Language', 'Tamil Language & Literature'),
(9, 'o/l', 'Language', 'Sinhala Language & Literature'),
(10, 'o/l', '1st Subject Group', 'Business & Accounting Studies'),
(11, 'o/l', '1st Subject Group', 'Geography'),
(12, 'o/l', '1st Subject Group', 'Second Language (Tamil)'),
(13, 'o/l', '1st Subject Group', 'Second Language (Sinhala)'),
(14, 'o/l', '1st Subject Group', 'Civic Education'),
(15, 'o/l', '2nd Subject Group', 'Music (Carnatic)'),
(16, 'o/l', '2nd Subject Group', 'Art'),
(17, 'o/l', '2nd Subject Group', 'Dancing (Bharatha)'),
(18, 'o/l', '2nd Subject Group', 'English Literature '),
(19, 'o/l', '2nd Subject Group', 'Tamil Literature'),
(20, 'o/l', '2nd Subject Group', 'Drama & Theatre'),
(21, 'o/l', '2nd Subject Group', 'Sinhala Literature'),
(22, 'o/l', '3rd Subject Group', 'Information & Communication Technology'),
(23, 'o/l', '3rd Subject Group', 'Aquatic Bioresources Technology'),
(24, 'o/l', '3rd Subject Group', 'Arts & Crafts'),
(25, 'o/l', '3rd Subject Group', 'Home Economics'),
(26, 'o/l', '3rd Subject Group', 'Health & Physical Education'),
(27, 'o/l', '3rd Subject Group', 'Communication & Media Studies'),
(28, 'o/l', '3rd Subject Group', 'Electrical & Electronic Technology'),
(29, 'o/l', '3rd Subject Group', 'Agriculture & Food Technology'),
(30, 'o/l', NULL, 'History'),
(31, 'o/l', NULL, 'Science'),
(33, 'A/L', 'Arts', 'Tamil'),
(34, 'A/L', 'Arts', 'Tamil'),
(35, 'A/L', 'Arts', 'Tami(A/L)'),
(36, 'A/L', 'Arts', 'Political Sceince'),
(37, 'A/L', 'Arts', 'Logic & Scientific Method'),
(38, 'A/L', 'Arts', 'Hindu Civilization'),
(39, 'A/L', 'Arts', 'Christian Civilization'),
(40, 'A/L', 'Arts', 'Islam Civilization'),
(41, 'A/L', 'Arts', 'English(A/L)'),
(42, 'A/L', 'Arts', 'Carnatic Music(A/L)'),
(43, 'A/L', 'Arts', 'Dancing (Bharatha)(A/L)'),
(44, 'A/L', 'Arts', 'History(A/L)'),
(45, 'A/L', 'Arts', 'Geography(A/L)'),
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
(1, 2, 1),
(2, 2, 2),
(14, 1, 16),
(15, 1, 17),
(16, 1, 18),
(24, 1, 27);

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
(1, 'Anusha', '123 Main Street, City, Colombo', '071234567', '987654321V', 12, '0000-00-00', '0000-00-00'),
(2, 'Pragash', ' Main Street, badulla, Sri Lanka', '0712345678', '200058426491', 13, NULL, NULL),
(15, 'K.nilila', '456 Another Street, badulla, srilanka', '0723456785', '123456799V', 37, '2024-09-01', NULL),
(16, 'K.nilila', '456 Another Street, badulla, srilanka', '0723456785', '123456799V', 38, '2024-09-01', NULL),
(17, 'K.nilila', '456 Another Street, badulla, srilanka', '0723456785', '123456799V', 39, '2024-09-01', NULL),
(18, 'K.nilila', '456 Another Street, badulla, srilanka', '0723456785', '123456799V', 40, '2024-09-01', NULL),
(19, 'K.nilila', '456 Another Street, badulla, srilanka', '0723456785', '123456799V', 41, '2024-09-01', NULL),
(20, 'K.nilila', '456 Another Street, badulla, srilanka', '0723456785', '123456799V', 42, '2024-09-01', NULL),
(26, 'rioshiniya', 'bungalow divition hopton', '0775248369', '123456789789', 48, '2024-09-05', NULL),
(27, 'niro', 'bungalow divition hopton', '0775248369', '12335121', 49, '2024-09-04', '0000-00-00');

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
  `image` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `password`, `email`, `role_id`, `image`) VALUES
(8, 'Raji', '$2y$10$XKq5YFuVV7R4GEE/vk3gzOu.COhi2/I7oAtja7KP7A29QLuN6RZuS', 'raji@example.com', NULL, NULL),
(9, 'Admin', '$2y$10$LwHrf7BZXG9Y0fhQMGKMWeH2JH7jw2vV5OnMCaMEcPLPqiHe4IBDm', 'admin@example.com', 1, NULL),
(10, 'teacher', '$2y$10$HfD9i9ysA2XjTvfqp0YwEOAQXHH3pe/Wh6kXSFUlTGOlNtrZ5hVrO', 'teacher@example.com', 2, NULL),
(11, 'student', '$2y$10$RgS2NmBonL.cXJ1ly/wmkOLTrx1VfViQXNKkqxhhmDGy2RTJIFC4K', 'student@example.com', 3, NULL),
(12, 'anusha', '$2y$10$76dHSMUt/7cNnYVtgQF53uh7krWDKi9hSXaX1U7ipLlR.5CcWZEbe', 'anusha@example.com', 2, NULL),
(13, 'pre', '$2y$10$3HiPsuOkwT5485KN3F53fezwNjGBq6jpCzVnjSECGdwihceJ1gGsW', 'pre@example.com', 2, NULL),
(31, 'johndoe123', '$2y$10$L77.TDnbJ6s2.MRAQIensOIkU9L9pAbs.oqt.Ctt1Out4y4B3TG/W', 'johndoe@example.com', 3, 0x7363686f6f6c2e6a706567),
(32, 'johndoe123', '$2y$10$hEw5kfQo21SEFnBskns6e.2sIPlgPk4Lb6G7UdvT/nZvaDNznZwJK', 'johndoe@example.com', 3, 0x7363686f6f6c2e6a706567),
(34, 'santhosh', '$2y$10$8rCd7NnfvsSW.khZSXfw8eP602C3vXLfSLtM1eyDsbKITJmKGk7c2', 'santhosg@gmail.com', 3, 0x746561636865722e706e67),
(35, 'daya', '$2y$10$PYKQa68QC2YD3JZJfp9HB.fnCU37RkQuv3fZMmqOlOsuf/LTKhuDC', 'daya@gmail.com', 3, 0x7374752e706e67),
(36, 'divishi', '$2y$10$ziveYIAu8LKYitlT6ddtcuIGndjdKIaFbc5A286wbgcaWfPy8zf3W', 'divishi@example.com', 2, NULL),
(37, 'nimi', '$2y$10$ZKqjHE6JPm03kWq4vw8IWeRjunokICUdemUADNNXwZYOB/2lmChhu', 'nimi@example.com', 2, NULL),
(38, 'nimi', '$2y$10$T72pfhoRTz6zT9LxJs1NuewgkYUKYrnZEMKMMffswf6Oa.M3haEUO', 'nimi@example.com', 2, NULL),
(39, 'nimi', '$2y$10$CWfnu9gGhQ1TcvfwYWz.H.dOpcjl/dubRmSLPOTL6/3/n3POXKNpu', 'nimi@example.com', 2, NULL),
(40, 'nimi', '$2y$10$xCLwTnfqQPieXUwtTZ7RCO4ny2sba4FJYLu2G/JMi/XiBb0XR3/Hi', 'nimi@example.com', 2, NULL),
(41, 'nimi', '$2y$10$tUjJDQreur2nSd0EGlKFUukEUJgCZ8OYEvdjX1.I4U3Ndh9w77N1O', 'nimi@example.com', 2, NULL),
(42, 'nimi', '$2y$10$xyth5JnySE9.PXu9W3BuieYoZuQIOGy./xDd5a5w.jjzEEd6aOR9m', 'nimi@example.com', 2, NULL),
(48, 'abcd', '$2y$10$Nc3lX54tCNl147m5pLuMqei4hr48yOpIEJFEWdHzw5nYYudqcdKpm', 'santhosg@gmail.com', 2, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363664653733613633313538635f51505049303933382e4a5047),
(49, 'abcv', '$2y$10$oWiWKMNaD7Ok6w3Zsc7cPeLLjMuG9blpG74R.8cd4mpsJh64ZRdL6', 'rajikakumar18@gmail.com', 2, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363664653734363336636637385f52484b41313530382e4d4f56),
(50, 'kuma', '$2y$10$KIXjSZTEM4eJZ9KDyEHJLOBK.3Vu5k8wy4kmFAPud7lEV4UMedGsS', 'kumar@example.com', 3, 0x61646d696e2074652e706e67),
(51, '', '$2y$10$DTmIbTpKnVoCOScNOYj8AuPOGdZKVQmIQgsjdjyB3Kvu10lihsDkS', '', 3, NULL),
(52, 'papa', '$2y$10$nsQSeqrEzMJD78oLNrxDJuTqhE0V5ICdb7jmNwmD6ZGHrl44NF.pi', 'papa@example.com', 3, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363665396665653032663434395f61646d696e2e706e67),
(53, 'fgh', '$2y$10$TPSsFhnM68XosM3v/BbsoeKh0cf3cNrhw02hp.EkQPn776FZU8y6a', 'santhosg@gmail.com', 3, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363666386162653863616637305f415349474e2e706e67),
(54, 'fgh', '$2y$10$Xh7fIOGZdIgMVB4xnRSxleXV5q7tX2x1SVMRODoNTRybLYPcptKle', 'santhosg@gmail.com', 3, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363666386164346136666637355f415349474e2e706e67),
(55, 'fgh', '$2y$10$7s8ppLrJDoz3SuicOepYleHKoQlJTBatD5GmS16MgtXgLwPA4VLeG', 'santhosg@gmail.com', 3, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363666386164346232363830615f415349474e2e706e67),
(56, 'lkj', '$2y$10$5Q4O0YMWtfssA4NqUh4TyeXA6SSYbZGjAjFGue0yG9j9zK30rDQg2', 'daya@gmail.com', 3, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363666393366326532633664615f61646d696e2e706e67),
(57, 'nivi', '$2y$10$LL0Ce6yt/GUZb1E9duSwT.58X.i.7k8kh26rrKn9.p2dhT1Hcb9ry', 'papa@example.com', 3, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363666393430633131623836305f61646d696e312e706e67),
(58, 'niviya', '$2y$10$/mZe6ynL5YFGjmX4ntY9zOedMc6RnulQN5e4ZnsX.oejjkBh/0R6C', 'nivi@example.com', 3, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363666393436303163316333375f61646d696e312e706e67),
(59, 'niviya', '$2y$10$nx2NlhvoqgyKwtKRy4S/RORElLx2fMerxG3aDYV2aN2xvQ/c.m20O', 'nivi@example.com', 3, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363666393436363032343866655f61646d696e312e706e67),
(60, 'niviya', '$2y$10$jsmSyb5hSX2XMZgw2fWguuccac7bdAq61mI4efgNIH9aJo/.37C/K', 'nivi@example.com', 3, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363666393436386534633337375f61646d696e312e706e67),
(61, 'fgh', '$2y$10$8NK/cbK03CFgq.WseaIuauVLyrxRluNifZU/KoCBxSIeKcXpXYkWG', 'santhosg@gmail.com', 3, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363666393437396134663633365f6f6c2e706e67);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidate`
--
ALTER TABLE `candidate`
  ADD PRIMARY KEY (`candidate_id`),
  ADD KEY `nomination_id` (`nomination_id`);

--
-- Indexes for table `election`
--
ALTER TABLE `election`
  ADD PRIMARY KEY (`election_id`);

--
-- Indexes for table `election_candidate`
--
ALTER TABLE `election_candidate`
  ADD PRIMARY KEY (`election_candidate_id`),
  ADD KEY `candidate_id` (`candidate_id`),
  ADD KEY `election_id` (`election_id`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`grade_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `grade_teacher`
--
ALTER TABLE `grade_teacher`
  ADD PRIMARY KEY (`grade_id`,`teacher_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `nomination`
--
ALTER TABLE `nomination`
  ADD PRIMARY KEY (`nomination_id`),
  ADD KEY `student_id` (`student_id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidate`
--
ALTER TABLE `candidate`
  MODIFY `candidate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `election`
--
ALTER TABLE `election`
  MODIFY `election_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `election_candidate`
--
ALTER TABLE `election_candidate`
  MODIFY `election_candidate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `nomination`
--
ALTER TABLE `nomination`
  MODIFY `nomination_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `staffs`
--
ALTER TABLE `staffs`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `student_exam`
--
ALTER TABLE `student_exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `subject_teacher`
--
ALTER TABLE `subject_teacher`
  MODIFY `subject_teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidate`
--
ALTER TABLE `candidate`
  ADD CONSTRAINT `candidate_ibfk_1` FOREIGN KEY (`nomination_id`) REFERENCES `nomination` (`nomination_id`);

--
-- Constraints for table `election_candidate`
--
ALTER TABLE `election_candidate`
  ADD CONSTRAINT `election_candidate_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidate` (`candidate_id`),
  ADD CONSTRAINT `election_candidate_ibfk_2` FOREIGN KEY (`election_id`) REFERENCES `election` (`election_id`);

--
-- Constraints for table `grade`
--
ALTER TABLE `grade`
  ADD CONSTRAINT `grade_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`);

--
-- Constraints for table `grade_teacher`
--
ALTER TABLE `grade_teacher`
  ADD CONSTRAINT `grade_teacher_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `grade` (`grade_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grade_teacher_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE;

--
-- Constraints for table `nomination`
--
ALTER TABLE `nomination`
  ADD CONSTRAINT `nomination_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `results_ibfk_3` FOREIGN KEY (`exam_id`) REFERENCES `student_exam` (`id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`grade_id`) REFERENCES `grade` (`grade_id`);

--
-- Constraints for table `student_exam`
--
ALTER TABLE `student_exam`
  ADD CONSTRAINT `student_exam_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `subject_teacher`
--
ALTER TABLE `subject_teacher`
  ADD CONSTRAINT `subject_teacher_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `subject_teacher_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
