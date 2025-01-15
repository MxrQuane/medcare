-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2025 at 01:46 AM
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
-- Database: `medcare`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `contact` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `user_id`, `clinic_id`, `contact`) VALUES
(1, 4, 1, '0562938495');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` enum('booked','cancelled','done') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `patient_id`, `doctor_id`, `clinic_id`, `date`, `time`, `status`) VALUES
(3, 1, 1, 1, '2025-01-10', '09:00:00', 'done'),
(4, 1, 2, 1, '2025-01-10', '10:30:00', 'done'),
(7, 1, 1, 1, '2025-01-14', '08:30:00', 'cancelled'),
(8, 1, 1, 1, '2025-01-15', '09:00:00', 'booked'),
(9, 1, 1, 1, '2025-01-14', '10:30:00', 'done'),
(11, 1, 1, 1, '2025-01-15', '09:30:00', 'booked');

-- --------------------------------------------------------

--
-- Table structure for table `clinics`
--

CREATE TABLE `clinics` (
  `clinic_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `municipality` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clinics`
--

INSERT INTO `clinics` (`clinic_id`, `name`, `address`, `phone`, `email`, `state`, `municipality`) VALUES
(1, 'City Heart Clinic', '123 Health St, City, Country', '+123 456 7890', 'info@cityheartclinic.com', 'jijel', 'jijel');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `specialty` varchar(100) NOT NULL,
  `experience` varchar(50) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `bio` text DEFAULT NULL,
  `rating` float NOT NULL,
  `review_count` int(11) NOT NULL,
  `pfp` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `user_id`, `specialty`, `experience`, `contact`, `bio`, `rating`, `review_count`, `pfp`) VALUES
(1, 3, 'Generalist', '10+ years', '0562423839', 'professional doctor with 10+ years of experience', 4, 96, ''),
(2, 6, 'Dentist', '+5 years', '0663728394', 'professional Dentist with 5+ years of experience', 3.61143, 105, '');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_working_hours`
--

CREATE TABLE `doctor_working_hours` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `day_of_week` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_working_hours`
--

INSERT INTO `doctor_working_hours` (`id`, `doctor_id`, `clinic_id`, `start_time`, `end_time`, `day_of_week`) VALUES
(1, 1, 1, '08:00:00', '13:00:00', 'Monday'),
(2, 1, 1, '08:00:00', '13:00:00', 'Tuesday'),
(3, 1, 1, '08:00:00', '13:00:00', 'Wednesday'),
(4, 1, 1, '08:00:00', '13:00:00', 'Thursday'),
(5, 1, 1, '08:00:00', '13:00:00', 'Friday'),
(6, 2, 1, '08:00:00', '13:00:00', 'Monday'),
(7, 2, 1, '08:00:00', '13:00:00', 'Tuesday'),
(8, 2, 1, '08:00:00', '13:00:00', 'Wednesday'),
(9, 2, 1, '08:00:00', '13:00:00', 'Thursday'),
(10, 2, 1, '08:00:00', '13:00:00', 'Friday');

-- --------------------------------------------------------

--
-- Table structure for table `inclinic`
--

CREATE TABLE `inclinic` (
  `clinic_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inclinic`
--

INSERT INTO `inclinic` (`clinic_id`, `doctor_id`) VALUES
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `contact` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `user_id`, `contact`) VALUES
(1, 1, '0562423839');

-- --------------------------------------------------------

--
-- Table structure for table `reviewed`
--

CREATE TABLE `reviewed` (
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Doctor','Patient') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`) VALUES
(1, 'marouane haine', 'marouane@gmail.com', '$2y$10$6W7P3wLtyPCDPOyZDKMfse3SlXksUMgYLfL2n2jCAWmhcu2x.7Qku', 'Patient'),
(3, 'adem haine', 'adem.haine@gmail.com', '$2y$10$2Yu6IK0YlUqi1.9GKBkZnedPp21m2dfkNUrw.MQlrpPUxbhxtWxDS', 'Doctor'),
(4, 'Lamouri Yakoub', 'yakoub.lamouri@gmail.com', '$2y$10$GAifShJ5cjF/iNEcEzxQ/OgKbrU4.Sx5a3RhUTta436g3TX2gN1qq', 'Admin'),
(6, 'aissa achouri', 'melamea@gmail.com', '$2y$10$cOMnHOHPOHqUScEm7MWREuOlLLNRpMoSzw/AdSZkFITDht5cu6dmy', 'Doctor'),
(8, 'taiga', 'taiga369@gmail.com', '$2y$10$WZvmaSimFskRKrv.qMI6ees9kaXbiBTugyi81FkQYT2sXevYKS3my', 'Doctor'),
(15, 'dzafa', 'yakoub.lamouleari@gmail.com', '$2y$10$5.TOr0jfjs3X5E8E9SS8ieYHV9K474F74eN9xf1wypR36.L6Veafe', 'Doctor'),
(16, 'aissa achouri', 'yakoub.lamoduleari@gmail.com', '$2y$10$QfsmPjcEY2t2sxetwZBPtO.nwAo2iM6OSIJmBuuxQ.BWRRawsefZK', 'Doctor'),
(17, 'marouane', 'yakoub.lamouleaddri@gmail.com', '$2y$10$S1TFcb4K4EB91z313NzZBemA6VGaSbj.SGT66WuU1sWjMYZ/FwA9O', 'Doctor');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `clinic_id` (`clinic_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `clinic_id` (`clinic_id`);

--
-- Indexes for table `clinics`
--
ALTER TABLE `clinics`
  ADD PRIMARY KEY (`clinic_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `doctor_working_hours`
--
ALTER TABLE `doctor_working_hours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `clinic_id` (`clinic_id`);

--
-- Indexes for table `inclinic`
--
ALTER TABLE `inclinic`
  ADD PRIMARY KEY (`clinic_id`,`doctor_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `reviewed`
--
ALTER TABLE `reviewed`
  ADD KEY `doctor_id` (`doctor_id`,`patient_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `clinics`
--
ALTER TABLE `clinics`
  MODIFY `clinic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `doctor_working_hours`
--
ALTER TABLE `doctor_working_hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admins_ibfk_2` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_working_hours`
--
ALTER TABLE `doctor_working_hours`
  ADD CONSTRAINT `doctor_working_hours_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_working_hours_ibfk_2` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE;

--
-- Constraints for table `inclinic`
--
ALTER TABLE `inclinic`
  ADD CONSTRAINT `inclinic_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inclinic_ibfk_2` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`clinic_id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviewed`
--
ALTER TABLE `reviewed`
  ADD CONSTRAINT `reviewed_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviewed_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
