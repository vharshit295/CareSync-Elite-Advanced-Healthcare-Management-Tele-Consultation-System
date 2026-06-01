-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2026 at 05:22 AM
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
-- Database: `caresync_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `target_role` enum('doctor','patient','both') DEFAULT 'both',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `app_date` date DEFAULT NULL,
  `problem_desc` text DEFAULT NULL,
  `token_number` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `report_file` varchar(255) DEFAULT NULL,
  `hide_identity` int(11) DEFAULT 0,
  `token_no` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `issue` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `feedback_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `appointment_id`, `sender_id`, `message`, `timestamp`) VALUES
(1, 8, 19, 'hiii', '2026-02-21 06:43:42'),
(2, 8, 19, 'hiii', '2026-02-21 06:43:42'),
(3, 33, 13, 'hiii', '2026-03-22 19:29:28'),
(4, 32, 22, 'hii', '2026-03-22 19:29:44'),
(5, 34, 22, 'hii sier', '2026-03-22 19:41:20'),
(6, 34, 22, 'i have problem', '2026-03-22 19:41:27');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `app_id` int(11) DEFAULT NULL,
  `medicine_name` text DEFAULT NULL,
  `advice` text DEFAULT NULL,
  `report_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `prescription_text` text DEFAULT NULL,
  `medicine_details` text DEFAULT NULL,
  `report_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `appointment_id`, `patient_id`, `doctor_id`, `prescription_text`, `medicine_details`, `report_file`, `created_at`) VALUES
(1, 7, 13, 19, 'dummy', NULL, '', '2026-02-19 21:44:24'),
(2, 8, 13, 19, 'just test', NULL, '', '2026-02-21 06:46:25'),
(3, 12, 13, 19, 'okay ', NULL, '', '2026-02-23 16:36:40'),
(4, 16, 13, 19, 'yo', NULL, '', '2026-02-23 18:25:22'),
(5, 34, 13, 22, 'yo bro', NULL, '', '2026-03-22 19:42:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` varchar(10) DEFAULT 'Male',
  `age` int(3) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `license_no` varchar(100) DEFAULT NULL,
  `specialization` varchar(255) NOT NULL,
  `clinic_address` text NOT NULL,
  `available_days` varchar(100) DEFAULT 'Mon-Fri',
  `shift_time` varchar(100) NOT NULL,
  `available_time` varchar(100) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `profile_complete` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `gender`, `age`, `email`, `password`, `role`, `license_no`, `specialization`, `clinic_address`, `available_days`, `shift_time`, `available_time`, `phone`, `status`, `profile_complete`) VALUES
(1, 'Anjali Verma', 'female', NULL, 'anjali@gmail.com', '123', 'doctor', 'MC-2026-AV01', 'Senior Specialist', 'Medical Square, Gorakhpur', 'Mon, Wed, Fri', '10:00 AM - 05:00 PM', '10am-5pm', '07007804318', 'approved', 0),
(2, 'Aditya Sharma', 'Male', NULL, 'aditya@elite.com', '123', 'doctor', 'MC-2026-AS05', 'Cardiologist', 'Civil Lines, Gorakhpur', 'Mon-Sat', '10:00 AM - 02:00 PM', NULL, '9876543210', 'approved', 0),
(3, 'Ishita Singh', 'Female', NULL, 'ishita@elite.com', '123', 'doctor', 'MC-2026-IS09', 'Neurologist', 'Medical Road, Gorakhpur', 'Mon-Fri', '04:00 PM - 08:00 PM', NULL, '9876543211', 'approved', 0),
(4, 'Rahul Verma', 'Male', NULL, 'rahul@elite.com', '123', 'doctor', 'MC-2026-RV03', 'Orthopedic', 'Golghar, Gorakhpur', 'Daily', '09:00 AM - 01:00 PM', NULL, '9876543212', 'approved', 0),
(5, 'Sana Khan', 'Female', NULL, 'sana@elite.com', '123', 'doctor', 'MC-2026-SK07', 'Dermatologist', 'Taramandal, Gorakhpur', 'Mon-Wed', '05:00 PM - 09:00 PM', NULL, '9876543213', 'approved', 0),
(6, 'Vikas Gupta', 'Male', NULL, 'vikas@elite.com', '123', 'doctor', 'MC-2026-VM14', 'Pediatrician', 'Basharatpur, Gorakhpur', 'Tue-Sat', '11:00 AM - 03:00 PM', NULL, '9876543214', 'approved', 0),
(7, 'Anjali Maurya', 'Female', NULL, 'anjali@elite.com', '123', 'doctor', 'MC-2026-AV01', 'Gynecologist', 'Rustampur, Gorakhpur', 'Mon-Fri', '10:00 AM - 02:00 PM', NULL, '9876543215', 'approved', 0),
(8, 'Amit Tiwari', 'Male', NULL, 'amit@elite.com', '123', 'doctor', 'MC-2026-AK29', 'General Physician', 'Khorabar, Gorakhpur', 'Daily', '08:00 AM - 12:00 PM', NULL, '9876543216', 'approved', 0),
(9, 'Priya Rai', 'Female', NULL, 'priya@elite.com', '123', 'doctor', 'VERIFICATION-PENDING', 'Dentist', 'Shahpur, Gorakhpur', 'Mon-Sat', '04:00 PM - 07:00 PM', NULL, '9876543217', 'approved', 0),
(10, 'Sumit Yadav', 'Male', NULL, 'sumit@elite.com', '123', 'doctor', 'VERIFICATION-PENDING', 'Psychiatrist', 'Dharamshala, Gorakhpur', 'Wed-Sun', '02:00 PM - 06:00 PM', NULL, '9876543218', 'approved', 0),
(11, 'Neha Jaiswal', 'Female', NULL, 'neha@elite.com', '123', 'doctor', 'MC-2026-NK11', 'Ophthalmologist', 'Railway Colony, Gorakhpur', 'Mon-Fri', '09:00 AM - 01:00 PM', NULL, '9876543219', 'approved', 0),
(12, 'Sameer Ansari', 'Male', NULL, 'sameer@elite.com', '123', 'doctor', 'MC-2026-SM22', 'Urologist', 'Pipraich, Gorakhpur', 'Sat-Sun', '10:00 AM - 04:00 PM', NULL, '9876543220', 'approved', 0),
(13, 'harshit verma', 'Male', 21, 'vharshirt295@gmail.com', '123', 'patient', NULL, '', '', 'Mon-Fri', '', NULL, '7460018806', 'approved', 0),
(16, 'Admin', 'Male', NULL, 'admin@mail.com', 'admin123', 'admin', NULL, '', '', 'Mon-Fri', '', NULL, '', 'approved', 0),
(19, 'Ayush Tiwari ', 'Male', 21, 'ayushdoc@gmail.com', '123', 'doctor', 'MC-1234', 'Orthopedic', 'Care Hospital,Gorakhpur', 'Mon - Tue', '10:00 AM-04:00 PM', '10AM-3PM', '1234567890', 'approved', 0),
(22, 'Ritesh Sharma', 'Male', 21, 'riteshdoc@gmail.com', '123', 'doctor', 'MC-2026-AV21', 'Cardiologist', 'Care Hospital,Gorakhpur', 'Mon, Wed, Fri, Sun', '09:00 AM - 01:11 PM', NULL, '9305782107', 'approved', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
