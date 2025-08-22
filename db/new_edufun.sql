-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 06:12 AM
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
-- Database: `new_edufun`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Aid` int(11) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Phone` bigint(20) NOT NULL,
  `aemail` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `status` int(11) NOT NULL,
  `gender` enum('MALE','FEMALE') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Aid`, `Name`, `Phone`, `aemail`, `password`, `status`, `gender`) VALUES
(3, 'yashadmin', 7894561239, 'yashadmin28', '$2y$10$SaTviNgb8Z1HrC1hShWNPe3FA6deSyltl0Vo.m8cEzz7uelrIVAyO', 0, 'MALE');

-- --------------------------------------------------------

--
-- Table structure for table `artsquizresult`
--

CREATE TABLE `artsquizresult` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `score` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artsquizresult`
--

INSERT INTO `artsquizresult` (`id`, `uid`, `email`, `score`, `created_at`) VALUES
(13, 9, 'yashraj@gmail.com', 9, '2025-03-19 18:45:34'),
(14, 28, 'Yashraj27', 9, '2025-05-04 04:53:40');

-- --------------------------------------------------------

--
-- Table structure for table `englishquizresult`
--

CREATE TABLE `englishquizresult` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `score` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `englishquizresult`
--

INSERT INTO `englishquizresult` (`id`, `uid`, `email`, `score`, `created_at`) VALUES
(9, 9, 'yashraj@gmail.com', 8, '2025-03-19 18:44:38');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `feedback` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `role`, `phone_number`, `subject`, `feedback`, `created_at`) VALUES
(1, 'yashraj', 'yashraj@gmail.com', 'Student', '1234567891', 'content is not display', 'jhfhfjfj hdhhd gdggdjdj.', '2024-11-24 16:54:19'),
(2, 'yashraj', 'yashraj@gmail.com', 'Student', '1234567891', 'content is not display', 'jhfhfjfj hdhhd gdggdjdj.', '2024-11-24 16:55:48'),
(3, 'parent', 'parent@gmail.com', 'Parent', '1598762469', 'content is not display', 'phfjhf hfhvjjj', '2024-11-24 17:03:35');

-- --------------------------------------------------------

--
-- Table structure for table `mathquizresult`
--

CREATE TABLE `mathquizresult` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `score` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mathquizresult`
--

INSERT INTO `mathquizresult` (`id`, `uid`, `name`, `email`, `phone`, `score`, `created_at`) VALUES
(36, 9, 'yashraj', 'yashraj@gmail.com', 1234567891, 4, '2025-03-19 18:43:53');

-- --------------------------------------------------------

--
-- Table structure for table `parent`
--

CREATE TABLE `parent` (
  `Pid` int(11) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Phone` bigint(20) NOT NULL,
  `Pemail` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `status` int(11) NOT NULL,
  `gender` enum('MALE','FEMALE') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `parent`
--

