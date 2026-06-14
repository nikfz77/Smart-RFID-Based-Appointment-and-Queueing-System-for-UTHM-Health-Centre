-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2026 at 09:42 AM
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
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `assigned_doctor_id` int(11) DEFAULT NULL,
  `assigned_room` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `matrix_number`, `student_name`, `schedule_time`, `service_type`, `notes`, `status`, `created_by`, `created_at`, `updated_at`, `assigned_doctor_id`, `assigned_room`) VALUES
(1, 'AI230087', 'NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM', '2026-05-25 09:48:00', 'General Consultation', 'allergic', 'Completed', 7, '2026-05-24 15:49:15', '2026-05-24 15:49:52', NULL, NULL),
(2, 'AI230087', 'NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM', '2026-05-25 09:00:00', 'Follow-up Check', 'TEST', 'Cancelled', 7, '2026-05-24 15:55:58', '2026-05-24 16:01:55', NULL, NULL),
(3, 'AI230087', 'NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM', '2026-05-25 09:56:00', 'General Consultation', '', 'Completed', 7, '2026-05-24 15:56:38', '2026-05-24 16:02:26', 9, 'Room 1'),
(4, 'AB230016', 'MUHAMMAD BIN ABU BAKAR', '2026-05-25 16:20:00', 'Vaccination', '', 'Completed', 7, '2026-05-24 15:59:08', '2026-05-24 16:02:13', 9, 'Room 1'),
(5, 'AI230087', 'NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM', '2026-05-27 15:00:00', 'General Consultation', '', 'Cancelled', 7, '2026-05-26 00:34:48', '2026-05-26 00:40:02', NULL, NULL),
(6, 'AI230087', 'NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM', '2026-05-26 04:00:00', 'Prescription Refill', '', 'Completed', 9, '2026-05-26 03:55:40', '2026-05-26 04:00:48', 9, 'Room 1'),
(7, 'AB230016', 'MUHAMMAD BIN ABU BAKAR', '2026-05-27 19:53:00', 'Prescription Refill', '', 'Cancelled', 20, '2026-05-26 05:53:50', '2026-05-26 05:55:27', 20, 'Room 6'),
(8, 'AI230087', 'NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM', '2026-05-27 05:00:00', 'Vaccination', '', 'Cancelled', 9, '2026-05-26 05:57:52', '2026-05-26 13:23:51', 11, 'Room 3'),
(9, 'AI230087', 'NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM', '2026-05-26 14:00:00', 'Prescription Refill', '', 'Completed', 11, '2026-05-26 13:59:32', '2026-05-26 14:00:11', 11, 'Room 3'),
(10, 'AI230087', 'NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM', '2026-05-26 14:02:00', 'Prescription Refill', '', 'Completed', 11, '2026-05-26 14:00:35', '2026-05-26 14:01:02', 11, 'Room 3'),
(11, 'AI230087', 'NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM', '2026-06-07 09:00:00', 'General Consultation', '', 'Cancelled', 7, '2026-06-06 05:11:18', '2026-06-06 15:34:27', NULL, NULL),
(12, 'AI230087', 'NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM', '2026-06-10 13:20:00', 'Follow-up Check', '', 'Confirmed', 7, '2026-06-09 10:21:01', '2026-06-09 10:22:21', 20, 'Room 6');

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
  `assigned_doctor_id` int(11) DEFAULT NULL,
  `node_id` int(11) DEFAULT 1,
  `source_faculty` varchar(20) DEFAULT NULL,
  `called_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`id`, `queue_number`, `matrix_number`, `service_type`, `queue_status`, `is_priority`, `checked_in_at`, `scheduled_time`, `created_at`, `updated_at`, `assigned_room`, `assigned_doctor_id`, `node_id`, `source_faculty`, `called_at`) VALUES
(1, 'Q001', 'AB230016', 'General Consultation', 'Completed', 0, NULL, '2026-05-04 22:00:00', '2026-05-04 20:42:47', '2026-05-04 21:58:12', NULL, NULL, 1, NULL, NULL),
(2, 'Q002', 'AB230016', 'Vaccination', 'Completed', 0, NULL, '2026-05-04 14:00:00', '2026-05-04 21:56:16', '2026-05-04 22:00:02', NULL, NULL, 1, NULL, NULL),
(3, 'Q003', 'AI230087', 'General Consultation', 'Completed', 0, NULL, '2026-05-04 23:00:00', '2026-05-04 22:07:45', '2026-05-05 11:57:31', NULL, NULL, 1, NULL, NULL),
(4, 'Q004', 'AF230026', 'Prescription Refill', 'Cancelled', 0, NULL, NULL, '2026-05-04 22:24:37', '2026-05-05 11:59:59', NULL, NULL, 1, NULL, NULL),
(5, 'Q005', 'AB230016', 'General Consultation', 'Cancelled', 0, NULL, NULL, '2026-05-04 22:24:57', '2026-05-05 12:00:00', NULL, NULL, 1, NULL, NULL),
(6, 'Q006', 'AD230039', 'Prescription Refill', 'Cancelled', 0, NULL, NULL, '2026-05-04 22:25:28', '2026-05-05 12:00:02', NULL, NULL, 1, NULL, NULL),
(7, 'Q001', 'AI230087', 'General Consultation', 'Completed', 0, NULL, '2026-05-06 22:59:00', '2026-05-06 22:53:49', '2026-05-06 22:59:52', NULL, NULL, 1, NULL, NULL),
(8, 'Q002', 'AB230016', 'Follow-up Check', 'Completed', 0, NULL, '2026-05-06 00:00:00', '2026-05-06 22:54:04', '2026-05-06 23:01:20', NULL, NULL, 1, NULL, NULL),
(9, 'Q001', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-05-16 20:31:54', NULL, '2026-05-16 20:31:54', '2026-05-16 20:32:14', NULL, NULL, 1, NULL, NULL),
(10, 'Q002', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-16 20:32:17', '2026-05-16 21:00:00', '2026-05-16 20:32:17', '2026-05-16 20:34:14', NULL, NULL, 1, NULL, NULL),
(11, 'Q003', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-05-16 20:40:34', NULL, '2026-05-16 20:40:34', '2026-05-16 21:56:21', NULL, NULL, 1, NULL, NULL),
(12, 'Q004', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-05-16 21:56:28', '2026-05-16 22:00:00', '2026-05-16 21:56:28', '2026-05-16 22:05:39', NULL, NULL, 1, NULL, NULL),
(13, 'Q005', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-16 22:18:26', '2026-05-16 22:30:00', '2026-05-16 22:18:26', '2026-05-16 22:19:20', NULL, NULL, 1, NULL, NULL),
(14, 'Q006', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-05-16 22:26:01', '2026-05-16 22:00:00', '2026-05-16 22:26:01', '2026-05-16 22:27:08', NULL, NULL, 1, NULL, NULL),
(15, 'Q007', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-16 22:29:03', '2026-05-16 23:00:00', '2026-05-16 22:29:03', '2026-05-16 22:31:37', NULL, NULL, 1, NULL, NULL),
(16, 'Q008', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-05-16 22:31:56', NULL, '2026-05-16 22:31:56', '2026-05-16 12:32:11', NULL, NULL, 1, NULL, NULL),
(17, 'Q001', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-05-18 03:41:35', NULL, '2026-05-18 03:41:35', '2026-05-18 03:42:58', NULL, NULL, 1, NULL, NULL),
(18, 'Q002', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-18 03:46:19', '2026-05-18 04:00:00', '2026-05-18 03:46:19', '2026-05-18 03:48:37', NULL, NULL, 1, NULL, NULL),
(19, 'Q003', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-18 03:52:17', '2026-05-18 04:00:00', '2026-05-18 03:52:17', '2026-05-18 03:54:25', NULL, NULL, 1, NULL, NULL),
(20, 'Q001', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-24 16:57:32', '2026-05-24 17:55:00', '2026-05-24 16:57:32', '2026-05-24 17:02:40', 'Room 1', 9, 1, NULL, NULL),
(21, 'Q001', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-26 00:40:30', '2026-05-26 16:44:00', '2026-05-26 00:40:30', '2026-05-26 00:41:57', 'Room 1', 9, 1, NULL, NULL),
(22, 'Q002', 'AI230087', 'Prescription Refill', 'Completed', 0, '2026-05-26 00:43:13', '2026-05-26 04:42:00', '2026-05-26 00:43:13', '2026-05-26 00:45:57', 'Room 1', 9, 1, NULL, NULL),
(23, 'Q003', 'AI230087', 'Prescription Refill', 'Completed', 0, '2026-05-26 03:39:29', NULL, '2026-05-26 03:39:29', '2026-05-26 03:43:23', 'Room 1', 9, 1, NULL, NULL),
(24, 'Q004', 'AI230087', 'Prescription Refill', 'Completed', 0, '2026-05-26 03:43:48', NULL, '2026-05-26 03:43:48', '2026-05-26 03:48:30', 'Room 1', 9, 1, NULL, NULL),
(25, 'Q005', 'AF230026', 'General Consultation', 'Completed', 0, '2026-05-26 04:26:49', NULL, '2026-05-26 04:26:49', '2026-05-26 04:29:15', 'Room 1', 9, 1, NULL, NULL),
(26, 'Q006', 'AI230087', 'Prescription Refill', 'Completed', 0, '2026-05-26 04:27:55', NULL, '2026-05-26 04:27:55', '2026-05-26 04:35:41', 'Room 1', 9, 1, NULL, NULL),
(27, 'Q007', 'AI230087', 'Follow-up Check', 'Completed', 0, '2026-05-26 04:43:23', NULL, '2026-05-26 04:43:23', '2026-05-26 05:06:48', 'Room 1', 9, 1, NULL, NULL),
(28, 'Q008', 'CE190567', 'General Consultation', 'Completed', 0, '2026-05-26 04:43:36', NULL, '2026-05-26 04:43:36', '2026-05-26 05:19:53', 'Room 1', 9, 1, NULL, NULL),
(29, 'Q009', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-26 05:20:09', NULL, '2026-05-26 05:20:09', '2026-05-26 05:22:57', 'Room 1', 9, 1, NULL, NULL),
(30, 'Q010', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-26 05:23:11', NULL, '2026-05-26 05:23:11', '2026-05-26 05:44:03', 'Room 1', 9, 1, NULL, NULL),
(31, 'Q011', 'BF220101', 'Vaccination', 'Completed', 0, '2026-05-26 05:45:08', NULL, '2026-05-26 05:45:08', '2026-05-26 05:58:40', 'Room 1', 9, 1, NULL, NULL),
(32, 'Q012', 'AI230087', 'General Consultation', 'Completed', 0, '2026-05-26 05:48:27', NULL, '2026-05-26 05:48:27', '2026-05-26 05:54:49', 'Room 6', 20, 1, NULL, NULL),
(33, 'Q013', 'AI230087', 'Vaccination', 'Completed', 0, '2026-05-26 13:27:27', NULL, '2026-05-26 13:27:27', '2026-05-26 13:42:24', 'Room 1', 9, 1, NULL, NULL),
(34, 'Q014', 'AF230026', 'Follow-up Check', 'Completed', 0, '2026-05-26 13:29:49', NULL, '2026-05-26 13:29:49', '2026-05-26 13:46:52', 'Room 3', 11, 1, NULL, '2026-05-26 13:42:44'),
(35, 'Q015', 'AI230087', 'Follow-up Check', 'Completed', 0, '2026-05-26 13:58:12', NULL, '2026-05-26 13:58:12', '2026-05-26 13:58:50', 'Room 3', 11, 1, NULL, '2026-05-26 13:58:36'),
(36, 'Q001', 'AI230087', 'General Consultation', 'Completed', 0, '2026-06-02 03:43:28', '2026-06-02 04:00:00', '2026-06-02 03:43:28', '2026-06-02 03:45:47', 'Room 6', 20, 1, 'FSKTM', '2026-06-02 03:45:40'),
(37, 'Q001', 'AI230087', 'General Consultation', 'Waiting', 0, '2026-06-03 02:15:14', NULL, '2026-06-03 02:15:14', '2026-06-03 02:15:14', NULL, NULL, 1, 'FSKTM', NULL),
(38, 'Q001', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-06-05 22:41:20', NULL, '2026-06-05 22:41:20', '2026-06-05 22:41:33', NULL, NULL, 1, 'FSKTM', NULL),
(39, 'Q002', 'AI230087', 'General Consultation', 'Completed', 0, '2026-06-05 23:04:52', NULL, '2026-06-05 23:04:52', '2026-06-05 23:08:28', 'Room 6', 20, 1, NULL, '2026-06-05 23:06:24'),
(40, 'Q003', 'AI230087', 'Follow-up Check', 'Cancelled', 0, '2026-06-05 23:08:41', NULL, '2026-06-05 23:08:41', '2026-06-05 23:14:34', 'Room 6', 20, 1, NULL, '2026-06-05 23:09:06'),
(41, 'Q004', 'AF230026', 'General Consultation', 'Cancelled', 0, '2026-06-05 23:16:34', NULL, '2026-06-05 23:16:34', '2026-06-05 23:17:08', 'Room 6', 20, 1, NULL, NULL),
(42, 'Q005', 'AF230026', 'Follow-up Check', 'Completed', 0, '2026-06-05 23:17:29', NULL, '2026-06-05 23:17:29', '2026-06-05 23:24:14', 'Room 6', 20, 1, NULL, '2026-06-05 23:19:28'),
(43, 'Q006', 'AI230087', 'Follow-up Check', 'Completed', 0, '2026-06-05 23:24:35', NULL, '2026-06-05 23:24:35', '2026-06-05 23:31:43', 'Room 6', 20, 1, NULL, '2026-06-05 23:24:52'),
(44, 'Q007', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-06-05 23:31:55', NULL, '2026-06-05 23:31:55', '2026-06-05 23:36:34', 'Room 6', 20, 1, NULL, '2026-06-05 23:32:59'),
(45, 'Q008', 'AF230026', 'Follow-up Check', 'Cancelled', 0, '2026-06-05 23:32:36', NULL, '2026-06-05 23:32:36', '2026-06-05 23:36:36', 'Room 6', 20, 1, NULL, NULL),
(46, 'Q009', 'AD230039', 'Follow-up Check', 'Completed', 0, '2026-06-05 23:37:10', NULL, '2026-06-05 23:37:10', '2026-06-05 23:42:41', 'Room 6', 20, 1, NULL, '2026-06-05 23:38:32'),
(47, 'Q010', 'AD230039', 'Follow-up Check', 'Waiting', 0, '2026-06-05 23:43:57', NULL, '2026-06-05 23:43:57', '2026-06-05 23:44:14', 'Room 6', 20, 1, NULL, '2026-06-05 23:44:14'),
(48, 'Q001', 'AI230087', 'Follow-up Check', 'Completed', 0, '2026-06-06 04:39:18', NULL, '2026-06-06 04:39:18', '2026-06-06 04:42:51', 'Room 3', 11, 1, NULL, '2026-06-06 04:39:32'),
(49, 'Q002', 'AF230026', 'General Consultation', 'Completed', 0, '2026-06-06 04:43:06', NULL, '2026-06-06 04:43:06', '2026-06-06 04:47:28', 'Room 3', 11, 1, NULL, '2026-06-06 04:45:29'),
(50, 'Q003', 'AD230039', 'Vaccination', 'Cancelled', 0, '2026-06-06 04:47:53', NULL, '2026-06-06 04:47:53', '2026-06-06 05:03:54', 'Room 3', 11, 1, NULL, NULL),
(51, 'Q004', 'CE190567', 'General Consultation', 'Completed', 0, '2026-06-06 05:12:46', NULL, '2026-06-06 05:12:46', '2026-06-06 05:14:47', 'Room 3', 11, 1, NULL, '2026-06-06 05:14:22'),
(52, 'Q005', 'AI230087', 'General Consultation', 'Completed', 0, '2026-06-06 05:25:06', NULL, '2026-06-06 05:25:06', '2026-06-06 05:25:50', 'Room 3', 11, 1, 'FSKTM', '2026-06-06 05:25:38'),
(53, 'Q006', 'BF220101', 'General Consultation', 'Cancelled', 0, '2026-06-06 13:42:20', NULL, '2026-06-06 13:42:20', '2026-06-06 13:48:55', 'Room 3', 11, 1, NULL, '2026-06-06 13:43:53'),
(54, 'Q007', 'AI230087', 'Vaccination', 'Completed', 0, '2026-06-06 13:52:27', NULL, '2026-06-06 13:52:27', '2026-06-06 13:58:35', 'Room 3', 11, 1, NULL, '2026-06-06 13:52:38'),
(55, 'Q008', 'BF220101', 'Prescription Refill', 'Cancelled', 0, '2026-06-06 13:59:03', NULL, '2026-06-06 13:59:03', '2026-06-06 14:04:16', 'Room 3', 11, 1, NULL, '2026-06-06 13:59:16'),
(56, 'Q009', 'AI230087', 'General Consultation', 'Completed', 0, '2026-06-06 15:29:24', NULL, '2026-06-06 15:29:24', '2026-06-06 15:43:03', 'Room 3', 11, 1, 'FSKTM', '2026-06-06 15:42:52'),
(57, 'Q010', 'AF230026', 'General Consultation', 'Completed', 0, '2026-06-06 15:33:33', NULL, '2026-06-06 15:33:33', '2026-06-06 15:43:06', 'Room 3', 11, 1, NULL, '2026-06-06 15:42:57'),
(58, 'Q011', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-06-06 15:43:11', '2026-06-06 16:00:00', '2026-06-06 15:43:11', '2026-06-06 15:47:09', 'Room 3', 11, 1, 'FSKTM', NULL),
(59, 'Q012', 'AD230087', 'Follow-up Check', 'Waiting', 0, '2026-06-06 15:47:20', '2026-06-06 16:00:00', '2026-06-06 15:47:20', '2026-06-06 15:47:25', NULL, NULL, 1, NULL, NULL),
(60, 'Q001', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-06-09 06:27:13', NULL, '2026-06-09 06:27:13', '2026-06-09 06:27:23', NULL, NULL, 1, 'FSKTM', NULL),
(61, 'Q002', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-06-09 06:41:05', NULL, '2026-06-09 06:41:05', '2026-06-09 09:00:35', 'Room 6', 20, 1, 'FSKTM', '2026-06-09 08:55:35'),
(62, 'Q003', 'AI230087', 'General Consultation', 'Completed', 0, '2026-06-09 09:18:33', '2026-06-09 10:00:00', '2026-06-09 09:18:33', '2026-06-09 09:24:54', 'Room 6', 20, 1, NULL, '2026-06-09 09:22:38'),
(63, 'Q004', 'AI230087', 'Vaccination', 'Completed', 0, '2026-06-09 09:32:44', NULL, '2026-06-09 09:32:44', '2026-06-09 10:11:53', 'Room 6', 20, 1, NULL, '2026-06-09 09:33:07'),
(64, 'Q005', 'AI230087', 'General Consultation', 'Completed', 0, '2026-06-09 10:27:50', NULL, '2026-06-09 10:27:50', '2026-06-09 10:31:54', 'Room 6', 20, 1, NULL, '2026-06-09 10:28:17'),
(65, 'Q006', 'AI230087', 'General Consultation', 'Cancelled', 0, '2026-06-09 10:32:18', NULL, '2026-06-09 10:32:18', '2026-06-09 10:40:18', 'Room 6', 20, 1, NULL, '2026-06-09 10:35:18'),
(66, 'Q007', 'GI230004', 'General Consultation', 'Waiting', 0, '2026-06-09 11:40:42', '2026-06-09 13:00:00', '2026-06-09 11:40:42', '2026-06-09 12:06:31', NULL, NULL, 1, 'FSKTM', NULL),
(67, 'Q001', 'GI230004', 'General Consultation', 'Completed', 0, '2026-06-14 11:50:06', NULL, '2026-06-14 11:50:06', '2026-06-14 12:19:14', 'Room 3', 11, 1, NULL, '2026-06-14 12:17:24'),
(68, 'Q002', 'AF230026', 'Vaccination', 'Completed', 0, '2026-06-14 12:19:41', NULL, '2026-06-14 12:19:41', '2026-06-14 12:21:18', 'Room 6', 20, 1, NULL, '2026-06-14 12:20:38'),
(69, 'Q003', 'BF220101', 'General Consultation', 'Completed', 0, '2026-06-14 12:38:17', NULL, '2026-06-14 12:38:17', '2026-06-14 12:41:33', 'Room 6', 20, 1, NULL, '2026-06-14 12:39:46'),
(70, 'Q004', 'BF220101', 'General Consultation', 'Cancelled', 0, '2026-06-14 12:42:02', NULL, '2026-06-14 12:42:02', '2026-06-14 12:47:28', 'Room 6', 20, 1, NULL, '2026-06-14 12:42:28'),
(71, 'Q005', 'BF220101', 'Vaccination', 'Cancelled', 0, '2026-06-14 12:52:50', NULL, '2026-06-14 12:52:50', '2026-06-14 12:59:53', 'Room 6', 20, 1, NULL, '2026-06-14 12:54:53'),
(72, 'Q006', 'PI230087', 'General Consultation', 'Completed', 0, '2026-06-14 13:48:47', NULL, '2026-06-14 13:48:47', '2026-06-14 13:54:55', 'Room 6', 20, 1, NULL, '2026-06-14 13:50:50'),
(73, 'Q007', 'AD230087', 'Prescription Refill', 'Completed', 0, '2026-06-14 14:56:11', NULL, '2026-06-14 14:56:11', '2026-06-14 14:57:51', 'Room 6', 20, 1, NULL, '2026-06-14 14:56:39'),
(74, 'Q008', 'AD230087', 'Vaccination', 'Cancelled', 0, '2026-06-14 14:58:07', NULL, '2026-06-14 14:58:07', '2026-06-14 15:03:33', 'Room 3', 11, 1, NULL, '2026-06-14 14:58:33');

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
(5, '3941EF50', 'AI230087', 'Active', '0000-00-00', '2026-05-16 20:13:07'),
(6, '41154DB8', 'GI230004', 'Active', '0000-00-00', '2026-06-09 11:38:58');

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
(6, 'AF230026', 'MUHAMMAD DANIAL IKHWAN BIN ASMAWIE', 'af230036@student.uthm.edu.my', '', 'FKAAB', 'civil engineering', 0, NULL, NULL, '', '', '2026-05-18 03:25:25', '2026-05-18 03:25:25'),
(7, 'AD230039', 'MUHAMMAD IRFAN HAZIQ BIN ISMADI', 'ad230039@student.uthm.edu.my', '', 'FKMP', 'mechanical engineer', 0, NULL, NULL, '', '', '2026-06-02 01:39:54', '2026-06-02 01:39:54'),
(8, 'AD230087', 'MUHAMMAD IRFAN', 'ad230087@student.uthm.edu.my', '', 'FKMP', 'mechanical engineering', 0, NULL, NULL, '', '', '2026-06-06 15:42:00', '2026-06-06 15:42:00'),
(9, 'GI230004', 'ALI ABDULLAH', 'gi230004@student.uthm.edu.my', '', 'FSKTM', 'Information Technology', 0, NULL, NULL, '', '', '2026-06-09 11:38:58', '2026-06-09 11:38:58'),
(10, 'PI230087', 'DR NAYEF ABDULWAHAB MOHAMMED ALDUAIS', 'nayeff@uthm.edu.my', '', 'FSKTM', 'Information Technology', 0, NULL, NULL, '', '', '2026-06-14 13:48:22', '2026-06-14 13:48:22');

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
(106, 7, 'staff2', 'Staff', 'Set Queue Time', 'Queue Q003 set to 04:00 for AI230087', '::1', '2026-05-18 03:53:15'),
(107, 9, 'dr_adam', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-24 15:36:38'),
(108, 9, 'dr_adam', 'Doctor', 'Clock In', 'Available in Room 1', '::1', '2026-05-24 15:36:49'),
(109, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-24 15:37:12'),
(110, 9, 'dr_adam', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-24 15:41:41'),
(111, 9, 'dr_adam', 'Doctor', 'Clock In', 'Available in Room 1', '::1', '2026-05-24 15:41:46'),
(112, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-24 15:43:16'),
(113, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-24 15:46:04'),
(114, 7, 'staff2', 'Staff', 'Update Appointment', 'Appointment ID 1 set to Confirmed', '::1', '2026-05-24 15:49:39'),
(115, 7, 'staff2', 'Staff', 'Update Appointment', 'Appointment ID 1 set to Completed', '::1', '2026-05-24 15:49:52'),
(116, 7, 'staff2', 'Staff', 'Assign Doctor to Appointment', 'Appointment #4 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-24 16:01:37'),
(117, 7, 'staff2', 'Staff', 'Update Appointment', 'Appointment ID 3 set to Confirmed', '::1', '2026-05-24 16:01:45'),
(118, 7, 'staff2', 'Staff', 'Assign Doctor to Appointment', 'Appointment #3 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-24 16:01:50'),
(119, 7, 'staff2', 'Staff', 'Update Appointment', 'Appointment ID 2 set to Cancelled', '::1', '2026-05-24 16:01:55'),
(120, 7, 'staff2', 'Staff', 'Update Appointment', 'Appointment ID 4 set to Confirmed', '::1', '2026-05-24 16:02:03'),
(121, 7, 'staff2', 'Staff', 'Update Appointment', 'Appointment ID 4 set to Completed', '::1', '2026-05-24 16:02:13'),
(122, 7, 'staff2', 'Staff', 'Assign Doctor to Appointment', 'Appointment #3 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-24 16:02:17'),
(123, 7, 'staff2', 'Staff', 'Assign Doctor to Appointment', 'Appointment #3 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-24 16:02:18'),
(124, 7, 'staff2', 'Staff', 'Assign Doctor to Appointment', 'Appointment #3 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-24 16:02:22'),
(125, 7, 'staff2', 'Staff', 'Update Appointment', 'Appointment ID 3 set to Completed', '::1', '2026-05-24 16:02:26'),
(126, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-24 16:05:13'),
(127, 0, 'dr_adam', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-24 16:20:40'),
(128, 9, 'dr_adam', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-24 16:20:48'),
(129, 9, 'dr_adam', 'Doctor', 'Clock Out', 'Set unavailable', '::1', '2026-05-24 16:21:01'),
(130, 9, 'dr_adam', 'Doctor', 'Clock In', 'Available in Room 1', '::1', '2026-05-24 16:21:14'),
(131, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-24 16:26:08'),
(132, 9, 'dr_adam', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-24 16:26:35'),
(133, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-24 16:26:56'),
(134, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-24 16:40:44'),
(135, 9, 'dr_adam', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-24 16:41:10'),
(136, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-24 16:41:31'),
(137, 9, 'dr_adam', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-24 16:42:06'),
(138, 9, 'dr_adam', 'Doctor', 'Clock In', 'Available in Room 1', '::1', '2026-05-24 16:42:14'),
(139, 9, 'dr_adam', 'Doctor', 'Clock In', 'Available in Room 1', '::1', '2026-05-24 16:42:15'),
(140, 9, 'dr_adam', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q001 (General Consultation) @ 17:55', '::1', '2026-05-24 16:57:32'),
(141, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q001 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-24 17:02:18'),
(142, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-24 17:03:45'),
(143, 9, 'dr_adam', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-24 17:18:44'),
(144, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-24 22:51:26'),
(145, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-26 00:32:23'),
(146, 9, 'dr_adam', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-26 00:36:50'),
(147, 9, 'dr_adam', 'Doctor', 'Update Appointment', 'Appointment ID 5 set to Confirmed', '::1', '2026-05-26 00:37:39'),
(148, 9, 'dr_adam', 'Doctor', 'Clock In', 'Available in Room 1', '::1', '2026-05-26 00:38:09'),
(149, 9, 'dr_adam', 'Doctor', 'Clock Out', 'Set unavailable', '::1', '2026-05-26 00:39:15'),
(150, 9, 'dr_adam', 'Doctor', 'Clock In', 'Available in Room 1', '::1', '2026-05-26 00:39:26'),
(151, 9, 'dr_adam', 'Doctor', 'Update Appointment', 'Appointment ID 5 set to Cancelled', '::1', '2026-05-26 00:40:02'),
(152, 9, 'dr_adam', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q001 (General Consultation) @ 16:44', '::1', '2026-05-26 00:40:30'),
(153, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q001 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 00:40:49'),
(154, 9, 'dr_adam', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q002 (Prescription Refill) @ 04:42', '::1', '2026-05-26 00:43:13'),
(155, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q002 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 00:44:46'),
(156, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-26 00:46:37'),
(157, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-26 03:31:59'),
(158, 11, 'dr_razif', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-26 03:34:55'),
(159, 11, 'dr_razif', 'Doctor', 'Clock In', 'Available in Room 3', '::1', '2026-05-26 03:35:01'),
(160, 20, 'dr_nadia', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-26 03:36:29'),
(161, 20, 'dr_nadia', 'Doctor', 'Clock In', 'Available in Room 6', '::1', '2026-05-26 03:36:35'),
(162, 9, 'dr_adam', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-26 03:36:49'),
(163, 0, 'dr_razif', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-26 03:37:46'),
(164, 11, 'dr_razif', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-26 03:37:53'),
(165, 20, 'dr_nadia', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-26 03:38:34'),
(166, 20, 'dr_nadia', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q003 (Prescription Refill)', '::1', '2026-05-26 03:39:29'),
(167, 20, 'dr_nadia', 'Doctor', 'Assign Doctor', 'Queue Q003 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 03:39:48'),
(168, 9, 'dr_adam', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-26 03:40:28'),
(169, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q003 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 03:40:50'),
(170, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q003 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 03:40:51'),
(171, 9, 'dr_adam', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q004 (Prescription Refill)', '::1', '2026-05-26 03:43:48'),
(172, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q004 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 03:44:13'),
(173, 9, 'dr_adam', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #6 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 04:00:39'),
(174, 9, 'dr_adam', 'Doctor', 'Update Appointment', 'Appointment ID 6 set to Completed', '::1', '2026-05-26 04:00:48'),
(175, 9, 'dr_adam', 'Doctor', 'Walk-In Queue', 'Walk-in: AF230026 → Q005 (General Consultation)', '::1', '2026-05-26 04:26:49'),
(176, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q005 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 04:27:09'),
(177, 9, 'dr_adam', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q006 (Prescription Refill)', '::1', '2026-05-26 04:27:55'),
(178, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q006 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 04:28:31'),
(179, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q006 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 04:28:33'),
(180, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q006 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 04:28:40'),
(181, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q006 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 04:29:32'),
(182, 9, 'dr_adam', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q007 (Follow-up Check)', '::1', '2026-05-26 04:43:23'),
(183, 9, 'dr_adam', 'Doctor', 'Walk-In Queue', 'Walk-in: CE190567 → Q008 (General Consultation)', '::1', '2026-05-26 04:43:36'),
(184, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q007 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 04:43:43'),
(185, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q008 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 04:43:47'),
(186, 9, 'dr_adam', 'Doctor', 'Update Queue Status', 'Queue #28 → Completed', '::1', '2026-05-26 05:19:53'),
(187, 9, 'dr_adam', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q009 (General Consultation)', '::1', '2026-05-26 05:20:09'),
(188, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q009 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 05:20:16'),
(189, 9, 'dr_adam', 'Doctor', 'Update Queue Status', 'Queue #29 → Being-Served', '::1', '2026-05-26 05:20:44'),
(190, 9, 'dr_adam', 'Doctor', 'Update Queue Status', 'Queue #29 → Completed', '::1', '2026-05-26 05:22:57'),
(191, 9, 'dr_adam', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q010 (General Consultation)', '::1', '2026-05-26 05:23:11'),
(192, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q010 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 05:23:17'),
(193, 9, 'dr_adam', 'Doctor', 'Update Queue Status', 'Queue #30 → Being-Served', '::1', '2026-05-26 05:23:31'),
(194, 9, 'dr_adam', 'Doctor', 'Update Queue Status', 'Queue #30 → Completed', '::1', '2026-05-26 05:44:03'),
(195, 9, 'dr_adam', 'Doctor', 'Walk-In Queue', 'Walk-in: BF220101 → Q011 (Vaccination)', '::1', '2026-05-26 05:45:08'),
(196, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q011 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 05:45:27'),
(197, 9, 'dr_adam', 'Doctor', 'Update Queue Status', 'Queue #31 → Being-Served', '::1', '2026-05-26 05:47:05'),
(198, 0, 'dr_nadia', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-26 05:47:35'),
(199, 20, 'dr_nadia', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-26 05:47:46'),
(200, 20, 'dr_nadia', 'Doctor', 'Clock In', 'Available in Room 6', '::1', '2026-05-26 05:48:03'),
(201, 20, 'dr_nadia', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q012 (General Consultation)', '::1', '2026-05-26 05:48:27'),
(202, 20, 'dr_nadia', 'Doctor', 'Assign Doctor', 'Queue Q012 assigned to Dr. Nadia Rashidah in Room 6', '::1', '2026-05-26 05:48:37'),
(203, 20, 'dr_nadia', 'Doctor', 'Update Queue Status', 'Queue #32 → Being-Served', '::1', '2026-05-26 05:49:34'),
(204, 20, 'dr_nadia', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #7 assigned to Dr. Nadia Rashidah in Room 6', '::1', '2026-05-26 05:54:19'),
(205, 20, 'dr_nadia', 'Doctor', 'Update Queue Status', 'Queue #32 → Completed', '::1', '2026-05-26 05:54:49'),
(206, 20, 'dr_nadia', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #7 assigned to Dr. Nadia Rashidah in Room 6', '::1', '2026-05-26 05:55:09'),
(207, 20, 'dr_nadia', 'Doctor', 'Update Appointment', 'Appointment ID 7 set to Cancelled', '::1', '2026-05-26 05:55:27'),
(208, 9, 'dr_adam', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-26 05:57:27'),
(209, 9, 'dr_adam', 'Doctor', 'Clock In', 'Available in Room 1', '::1', '2026-05-26 05:58:18'),
(210, 9, 'dr_adam', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #8 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 05:58:27'),
(211, 9, 'dr_adam', 'Doctor', 'Update Queue Status', 'Queue #31 → Completed', '::1', '2026-05-26 05:58:40'),
(212, 9, 'dr_adam', 'Doctor', 'Update Appointment', 'Appointment ID 8 set to Confirmed', '::1', '2026-05-26 06:00:01'),
(213, 9, 'dr_adam', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #8 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 06:00:07'),
(214, 9, 'dr_adam', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #8 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 06:00:18'),
(215, 9, 'dr_adam', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #8 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 06:00:51'),
(216, 9, 'dr_adam', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #8 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 13:09:19'),
(217, 9, 'dr_adam', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-26 13:09:40'),
(218, 8, 'staff3', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-26 13:10:01'),
(219, 8, 'staff3', 'Staff', 'Assign Doctor to Appointment', 'Appointment #8 assigned to Dr. Razif Zulkifli in Room 3', '::1', '2026-05-26 13:10:17'),
(220, 9, 'dr_adam', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-26 13:10:34'),
(221, 9, 'dr_adam', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #8 assigned to Dr. Razif Zulkifli in Room 3', '::1', '2026-05-26 13:17:06'),
(222, 9, 'dr_adam', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #8 assigned to Dr. Razif Zulkifli in Room 3', '::1', '2026-05-26 13:17:07'),
(223, 9, 'dr_adam', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #8 assigned to Dr. Razif Zulkifli in Room 3', '::1', '2026-05-26 13:17:07'),
(224, 9, 'dr_adam', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #8 assigned to Dr. Razif Zulkifli in Room 3', '::1', '2026-05-26 13:17:07'),
(225, 9, 'dr_adam', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #8 assigned to Dr. Razif Zulkifli in Room 3', '::1', '2026-05-26 13:17:14'),
(226, 9, 'dr_adam', 'Doctor', 'Update Appointment', 'Appointment ID 8 set to Cancelled', '::1', '2026-05-26 13:23:51'),
(227, 9, 'dr_adam', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q013 (Vaccination)', '::1', '2026-05-26 13:27:27'),
(228, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q013 assigned to Dr. Razif Zulkifli in Room 3', '::1', '2026-05-26 13:27:35'),
(229, 9, 'dr_adam', 'Doctor', 'Clock In', 'Available in Room 1', '::1', '2026-05-26 13:27:56'),
(230, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q013 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 13:28:13'),
(231, 9, 'dr_adam', 'Doctor', 'Walk-In Queue', 'Walk-in: AF230026 → Q014 (Follow-up Check)', '::1', '2026-05-26 13:29:49'),
(232, 9, 'dr_adam', 'Doctor', 'Assign Doctor', 'Queue Q014 assigned to Dr. Razif Zulkifli in Room 3', '::1', '2026-05-26 13:30:01'),
(233, 9, 'dr_adam', 'Doctor', 'Update Queue Status', 'Queue #33 → Being-Served', '::1', '2026-05-26 13:30:17'),
(234, 9, 'dr_adam', 'Doctor', 'Update Queue Status', 'Queue #33 → Completed', '::1', '2026-05-26 13:42:24'),
(235, 11, 'dr_razif', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-26 13:42:37'),
(236, 11, 'dr_razif', 'Doctor', 'Update Queue Status', 'Queue #34 → Called', '::1', '2026-05-26 13:42:44'),
(237, 11, 'dr_razif', 'Doctor', 'Update Queue Status', 'Queue #34 → Being-Served', '::1', '2026-05-26 13:44:02'),
(238, 11, 'dr_razif', 'Doctor', 'Update Queue Status', 'Queue #34 → Completed', '::1', '2026-05-26 13:46:52'),
(239, 11, 'dr_razif', 'Doctor', 'Clock In', 'Available in Room 3', '::1', '2026-05-26 13:47:07'),
(240, 11, 'dr_razif', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q015 (Follow-up Check)', '::1', '2026-05-26 13:58:12'),
(241, 11, 'dr_razif', 'Doctor', 'Assign Doctor', 'Queue Q015 assigned to Dr. Razif Zulkifli in Room 3', '::1', '2026-05-26 13:58:29'),
(242, 11, 'dr_razif', 'Doctor', 'Update Queue Status', 'Queue #35 → Called', '::1', '2026-05-26 13:58:36'),
(243, 11, 'dr_razif', 'Doctor', 'Update Queue Status', 'Queue #35 → Being-Served', '::1', '2026-05-26 13:58:41'),
(244, 11, 'dr_razif', 'Doctor', 'Update Queue Status', 'Queue #35 → Completed', '::1', '2026-05-26 13:58:50'),
(245, 11, 'dr_razif', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #9 assigned to Dr. Adam Hafizi in Room 1', '::1', '2026-05-26 13:59:45'),
(246, 11, 'dr_razif', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #9 assigned to Dr. Razif Zulkifli in Room 3', '::1', '2026-05-26 13:59:49'),
(247, 11, 'dr_razif', 'Doctor', 'Update Appointment', 'Appointment ID 9 set to Confirmed', '::1', '2026-05-26 13:59:52'),
(248, 11, 'dr_razif', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #9 assigned to Dr. Razif Zulkifli in Room 3', '::1', '2026-05-26 13:59:56'),
(249, 11, 'dr_razif', 'Doctor', 'Update Appointment', 'Appointment ID 9 set to Completed', '::1', '2026-05-26 14:00:11'),
(250, 11, 'dr_razif', 'Doctor', 'Update Appointment', 'Appointment ID 10 set to Confirmed', '::1', '2026-05-26 14:00:47'),
(251, 11, 'dr_razif', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #10 assigned to Dr. Razif Zulkifli in Room 3', '::1', '2026-05-26 14:00:52'),
(252, 11, 'dr_razif', 'Doctor', 'Update Appointment', 'Appointment ID 10 set to Completed', '::1', '2026-05-26 14:01:02'),
(253, 0, 'admin', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-26 14:07:06'),
(254, 0, 'admin', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-26 14:07:08'),
(255, 0, 'admin', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-26 14:07:28'),
(256, 0, 'admin', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-26 14:07:29'),
(257, 0, 'admin', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-26 14:07:29'),
(258, 0, 'admin', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-26 14:08:24'),
(259, 0, 'admin', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-26 14:10:04'),
(260, 0, 'admin', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-26 14:10:06'),
(261, 0, 'admin', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-26 14:10:07'),
(262, 0, 'admin', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-26 14:10:07'),
(263, 0, 'admin', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-26 14:10:07'),
(264, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-26 14:10:17'),
(265, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-26 14:11:47'),
(266, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-05-26 14:22:28'),
(267, 9, 'dr_saiful', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-26 14:29:18'),
(268, 9, 'dr_saiful', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-26 14:33:03'),
(269, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-30 21:26:07'),
(270, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-30 21:27:03'),
(271, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-30 21:47:55'),
(272, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-31 13:52:53'),
(273, 0, 'dr_akmal', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-31 13:53:36'),
(274, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-31 13:53:41'),
(275, 11, 'dr_akmal', 'Doctor', 'Clock Out', 'Set unavailable', '::1', '2026-05-31 13:54:11'),
(276, 11, 'dr_akmal', 'Doctor', 'Clock In', 'Available in Room 3', '::1', '2026-05-31 13:54:21'),
(277, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-31 13:57:24'),
(278, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-05-31 13:57:42'),
(279, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-31 14:07:14'),
(280, 0, 'staff2', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-05-31 17:03:32'),
(281, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-05-31 17:03:35'),
(282, 0, 'staff2', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-06-02 01:10:48'),
(283, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-02 01:11:11'),
(284, 7, 'staff2', 'Staff', 'Register Patient', 'Registered: AD230039 (MUHAMMAD IRFAN HAZIQ BIN ISMADI) Type: student', '::1', '2026-06-02 01:39:54'),
(285, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-02 01:52:10'),
(286, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-02 01:52:45'),
(287, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-02 02:01:00'),
(288, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-02 03:43:01'),
(289, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-02 03:43:20'),
(290, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q001 [FSKTM Node 1]', '192.168.0.242', '2026-06-02 03:43:28'),
(291, 0, 'dr_hartini', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-06-02 03:43:56'),
(292, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-02 03:44:02'),
(293, 20, 'dr_hartini', 'Doctor', 'Clock In', 'Available in Room 6', '::1', '2026-06-02 03:44:09'),
(294, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q001 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-02 03:45:07'),
(295, 20, 'dr_hartini', 'Doctor', 'Set Queue Time', 'Queue Q001 set to 04:00 for AI230087 in Room 6', '::1', '2026-06-02 03:45:15'),
(296, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #36 → Called', '::1', '2026-06-02 03:45:40'),
(297, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #36 → Being-Served', '::1', '2026-06-02 03:45:44'),
(298, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #36 → Completed', '::1', '2026-06-02 03:45:47'),
(299, 20, 'dr_hartini', 'Doctor', 'Clock Out', 'Set unavailable', '::1', '2026-06-02 03:48:50'),
(300, 20, 'dr_hartini', 'Doctor', 'Clock In', 'Available in Room 6', '::1', '2026-06-02 03:51:34'),
(301, 20, 'dr_hartini', 'Doctor', 'Clock Out', 'Set unavailable', '::1', '2026-06-02 03:51:42'),
(302, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q001 [FSKTM Node 1]', '192.168.0.242', '2026-06-03 02:15:14'),
(303, 0, 'staff2', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-06-03 07:26:28'),
(304, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-03 07:26:33'),
(305, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-05 20:55:28'),
(306, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-05 21:30:13'),
(307, 1, 'admin', 'Admin', 'Toggle Staff Status', 'Set user ID 2 to Active', '::1', '2026-06-05 21:31:36'),
(308, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-05 22:33:59'),
(309, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-05 22:34:15'),
(310, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q001 [FSKTM Node 1]', '192.168.0.242', '2026-06-05 22:41:20'),
(311, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #38 → Cancelled', '::1', '2026-06-05 22:41:33'),
(312, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-05 22:43:20'),
(313, 0, 'staff2', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-06-05 22:51:38'),
(314, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-05 22:51:43'),
(315, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-05 23:03:56'),
(316, 7, 'staff2', 'Staff', 'Walk-In Queue', 'Walk-in: AI230087 → Q002 (General Consultation)', '::1', '2026-06-05 23:04:52'),
(317, 0, 'dr_hartini', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-06-05 23:05:22'),
(318, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-05 23:05:28'),
(319, 20, 'dr_hartini', 'Doctor', 'Clock In', 'Available in Room 6', '::1', '2026-06-05 23:05:35'),
(320, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q002 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-05 23:05:48'),
(321, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #39 → Called', '::1', '2026-06-05 23:06:24'),
(322, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #39 → Being-Served', '::1', '2026-06-05 23:06:39'),
(323, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #39 → Completed', '::1', '2026-06-05 23:08:28'),
(324, 20, 'dr_hartini', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q003 (Follow-up Check)', '::1', '2026-06-05 23:08:41'),
(325, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q003 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-05 23:08:53'),
(326, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #40 → Called', '::1', '2026-06-05 23:09:06'),
(327, 20, 'dr_hartini', 'Doctor', 'Walk-In Queue', 'Walk-in: AF230026 → Q004 (General Consultation)', '::1', '2026-06-05 23:16:34'),
(328, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q004 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-05 23:16:45'),
(329, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q004 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-05 23:16:53'),
(330, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #41 → Cancelled', '::1', '2026-06-05 23:17:08'),
(331, 20, 'dr_hartini', 'Doctor', 'Walk-In Queue', 'Walk-in: AF230026 → Q005 (Follow-up Check)', '::1', '2026-06-05 23:17:29'),
(332, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q005 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-05 23:19:17'),
(333, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #42 → Called', '::1', '2026-06-05 23:19:28'),
(334, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #42 → Being-Served', '::1', '2026-06-05 23:19:38'),
(335, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #42 → Completed', '::1', '2026-06-05 23:24:14'),
(336, 20, 'dr_hartini', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q006 (Follow-up Check)', '::1', '2026-06-05 23:24:35'),
(337, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q006 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-05 23:24:40'),
(338, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q006 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-05 23:24:48'),
(339, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #43 → Called', '::1', '2026-06-05 23:24:52'),
(340, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #43 → Being-Served', '::1', '2026-06-05 23:25:05'),
(341, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #43 → Completed', '::1', '2026-06-05 23:31:43'),
(342, 20, 'dr_hartini', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q007 (General Consultation)', '::1', '2026-06-05 23:31:55'),
(343, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q007 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-05 23:32:05'),
(344, 20, 'dr_hartini', 'Doctor', 'Walk-In Queue', 'Walk-in: AF230026 → Q008 (Follow-up Check)', '::1', '2026-06-05 23:32:36'),
(345, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q008 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-05 23:32:44'),
(346, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q007 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-05 23:32:51'),
(347, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #44 → Called', '::1', '2026-06-05 23:32:59'),
(348, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #44 → Cancelled', '::1', '2026-06-05 23:36:34'),
(349, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #45 → Cancelled', '::1', '2026-06-05 23:36:36'),
(350, 20, 'dr_hartini', 'Doctor', 'Walk-In Queue', 'Walk-in: AD230039 → Q009 (Follow-up Check)', '::1', '2026-06-05 23:37:10'),
(351, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q009 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-05 23:37:17'),
(352, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #46 → Called', '::1', '2026-06-05 23:38:32'),
(353, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #46 → Being-Served', '::1', '2026-06-05 23:42:29'),
(354, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #46 → Completed', '::1', '2026-06-05 23:42:41'),
(355, 20, 'dr_hartini', 'Doctor', 'Walk-In Queue', 'Walk-in: AD230039 → Q010 (Follow-up Check)', '::1', '2026-06-05 23:43:57'),
(356, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q010 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-05 23:44:04'),
(357, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #47 → Called', '::1', '2026-06-05 23:44:14'),
(358, 9, 'dr_saiful', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-05 23:53:04'),
(359, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-05 23:54:14'),
(360, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-06 04:27:02'),
(361, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-06 04:27:59'),
(362, 11, 'dr_akmal', 'Doctor', 'Clock In', 'Available in Room 3', '::1', '2026-06-06 04:39:00'),
(363, 11, 'dr_akmal', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q001 (Follow-up Check)', '::1', '2026-06-06 04:39:18'),
(364, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q001 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-06 04:39:25'),
(365, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q001 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-06 04:39:27'),
(366, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #48 → Called', '::1', '2026-06-06 04:39:32'),
(367, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #48 → Being-Served', '::1', '2026-06-06 04:39:48'),
(368, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #48 → Completed', '::1', '2026-06-06 04:42:51'),
(369, 11, 'dr_akmal', 'Doctor', 'Walk-In Queue', 'Walk-in: AF230026 → Q002 (General Consultation)', '::1', '2026-06-06 04:43:06'),
(370, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q002 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-06 04:43:15'),
(371, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #49 → Called', '::1', '2026-06-06 04:45:29'),
(372, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #49 → Completed', '::1', '2026-06-06 04:47:28'),
(373, 11, 'dr_akmal', 'Doctor', 'Walk-In Queue', 'Walk-in: AD230039 → Q003 (Vaccination)', '::1', '2026-06-06 04:47:53'),
(374, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q003 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-06 04:48:02'),
(375, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-06 04:50:10'),
(376, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-06 04:53:04'),
(377, 9, 'dr_saiful', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-06 04:53:22'),
(378, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-06 04:53:46'),
(379, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-06 04:55:57'),
(380, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-06 05:00:49'),
(381, 11, 'dr_akmal', 'Doctor', 'Clock In', 'Available in Room 3', '::1', '2026-06-06 05:01:42'),
(382, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-06 05:02:18'),
(383, 0, 'dr_akmal', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-06-06 05:03:04'),
(384, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-06 05:03:23'),
(385, 7, 'staff2', 'Staff', 'Update Queue Status', 'Queue #50 → Cancelled', '::1', '2026-06-06 05:03:54'),
(386, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-06 05:09:42'),
(387, 7, 'staff2', 'Staff', 'Walk-In Queue', 'Walk-in: CE190567 → Q004 (General Consultation)', '::1', '2026-06-06 05:12:46'),
(388, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-06 05:13:17'),
(389, 11, 'dr_akmal', 'Doctor', 'Clock In', 'Available in Room 3', '::1', '2026-06-06 05:13:36'),
(390, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q004 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-06 05:13:59'),
(391, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #51 → Called', '::1', '2026-06-06 05:14:22'),
(392, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #51 → Completed', '::1', '2026-06-06 05:14:47'),
(393, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-06 05:15:01'),
(394, 0, 'dr_akmal', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-06-06 05:18:42'),
(395, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-06 05:18:49'),
(396, 11, 'dr_akmal', 'Doctor', 'Clock In', 'Available in Room 3', '::1', '2026-06-06 05:19:26'),
(397, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q005 [FSKTM Node 1]', '192.168.0.242', '2026-06-06 05:25:06'),
(398, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q005 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-06 05:25:26'),
(399, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #52 → Called', '::1', '2026-06-06 05:25:38'),
(400, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #52 → Completed', '::1', '2026-06-06 05:25:50'),
(401, 11, 'dr_akmal', 'Doctor', 'Clock Out', 'Set unavailable', '::1', '2026-06-06 13:29:04'),
(402, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-06 13:41:55'),
(403, 7, 'staff2', 'Staff', 'Walk-In Queue', 'Walk-in: BF220101 → Q006 (General Consultation)', '::1', '2026-06-06 13:42:20'),
(404, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-06 13:42:32'),
(405, 11, 'dr_akmal', 'Doctor', 'Clock In', 'Available in Room 3', '::1', '2026-06-06 13:42:39'),
(406, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q006 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-06 13:42:47'),
(407, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #53 → Called', '::1', '2026-06-06 13:43:53'),
(408, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #53 → Not-Arrived', '::1', '2026-06-06 13:48:54'),
(409, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #53 → Not-Arrived', '::1', '2026-06-06 13:48:55'),
(410, 11, 'dr_akmal', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q007 (Vaccination)', '::1', '2026-06-06 13:52:27'),
(411, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q007 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-06 13:52:32'),
(412, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #54 → Called', '::1', '2026-06-06 13:52:38'),
(413, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #54 → Completed', '::1', '2026-06-06 13:58:35'),
(414, 11, 'dr_akmal', 'Doctor', 'Walk-In Queue', 'Walk-in: BF220101 → Q008 (Prescription Refill)', '::1', '2026-06-06 13:59:03'),
(415, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q008 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-06 13:59:08'),
(416, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #55 → Called', '::1', '2026-06-06 13:59:16'),
(417, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #55 → Cancelled', '::1', '2026-06-06 14:04:16'),
(418, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q009 [FSKTM Node 1]', '192.168.0.242', '2026-06-06 15:29:24'),
(419, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q009 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-06 15:29:42'),
(420, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-06 15:30:42'),
(421, 7, 'staff2', 'Staff', 'Walk-In Queue', 'Walk-in: AF230026 → Q010 (General Consultation)', '::1', '2026-06-06 15:33:33'),
(422, 7, 'staff2', 'Staff', 'Update Appointment', 'Appointment ID 11 set to Cancelled', '::1', '2026-06-06 15:34:27'),
(423, 7, 'staff2', 'Staff', 'Assign Doctor', 'Queue Q010 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-06 15:37:23'),
(424, 7, 'staff2', 'Staff', 'Register Patient', 'Registered: AD230087 (MUHAMMAD IRFAN) Type: student', '::1', '2026-06-06 15:42:00'),
(425, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-06 15:42:36'),
(426, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #56 → Called', '::1', '2026-06-06 15:42:52'),
(427, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #56 → Being-Served', '::1', '2026-06-06 15:42:55'),
(428, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #57 → Called', '::1', '2026-06-06 15:42:57'),
(429, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #57 → Being-Served', '::1', '2026-06-06 15:43:00'),
(430, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #56 → Completed', '::1', '2026-06-06 15:43:03'),
(431, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #57 → Completed', '::1', '2026-06-06 15:43:06'),
(432, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q011 [FSKTM Node 1]', '192.168.0.242', '2026-06-06 15:43:11'),
(433, 11, 'dr_akmal', 'Doctor', 'Clock In', 'Available in Room 3', '::1', '2026-06-06 15:43:42'),
(434, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q011 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-06 15:44:44');
INSERT INTO `system_logs` (`id`, `user_id`, `username`, `role`, `action`, `details`, `ip_address`, `created_at`) VALUES
(435, 11, 'dr_akmal', 'Doctor', 'Set Queue Time', 'Queue Q011 set to 16:00 for AI230087 in Room 3', '::1', '2026-06-06 15:44:49'),
(436, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #58 → Cancelled', '::1', '2026-06-06 15:47:09'),
(437, 11, 'dr_akmal', 'Doctor', 'Walk-In Queue', 'Walk-in: AD230087 → Q012 (Follow-up Check)', '::1', '2026-06-06 15:47:20'),
(438, 11, 'dr_akmal', 'Doctor', 'Set Queue Time', 'Queue Q012 set to 16:00 for AD230087', '::1', '2026-06-06 15:47:25'),
(439, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-06 15:56:53'),
(440, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 04:40:53'),
(441, 7, 'staff2', 'Staff', 'Password Reset Request', 'Reset email sent to ai230087@student.uthm.edu.my', '::1', '2026-06-09 04:49:27'),
(442, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:14:24'),
(443, 20, 'dr_hartini', 'Doctor', 'Clock In', 'Available in Room 6', '::1', '2026-06-09 05:14:55'),
(444, 20, 'dr_hartini', 'Doctor', 'Clock Out', 'Set unavailable', '::1', '2026-06-09 05:15:12'),
(445, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:15:23'),
(446, 11, 'dr_akmal', 'Doctor', 'Clock Out', 'Set unavailable', '::1', '2026-06-09 05:15:27'),
(447, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:22:03'),
(448, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:22:55'),
(449, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:23:21'),
(450, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:25:19'),
(451, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:25:33'),
(452, 20, 'dr_hartini', 'Doctor', 'Clock In', 'Available in Room 6', '::1', '2026-06-09 05:25:43'),
(453, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:25:54'),
(454, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:31:03'),
(455, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:31:30'),
(456, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:31:48'),
(457, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:33:47'),
(458, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:38:57'),
(459, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:39:11'),
(460, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:44:38'),
(461, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:44:59'),
(462, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:45:34'),
(463, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 05:50:09'),
(464, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 06:07:07'),
(465, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 06:11:06'),
(466, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 06:11:38'),
(467, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 06:12:47'),
(468, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 06:13:14'),
(469, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 06:14:43'),
(470, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 06:15:14'),
(471, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 06:18:07'),
(472, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-09 06:18:21'),
(473, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 06:22:36'),
(474, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-09 06:23:30'),
(475, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 06:25:43'),
(476, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q001 [FSKTM Node 1]', '192.168.0.242', '2026-06-09 06:27:13'),
(477, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #60 → Cancelled', '::1', '2026-06-09 06:27:23'),
(478, 0, 'AI230087', 'Student', 'RFID Check-in', 'RFID: 3941EF50 → AI230087 (NIK MUHAMMAD FAIEZ BIN NIK SHAHRUL NIZAM) → Q002 [FSKTM Node 1]', '172.20.10.3', '2026-06-09 06:41:05'),
(479, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 08:48:18'),
(480, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 08:48:48'),
(481, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-09 08:49:04'),
(482, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 08:49:15'),
(483, 20, 'dr_hartini', 'Doctor', 'Clock In', 'Available in Room 6', '::1', '2026-06-09 08:55:23'),
(484, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q002 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-09 08:55:29'),
(485, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #61 → Called', '::1', '2026-06-09 08:55:35'),
(486, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-09 08:58:16'),
(487, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 08:58:47'),
(488, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #61 → Cancelled', '::1', '2026-06-09 09:00:35'),
(489, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 09:01:05'),
(490, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 09:01:13'),
(491, 20, 'dr_hartini', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q003 (General Consultation)', '::1', '2026-06-09 09:18:33'),
(492, 20, 'dr_hartini', 'Doctor', 'Clock In', 'Available in Room 6', '::1', '2026-06-09 09:18:47'),
(493, 20, 'dr_hartini', 'Doctor', 'Set Queue Time', 'Queue Q003 set to 10:00 for AI230087', '::1', '2026-06-09 09:18:56'),
(494, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-09 09:19:49'),
(495, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 09:21:38'),
(496, 20, 'dr_hartini', 'Doctor', 'Clock In', 'Available in Room 6', '::1', '2026-06-09 09:21:44'),
(497, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q003 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-09 09:22:11'),
(498, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #62 → Called', '::1', '2026-06-09 09:22:38'),
(499, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #62 → Being-Served', '::1', '2026-06-09 09:23:06'),
(500, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #62 → Completed', '::1', '2026-06-09 09:24:54'),
(501, 20, 'dr_hartini', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q004 (Vaccination)', '::1', '2026-06-09 09:32:44'),
(502, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q004 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-09 09:32:56'),
(503, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #63 → Called', '::1', '2026-06-09 09:33:07'),
(504, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #63 → Being-Served', '::1', '2026-06-09 09:33:33'),
(505, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 10:10:50'),
(506, 7, 'staff2', 'Staff', 'Update Queue Status', 'Queue #63 → Completed', '::1', '2026-06-09 10:11:53'),
(507, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 10:12:18'),
(508, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 10:17:19'),
(509, 7, 'staff2', 'Staff', 'Update Appointment', 'Appointment ID 12 set to Confirmed', '::1', '2026-06-09 10:21:16'),
(510, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 10:22:01'),
(511, 20, 'dr_hartini', 'Doctor', 'Clock In', 'Available in Room 6', '::1', '2026-06-09 10:22:12'),
(512, 20, 'dr_hartini', 'Doctor', 'Assign Doctor to Appointment', 'Appointment #12 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-09 10:22:21'),
(513, 20, 'dr_hartini', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q005 (General Consultation)', '::1', '2026-06-09 10:27:50'),
(514, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q005 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-09 10:28:06'),
(515, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #64 → Called', '::1', '2026-06-09 10:28:17'),
(516, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #64 → Completed', '::1', '2026-06-09 10:31:54'),
(517, 20, 'dr_hartini', 'Doctor', 'Walk-In Queue', 'Walk-in: AI230087 → Q006 (General Consultation)', '::1', '2026-06-09 10:32:18'),
(518, 20, 'dr_hartini', 'Doctor', 'Assign Doctor', 'Queue Q006 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-09 10:32:23'),
(519, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 10:32:36'),
(520, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-09 10:33:02'),
(521, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 10:33:16'),
(522, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 10:33:29'),
(523, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 10:35:02'),
(524, 20, 'dr_hartini', 'Doctor', 'Clock In', 'Available in Room 6', '::1', '2026-06-09 10:35:07'),
(525, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #65 → Called', '::1', '2026-06-09 10:35:18'),
(526, 0, 'admin', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-06-09 10:39:41'),
(527, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-09 10:39:46'),
(528, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #65 → Not-Arrived', '::1', '2026-06-09 10:40:18'),
(529, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 10:42:03'),
(530, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 10:52:30'),
(531, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 10:53:47'),
(532, 11, 'dr_akmal', 'Doctor', 'Logout', 'User logged out', '::1', '2026-06-09 11:06:10'),
(533, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:36:40'),
(534, 20, 'dr_hartini', 'Doctor', 'Register Patient', 'Registered: GI230004 (ALI ABDULLAH) Type: student RFID: 41154DB8', '::1', '2026-06-09 11:38:58'),
(535, 0, 'staff2', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-06-09 11:39:25'),
(536, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:39:29'),
(537, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:40:36'),
(538, 0, 'GI230004', 'Student', 'RFID Check-in', 'RFID: 41154DB8 → GI230004 (ALI ABDULLAH) → Q007 [FSKTM Node 1]', '172.20.10.3', '2026-06-09 11:40:42'),
(539, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:41:00'),
(540, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:41:23'),
(541, 8, 'staff3', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:41:48'),
(542, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:42:11'),
(543, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:43:15'),
(544, 0, 'staff2', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-06-09 11:44:29'),
(545, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:44:30'),
(546, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:45:37'),
(547, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:46:15'),
(548, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:46:39'),
(549, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:50:11'),
(550, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:50:25'),
(551, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:50:37'),
(552, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:52:06'),
(553, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 11:58:06'),
(554, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-09 12:06:11'),
(555, 20, 'dr_hartini', 'Doctor', 'Set Queue Time', 'Queue Q007 set to 13:00 for GI230004', '::1', '2026-06-09 12:06:31'),
(556, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-09 14:01:51'),
(557, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-10 15:13:03'),
(558, 0, 'staff2', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-06-11 03:13:48'),
(559, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-11 03:13:53'),
(560, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-11 03:15:20'),
(561, 0, 'staff2', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-06-11 04:40:18'),
(562, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-11 04:40:25'),
(563, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-12 18:23:15'),
(564, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-12 18:26:06'),
(565, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-12 18:26:35'),
(566, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-14 10:55:05'),
(567, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-14 10:55:20'),
(568, 20, 'dr_hartini', 'Doctor', 'Clock Out', 'Set unavailable', '::1', '2026-06-14 10:55:25'),
(569, 1, 'admin', 'Admin', 'Login', 'User logged in successfully', '::1', '2026-06-14 10:55:52'),
(570, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-14 11:44:26'),
(571, 7, 'staff2', 'Staff', 'Walk-In Queue', 'Walk-in: GI230004 → Q001 (General Consultation)', '::1', '2026-06-14 11:50:06'),
(572, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-14 11:50:21'),
(573, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-14 11:50:37'),
(574, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-14 11:53:45'),
(575, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-14 11:54:12'),
(576, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-14 11:57:27'),
(577, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-14 12:00:21'),
(578, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-14 12:00:35'),
(579, 0, 'dr_akmal', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-06-14 12:00:45'),
(580, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-14 12:00:49'),
(581, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-14 12:01:44'),
(582, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-14 12:01:57'),
(583, 0, 'dr_hartini', 'Unknown', 'Failed Login', 'Incorrect password attempt', '::1', '2026-06-14 12:02:07'),
(584, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-14 12:02:12'),
(585, 7, 'staff2', 'Staff', 'Login', 'User logged in successfully', '::1', '2026-06-14 12:03:39'),
(586, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-14 12:10:38'),
(587, 11, 'dr_akmal', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-14 12:11:14'),
(588, 11, 'dr_akmal', 'Doctor', 'Clock In', 'Available in Room 3', '::1', '2026-06-14 12:11:27'),
(589, 11, 'dr_akmal', 'Doctor', 'Clock In', 'Available in Room 3', '::1', '2026-06-14 12:11:30'),
(590, 20, 'dr_hartini', 'Doctor', 'Login', 'User logged in successfully', '::1', '2026-06-14 12:16:31'),
(591, 20, 'dr_hartini', 'Doctor', 'Clock In', 'Available in Room 6', '::1', '2026-06-14 12:16:40'),
(592, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q001 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-14 12:17:16'),
(593, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #67 → Called', '::1', '2026-06-14 12:17:24'),
(594, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #67 → Being-Served', '::1', '2026-06-14 12:19:08'),
(595, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #67 → Completed', '::1', '2026-06-14 12:19:14'),
(596, 11, 'dr_akmal', 'Doctor', 'Walk-In Queue', 'Walk-in: AF230026 → Q002 (Vaccination)', '::1', '2026-06-14 12:19:41'),
(597, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q002 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-14 12:20:18'),
(598, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q002 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-14 12:20:19'),
(599, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #68 → Called', '::1', '2026-06-14 12:20:38'),
(600, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #68 → Being-Served', '::1', '2026-06-14 12:21:15'),
(601, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #68 → Completed', '::1', '2026-06-14 12:21:18'),
(602, 11, 'dr_akmal', 'Doctor', 'Walk-In Queue', 'Walk-in: BF220101 → Q003 (General Consultation)', '::1', '2026-06-14 12:38:17'),
(603, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q003 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-14 12:38:41'),
(604, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #69 → Called', '::1', '2026-06-14 12:39:46'),
(605, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #69 → Being-Served', '::1', '2026-06-14 12:41:05'),
(606, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #69 → Completed', '::1', '2026-06-14 12:41:33'),
(607, 11, 'dr_akmal', 'Doctor', 'Walk-In Queue', 'Walk-in: BF220101 → Q004 (General Consultation)', '::1', '2026-06-14 12:42:02'),
(608, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q004 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-14 12:42:09'),
(609, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #70 → Called', '::1', '2026-06-14 12:42:28'),
(610, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #70 → Cancelled', '::1', '2026-06-14 12:47:28'),
(611, 11, 'dr_akmal', 'Doctor', 'Walk-In Queue', 'Walk-in: BF220101 → Q005 (Vaccination)', '::1', '2026-06-14 12:52:50'),
(612, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q005 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-14 12:53:12'),
(613, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #71 → Called', '::1', '2026-06-14 12:54:53'),
(614, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #71 → Cancelled', '::1', '2026-06-14 12:59:53'),
(615, 11, 'dr_akmal', 'Doctor', 'Register Patient', 'Registered: PI230087 (DR NAYEF ABDULWAHAB MOHAMMED ALDUAIS) Type: staff', '::1', '2026-06-14 13:48:22'),
(616, 11, 'dr_akmal', 'Doctor', 'Walk-In Queue', 'Walk-in: PI230087 → Q006 (General Consultation)', '::1', '2026-06-14 13:48:47'),
(617, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q006 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-14 13:49:01'),
(618, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #72 → Called', '::1', '2026-06-14 13:50:50'),
(619, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #72 → Being-Served', '::1', '2026-06-14 13:51:58'),
(620, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #72 → Completed', '::1', '2026-06-14 13:54:55'),
(621, 11, 'dr_akmal', 'Doctor', 'Walk-In Queue', 'Walk-in: AD230087 → Q007 (Prescription Refill)', '::1', '2026-06-14 14:56:11'),
(622, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q007 assigned to Dr. HARTINI BINTI SHAFII in Room 6', '::1', '2026-06-14 14:56:29'),
(623, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #73 → Called', '::1', '2026-06-14 14:56:39'),
(624, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #73 → Being-Served', '::1', '2026-06-14 14:57:16'),
(625, 20, 'dr_hartini', 'Doctor', 'Update Queue Status', 'Queue #73 → Completed', '::1', '2026-06-14 14:57:51'),
(626, 11, 'dr_akmal', 'Doctor', 'Walk-In Queue', 'Walk-in: AD230087 → Q008 (Vaccination)', '::1', '2026-06-14 14:58:07'),
(627, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q008 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-14 14:58:12'),
(628, 11, 'dr_akmal', 'Doctor', 'Assign Doctor', 'Queue Q008 assigned to DR. AKMAL ARIF BIN NORDIAN @ NORDIN in Room 3', '::1', '2026-06-14 14:58:13'),
(629, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #74 → Called', '::1', '2026-06-14 14:58:33'),
(630, 11, 'dr_akmal', 'Doctor', 'Update Queue Status', 'Queue #74 → Cancelled', '::1', '2026-06-14 15:03:33');

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
  `clocked_in_at` datetime DEFAULT NULL,
  `last_active` datetime DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `role`, `status`, `created_at`, `updated_at`, `email`, `reset_token`, `reset_token_expires`, `room`, `is_available`, `clocked_in_at`, `last_active`, `profile_pic`) VALUES
(1, 'admin', '$2y$10$YourHashHere', 'DR\' ARBA\'AH BINTI SALIM ', 'Admin', 'Active', '2026-05-04 20:18:28', '2026-06-14 10:55:52', 'admin@uthm.edu.my', NULL, NULL, NULL, 0, NULL, NULL, NULL),
(2, 'staff1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Staff One', 'Staff', 'Active', '2026-05-04 20:18:28', '2026-06-05 21:31:36', 'staff1@uthm.edu.my', NULL, NULL, NULL, 0, NULL, NULL, NULL),
(7, 'staff2', '$2y$10$JK/iyOwVwf11qowWcEIMI.nveJrparacYeLGGIHrpJ.UdokLwMM5u', 'ENCIK ZUL ARIF BIN ZAIDIN', 'Staff', 'Active', '0000-00-00 00:00:00', '2026-06-14 12:03:39', 'ai230087@student.uthm.edu.my', '3f2099312ca0d76ce7edaa6a8fa90fbc71a8ff202fef911ccab0a079bf8ca532', '2026-06-09 05:49:21', NULL, 0, NULL, NULL, NULL),
(8, 'staff3', '$2y$10$cpVKNCdvYKpp.jkW0boVbO6a9Mghjk.PpNARYKJ2nJSPufQcNyVC.', 'PUAN MUNIR', 'Staff', 'Active', '2026-05-06 23:13:44', '2026-06-09 11:41:48', 'puanmunir@uthm.edu.my', NULL, NULL, NULL, 0, NULL, NULL, NULL),
(9, 'dr_saiful', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'DR. AHMAD SAIFUL BIN RAZALI', 'Doctor', 'Active', '2026-05-24 15:25:16', '2026-06-06 04:53:31', NULL, NULL, NULL, 'Room 1', 0, NULL, '2026-06-06 04:53:31', 'dr.saiful.jpg'),
(10, 'dr_faridah', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. FARIDAH BINTI ATAN', 'Doctor', 'Active', '2026-05-24 15:25:16', '2026-05-26 14:18:19', NULL, NULL, NULL, 'Room 2', 0, NULL, NULL, NULL),
(11, 'dr_akmal', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'DR. AKMAL ARIF BIN NORDIAN @ NORDIN', 'Doctor', 'Active', '2026-05-24 15:25:16', '2026-06-14 15:42:13', NULL, NULL, NULL, 'Room 3', 1, NULL, '2026-06-14 15:42:13', NULL),
(18, 'dr_farisha', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. FARISHA IZZATI BINTI JAIDIN', 'Doctor', 'Active', '2026-05-24 15:41:10', '2026-05-26 14:18:46', NULL, NULL, NULL, 'Room 4', 0, NULL, NULL, NULL),
(19, 'dr_jamaludin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. JAMALUDIN BIN MOHD ELMI', 'Doctor', 'Active', '2026-05-24 15:41:10', '2026-05-26 14:20:19', NULL, NULL, NULL, 'Room 5', 0, NULL, NULL, NULL),
(20, 'dr_hartini', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. HARTINI BINTI SHAFII', 'Doctor', 'Active', '2026-05-24 15:41:10', '2026-06-14 15:42:15', NULL, NULL, NULL, 'Room 6', 1, NULL, '2026-06-14 15:42:15', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `rfid_tags`
--
ALTER TABLE `rfid_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=631;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
