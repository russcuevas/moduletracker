-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2025 at 03:40 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `modulefinder`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_academic_strands`
--

CREATE TABLE `tbl_academic_strands` (
  `id` int(11) NOT NULL,
  `strand_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_academic_strands`
--

INSERT INTO `tbl_academic_strands` (`id`, `strand_name`) VALUES
(9, 'ABM'),
(8, 'GAS'),
(4, 'HE'),
(5, 'HUMSS'),
(6, 'ICT'),
(7, 'STEM'),
(10, 'TOURISM');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_information`
--

CREATE TABLE `tbl_information` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `grade_level` varchar(255) NOT NULL,
  `section` varchar(50) NOT NULL,
  `academic_strand_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_students_subjects`
--

CREATE TABLE `tbl_students_subjects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `module_received` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subjects`
--

CREATE TABLE `tbl_subjects` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `strand_id` int(11) NOT NULL,
  `grade_level` varchar(255) NOT NULL,
  `semester` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_subjects`
--

INSERT INTO `tbl_subjects` (`id`, `subject_name`, `strand_id`, `grade_level`, `semester`) VALUES
(29, 'Introduction to the Philosophy of the Human Person', 6, 'Grade 12', '1st Semester'),
(30, 'Physical Science', 6, 'Grade 12', '1st Semester'),
(31, 'Physical Education and Health 3', 6, 'Grade 12', '1st Semester'),
(32, 'English for Academic and Professional Purposes', 6, 'Grade 12', '1st Semester'),
(33, 'Practical Research 2', 6, 'Grade 12', '1st Semester'),
(34, 'Pagsulat sa Filipino sa Larangan (Tekbok)', 6, 'Grade 12', '1st Semester'),
(35, 'Entrepreneurship', 6, 'Grade 12', '1st Semester'),
(36, 'Animation 1', 6, 'Grade 12', '1st Semester'),
(37, 'Animation 2', 6, 'Grade 12', '1st Semester'),
(38, 'Contemporary Philippines Arts from the Regions', 6, 'Grade 12', '2nd Semester'),
(39, 'Physical Education and Health 4', 6, 'Grade 12', '2nd Semester'),
(40, 'Media and Information Literacy', 6, 'Grade 12', '2nd Semester'),
(41, 'Inquiries, Investigations and Immersion', 6, 'Grade 12', '2nd Semester'),
(42, 'Animation 3', 6, 'Grade 12', '2nd Semester'),
(43, 'Animation 4', 6, 'Grade 12', '2nd Semester'),
(44, 'Work Immersion', 6, 'Grade 12', '2nd Semester'),
(45, 'Oral Communication', 9, 'Grade 11', '1st Semester'),
(46, 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 9, 'Grade 11', '1st Semester'),
(47, 'General Mathematics', 9, 'Grade 11', '1st Semester'),
(48, 'Earth and Life Science/Eath Science', 9, 'Grade 11', '1st Semester'),
(49, 'Understanding Culture, Society and Politics', 9, 'Grade 11', '1st Semester'),
(50, 'Physical Education and Health 1', 9, 'Grade 11', '1st Semester'),
(51, 'Personal Development/Pansariling Kaunlaran', 9, 'Grade 11', '1st Semester'),
(52, 'Principles of marketing', 9, 'Grade 11', '1st Semester'),
(53, 'Reading and Writing', 9, 'Grade 11', '2nd Semester'),
(54, 'Pagbasa at Pagsusuri ng Iba\'t-ibang Teksto Tungo sa Pananaliksik', 9, 'Grade 11', '2nd Semester'),
(55, '21st Century Literature from the Philippines and the World', 9, 'Grade 11', '2nd Semester'),
(56, 'Statistics and Probability', 9, 'Grade 11', '2nd Semester'),
(57, 'Physical Education and Health 2', 9, 'Grade 11', '2nd Semester'),
(58, 'Practical Research 1', 9, 'Grade 11', '2nd Semester'),
(59, 'Principles of marketing', 9, 'Grade 11', '2nd Semester'),
(60, 'Oral Communication', 4, 'Grade 11', '1st Semester'),
(61, 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 4, 'Grade 11', '1st Semester'),
(62, 'General Mathematics', 4, 'Grade 11', '1st Semester'),
(63, 'Earth and Life Science/Eath Science', 4, 'Grade 11', '1st Semester'),
(64, 'Understanding Culture, Society and Politics', 4, 'Grade 11', '1st Semester'),
(65, 'Physical Education and Health 1', 4, 'Grade 11', '1st Semester'),
(66, 'Personal Development/Pansariling Kaunlaran', 4, 'Grade 11', '1st Semester'),
(67, 'FBS 1', 4, 'Grade 11', '1st Semester'),
(68, 'FBS 2', 4, 'Grade 11', '1st Semester'),
(69, 'Reading and Writing', 4, 'Grade 11', '2nd Semester'),
(70, 'Pagbasa at Pagsusuri ng Iba\'t-ibang Teksto Tungo sa Pananaliksik', 4, 'Grade 11', '2nd Semester'),
(71, '21st Century Literature from the Philippines and the World', 4, 'Grade 11', '2nd Semester'),
(72, 'Statistics and Probability', 4, 'Grade 11', '2nd Semester'),
(73, 'Physical Education and Health 2', 4, 'Grade 11', '2nd Semester'),
(74, 'Practical Research 1', 4, 'Grade 11', '2nd Semester'),
(75, 'BPP 1', 4, 'Grade 11', '2nd Semester'),
(76, 'BPP2', 4, 'Grade 11', '2nd Semester'),
(77, 'Oral Communication', 5, 'Grade 11', '1st Semester'),
(78, 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 5, 'Grade 11', '1st Semester'),
(79, 'General Mathematics', 5, 'Grade 11', '1st Semester'),
(80, 'Earth and Life Science/Eath Science', 5, 'Grade 11', '1st Semester'),
(81, 'Understanding Culture, Society and Politics', 5, 'Grade 11', '1st Semester'),
(82, 'Physical Education and Health 1', 5, 'Grade 11', '1st Semester'),
(83, 'Personal Development/Pansariling Kaunlaran', 5, 'Grade 11', '1st Semester'),
(84, 'UCSP', 5, 'Grade 11', '1st Semester'),
(85, 'DIASS', 5, 'Grade 11', '1st Semester'),
(86, 'Reading and Writing', 5, 'Grade 11', '2nd Semester'),
(87, 'Pagbasa at Pagsusuri ng Iba\'t-ibang Teksto Tungo sa Pananaliksik', 5, 'Grade 11', '2nd Semester'),
(88, '21st Century Literature from the Philippines and the World', 5, 'Grade 11', '2nd Semester'),
(89, 'Statistics and Probability', 5, 'Grade 11', '2nd Semester'),
(90, 'Physical Education and Health 2', 5, 'Grade 11', '2nd Semester'),
(91, 'Practical Research 1', 5, 'Grade 11', '2nd Semester'),
(92, 'POLGOV', 5, 'Grade 11', '2nd Semester'),
(93, 'DIASS', 5, 'Grade 11', '2nd Semester'),
(94, 'Oral Communication', 10, 'Grade 11', '1st Semester'),
(95, 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 10, 'Grade 11', '1st Semester'),
(96, 'General Mathematics', 10, 'Grade 11', '1st Semester'),
(97, 'Earth and Life Science/Eath Science', 10, 'Grade 11', '1st Semester'),
(98, 'Understanding Culture, Society and Politics', 10, 'Grade 11', '1st Semester'),
(99, 'Physical Education and Health 1', 10, 'Grade 11', '1st Semester'),
(100, 'Personal Development/Pansariling Kaunlaran', 10, 'Grade 11', '1st Semester'),
(101, 'Tourism promotion', 10, 'Grade 11', '1st Semester'),
(102, 'Reading and Writing', 10, 'Grade 11', '2nd Semester'),
(103, 'Pagbasa at Pagsusuri ng Iba\'t-ibang Teksto Tungo sa Pananaliksik', 10, 'Grade 11', '2nd Semester'),
(104, '21st Century Literature from the Philippines and the World', 10, 'Grade 11', '2nd Semester'),
(105, 'Statistics and Probability', 10, 'Grade 11', '2nd Semester'),
(106, 'Physical Education and Health 2', 10, 'Grade 11', '2nd Semester'),
(107, 'Practical Research 1', 10, 'Grade 11', '2nd Semester'),
(108, 'Tourism promotion 2', 10, 'Grade 11', '2nd Semester'),
(109, 'Oral Communication', 6, 'Grade 11', '1st Semester'),
(110, 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 6, 'Grade 11', '1st Semester'),
(111, 'Earth and Life Science/Eath Science', 6, 'Grade 11', '1st Semester'),
(112, 'Understanding Culture, Society and Politics', 6, 'Grade 11', '1st Semester'),
(113, 'Physical Education and Health 1', 6, 'Grade 11', '1st Semester'),
(114, 'Personal Development/Pansariling Kaunlaran', 6, 'Grade 11', '1st Semester'),
(115, 'Computer Programming 1', 6, 'Grade 11', '1st Semester'),
(116, 'Computer Programming 2', 6, 'Grade 11', '1st Semester'),
(117, 'Reading and Writing', 6, 'Grade 11', '2nd Semester'),
(118, 'Pagbasa at Pagsusuri ng Iba\'t-ibang Teksto Tungo sa Pananaliksik', 6, 'Grade 11', '2nd Semester'),
(119, '21st Century Literature from the Philippines and the World', 6, 'Grade 11', '2nd Semester'),
(120, 'Statistics and Probability', 6, 'Grade 11', '2nd Semester'),
(121, 'Physical Education and Health 2', 6, 'Grade 11', '2nd Semester'),
(122, 'Practical Research 1', 6, 'Grade 11', '2nd Semester'),
(123, 'Computer Programming 3', 6, 'Grade 11', '2nd Semester'),
(124, 'Computer Programming 4', 6, 'Grade 11', '2nd Semester'),
(126, 'Oral Communication', 7, 'Grade 11', '1st Semester'),
(127, 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 7, 'Grade 11', '1st Semester'),
(128, 'General Mathematics', 7, 'Grade 11', '1st Semester'),
(129, 'Earth and Life Science/Eath Science', 7, 'Grade 11', '1st Semester'),
(130, 'Understanding Culture, Society and Politics', 7, 'Grade 11', '1st Semester'),
(131, 'Physical Education and Health 1', 7, 'Grade 11', '1st Semester'),
(132, 'Personal Development/Pansariling Kaunlaran', 7, 'Grade 11', '1st Semester'),
(133, 'Pre calculus', 7, 'Grade 11', '1st Semester'),
(134, 'Basic cal', 7, 'Grade 11', '1st Semester'),
(135, 'Reading and Writing', 7, 'Grade 11', '2nd Semester'),
(136, 'Pagbasa at Pagsusuri ng Iba\'t-ibang Teksto Tungo sa Pananaliksik', 7, 'Grade 11', '2nd Semester'),
(137, '21st Century Literature from the Philippines and the World', 7, 'Grade 11', '2nd Semester'),
(138, 'Statistics and Probability', 7, 'Grade 11', '2nd Semester'),
(139, 'Physical Education and Health 2', 7, 'Grade 11', '2nd Semester'),
(140, 'Practical Research 1', 7, 'Grade 11', '2nd Semester'),
(141, 'Gen chem 1', 7, 'Grade 11', '2nd Semester'),
(142, 'Gen chem 2', 7, 'Grade 11', '2nd Semester'),
(148, 'Introduction to the Philosophy of the Human Person', 7, 'Grade 12', '1st Semester'),
(149, 'Physical Science', 7, 'Grade 12', '1st Semester'),
(150, 'Physical Education and Health 3', 7, 'Grade 12', '1st Semester'),
(151, 'Gen bio 1', 7, 'Grade 12', '1st Semester'),
(152, 'Gen bio 2', 7, 'Grade 12', '1st Semester'),
(153, 'Contemporary Philippines Arts from the Regions', 7, 'Grade 12', '2nd Semester'),
(154, 'Physical Education and Health 4', 7, 'Grade 12', '2nd Semester'),
(155, 'Media and Information Literacy', 7, 'Grade 12', '2nd Semester'),
(156, 'Gen physics 1', 7, 'Grade 12', '2nd Semester'),
(157, 'Gen physics 2', 7, 'Grade 12', '2nd Semester'),
(158, 'Introduction to the Philosophy of the Human Person', 9, 'Grade 12', '1st Semester'),
(159, 'Physical Science', 9, 'Grade 12', '1st Semester'),
(160, 'Physical Education and Health 3', 9, 'Grade 12', '1st Semester'),
(161, 'Fundamentals of Accountancy Business', 9, 'Grade 12', '1st Semester'),
(162, 'Management Business Finance', 9, 'Grade 12', '1st Semester'),
(163, 'Contemporary Philippines Arts from the Regions', 9, 'Grade 12', '2nd Semester'),
(164, 'Physical Education and Health 4', 9, 'Grade 12', '2nd Semester'),
(165, 'Media and Information Literacy', 9, 'Grade 12', '2nd Semester'),
(166, 'Business Ethics', 9, 'Grade 12', '2nd Semester'),
(167, 'Applied Economics', 9, 'Grade 12', '2nd Semester'),
(168, 'Introduction to the Philosophy of the Human Person', 4, 'Grade 12', '1st Semester'),
(169, 'Physical Science', 4, 'Grade 12', '1st Semester'),
(170, 'Physical Education and Health 3', 4, 'Grade 12', '1st Semester'),
(171, 'Food Bevarage Service 1', 4, 'Grade 12', '1st Semester'),
(172, 'Food Bevarage Service 2', 4, 'Grade 12', '1st Semester'),
(173, 'Contemporary Philippines Arts from the Regions', 4, 'Grade 12', '2nd Semester'),
(174, 'Physical Education and Health 4', 4, 'Grade 12', '2nd Semester'),
(175, 'Media and Information Literacy', 4, 'Grade 12', '2nd Semester'),
(176, 'BPP 1', 4, 'Grade 12', '2nd Semester'),
(177, 'BPP2', 4, 'Grade 12', '2nd Semester'),
(178, 'Introduction to the Philosophy of the Human Person', 8, 'Grade 12', '1st Semester'),
(179, 'Physical Science', 8, 'Grade 12', '1st Semester'),
(180, 'Physical Education and Health 3', 8, 'Grade 12', '1st Semester'),
(181, 'English for Academic and Professional Purposes', 8, 'Grade 12', '1st Semester'),
(182, 'disaster readiness and risk reduction', 8, 'Grade 12', '1st Semester'),
(183, 'Contemporary Philippines Arts from the Regions', 8, 'Grade 12', '2nd Semester'),
(184, 'Physical Education and Health 4', 8, 'Grade 12', '2nd Semester'),
(185, 'Media and Information Literacy', 8, 'Grade 12', '2nd Semester'),
(186, 'Gen physics 1', 8, 'Grade 12', '2nd Semester'),
(187, 'Applied economics', 8, 'Grade 12', '2nd Semester'),
(188, 'Introduction to the Philosophy of the Human Person', 5, 'Grade 12', '1st Semester'),
(189, 'Physical Science', 5, 'Grade 12', '1st Semester'),
(190, 'Physical Education and Health 3', 5, 'Grade 12', '1st Semester'),
(191, 'English for academic and professional purposes', 5, 'Grade 12', '1st Semester'),
(192, 'Community engagement and solidarity', 5, 'Grade 12', '1st Semester'),
(193, 'Contemporary Philippines Arts from the Regions', 5, 'Grade 12', '2nd Semester'),
(194, 'Physical Education and Health 4', 5, 'Grade 12', '2nd Semester'),
(195, 'Media and Information Literacy', 5, 'Grade 12', '2nd Semester'),
(196, 'Creative writing non fiction', 5, 'Grade 12', '2nd Semester'),
(197, 'Trend,Network and thinking', 5, 'Grade 12', '2nd Semester'),
(206, 'Oral Communication', 8, 'Grade 11', '1st Semester'),
(207, 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 8, 'Grade 11', '1st Semester'),
(208, 'General Mathematics', 8, 'Grade 11', '1st Semester'),
(209, 'Earth and Life Science/Eath Science', 8, 'Grade 11', '1st Semester'),
(210, 'Understanding Culture, Society and Politics', 8, 'Grade 11', '1st Semester'),
(211, 'Physical Education and Health 1', 8, 'Grade 11', '1st Semester'),
(212, 'Personal Development/Pansariling Kaunlaran', 8, 'Grade 11', '1st Semester'),
(213, 'Pre calculus', 8, 'Grade 11', '1st Semester'),
(214, 'Organizational Management', 8, 'Grade 11', '1st Semester'),
(215, 'Reading and Writing', 8, 'Grade 11', '2nd Semester'),
(216, 'Pagbasa at Pagsusuri ng Iba\'t-ibang Teksto Tungo sa Pananaliksik', 8, 'Grade 11', '2nd Semester'),
(217, '21st Century Literature from the Philippines and the World', 8, 'Grade 11', '2nd Semester'),
(218, 'Statistics and Probability', 8, 'Grade 11', '2nd Semester'),
(219, 'Physical Education and Health 2', 8, 'Grade 11', '2nd Semester'),
(220, 'Practical Research 1', 8, 'Grade 11', '2nd Semester'),
(221, 'Polgov', 8, 'Grade 11', '2nd Semester'),
(222, 'Diass', 8, 'Grade 11', '2nd Semester');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('teacher','student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_academic_strands`
--
ALTER TABLE `tbl_academic_strands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `strand_name` (`strand_name`);

--
-- Indexes for table `tbl_information`
--
ALTER TABLE `tbl_information`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `academic_strand_id` (`academic_strand_id`);

--
-- Indexes for table `tbl_students_subjects`
--
ALTER TABLE `tbl_students_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `strand_id` (`strand_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_academic_strands`
--
ALTER TABLE `tbl_academic_strands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_information`
--
ALTER TABLE `tbl_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_students_subjects`
--
ALTER TABLE `tbl_students_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_information`
--
ALTER TABLE `tbl_information`
  ADD CONSTRAINT `tbl_information_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_information_ibfk_2` FOREIGN KEY (`academic_strand_id`) REFERENCES `tbl_academic_strands` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_students_subjects`
--
ALTER TABLE `tbl_students_subjects`
  ADD CONSTRAINT `tbl_students_subjects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tbl_information` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_students_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `tbl_subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  ADD CONSTRAINT `tbl_subjects_ibfk_1` FOREIGN KEY (`strand_id`) REFERENCES `tbl_academic_strands` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
