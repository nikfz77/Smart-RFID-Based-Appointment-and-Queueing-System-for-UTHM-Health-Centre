-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2026 at 09:35 AM
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
-- Database: `queue_and_appointment_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `matrix_number` varchar(10) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `schedule_time` datetime NOT NULL,
  `service_type` varchar(50) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Pending','Confirmed','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `id` int(11) NOT NULL,
  `queue_number` varchar(10) NOT NULL,
  `matrix_number` varchar(10) NOT NULL,
  `service_type` varchar(50) NOT NULL,
  `queue_status` enum('Waiting','Being-Served','Completed','Cancelled') NOT NULL DEFAULT 'Waiting',
  `is_priority` tinyint(1) NOT NULL DEFAULT 0,
  `checked_in_at` datetime DEFAULT NULL,
  `scheduled_time` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `assigned_room` varchar(20) DEFAULT NULL,
  `assigned_doctor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`id`, `queue_number`, `matrix_number`, `service_type`, `queue_status`, `is_priority`, `checked_in_at`, `scheduled_time`, `created_at`, `updated_at`, `assigned_room`, `assigned_doctor_id`) VALUES
(1, 'Q001', 'AB230016', 'General Consultation', 'Completed', 0, NULL, '2026-05-04 22:00:00', '2026-05-04 20:42:47', '2026-05-04 21:58:12', NULL, NULL),
(2, 'Q002', 'AB230016', 'Vaccination', 'Completed', 0, NULL, '2026-05-04 14:00:00', '2026-05-04 21:56:16', '2026-05-04 22:00:02', NULL, NULL),
(3, 'Q003', 'AI230087', 'General Consultation', 'Completed', 0, NULL, '2026-05-04 23:00:00', '2026-05-04 22:07:45', '2026-05-05 11:57:31', NULL, NULL),
(4, 'Q004', 'AF230026', 'Prescription Refill', 'Cancelled', 0, NULL, NULL, '2026-05-04 22:24:37', '2026-05-05 11:59:59', NULL, NULL),
(5, 'Q005', 'AB230016', 'General Consultation', 'Cancelled', 0, NULL, NULL, '2026-05-04 22:24:57', '2026-05-05 12:00:00', NULL, NULL),
(6, 'Q006', 'AD230039', 'Prescription Refill', 'Cancelled', 0, NULL, NULL, '2026-05-04 22:25:28', '2026-05-05 12:00:02', NULL, NULL),
(7, 'Q001', 'AI230087', 'General Consultation', 'Completed', 0, NULL, '2026-05-06 22:59:00', '2026-05-06 22:53:49', '2026-05-06 22:59:52', NULL, NULL),
(8, 'Q002', 'AB230016', 'Follow-up Check', 'Completed', 0, NULL, '2026-05-06 00:00:00', '2026-05-06 22:54:04', '2026-05-06 23:01:20', NULL, NULL),
(9, 'Q001', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-05-16 20:31:54', NULL, '2026-05-16 20:31:54', '2026-05-16 20:32:14', NULL, NULL),
(10, 'Q002', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-16 20:32:17', '2026-05-16 21:00:00', '2026-05-16 20:32:17', '2026-05-16 20:34:14', NULL, NULL),
(11, 'Q003', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-05-16 20:40:34', NULL, '2026-05-16 20:40:34', '2026-05-16 21:56:21', NULL, NULL),
(12, 'Q004', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-05-16 21:56:28', '2026-05-16 22:00:00', '2026-05-16 21:56:28', '2026-05-16 22:05:39', NULL, NULL),
(13, 'Q005', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-16 22:18:26', '2026-05-16 22:30:00', '2026-05-16 22:18:26', '2026-05-16 22:19:20', NULL, NULL),
(14, 'Q006', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-05-16 22:26:01', '2026-05-16 22:00:00', '2026-05-16 22:26:01', '2026-05-16 22:27:08', NULL, NULL),
(15, 'Q007', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-16 22:29:03', '2026-05-16 23:00:00', '2026-05-16 22:29:03', '2026-05-16 22:31:37', NULL, NULL),
(16, 'Q008', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-05-16 22:31:56', NULL, '2026-05-16 22:31:56', '2026-05-16 12:32:11', NULL, NULL),
(17, 'Q001', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-05-18 03:41:35', NULL, '2026-05-18 03:41:35', '2026-05-18 03:42:58', NULL, NULL),
(18, 'Q002', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-18 03:46:19', '2026-05-18 04:00:00', '2026-05-18 03:46:19', '2026-05-18 03:48:37', NULL, NULL),
(19, 'Q003', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-18 03:52:17', '2026-05-18 04:00:00', '2026-05-18 03:52:17', '2026-05-18 03:54:25', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rfid_tags`
--

CREATE TABLE `rfid_tags` (
  `id` int(11) NOT NULL,
  `rfid_tag` varchar(20) NOT NULL,
  `matrix_number` varchar(10) NOT NULL,
  `status` enum('Active','Inactive','Lost') NOT NULL DEFAULT 'Active',
  `issued_date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rfid_tags`
--

INSERT INTO `rfid_tags` (`id`, `rfid_tag`, `matrix_number`, `status`, `issued_date`, `created_at`) VALUES
(5, '3941EF50', 'AI230087', 'Active', '0000-00-00', '2026-05-16 20:13:07');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `matrix_number` varchar(10) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `faculty` varchar(10) NOT NULL,
  `program` varchar(100) NOT NULL,
  `year_of_study` tinyint(4) NOT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `emergency_contact_name` varchar(100) NOT NULL,
  `emergency_contact_phone` varchar(15) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `matrix_number`, `full_name`, `email`, `phone`, `faculty`, `program`, `year_of_study`, `blood_type`, `allergies`, `emergency_contact_name`, `emergency_contact_phone`, `created_at`, `updated_at`) VALUES
(2, 'BF220101', 'Nurul Aina binti Zulkifli', 'bf220101@student.uthm.edu.my', '0134567890', 'FKEE', 'Electrical Engineering', 1, 'A+', NULL, 'Zulkifli bin Omar', '0187654321', '2026-05-04 20:18:28', '2026-05-04 20:18:28'),
(3, 'CE190567', 'Lim Wei Jian', 'ce190567@student.uthm.edu.my', '0112345678', 'FKMP', 'Mechanical Engineering', 3, 'B+', NULL, 'Lim Ah Kow', '0176543210', '2026-05-04 20:18:28', '2026-05-04 20:18:28'),
(5, 'AI230087', 'NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM', 'ai230087@student.uthm.edu.my', '', 'FSKTM', 'web technolpgy', 0, NULL, NULL, '', '', '2026-05-16 20:13:07', '2026-05-16 20:13:07'),
(6, 'AF230026', 'MUHAMMAD DANIAL IKHWAN BIN ASMAWIE', 'af230036@student.uthm.edu.my', '', 'FKAAB', 'civil engineering', 0, NULL, NULL, '', '', '2026-05-18 03:25:25', '2026-05-18 03:25:25');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `username` varchar(50) NOT NULL,
  `role` varchar(20) NOT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL DEFAULT '0.0.0.0',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`id`, `user_id`, `username`, `role`, `action`, `details`, `ip_address`, `created_at`) VALUES
(1, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-04 20:34:23'),
(2, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-04 20:36:09'),
(3, 2, 'staff1', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-04 20:37:09'),
(4, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-04 20:44:02'),
(5, 3, 'doctor1', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-04 21:53:16'),
(6, 3, 'doctor1', 'Doctor', 'Set Queue Time', 'Queue Q001 set to 08:30 for AB230016', '::1', '2026-05-04 21:54:27'),
(7, 3, 'doctor1', 'Doctor', 'Set Queue Time', 'Queue Q001 set to 22:00 for AB230016', '::1', '2026-05-04 21:54:44'),
(8, 3, 'doctor1', 'Doctor', 'Set Queue Time', 'Queue Q002 set to 22:00 for AB230016', '::1', '2026-05-04 21:57:41'),
(9, 3, 'doctor1', 'Doctor', 'Set Queue Time', 'Queue Q002 set to 22:00 for AB230016', '::1', '2026-05-04 21:57:44'),
(10, 3, 'doctor1', 'Doctor', 'Set Queue Time', 'Queue Q002 set to 14:00 for AB230016', '::1', '2026-05-04 21:58:19'),
(11, 3, 'doctor1', 'Doctor', 'Set Queue Time', 'Queue Q003 set to 12:00 for AI230087', '::1', '2026-05-04 22:14:58'),
(12, 3, 'doctor1', 'Doctor', 'Set Queue Time', 'Queue Q003 set to 23:00 for AI230087', '::1', '2026-05-04 22:15:09'),
(13, 3, 'doctor1', 'Doctor', 'Set Queue Time', 'Queue Q003 set to 23:00 for AI230087', '::1', '2026-05-04 22:15:13'),
(14, 0, 'admin', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-04 22:17:25'),
(15, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-04 22:17:30'),
(16, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-04 22:20:52'),
(17, 3, 'doctor1', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-04 22:24:00'),
(18, 3, 'doctor1', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-04 22:27:27'),
(19, 2, 'staff1', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-04 23:40:54'),
(20, 0, 'admin', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-05 11:53:58'),
(21, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-05 11:54:06'),
(22, 1, 'admin', 'Admin', 'Add Staff', 'Added staff: staff2 (Staff)', '::1', '2026-05-05 11:56:45'),
(23, 1, 'admin', 'Admin', 'Toggle Staff Status', 'Set user ID 2 to Inactive', '::1', '2026-05-05 11:56:58'),
(24, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-05 11:57:19'),
(25, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-05 11:58:15'),
(26, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-05 11:59:19'),
(27, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-05 11:59:31'),
(28, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-05 12:00:56'),
(29, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-06 22:52:42'),
(30, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-06 22:53:18'),
(31, 7, 'staff2', 'Staff', 'Set Queue Time', 'Queue Q001 set to 23:30 for AI230087', '::1', '2026-05-06 22:58:04'),
(32, 7, 'staff2', 'Staff', 'Set Queue Time', 'Queue Q001 set to 23:30 for AI230087', '::1', '2026-05-06 22:58:20'),
(33, 7, 'staff2', 'Staff', 'Set Queue Time', 'Queue Q001 set to 22:59 for AI230087', '::1', '2026-05-06 22:58:56'),
(34, 7, 'staff2', 'Staff', 'Set Queue Time', 'Queue Q002 set to 00:00 for AB230016', '::1', '2026-05-06 23:00:37'),
(35, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-06 23:10:36'),
(36, 1, 'admin', 'Admin', 'Toggle Staff Status', 'Set user ID 2 to Active', '::1', '2026-05-06 23:11:54'),
(37, 1, 'admin', 'Admin', 'Toggle Staff Status', 'Set user ID 2 to Inactive', '::1', '2026-05-06 23:11:58'),
(38, 1, 'admin', 'Admin', 'Add Staff', 'Added staff: staff3 (Staff)', '::1', '2026-05-06 23:13:44'),
(39, 3, 'doctor1', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-06 23:14:38'),
(40, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-06 23:15:03'),
(41, 1, 'admin', 'Admin', 'Toggle Staff Status', 'Set user ID 3 to Inactive', '::1', '2026-05-06 23:15:14'),
(42, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-06 23:15:58'),
(43, 1, 'admin', 'Admin', 'Delete Staff', 'Deleted staff: doctor1', '::1', '2026-05-06 23:16:10'),
(44, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-06 23:21:50'),
(45, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-11 14:20:28'),
(46, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-13 01:59:40'),
(47, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-13 02:05:00'),
(48, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-14 17:03:44'),
(49, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-14 17:04:24'),
(50, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-14 17:08:02'),
(51, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-14 20:44:09'),
(52, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-14 23:47:48'),
(53, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-14 23:56:54'),
(54, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-15 00:14:59'),
(55, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-15 00:15:48'),
(56, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-15 00:17:09'),
(57, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-15 00:21:14'),
(58, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-15 00:29:26'),
(59, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-15 00:30:01'),
(60, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-16 15:56:21'),
(61, 7, 'staff2', 'Staff', 'Password Reset Request', 'Reset email sent to af230026@student.uthm.edu.my', '::1', '2026-05-16 15:59:11'),
(62, 7, 'staff2', 'Staff', 'Password Reset Request', 'Reset email sent to ai230087@student.uthm.edu.my', '::1', '2026-05-16 16:01:03'),
(63, 7, 'staff2', 'Staff', 'Password Reset Request', 'Reset email sent to nikfaiezzz@gmail.com', '::1', '2026-05-16 16:03:27'),
(64, 7, 'staff2', 'Staff', 'Password Reset Request', 'Reset email sent to ai230087@student.uthm.edu.my', '::1', '2026-05-16 16:04:49'),
(65, 7, 'staff2', 'Staff', 'Password Reset Request', 'Reset email sent to af230026@student.uthm.edu.my', '::1', '2026-05-16 16:17:25'),
(66, 7, 'staff2', 'Staff', 'Password Reset Request', 'Reset email sent to ai230087@student.uthm.edu.my', '::1', '2026-05-16 16:18:40'),
(67, 7, 'staff2', 'Staff', 'Password Reset Request', 'Reset email sent to ai230087@student.uthm.edu.my', '::1', '2026-05-16 16:22:30'),
(68, 7, 'staff2', 'Staff', 'Password Reset Request', 'Reset email sent to ai230087@student.uthm.edu.my', '::1', '2026-05-16 16:29:11'),
(69, 7, 'staff2', 'Staff', 'Password Reset Request', 'Reset email sent to ai230087@student.uthm.edu.my', '::1', '2026-05-16 16:32:04'),
(70, 7, 'staff2', 'Staff', 'Password Reset Request', 'Reset email sent to ai230087@student.uthm.edu.my', '::1', '2026-05-16 16:34:56'),
(71, 7, 'staff2', 'Staff', 'Password Reset Request', 'Reset email sent to ai230087@student.uthm.edu.my', '::1', '2026-05-16 16:37:40'),
(72, 7, 'staff2', 'Staff', 'Password Reset', 'Password reset successfully', '::1', '2026-05-16 16:38:42'),
(73, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-16 16:38:55'),
(74, 7, 'staff2', 'Staff', 'Password Reset Request', 'Reset email sent to ai230087@student.uthm.edu.my', '::1', '2026-05-16 19:49:39'),
(75, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-16 19:55:31'),
(76, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-16 19:59:17'),
(77, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-16 20:00:05'),
(78, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-16 20:00:17'),
(79, 7, 'staff2', 'Staff', 'Register Patient', 'Registered: AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) Type: student RFID: 3941EF50', '::1', '2026-05-16 20:13:07'),
(80, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q001', '192.168.0.243', '2026-05-16 20:31:54'),
(81, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q002', '192.168.0.243', '2026-05-16 20:32:17'),
(82, 7, 'staff2', 'Staff', 'Set Queue Time', 'Queue Q002 set to 21:00 for AI230087', '::1', '2026-05-16 20:33:45'),
(83, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q003', '192.168.0.243', '2026-05-16 20:40:34'),
(84, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q004', '192.168.0.243', '2026-05-16 21:56:28'),
(85, 7, 'staff2', 'Staff', 'Set Queue Time', 'Queue Q004 set to 22:00 for AI230087', '::1', '2026-05-16 21:57:21'),
(86, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-16 22:18:03'),
(87, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q005', '192.168.0.243', '2026-05-16 22:18:26'),
(88, 7, 'staff2', 'Staff', 'Set Queue Time', 'Queue Q005 set to 22:30 for AI230087', '::1', '2026-05-16 22:18:53'),
(89, 8, 'staff3', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-16 22:23:39'),
(90, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q006', '192.168.0.243', '2026-05-16 22:26:01'),
(91, 8, 'staff3', 'Staff', 'Set Queue Time', 'Queue Q006 set to 22:00 for AI230087', '::1', '2026-05-16 22:27:06'),
(92, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q007', '192.168.0.243', '2026-05-16 22:29:03'),
(93, 8, 'staff3', 'Staff', 'Set Queue Time', 'Queue Q007 set to 23:00 for AI230087', '::1', '2026-05-16 22:29:25'),
(94, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q008', '192.168.0.243', '2026-05-16 22:31:56'),
(95, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-18 03:09:46'),
(96, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-18 03:23:37'),
(97, 7, 'staff2', 'Staff', 'Register Patient', 'Registered: AF230026 (MUHAMMAD DANIAL IKHWAN BIN ASMAWIE) Type: student', '::1', '2026-05-18 03:25:25'),
(98, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-18 03:26:14'),
(99, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-18 03:28:13'),
(100, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-18 03:40:16'),
(101, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q001', '192.168.0.242', '2026-05-18 03:41:35'),
(102, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-18 03:45:04'),
(103, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q002', '192.168.0.242', '2026-05-18 03:46:19'),
(104, 7, 'staff2', 'Staff', 'Set Queue Time', 'Queue Q002 set to 04:00 for AI230087', '::1', '2026-05-18 03:46:56'),
(105, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q003', '192.168.0.242', '2026-05-18 03:52:17'),
(106, 7, 'staff2', 'Staff', 'Set Queue Time', 'Queue Q003 set to 04:00 for AI230087', '::1', '2026-05-18 03:53:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('Admin','Staff','Doctor') NOT NULL DEFAULT 'Staff',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email` varchar(100) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `room` varchar(20) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 0,
  `clocked_in_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `role`, `status`, `created_at`, `updated_at`, `email`, `reset_token`, `reset_token_expires`, `room`, `is_available`, `clocked_in_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'Admin', 'Active', '2026-05-04 20:18:28', '2026-05-16 15:56:10', 'admin@uthm.edu.my', NULL, NULL, NULL, 0, NULL),
(2, 'staff1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Staff One', 'Staff', 'Inactive', '2026-05-04 20:18:28', '2026-05-16 16:02:10', 'staff1@uthm.edu.my', NULL, NULL, NULL, 0, NULL),
(7, 'staff2', '$2y$10$JK/iyOwVwf11qowWcEIMI.nveJrparacYeLGGIHrpJ.UdokLwMM5u', 'ENCIL ZUL ARIF BIN ZAIDIN', 'Staff', 'Active', '2026-05-05 11:56:45', '2026-05-18 03:45:04', 'ai230087@student.uthm.edu.my', '41b5083e2956560ebf7e5ee2f01ccbfce3e92d7e37b8d0eaa8eb8a834e961a43', '2026-05-16 20:49:35', NULL, 0, NULL),
(8, 'staff3', '$2y$10$cpVKNCdvYKpp.jkW0boVbO6a9Mghjk.PpNARYKJ2nJSPufQcNyVC.', 'PUAN MUNIR', 'Staff', 'Active', '2026-05-06 23:13:44', '2026-05-16 22:23:39', 'puanmunir@uthm.edu.my', NULL, NULL, NULL, 0, NULL),
(9, 'dr_adam', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Adam Hafizi', 'Doctor', 'Active', '2026-05-24 15:25:16', '2026-05-24 15:30:39', NULL, NULL, NULL, 'Room 1', 0, NULL),
(10, 'dr_sarah', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Sarah Aina', 'Doctor', 'Active', '2026-05-24 15:25:16', '2026-05-24 15:30:39', NULL, NULL, NULL, 'Room 2', 0, NULL),
(11, 'dr_razif', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Razif Zulkifli', 'Doctor', 'Active', '2026-05-24 15:25:16', '2026-05-24 15:30:39', NULL, NULL, NULL, 'Room 3', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_appt_matrix` (`matrix_number`),
  ADD KEY `idx_appt_status` (`status`),
  ADD KEY `idx_appt_time` (`schedule_time`);

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_queue_status` (`queue_status`),
  ADD KEY `idx_queue_date` (`created_at`),
  ADD KEY `idx_queue_matrix` (`matrix_number`);

--
-- Indexes for table `rfid_tags`
--
ALTER TABLE `rfid_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rfid_tag` (`rfid_tag`),
  ADD KEY `matrix_number` (`matrix_number`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matrix_number` (`matrix_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_logs_user` (`user_id`),
  ADD KEY `idx_logs_action` (`action`),
  ADD KEY `idx_logs_date` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `rfid_tags`
--
ALTER TABLE `rfid_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `rfid_tags`
--
ALTER TABLE `rfid_tags`
  ADD CONSTRAINT `rfid_tags_ibfk_1` FOREIGN KEY (`matrix_number`) REFERENCES `students` (`matrix_number`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