INSERT INTO `parent` (`Pid`, `Name`, `Phone`, `Pemail`, `password`, `status`, `gender`) VALUES
(3, 'raj patil', 4865935789, 'raj1848', '$2y$10$PxkmCGZ73zGqHn7GXLAOtOr2VUpAlgBZwyn09bP9JllIm7Rs0mk0C', 0, 'MALE');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `gender` enum('MALE','FEMALE') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `name`, `phone`, `email`, `password`, `status`, `gender`) VALUES
(9, 'yashraj', 1234567891, 'yashraj@gmail.com', '123', 0, 'MALE'),
(26, 'yashraj vadagave', 9975040506, 'yashraj27@gmail.com', '$2y$10$JgI4eNjW/e0zuTpoTrBoV.A3Yrp8yedU.jBWF9FecdsLqCj0hL8MS', 0, 'MALE'),
(27, 'yash Vadagave', 7589632415, 'yash27@gmail.com', '$2y$10$2NnVMa0K.Qx46l0F7nnPmOorh/Y/dcV3cTlSdj6KcfLvWEpB2fcIW', 0, 'MALE'),
(28, 'Yashraj Vadagave', 7277692728, 'Yashraj27', '$2y$10$m/lVA9ielhS9xZKWItMwIOBpBOfsbCOwZg3eWNAy6YpL/gI4msEU.', 0, 'MALE'),
(30, 'manthan sawant', 4865935789, 'manthan18', '$2y$10$i.c.K7.n.GPxgJeBBxHWBemCcIYIqMsEWgyfN4Chl9K9JkI.1Rl/e', 1, 'MALE'),
(31, 'Prathmesh Bhagwan Patil', 9325383531, 'Prathmesh1234', '$2y$10$DVU/NC3dylNCivQMu6wDkOeXQh3Xg4rys5Ud8jdqkg4RT7AU/eR0C', 0, 'MALE'),
(32, 'tt', 7777777777, 'yy', '$2y$10$izHxjg.Cx5fzjP9o3gnsIu89eMJVZO2aernFS2GFnN0MV4vhrZxjm', 0, 'MALE'),
(33, 'rohan kashid', 8329082460, 'rohan_kashid', '$2y$10$8f4yXhCekRnZFRnZBJKTSuxz9uWTyzb3XM1aXx88b3xthFXo5byrK', 0, 'MALE');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `video_link` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `title`, `category`, `video_link`, `created_at`) VALUES
(2, 'How To Draw Fall Pikachu', 'art', 'https://youtu.be/t-ZEi0gXmkw?si=ySECn3WsyJv57Wz-', '2025-01-10 16:22:48'),
(3, 'Basic Maths Addition For Kids', 'maths', 'https://youtu.be/mjlsSYLLOSE?si=uHJQeEQgPobckqcR', '2025-01-10 16:40:34'),
(4, 'ABC Surprise Box', 'english', 'https://youtu.be/hFnnK-8zCSU?si=EiUUi_r5vPX23GyY', '2025-01-10 16:42:01'),
(5, 'True Friend', 'stories', 'https://youtu.be/2_t38oktRig?si=lvt-epzcQ4Vv_2Xp', '2025-01-10 16:42:46'),
(6, 'Counting Numbers', 'maths', 'https://youtu.be/D0Ajq682yrA?si=SGgwy-cSNX3FEaPo', '2025-05-06 10:38:45'),
(9, 'Math Fill The Blank Number for children', 'maths', 'https://youtu.be/elqDysYFhjU?si=3vtcFqxXr2S4kjYw', '2025-05-10 16:58:13'),
(10, 'Find The Missing Number', 'maths', 'https://youtu.be/vwtjQzAOlo8?si=FaTVII8TDjyAAi45', '2025-05-10 16:58:57'),
(11, 'Count and Write', 'maths', 'https://youtu.be/VsmusnAZ4QM?si=TEQGVYjSJYUdCrXr', '2025-05-10 16:59:32'),
(12, 'Writing Aplhabet Letters', 'english', 'https://youtu.be/Sw2KZki-eaA?si=x7tBXfj8SCnRop-a', '2025-05-10 17:36:36'),
(15, 'Fill In The Blanks With Correct Vowels', 'english', 'https://youtu.be/ViS_nq9vPuU?si=QQlQ6lmJTEtQPCXq', '2025-05-10 17:40:25'),
(16, 'Learn Basic English Vocabulary', 'english', 'https://youtu.be/QXYW-NgP8EA?si=v6n7DSOJtlwwep3Q', '2025-05-10 17:41:29'),
(17, 'Princess Story', 'stories', 'https://youtu.be/QJzckNkCXps?si=wtN74cNUguaebOPP', '2025-05-10 17:44:31'),
(18, 'Old MacDonald Song with Safari Animals', 'stories', 'https://youtu.be/drkOBuiGPCM?si=KjvhGTG-wtfX7grS', '2025-05-10 17:46:30'),
(19, '10 Easy Drawing For Kids', 'art', 'https://youtu.be/7SWvlUd2at8?si=IN-TqbFO2j47z38g', '2025-05-10 17:47:40'),
(20, 'Learn Shapes', 'art', 'https://youtu.be/jlzX8jt0Now?si=XsqVcnRCX7EfUDzr', '2025-05-10 17:48:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Aid`);

--
-- Indexes for table `artsquizresult`
--
ALTER TABLE `artsquizresult`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `englishquizresult`
--
ALTER TABLE `englishquizresult`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mathquizresult`
--
ALTER TABLE `mathquizresult`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `parent`
--
ALTER TABLE `parent`
  ADD PRIMARY KEY (`Pid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `artsquizresult`
--
ALTER TABLE `artsquizresult`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `englishquizresult`
--
ALTER TABLE `englishquizresult`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mathquizresult`
--
ALTER TABLE `mathquizresult`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `parent`
--
ALTER TABLE `parent`
  MODIFY `Pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artsquizresult`
--
ALTER TABLE `artsquizresult`
  ADD CONSTRAINT `artsquizresult_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);

--
-- Constraints for table `englishquizresult`
--
ALTER TABLE `englishquizresult`
  ADD CONSTRAINT `englishquizresult_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);

--
-- Constraints for table `mathquizresult`
--
ALTER TABLE `mathquizresult`
  ADD CONSTRAINT `mathquizresult_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
