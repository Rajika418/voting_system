-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2024 at 01:09 PM
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
(40, 1, 'C', 15),
(41, 2, 'A', 15),
(42, 30, 'B', 15),
(43, 31, 'B', 15),
(44, 4, 'A', 15),
(45, 8, 'A', 15),
(46, 11, 'A', 15),
(47, 15, 'A', 15),
(48, 29, 'A', 15);

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
(21, 'nivithaya', 'kamal', 62, 52, '123 Main Street, Springfield', 'null', '1234567888', 'REG123463', '2024-09-06', NULL),
(22, 'kamalanathan', 'kamal', 63, 53, '123 Main Street, Springfield', NULL, '1234567889', 'REG123464', '2024-09-06', NULL),
(23, 'mary', 'Antony', 64, 49, '123 Main Street, Springfield', 'null', '1234567810', 'REG123465', '2024-09-06', NULL),
(24, 'Dthurai', 'Dhurai', 65, 50, '123 Main Street, Springfield', NULL, '1234567811', 'REG123467', '2024-09-06', NULL);

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
(15, 23, 'General Certificate of Education - Ordinary Level (G.C.E(O/L))', '2024', '3698', '7894562');

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
(8, 'o/l', '', 'Tamil Language & Literature'),
(10, 'o/l', '1st Subject Group', 'Business & Accounting Studies'),
(11, 'o/l', '1st Subject Group', 'Geography'),
(13, 'o/l', '1st Subject Group', 'Second Language (Sinhala)'),
(14, 'o/l', '1st Subject Group', 'Civic Education'),
(15, 'o/l', '2nd Subject Group', 'Music (Carnatic)'),
(16, 'o/l', '2nd Subject Group', 'Art'),
(19, 'o/l', '2nd Subject Group', 'Tamil Literature'),
(22, 'o/l', '3rd Subject Group', 'Information & Communication Technology'),
(23, 'o/l', '3rd Subject Group', 'Aquatic Bioresources Technology'),
(25, 'o/l', '3rd Subject Group', 'Home Economics'),
(26, 'o/l', '3rd Subject Group', 'Health & Physical Education'),
(29, 'o/l', '3rd Subject Group', 'Agriculture & Food Technology'),
(30, 'o/l', NULL, 'History'),
(31, 'o/l', NULL, 'Science'),
(35, 'A/L', 'Subject 1', 'Tami(A/L)'),
(36, 'A/L', 'Subject 1', 'Political Sceince'),
(37, 'A/L', 'Subject 1', 'Logic & Scientific Method'),
(38, 'A/L', 'Subject 1', 'Hindu Civilization'),
(39, 'A/L', 'Subject 1', 'Christian Civilization'),
(40, 'A/L', 'Subject 1', 'Islam Civilization'),
(42, 'A/L', 'Subject 1', 'Carnatic Music(A/L)'),
(43, 'A/L', 'Subject 1', 'Dancing (Bharatha)(A/L)'),
(44, 'A/L', 'Subject 1', 'History(A/L)'),
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
(36, 'divishi', '$2y$10$ziveYIAu8LKYitlT6ddtcuIGndjdKIaFbc5A286wbgcaWfPy8zf3W', 'divishi@example.com', 2, NULL),
(37, 'nimi', '$2y$10$ZKqjHE6JPm03kWq4vw8IWeRjunokICUdemUADNNXwZYOB/2lmChhu', 'nimi@example.com', 2, NULL),
(38, 'nimi', '$2y$10$T72pfhoRTz6zT9LxJs1NuewgkYUKYrnZEMKMMffswf6Oa.M3haEUO', 'nimi@example.com', 2, NULL),
(39, 'nimi', '$2y$10$CWfnu9gGhQ1TcvfwYWz.H.dOpcjl/dubRmSLPOTL6/3/n3POXKNpu', 'nimi@example.com', 2, NULL),
(40, 'nimi', '$2y$10$xCLwTnfqQPieXUwtTZ7RCO4ny2sba4FJYLu2G/JMi/XiBb0XR3/Hi', 'nimi@example.com', 2, NULL),
(41, 'nimi', '$2y$10$tUjJDQreur2nSd0EGlKFUukEUJgCZ8OYEvdjX1.I4U3Ndh9w77N1O', 'nimi@example.com', 2, NULL),
(42, 'nimi', '$2y$10$xyth5JnySE9.PXu9W3BuieYoZuQIOGy./xDd5a5w.jjzEEd6aOR9m', 'nimi@example.com', 2, NULL),
(51, '', '$2y$10$DTmIbTpKnVoCOScNOYj8AuPOGdZKVQmIQgsjdjyB3Kvu10lihsDkS', '', 3, NULL),
(62, 'niviya', '$2y$10$CFnsSZiB/2R98daJpIgprOQbqcc9jurTAx6chvklOpZuBuN3itX6m', 'nivi@example.com', 3, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363666643733353939653565325f494d475f3036323220636f70792e6a7067),
(63, 'kamal', '$2y$10$fnEdCmoRU8Z7OIAr3g30vO4koLsKGjA4.L5NKW/GIZgr7ZptXJolK', 'kamal@example.com', 3, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363666643733636632656439615f4d4a484d303937312e4a5047),
(64, 'mary', '$2y$10$//cSAYv4by27m/ECuhTZz.cB/AlV.GLTpqfMH5u6O55lzYZsxdW1q', 'mary@example.com', 3, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363666643734623637633034315f4443544c393635382e4a5047),
(65, 'dhurai', '$2y$10$aLQ6PT.UtcfOsYNaHRnUJ.6hSFD80acTK4B0w4zseqEj4bLeCI.zu', 'dhurai@example.com', 3, 0x687474703a2f2f6c6f63616c686f73742f766f74696e675f73797374656d2f75706c6f6164732f363666643735316264623937365f4443544c393635382e4a5047);

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
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `nomination`
--
ALTER TABLE `nomination`
  MODIFY `nomination_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

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
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `student_exam`
--
ALTER TABLE `student_exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

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
