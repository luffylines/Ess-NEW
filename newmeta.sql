-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2026 at 02:46 PM
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
-- Database: `newmeta`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action_type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action_type`, `description`, `ip_address`, `user_agent`, `properties`, `created_at`, `updated_at`) VALUES
(1, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 14:05:01', '2025-12-01 14:05:01'),
(2, 4, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 14:11:59', '2025-12-01 14:11:59'),
(3, 1, 'profile_completed', 'Completed profile setup and activated account', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"employee_id\":\"admin01\",\"completion_timestamp\":\"2025-12-01T14:16:52.043592Z\"}', '2025-12-01 14:16:52', '2025-12-01 14:16:52'),
(4, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2025-12-01 14:23:08', '2025-12-01 14:23:08'),
(5, 1, 'attendance_time_in', 'Marked time_in at 11:09 PM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"attendance_id\":1,\"date\":\"2025-12-01\",\"timestamp\":\"2025-12-01T15:09:07.405610Z\"}', '2025-12-01 15:09:07', '2025-12-01 15:09:07'),
(6, 1, 'attendance_break_in', 'Marked break_in at 11:09 PM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"attendance_id\":1,\"date\":\"2025-12-01\",\"timestamp\":\"2025-12-01T15:09:19.500326Z\"}', '2025-12-01 15:09:19', '2025-12-01 15:09:19'),
(7, 1, 'attendance_break_out', 'Marked break_out at 11:10 PM', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '{\"attendance_id\":1,\"date\":\"2025-12-01\",\"timestamp\":\"2025-12-01T15:10:04.387469Z\"}', '2025-12-01 15:10:04', '2025-12-01 15:10:04'),
(8, 4, 'logout', 'User logged out', '209.35.167.236', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 15:19:41', '2025-12-01 15:19:41'),
(9, 4, 'login', 'User logged in', '209.35.167.236', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 15:20:04', '2025-12-01 15:20:04'),
(10, 1, 'attendance_time_out', 'Marked time_out at 11:21 PM', '209.35.167.236', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '{\"attendance_id\":1,\"date\":\"2025-12-01\",\"timestamp\":\"2025-12-01T15:21:29.132515Z\"}', '2025-12-01 15:21:29', '2025-12-01 15:21:29'),
(11, 1, 'attendance_time_in', 'Marked time_in at 11:23 PM', '209.35.167.236', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"attendance_id\":2,\"date\":\"2025-12-01\",\"timestamp\":\"2025-12-01T15:23:16.088613Z\"}', '2025-12-01 15:23:16', '2025-12-01 15:23:16'),
(12, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 15:25:11', '2025-12-01 15:25:11'),
(13, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 15:25:49', '2025-12-01 15:25:49'),
(14, 1, 'attendance_break_in', 'Marked break_in at 11:26 PM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"attendance_id\":2,\"date\":\"2025-12-01\",\"timestamp\":\"2025-12-01T15:26:02.508827Z\"}', '2025-12-01 15:26:02', '2025-12-01 15:26:02'),
(15, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 15:37:39', '2025-12-01 15:37:39'),
(16, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 15:38:42', '2025-12-01 15:38:42'),
(17, 1, 'google_login', 'User signed in with Google', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Version/18.6.2 Safari/605.1.15', '{\"google_email\":\"christianaring6@gmail.com\",\"google_name\":\"Christianaring\",\"google_id\":\"117961855686338940761\",\"login_method\":\"google_oauth\"}', '2025-12-01 15:43:02', '2025-12-01 15:43:02'),
(18, 1, 'attendance_break_out', 'Marked break_out at 11:43 PM', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Version/18.6.2 Safari/605.1.15', '{\"attendance_id\":2,\"date\":\"2025-12-01\",\"timestamp\":\"2025-12-01T15:43:25.217185Z\"}', '2025-12-01 15:43:25', '2025-12-01 15:43:25'),
(19, 1, 'attendance_time_out', 'Marked time_out at 11:49 PM', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Version/18.6.2 Safari/605.1.15', '{\"attendance_id\":2,\"date\":\"2025-12-01\",\"timestamp\":\"2025-12-01T15:49:23.155625Z\"}', '2025-12-01 15:49:23', '2025-12-01 15:49:23'),
(20, 1, 'attendance_time_in', 'Marked time_in at 12:02 AM', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Version/18.6.2 Safari/605.1.15', '{\"attendance_id\":3,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T16:02:54.423738Z\"}', '2025-12-01 16:02:54', '2025-12-01 16:02:54'),
(21, 1, 'attendance_break_in', 'Marked break_in at 12:03 AM', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Version/18.6.2 Safari/605.1.15', '{\"attendance_id\":3,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T16:03:54.399939Z\"}', '2025-12-01 16:03:54', '2025-12-01 16:03:54'),
(22, 1, 'attendance_break_out', 'Marked break_out at 12:05 AM', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Version/18.6.2 Safari/605.1.15', '{\"attendance_id\":3,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T16:05:17.679740Z\"}', '2025-12-01 16:05:17', '2025-12-01 16:05:17'),
(23, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Version/18.6.2 Safari/605.1.15', '[]', '2025-12-01 16:05:31', '2025-12-01 16:05:31'),
(24, 1, 'google_login', 'User signed in with Google', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Version/18.6.2 Safari/605.1.15', '{\"google_email\":\"christianaring6@gmail.com\",\"google_name\":\"Christianaring\",\"google_id\":\"117961855686338940761\",\"login_method\":\"google_oauth\"}', '2025-12-01 16:05:42', '2025-12-01 16:05:42'),
(25, 1, 'attendance_time_out', 'Marked time_out at 12:05 AM', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Version/18.6.2 Safari/605.1.15', '{\"attendance_id\":3,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T16:05:51.996944Z\"}', '2025-12-01 16:05:51', '2025-12-01 16:05:51'),
(26, 1, 'profile_completed', 'Completed profile setup and activated account', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"employee_id\":\"emp01\",\"completion_timestamp\":\"2025-12-01T16:10:52.719368Z\"}', '2025-12-01 16:10:52', '2025-12-01 16:10:52'),
(27, NULL, 'profile_completed', 'Completed profile setup and activated account', '136.158.37.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Mobile/15E148 Safari/604.1', '{\"employee_id\":\"hr01\",\"completion_timestamp\":\"2025-12-01T16:29:06.861687Z\"}', '2025-12-01 16:29:06', '2025-12-01 16:29:06'),
(28, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Mobile/15E148 Safari/604.1', '[]', '2025-12-01 16:30:19', '2025-12-01 16:30:19'),
(29, 1, 'attendance_time_in', 'Marked time_in at 12:34 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"attendance_id\":4,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T16:34:39.819260Z\"}', '2025-12-01 16:34:39', '2025-12-01 16:34:39'),
(30, 1, 'attendance_break_in', 'Marked break_in at 12:43 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"attendance_id\":4,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T16:43:44.230030Z\"}', '2025-12-01 16:43:44', '2025-12-01 16:43:44'),
(31, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 16:43:55', '2025-12-01 16:43:55'),
(32, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 16:44:32', '2025-12-01 16:44:32'),
(33, 1, 'attendance_break_out', 'Marked break_out at 12:52 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"attendance_id\":4,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T16:52:16.449904Z\"}', '2025-12-01 16:52:16', '2025-12-01 16:52:16'),
(34, 1, 'attendance_time_out', 'Marked time_out at 12:55 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"attendance_id\":4,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T16:55:04.590439Z\"}', '2025-12-01 16:55:04', '2025-12-01 16:55:04'),
(35, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 17:22:14', '2025-12-01 17:22:14'),
(36, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', '[]', '2025-12-01 17:23:47', '2025-12-01 17:23:47'),
(37, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', '[]', '2025-12-01 17:23:56', '2025-12-01 17:23:56'),
(38, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2025-12-01 17:28:01', '2025-12-01 17:28:01'),
(39, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2025-12-01 17:28:11', '2025-12-01 17:28:11'),
(40, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2025-12-01 17:28:29', '2025-12-01 17:28:29'),
(41, 1, 'attendance_time_in', 'Marked time_in at 01:28 AM', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '{\"attendance_id\":5,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T17:28:36.926844Z\"}', '2025-12-01 17:28:36', '2025-12-01 17:28:36'),
(42, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', '[]', '2025-12-01 17:30:37', '2025-12-01 17:30:37'),
(43, 1, 'attendance_break_in', 'Marked break_in at 01:31 AM', '136.158.37.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', '{\"attendance_id\":5,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T17:31:14.729108Z\"}', '2025-12-01 17:31:14', '2025-12-01 17:31:14'),
(44, 1, 'attendance_break_out', 'Marked break_out at 01:31 AM', '136.158.37.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', '{\"attendance_id\":5,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T17:31:33.925864Z\"}', '2025-12-01 17:31:33', '2025-12-01 17:31:33'),
(45, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', '[]', '2025-12-01 17:40:55', '2025-12-01 17:40:55'),
(46, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', '[]', '2025-12-01 17:41:12', '2025-12-01 17:41:12'),
(47, 1, 'attendance_time_out', 'Marked time_out at 01:45 AM', '136.158.37.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', '{\"attendance_id\":5,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T17:45:00.222139Z\"}', '2025-12-01 17:45:00', '2025-12-01 17:45:00'),
(48, 1, 'attendance_time_in', 'Marked time_in at 01:45 AM', '136.158.37.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', '{\"attendance_id\":6,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T17:45:39.072498Z\"}', '2025-12-01 17:45:39', '2025-12-01 17:45:39'),
(49, 1, 'attendance_break_in', 'Marked break_in at 01:45 AM', '136.158.37.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', '{\"attendance_id\":6,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T17:45:44.329255Z\"}', '2025-12-01 17:45:44', '2025-12-01 17:45:44'),
(50, 1, 'attendance_break_out', 'Marked break_out at 01:45 AM', '136.158.37.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', '{\"attendance_id\":6,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T17:45:49.571706Z\"}', '2025-12-01 17:45:49', '2025-12-01 17:45:49'),
(51, 1, 'attendance_time_out', 'Marked time_out at 01:45 AM', '136.158.37.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', '{\"attendance_id\":6,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T17:45:55.277699Z\"}', '2025-12-01 17:45:55', '2025-12-01 17:45:55'),
(52, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2025-12-01 17:49:22', '2025-12-01 17:49:22'),
(53, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '[]', '2025-12-01 18:00:52', '2025-12-01 18:00:52'),
(54, 1, 'login', 'User logged in', '209.35.167.236', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2025-12-01 18:15:59', '2025-12-01 18:15:59'),
(55, 1, 'attendance_time_in', 'Marked time_in at 02:19 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '{\"attendance_id\":7,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T18:19:43.543918Z\"}', '2025-12-01 18:19:43', '2025-12-01 18:19:43'),
(56, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 18:25:12', '2025-12-01 18:25:12'),
(57, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 18:25:24', '2025-12-01 18:25:24'),
(58, 1, 'login', 'User logged in', '209.35.167.236', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2025-12-01 18:34:13', '2025-12-01 18:34:13'),
(59, 1, 'login', 'User logged in', '209.35.167.236', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2025-12-01 18:35:27', '2025-12-01 18:35:27'),
(60, 1, 'attendance_break_in', 'Marked break_in at 02:35 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '{\"attendance_id\":7,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T18:35:40.876729Z\"}', '2025-12-01 18:35:40', '2025-12-01 18:35:40'),
(61, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '[]', '2025-12-01 18:37:42', '2025-12-01 18:37:42'),
(62, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '[]', '2025-12-01 18:37:48', '2025-12-01 18:37:48'),
(63, 1, 'attendance_time_in', 'Marked time_in at 02:38 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '{\"attendance_id\":8,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T18:38:52.890681Z\"}', '2025-12-01 18:38:52', '2025-12-01 18:38:52'),
(64, 1, 'attendance_break_in', 'Marked break_in at 02:39 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '{\"attendance_id\":8,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T18:39:02.599684Z\"}', '2025-12-01 18:39:02', '2025-12-01 18:39:02'),
(65, 1, 'attendance_break_out', 'Marked break_out at 02:39 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '{\"attendance_id\":8,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T18:39:07.443257Z\"}', '2025-12-01 18:39:07', '2025-12-01 18:39:07'),
(66, 1, 'attendance_time_in', 'Marked time_in at 02:59 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '{\"attendance_id\":9,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T18:59:24.686399Z\"}', '2025-12-01 18:59:24', '2025-12-01 18:59:24'),
(67, 1, 'attendance_break_in', 'Marked break_in at 03:00 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '{\"attendance_id\":9,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T19:00:10.342094Z\"}', '2025-12-01 19:00:10', '2025-12-01 19:00:10'),
(68, 1, 'attendance_break_out', 'Marked break_out at 03:01 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '{\"attendance_id\":9,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T19:01:47.650489Z\"}', '2025-12-01 19:01:47', '2025-12-01 19:01:47'),
(69, 1, 'attendance_time_in', 'Marked time_in at 03:02 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '{\"attendance_id\":10,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T19:02:15.170774Z\"}', '2025-12-01 19:02:15', '2025-12-01 19:02:15'),
(70, 1, 'attendance_time_in', 'Marked time_in at 03:15 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '{\"attendance_id\":11,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T19:15:17.716336Z\"}', '2025-12-01 19:15:17', '2025-12-01 19:15:17'),
(71, 1, 'attendance_break_in', 'Marked break_in at 03:16 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '{\"attendance_id\":11,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T19:16:01.167896Z\"}', '2025-12-01 19:16:01', '2025-12-01 19:16:01'),
(72, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '[]', '2025-12-01 19:16:30', '2025-12-01 19:16:30'),
(73, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '[]', '2025-12-01 19:16:38', '2025-12-01 19:16:38'),
(74, 1, 'attendance_time_in', 'Marked time_in at 03:17 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '{\"attendance_id\":12,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T19:17:12.339605Z\"}', '2025-12-01 19:17:12', '2025-12-01 19:17:12'),
(75, 1, 'attendance_break_in', 'Marked break_in at 03:25 AM', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '{\"attendance_id\":12,\"date\":\"2025-12-02\",\"timestamp\":\"2025-12-01T19:25:47.418492Z\"}', '2025-12-01 19:25:47', '2025-12-01 19:25:47'),
(76, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-02 06:21:42', '2025-12-02 06:21:42'),
(77, 2, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-02 07:34:39', '2025-12-02 07:34:39'),
(78, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-02 07:55:56', '2025-12-02 07:55:56'),
(79, NULL, 'google_login_denied', 'Google sign-in denied - User not found: chba.aring.sjc@phinmaed.com', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"google_email\":\"chba.aring.sjc@phinmaed.com\",\"google_name\":\"Christian Baynado Aring\",\"google_id\":\"102552381699817374217\",\"reason\":\"user_not_registered\"}', '2025-12-02 07:56:17', '2025-12-02 07:56:17'),
(80, 4, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-02 07:58:03', '2025-12-02 07:58:03'),
(81, 4, 'profile_completed', 'Completed profile setup and activated account', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"employee_id\":\"admin01\",\"completion_timestamp\":\"2025-12-02T08:00:29.288184Z\"}', '2025-12-02 08:00:29', '2025-12-02 08:00:29'),
(82, NULL, 'profile_completed', 'Completed profile setup and activated account', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '{\"employee_id\":\"emp01\",\"completion_timestamp\":\"2025-12-02T08:02:55.105316Z\"}', '2025-12-02 08:02:55', '2025-12-02 08:02:55'),
(83, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2025-12-02 08:03:10', '2025-12-02 08:03:10'),
(84, 4, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-02 08:30:02', '2025-12-02 08:30:02'),
(85, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-02 08:30:20', '2025-12-02 08:30:20'),
(86, 2, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-02 08:35:36', '2025-12-02 08:35:36'),
(87, 4, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2025-12-02 09:19:44', '2025-12-02 09:19:44'),
(88, 4, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2025-12-02 09:55:31', '2025-12-02 09:55:31'),
(89, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-02 10:40:19', '2025-12-02 10:40:19'),
(90, 4, 'google_login', 'User signed in with Google', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"google_email\":\"chba.aring.sjc@phinmaed.com\",\"google_name\":\"Christian Baynado Aring\",\"google_id\":\"102552381699817374217\",\"login_method\":\"google_oauth\"}', '2025-12-02 10:40:27', '2025-12-02 10:40:27'),
(91, 2, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-02 13:00:47', '2025-12-02 13:00:47'),
(92, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '[]', '2025-12-02 13:02:48', '2025-12-02 13:02:48'),
(93, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-03 05:10:21', '2025-12-03 05:10:21'),
(94, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-03 05:11:26', '2025-12-03 05:11:26'),
(95, 2, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-03 05:11:43', '2025-12-03 05:11:43'),
(96, 2, 'attendance_marked_by_admin', 'Marked Christian Aring as present on 2025-12-03', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"attendance_id\":13,\"employee_id\":1,\"employee_name\":\"Christian Aring\",\"date\":\"2025-12-03\",\"status\":\"present\"}', '2025-12-03 05:11:58', '2025-12-03 05:11:58'),
(97, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-03 05:17:25', '2025-12-03 05:17:25'),
(98, 4, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '[]', '2025-12-03 05:54:34', '2025-12-03 05:54:34'),
(99, 1, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-03 07:47:21', '2025-12-03 07:47:21'),
(100, 2, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-03 07:48:07', '2025-12-03 07:48:07'),
(101, 4, 'employee_created', 'Created new employee: Channsn (ID: emp02)', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '{\"employee_id\":7,\"employee_email\":\"channics@gmail.com\",\"employee_role\":\"employee\",\"generated_employee_id\":\"emp02\"}', '2025-12-03 09:12:06', '2025-12-03 09:12:06'),
(102, 2, 'logout', 'User logged out', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-03 09:19:22', '2025-12-03 09:19:22'),
(103, NULL, 'profile_completed', 'Completed profile setup and activated account', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '{\"employee_id\":\"emp02\",\"completion_timestamp\":\"2025-12-03T09:19:52.651352Z\"}', '2025-12-03 09:19:52', '2025-12-03 09:19:52'),
(104, 7, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-03 09:20:09', '2025-12-03 09:20:09'),
(105, 1, 'login', 'User logged in', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '[]', '2026-01-04 04:33:52', '2026-01-04 04:33:52'),
(106, 7, 'logout', 'User logged out', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '[]', '2026-01-04 04:35:26', '2026-01-04 04:35:26'),
(107, 4, 'login', 'User logged in', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '[]', '2026-01-04 04:35:44', '2026-01-04 04:35:44'),
(108, 1, 'logout', 'User logged out', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '[]', '2026-01-04 04:38:11', '2026-01-04 04:38:11'),
(109, 7, 'login', 'User logged in', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '[]', '2026-01-04 04:38:37', '2026-01-04 04:38:37'),
(110, 1, 'login', 'User logged in', '136.158.37.22', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2026-01-04 05:04:32', '2026-01-04 05:04:32'),
(111, 1, 'attendance_time_in', 'Marked time_in at 01:20 PM', '136.158.37.22', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '{\"attendance_id\":14,\"date\":\"2026-01-04\",\"timestamp\":\"2026-01-04T05:20:15.019520Z\"}', '2026-01-04 05:20:15', '2026-01-04 05:20:15'),
(112, 1, 'login', 'User logged in', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '[]', '2026-01-04 05:26:58', '2026-01-04 05:26:58'),
(113, 1, 'attendance_break_in', 'Marked break_in at 01:27 PM', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"attendance_id\":14,\"date\":\"2026-01-04\",\"timestamp\":\"2026-01-04T05:27:11.940675Z\"}', '2026-01-04 05:27:11', '2026-01-04 05:27:11'),
(114, 1, 'attendance_break_out', 'Marked break_out at 01:35 PM', '136.158.37.22', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '{\"attendance_id\":14,\"date\":\"2026-01-04\",\"timestamp\":\"2026-01-04T05:35:10.280715Z\"}', '2026-01-04 05:35:10', '2026-01-04 05:35:10'),
(115, 1, 'attendance_time_out', 'Marked time_out at 01:35 PM', '136.158.37.22', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '{\"attendance_id\":14,\"date\":\"2026-01-04\",\"timestamp\":\"2026-01-04T05:35:18.374188Z\"}', '2026-01-04 05:35:18', '2026-01-04 05:35:18'),
(116, 1, 'logout', 'User logged out', '136.158.37.22', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2026-01-04 05:36:42', '2026-01-04 05:36:42'),
(117, 1, 'login', 'User logged in', '136.158.37.22', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2026-01-04 05:36:56', '2026-01-04 05:36:56'),
(118, 1, 'logout', 'User logged out', '112.198.104.240', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2026-01-04 05:37:30', '2026-01-04 05:37:30'),
(119, 7, 'login', 'User logged in', '112.198.104.240', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2026-01-04 05:38:18', '2026-01-04 05:38:18'),
(120, 4, 'google_login_denied', 'Google sign-in denied - User not found: lynicring123@gmail.com', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"google_email\":\"lynicring123@gmail.com\",\"google_name\":\"Cinelyn aring\",\"google_id\":\"100404111038337196703\",\"reason\":\"user_not_registered\"}', '2026-01-04 05:42:29', '2026-01-04 05:42:29'),
(121, 4, 'logout', 'User logged out', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '[]', '2026-01-04 05:42:51', '2026-01-04 05:42:51'),
(122, NULL, 'google_login_denied', 'Google sign-in denied - User not found: lynicring123@gmail.com', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"google_email\":\"lynicring123@gmail.com\",\"google_name\":\"Cinelyn aring\",\"google_id\":\"100404111038337196703\",\"reason\":\"user_not_registered\"}', '2026-01-04 05:43:10', '2026-01-04 05:43:10'),
(123, 4, 'google_login', 'User signed in with Google', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"google_email\":\"chba.aring.sjc@phinmaed.com\",\"google_name\":\"BSIT 3-1_Christian Baynado Aring\",\"google_id\":\"102552381699817374217\",\"login_method\":\"google_oauth\"}', '2026-01-04 05:43:36', '2026-01-04 05:43:36'),
(124, 4, 'google_login', 'User signed in with Google', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"google_email\":\"chba.aring.sjc@phinmaed.com\",\"google_name\":\"BSIT 3-1_Aring, Christian Baynado\",\"google_id\":\"102552381699817374217\",\"login_method\":\"google_oauth\"}', '2026-01-24 05:35:41', '2026-01-24 05:35:41'),
(125, 4, 'logout', 'User logged out', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '[]', '2026-01-24 05:58:05', '2026-01-24 05:58:05'),
(126, 1, 'login', 'User logged in', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '[]', '2026-01-24 05:58:35', '2026-01-24 05:58:35'),
(127, 2, 'login', 'User logged in', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '[]', '2026-01-24 05:59:13', '2026-01-24 05:59:13'),
(128, 2, 'google_login', 'User signed in with Google', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"google_email\":\"luffylines@gmail.com\",\"google_name\":\"Luffy lines\",\"google_id\":\"103530698650697730442\",\"login_method\":\"google_oauth\"}', '2026-01-24 06:00:00', '2026-01-24 06:00:00'),
(129, 1, 'login', 'User logged in', '136.158.37.22', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2026-01-24 06:15:01', '2026-01-24 06:15:01'),
(130, 1, 'attendance_time_in', 'Marked time_in at 02:55 PM', '136.158.37.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"attendance_id\":15,\"date\":\"2026-01-24\",\"timestamp\":\"2026-01-24T06:55:47.475495Z\"}', '2026-01-24 06:55:47', '2026-01-24 06:55:47'),
(131, 1, 'attendance_break_in', 'Marked break_in at 03:07 PM', '136.158.37.22', 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 10 Build/MOB31T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"attendance_id\":15,\"date\":\"2026-01-24\",\"timestamp\":\"2026-01-24T07:07:06.440305Z\"}', '2026-01-24 07:07:06', '2026-01-24 07:07:06'),
(132, 2, 'attendance_marked_by_admin', 'Marked Channsn as present on 2026-01-24', '136.158.37.22', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', '{\"attendance_id\":16,\"employee_id\":7,\"employee_name\":\"Channsn\",\"date\":\"2026-01-24\",\"status\":\"present\"}', '2026-01-24 07:11:27', '2026-01-24 07:11:27'),
(133, 2, 'login', 'User logged in', '136.158.37.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '[]', '2026-02-25 12:25:21', '2026-02-25 12:25:21'),
(134, 2, 'logout', 'User logged out', '136.158.37.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '[]', '2026-02-25 12:26:21', '2026-02-25 12:26:21'),
(135, 1, 'login', 'User logged in', '136.158.37.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '[]', '2026-02-25 12:27:05', '2026-02-25 12:27:05'),
(136, 4, 'google_login', 'User signed in with Google', '136.158.37.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Mobile/15E148 Safari/604.1', '{\"google_email\":\"chba.aring.sjc@phinmaed.com\",\"google_name\":\"BSIT 3-1_Aring, Christian Baynado\",\"google_id\":\"102552381699817374217\",\"login_method\":\"google_oauth\"}', '2026-02-25 12:30:28', '2026-02-25 12:30:28'),
(137, 4, 'google_login', 'User signed in with Google', '136.158.37.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Mobile/15E148 Safari/604.1', '{\"google_email\":\"chba.aring.sjc@phinmaed.com\",\"google_name\":\"BSIT 3-1_Aring, Christian Baynado\",\"google_id\":\"102552381699817374217\",\"login_method\":\"google_oauth\"}', '2026-02-25 12:30:36', '2026-02-25 12:30:36'),
(138, 4, 'logout', 'User logged out', '136.158.37.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Mobile/15E148 Safari/604.1', '[]', '2026-02-25 12:31:02', '2026-02-25 12:31:02'),
(139, 2, 'google_login', 'User signed in with Google', '136.158.37.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Mobile/15E148 Safari/604.1', '{\"google_email\":\"luffylines@gmail.com\",\"google_name\":\"Luffy lines\",\"google_id\":\"103530698650697730442\",\"login_method\":\"google_oauth\"}', '2026-02-25 12:31:54', '2026-02-25 12:31:54'),
(140, 2, 'logout', 'User logged out', '136.158.37.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Mobile/15E148 Safari/604.1', '[]', '2026-02-25 12:32:10', '2026-02-25 12:32:10'),
(141, 1, 'login', 'User logged in', '136.158.37.169', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2026-02-25 12:33:36', '2026-02-25 12:33:36'),
(142, 1, 'login', 'User logged in', '136.158.37.169', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '[]', '2026-02-25 13:15:08', '2026-02-25 13:15:08'),
(143, 1, 'login', 'User logged in', '136.158.37.169', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '[]', '2026-02-25 14:00:06', '2026-02-25 14:00:06'),
(144, 7, 'login', 'User logged in', '136.158.37.111', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '[]', '2026-03-03 13:02:26', '2026-03-03 13:02:26'),
(145, 1, 'logout', 'User logged out', '136.158.37.111', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '[]', '2026-03-03 13:06:15', '2026-03-03 13:06:15'),
(146, 7, 'attendance_time_in', 'Marked time_in at 09:06 PM', '136.158.37.111', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"attendance_id\":17,\"date\":\"2026-03-03\",\"timestamp\":\"2026-03-03T13:06:57.506575Z\"}', '2026-03-03 13:06:57', '2026-03-03 13:06:57'),
(147, 7, 'attendance_break_in', 'Marked break_in at 09:07 PM', '136.158.37.111', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"attendance_id\":17,\"date\":\"2026-03-03\",\"timestamp\":\"2026-03-03T13:07:07.126069Z\"}', '2026-03-03 13:07:07', '2026-03-03 13:07:07'),
(148, 7, 'attendance_break_out', 'Marked break_out at 09:07 PM', '136.158.37.111', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"attendance_id\":17,\"date\":\"2026-03-03\",\"timestamp\":\"2026-03-03T13:07:12.084162Z\"}', '2026-03-03 13:07:12', '2026-03-03 13:07:12'),
(149, 7, 'attendance_time_out', 'Marked time_out at 09:07 PM', '136.158.37.111', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"attendance_id\":17,\"date\":\"2026-03-03\",\"timestamp\":\"2026-03-03T13:07:16.790561Z\"}', '2026-03-03 13:07:16', '2026-03-03 13:07:16'),
(150, 2, 'login', 'User logged in', '136.158.37.111', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '[]', '2026-03-03 13:07:42', '2026-03-03 13:07:42'),
(151, 2, 'attendance_marked_by_admin', 'Marked Christian Aring as present on 2026-03-03', '136.158.37.111', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"attendance_id\":18,\"employee_id\":1,\"employee_name\":\"Christian Aring\",\"date\":\"2026-03-03\",\"status\":\"present\"}', '2026-03-03 13:08:09', '2026-03-03 13:08:09'),
(152, 1, 'login', 'User logged in', '136.158.37.111', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', '[]', '2026-03-03 13:14:29', '2026-03-03 13:14:29');

-- --------------------------------------------------------

--
-- Table structure for table `allowed_networks`
--

CREATE TABLE `allowed_networks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ip_ranges` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `allowed_networks`
--

INSERT INTO `allowed_networks` (`id`, `name`, `ip_ranges`, `active`, `created_at`, `updated_at`) VALUES
(10, 'Chan 2i', '[\"136.158.37.22\"]', 1, '2025-12-01 18:18:04', '2026-01-04 05:19:54');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `day_type` enum('regular','holiday','rest_day','overtime') NOT NULL DEFAULT 'regular',
  `time_in` timestamp NULL DEFAULT NULL,
  `time_out` timestamp NULL DEFAULT NULL,
  `breaktime_in` time DEFAULT NULL,
  `breaktime_out` time DEFAULT NULL,
  `total_hours` decimal(4,2) NOT NULL DEFAULT 0.00,
  `regular_hours` decimal(4,2) NOT NULL DEFAULT 0.00,
  `deduction_hours` decimal(4,2) NOT NULL DEFAULT 0.00,
  `deduction_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `daily_rate` decimal(8,2) NOT NULL DEFAULT 600.00,
  `earned_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `user_id`, `date`, `day_type`, `time_in`, `time_out`, `breaktime_in`, `breaktime_out`, `total_hours`, `regular_hours`, `deduction_hours`, `deduction_amount`, `daily_rate`, `earned_amount`, `created_at`, `updated_at`, `status`, `approved_by`, `approved_at`, `rejection_reason`, `remarks`, `created_by`) VALUES
(13, 1, '2025-12-03', 'regular', '2025-12-03 00:00:00', '2025-12-03 09:00:00', '12:00:00', '13:00:00', 8.00, 8.00, 0.00, 0.00, 600.00, 600.00, '2025-12-03 05:11:58', '2025-12-03 05:11:58', 'approved', 2, '2025-12-03 05:11:58', NULL, NULL, 2),
(14, 1, '2026-01-04', 'regular', '2026-01-04 05:20:15', '2026-01-04 05:35:18', '13:27:11', '13:35:10', 0.12, 0.12, 7.88, 591.00, 600.00, 9.00, '2026-01-04 05:20:15', '2026-01-04 05:35:18', 'pending', NULL, NULL, NULL, NULL, 1),
(15, 1, '2026-01-24', 'regular', '2026-01-24 06:55:47', NULL, '15:07:06', NULL, 0.00, 0.00, 0.00, 0.00, 600.00, 0.00, '2026-01-24 06:55:47', '2026-01-24 07:07:06', 'pending', NULL, NULL, NULL, NULL, 1),
(16, 7, '2026-01-24', 'regular', '2026-01-24 00:00:00', '2026-01-24 09:00:00', '12:00:00', '13:00:00', 8.00, 8.00, 0.00, 0.00, 600.00, 600.00, '2026-01-24 07:11:27', '2026-01-24 07:11:27', 'approved', 2, '2026-01-24 07:11:27', NULL, NULL, 2),
(17, 7, '2026-03-03', 'regular', '2026-03-03 13:06:57', '2026-03-03 13:07:16', '21:07:07', '21:07:12', 0.00, 0.00, 8.00, 600.00, 600.00, 0.00, '2026-03-03 13:06:57', '2026-03-03 13:07:16', 'pending', NULL, NULL, NULL, NULL, 7),
(18, 1, '2026-03-03', 'regular', '2026-03-03 00:00:00', '2026-03-03 09:00:00', '12:00:00', '13:00:00', 8.00, 8.00, 0.00, 0.00, 600.00, 600.00, '2026-03-03 13:08:09', '2026-03-03 13:08:09', 'approved', 2, '2026-03-03 13:08:09', NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `hr_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('active','closed','pending') NOT NULL DEFAULT 'active',
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `user_id`, `hr_user_id`, `status`, `last_message_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'active', '2026-03-03 14:42:37', '2026-01-24 06:42:26', '2026-03-03 14:42:37'),
(2, 7, NULL, 'active', '2026-03-03 14:40:53', '2026-03-03 13:50:37', '2026-03-03 14:40:53');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chat_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `message` text NOT NULL,
  `sender_type` enum('user','bot','employee','system') NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `chat_id`, `user_id`, `message`, `sender_type`, `is_read`, `created_at`, `updated_at`) VALUES
(6, 2, 7, 'Show my payslip', 'user', 1, '2026-03-03 13:50:37', '2026-03-03 13:50:37'),
(7, 2, 7, '<strong>Your Latest Payslip</strong> 💰<br><br>📅 <strong>Date:</strong> Mar 03, 2026<br>💵 <strong>Gross Pay:</strong> ₱600.00<br>💰 <strong>Net Pay:</strong> ₱258.00<br><br>Want to see how to download your payslip? <a href=\'#\' onclick=\'sendMessage(\"yes, how to download payslip\")\' class=\'chat-link\'>Yes, show me!</a>', 'bot', 1, '2026-03-03 13:50:37', '2026-03-03 13:50:37'),
(8, 2, 7, 'yes, how to download payslip', 'user', 1, '2026-03-03 13:50:41', '2026-03-03 13:50:41'),
(9, 2, 7, '<strong>Your Latest Payslip</strong> 💰<br><br>📅 <strong>Date:</strong> Mar 03, 2026<br>💵 <strong>Gross Pay:</strong> ₱600.00<br>💰 <strong>Net Pay:</strong> ₱258.00<br><br>Want to see how to download your payslip? <a href=\'#\' onclick=\'sendMessage(\"yes, how to download payslip\")\' class=\'chat-link\'>Yes, show me!</a>', 'bot', 1, '2026-03-03 13:50:41', '2026-03-03 13:50:41'),
(10, 2, 7, 'yes', 'user', 1, '2026-03-03 13:50:53', '2026-03-03 13:50:53'),
(11, 2, 7, 'I can only help with your employee information. 😊<br><br>Try asking about:<br>• 💰 Your <strong>Payslip</strong><br>• 📅 Your <strong>Schedule</strong><br>• 🎉 <strong>Holidays</strong><br>• 🏖️ Your <strong>Leave Balance</strong><br>• ⏰ Your <strong>Attendance</strong><br>• 👤 Your <strong>Profile Info</strong>', 'bot', 1, '2026-03-03 13:50:53', '2026-03-03 13:50:53'),
(12, 2, 7, 'My profile info', 'user', 1, '2026-03-03 13:50:56', '2026-03-03 13:50:56'),
(13, 2, 7, '<strong>Your Profile Information:</strong><br><br>📛 <strong>Name:</strong> Channsn<br>📧 <strong>Email:</strong> channics@gmail.com<br>📱 <strong>Phone:</strong> +639278856264<br>💼 <strong>Role:</strong> Employee<br>🆔 <strong>Employee ID:</strong> emp02', 'bot', 1, '2026-03-03 13:50:56', '2026-03-03 13:50:56'),
(14, 2, 7, 'Show my payslip', 'user', 1, '2026-03-03 13:51:44', '2026-03-03 13:51:44'),
(15, 2, 7, '<strong>Your Latest Payslip</strong> 💰<br><br>📅 <strong>Date:</strong> Mar 03, 2026<br>💵 <strong>Gross Pay:</strong> ₱600.00<br>💰 <strong>Net Pay:</strong> ₱258.00<br><br>Want to see how to download your payslip? <a href=\'#\' onclick=\'sendMessage(\"yes, how to download payslip\")\' class=\'chat-link\'>Yes, show me!</a>', 'bot', 1, '2026-03-03 13:51:44', '2026-03-03 13:51:44'),
(16, 2, 7, 'Next holiday?', 'user', 1, '2026-03-03 13:51:45', '2026-03-03 13:51:45'),
(17, 2, 7, 'There are no upcoming holidays scheduled. Keep working! 💪', 'bot', 1, '2026-03-03 13:51:45', '2026-03-03 13:51:45'),
(18, 2, 7, 'Leave balance?', 'user', 1, '2026-03-03 13:51:47', '2026-03-03 13:51:47'),
(19, 2, 7, '<strong>🏖️ Your Leave Balance:</strong><br><br>✅ <strong>Remaining:</strong> 15 days<br>📊 <strong>Annual Allowance:</strong> 15 days<br>📉 <strong>Days Used:</strong> 0 days<br><br>Need a tutorial on how to apply for leave? <a href=\'#\' onclick=\'sendMessage(\"how to apply leave\")\' class=\'chat-link\'>Yes, show me</a>', 'bot', 1, '2026-03-03 13:51:47', '2026-03-03 13:51:47'),
(20, 2, 7, 'What is my shift?', 'user', 1, '2026-03-03 13:53:15', '2026-03-03 13:53:15'),
(21, 2, 7, 'You have no shift scheduled for today. 🏖️<br><br>You have no shift scheduled for tomorrow. ✨<br><br>Want to know how to request a shift change? <a href=\'#\' onclick=\'sendMessage(\"how to change shift\")\' class=\'chat-link\'>Yes, tell me</a>', 'bot', 1, '2026-03-03 13:53:15', '2026-03-03 13:53:15'),
(22, 2, 7, 'how]', 'user', 1, '2026-03-03 13:54:06', '2026-03-03 13:54:06'),
(23, 2, 7, 'I can only help with your employee information. 😊<br><br>Try asking about:<br>• 💰 Your <strong>Payslip</strong><br>• 📅 Your <strong>Schedule</strong><br>• 🎉 <strong>Holidays</strong><br>• 🏖️ Your <strong>Leave Balance</strong><br>• ⏰ Your <strong>Attendance</strong><br>• 👤 Your <strong>Profile Info</strong>', 'bot', 1, '2026-03-03 13:54:06', '2026-03-03 13:54:06'),
(24, 2, 7, 'profile', 'user', 1, '2026-03-03 13:54:13', '2026-03-03 13:54:13'),
(25, 2, 7, 'I can only help with your employee information. 😊<br><br>Try asking about:<br>• 💰 Your <strong>Payslip</strong><br>• 📅 Your <strong>Schedule</strong><br>• 🎉 <strong>Holidays</strong><br>• 🏖️ Your <strong>Leave Balance</strong><br>• ⏰ Your <strong>Attendance</strong><br>• 👤 Your <strong>Profile Info</strong>', 'bot', 1, '2026-03-03 13:54:13', '2026-03-03 13:54:13'),
(26, 2, 7, 'me', 'user', 1, '2026-03-03 13:54:27', '2026-03-03 13:54:27'),
(27, 2, 7, 'I can only help with your employee information. 😊<br><br>Try asking about:<br>• 💰 Your <strong>Payslip</strong><br>• 📅 Your <strong>Schedule</strong><br>• 🎉 <strong>Holidays</strong><br>• 🏖️ Your <strong>Leave Balance</strong><br>• ⏰ Your <strong>Attendance</strong><br>• 👤 Your <strong>Profile Info</strong>', 'bot', 1, '2026-03-03 13:54:27', '2026-03-03 13:54:27'),
(28, 2, 7, 'payslip', 'user', 1, '2026-03-03 13:54:36', '2026-03-03 13:54:36'),
(29, 2, 7, '<strong>Your Latest Payslip</strong> 💰<br><br>📅 <strong>Date:</strong> Mar 03, 2026<br>💵 <strong>Gross Pay:</strong> ₱600.00<br>💰 <strong>Net Pay:</strong> ₱258.00<br><br>Want to see how to download your payslip? <a href=\'#\' onclick=\'sendMessage(\"yes, how to download payslip\")\' class=\'chat-link\'>Yes, show me!</a>', 'bot', 1, '2026-03-03 13:54:36', '2026-03-03 13:54:36'),
(30, 2, 7, 'yes, how to download payslip', 'user', 1, '2026-03-03 13:54:43', '2026-03-03 13:54:43'),
(31, 2, 7, '<strong>Your Latest Payslip</strong> 💰<br><br>📅 <strong>Date:</strong> Mar 03, 2026<br>💵 <strong>Gross Pay:</strong> ₱600.00<br>💰 <strong>Net Pay:</strong> ₱258.00<br><br>Want to see how to download your payslip? <a href=\'#\' onclick=\'sendMessage(\"yes, how to download payslip\")\' class=\'chat-link\'>Yes, show me!</a>', 'bot', 1, '2026-03-03 13:54:43', '2026-03-03 13:54:43'),
(32, 2, 7, 'yes', 'user', 1, '2026-03-03 13:54:56', '2026-03-03 13:54:56'),
(33, 2, 7, 'I can only help with your employee information. 😊<br><br>Try asking about:<br>• 💰 Your <strong>Payslip</strong><br>• 📅 Your <strong>Schedule</strong><br>• 🎉 <strong>Holidays</strong><br>• 🏖️ Your <strong>Leave Balance</strong><br>• ⏰ Your <strong>Attendance</strong><br>• 👤 Your <strong>Profile Info</strong>', 'bot', 1, '2026-03-03 13:54:56', '2026-03-03 13:54:56'),
(34, 2, 7, 'Show my payslip', 'user', 1, '2026-03-03 13:55:12', '2026-03-03 13:55:12'),
(35, 2, 7, '<strong>Your Latest Payslip</strong> 💰<br><br>📅 <strong>Date:</strong> Mar 03, 2026<br>💵 <strong>Gross Pay:</strong> ₱600.00<br>💰 <strong>Net Pay:</strong> ₱258.00<br><br>Want to see how to download your payslip? <a href=\'#\' onclick=\'sendMessage(\"yes, how to download payslip\")\' class=\'chat-link\'>Yes, show me!</a>', 'bot', 1, '2026-03-03 13:55:12', '2026-03-03 13:55:12'),
(36, 2, 7, 'My profile info', 'user', 1, '2026-03-03 14:09:20', '2026-03-03 14:09:20'),
(37, 2, 7, '<strong>Your Profile Information:</strong><br><br>📛 <strong>Name:</strong> Channsn<br>📧 <strong>Email:</strong> channics@gmail.com<br>📱 <strong>Phone:</strong> +639278856264<br>💼 <strong>Role:</strong> Employee<br>🆔 <strong>Employee ID:</strong> emp02', 'bot', 1, '2026-03-03 14:09:20', '2026-03-03 14:09:20'),
(38, 2, 7, 'Next holiday?', 'user', 1, '2026-03-03 14:09:26', '2026-03-03 14:09:26'),
(39, 2, 7, 'There are no upcoming holidays scheduled. Keep working! 💪', 'bot', 1, '2026-03-03 14:09:26', '2026-03-03 14:09:26'),
(40, 2, 7, 'Show my payslip', 'user', 1, '2026-03-03 14:09:28', '2026-03-03 14:09:28'),
(41, 2, 7, '<strong>Your Latest Payslip</strong> 💰<br><br>📅 <strong>Date:</strong> Mar 03, 2026<br>💵 <strong>Gross Pay:</strong> ₱600.00<br>💰 <strong>Net Pay:</strong> ₱258.00<br><br>Want to see how to download your payslip? <a href=\'#\' onclick=\'sendMessage(\"yes, show me payslip tutorial\")\' class=\'chat-link\'>Yes, show me!</a><br><br><a href=\'/payslips\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>📄 Go to My Payslips</a>', 'bot', 1, '2026-03-03 14:09:28', '2026-03-03 14:09:28'),
(42, 2, 7, 'yes', 'user', 1, '2026-03-03 14:09:54', '2026-03-03 14:09:54'),
(43, 2, 7, '<strong>📄 How to Access & Download Your Payslip:</strong><br><br><strong>Step 1:</strong> Click <strong>My Payslips</strong> from the sidebar menu on the left<br><strong>Step 2:</strong> Find the month you want to view<br><strong>Step 3:</strong> Click the <strong>Download PDF</strong> button or Print icon<br><strong>Step 4:</strong> Your payslip will save as a PDF file to your device 📥<br><br><a href=\'/payslips\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;\'>📄 Go to My Payslips Now</a>', 'bot', 1, '2026-03-03 14:09:54', '2026-03-03 14:09:54'),
(44, 2, 7, 'Leave balance?', 'user', 1, '2026-03-03 14:11:15', '2026-03-03 14:11:15'),
(45, 2, 7, '<strong>🏖️ Your Leave Balance:</strong><br><br>✅ <strong>Remaining:</strong> 15 days<br>📊 <strong>Annual Allowance:</strong> 15 days<br>📉 <strong>Days Used:</strong> 0 days<br><br>Need help applying for leave? <a href=\'#\' onclick=\'sendMessage(\"yes, show me leave tutorial\")\' class=\'chat-link\'>Yes, show me</a><br><br><a href=\'/leave-requests\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>🏖️ Apply for Leave</a>', 'bot', 1, '2026-03-03 14:11:15', '2026-03-03 14:11:15'),
(46, 2, 7, 'oo', 'user', 1, '2026-03-03 14:11:22', '2026-03-03 14:11:22'),
(47, 2, 7, '<strong>🏖️ How to Apply for Leave:</strong><br><br><strong>Step 1:</strong> Click <strong>Leave Requests</strong> from the sidebar<br><strong>Step 2:</strong> Click the <strong>New Request</strong> button<br><strong>Step 3:</strong> Select <strong>Leave Type</strong> (Sick Leave, Vacation, etc.)<br><strong>Step 4:</strong> Choose your <strong>Dates</strong><br><strong>Step 5:</strong> Add a reason and click <strong>Submit</strong><br><strong>Step 6:</strong> Wait for your manager\'s approval - you\'ll get an email! 📩<br><br><a href=\'/leave-requests\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;\'>🏖️ Apply for Leave Now</a>', 'bot', 1, '2026-03-03 14:11:22', '2026-03-03 14:11:22'),
(48, 1, 1, 'What is my shift?', 'user', 1, '2026-03-03 14:12:36', '2026-03-03 14:12:36'),
(49, 1, 1, 'You have no shift scheduled for today. 🏖️<br><br>You have no shift scheduled for tomorrow. ✨<br><br>Want to know how to request a shift change? <a href=\'#\' onclick=\'sendMessage(\"yes, show me shift tutorial\")\' class=\'chat-link\'>Yes, tell me</a><br><br><a href=\'/schedule\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>📅 View My Schedule</a>', 'bot', 1, '2026-03-03 14:12:36', '2026-03-03 14:12:36'),
(50, 1, 1, 'yes, show me shift tutorial', 'user', 1, '2026-03-03 14:12:39', '2026-03-03 14:12:39'),
(51, 1, 1, '<strong>📅 How to View & Manage Your Shifts:</strong><br><br><strong>Step 1:</strong> Click <strong>My Schedule</strong> from the sidebar menu<br><strong>Step 2:</strong> View your schedule for the week/month<br><strong>Step 3 (Request Change):</strong> Contact your <strong>Store Manager</strong> or Supervisor<br><strong>Step 4:</strong> Once approved, changes will appear in your schedule<br><br><a href=\'/schedule\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;\'>📅 View My Schedule Now</a>', 'bot', 1, '2026-03-03 14:12:39', '2026-03-03 14:12:39'),
(52, 1, 1, 'What is my shift?', 'user', 1, '2026-03-03 14:12:51', '2026-03-03 14:12:51'),
(53, 1, 1, 'You have no shift scheduled for today. 🏖️<br><br>You have no shift scheduled for tomorrow. ✨<br><br>Want to know how to request a shift change? <a href=\'#\' onclick=\'sendMessage(\"yes, show me shift tutorial\")\' class=\'chat-link\'>Yes, tell me</a><br><br><a href=\'/schedule\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>📅 View My Schedule</a>', 'bot', 1, '2026-03-03 14:12:51', '2026-03-03 14:12:51'),
(54, 1, 1, 'Yes', 'user', 1, '2026-03-03 14:12:56', '2026-03-03 14:12:56'),
(55, 1, 1, '<strong>📅 How to View & Manage Your Shifts:</strong><br><br><strong>Step 1:</strong> Click <strong>My Schedule</strong> from the sidebar menu<br><strong>Step 2:</strong> View your schedule for the week/month<br><strong>Step 3 (Request Change):</strong> Contact your <strong>Store Manager</strong> or Supervisor<br><strong>Step 4:</strong> Once approved, changes will appear in your schedule<br><br><a href=\'/schedule\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;\'>📅 View My Schedule Now</a>', 'bot', 1, '2026-03-03 14:12:56', '2026-03-03 14:12:56'),
(56, 1, 1, 'Next holiday?', 'user', 1, '2026-03-03 14:13:13', '2026-03-03 14:13:13'),
(57, 1, 1, 'There are no upcoming holidays scheduled. Keep working! 💪', 'bot', 1, '2026-03-03 14:13:13', '2026-03-03 14:13:13'),
(58, 1, 1, 'Leave balance?', 'user', 1, '2026-03-03 14:13:14', '2026-03-03 14:13:14'),
(59, 1, 1, '<strong>🏖️ Your Leave Balance:</strong><br><br>✅ <strong>Remaining:</strong> 15 days<br>📊 <strong>Annual Allowance:</strong> 15 days<br>📉 <strong>Days Used:</strong> 0 days<br><br>Need help applying for leave? <a href=\'#\' onclick=\'sendMessage(\"yes, show me leave tutorial\")\' class=\'chat-link\'>Yes, show me</a><br><br><a href=\'/leave-requests\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>🏖️ Apply for Leave</a>', 'bot', 1, '2026-03-03 14:13:15', '2026-03-03 14:13:15'),
(60, 1, 1, 'yes, show me leave tutorial', 'user', 1, '2026-03-03 14:13:16', '2026-03-03 14:13:16'),
(61, 1, 1, '<strong>🏖️ How to Apply for Leave:</strong><br><br><strong>Step 1:</strong> Click <strong>Leave Requests</strong> from the sidebar<br><strong>Step 2:</strong> Click the <strong>New Request</strong> button<br><strong>Step 3:</strong> Select <strong>Leave Type</strong> (Sick Leave, Vacation, etc.)<br><strong>Step 4:</strong> Choose your <strong>Dates</strong><br><strong>Step 5:</strong> Add a reason and click <strong>Submit</strong><br><strong>Step 6:</strong> Wait for your manager\'s approval - you\'ll get an email! 📩<br><br><a href=\'/leave-requests\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;\'>🏖️ Apply for Leave Now</a>', 'bot', 1, '2026-03-03 14:13:16', '2026-03-03 14:13:16'),
(62, 2, 7, 'What is my shift?', 'user', 1, '2026-03-03 14:13:47', '2026-03-03 14:13:47'),
(63, 2, 7, 'You have no shift scheduled for today. 🏖️<br><br>You have no shift scheduled for tomorrow. ✨<br><br>Want to know how to request a shift change? <a href=\'#\' onclick=\'sendMessage(\"yes, show me shift tutorial\")\' class=\'chat-link\'>Yes, tell me</a><br><br><a href=\'/schedule\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>📅 View My Schedule</a>', 'bot', 1, '2026-03-03 14:13:47', '2026-03-03 14:13:47'),
(64, 2, 7, 'My profile info', 'user', 1, '2026-03-03 14:15:06', '2026-03-03 14:15:06'),
(65, 2, 7, '<strong>Your Profile Information:</strong><br><br>📛 <strong>Name:</strong> Channsn<br>📧 <strong>Email:</strong> channics@gmail.com<br>📱 <strong>Phone:</strong> +639278856264<br>💼 <strong>Role:</strong> Employee<br>🆔 <strong>Employee ID:</strong> emp02', 'bot', 1, '2026-03-03 14:15:06', '2026-03-03 14:15:06'),
(66, 2, 7, 'What is my shift?', 'user', 1, '2026-03-03 14:15:08', '2026-03-03 14:15:08'),
(67, 2, 7, 'You have no shift scheduled for today. 🏖️<br><br>You have no shift scheduled for tomorrow. ✨<br><br>Want to know how to request a shift change? <a href=\'#\' onclick=\'sendMessage(\"yes, show me shift tutorial\")\' class=\'chat-link\'>Yes, tell me</a><br><br><a href=\'/schedule\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>📅 View My Schedule</a>', 'bot', 1, '2026-03-03 14:15:08', '2026-03-03 14:15:08'),
(68, 2, 7, 'yes, show me shift tutorial', 'user', 1, '2026-03-03 14:15:25', '2026-03-03 14:15:25'),
(69, 2, 7, '<strong>📅 How to View & Manage Your Shifts:</strong><br><br><strong>Step 1:</strong> Click <strong>My Schedule</strong> from the sidebar menu<br><strong>Step 2:</strong> View your schedule for the week/month<br><strong>Step 3 (Request Change):</strong> Contact your <strong>Store Manager</strong> or Supervisor<br><strong>Step 4:</strong> Once approved, changes will appear in your schedule<br><br><a href=\'/schedule\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;\'>📅 View My Schedule Now</a>', 'bot', 1, '2026-03-03 14:15:25', '2026-03-03 14:15:25'),
(70, 2, 7, 'What is my shift?', 'user', 1, '2026-03-03 14:15:49', '2026-03-03 14:15:49'),
(71, 2, 7, 'You have no shift scheduled for today. 🏖️<br><br>You have no shift scheduled for tomorrow. ✨<br><br>Want to know how to request a shift change? <a href=\'#\' onclick=\'sendMessage(\"yes, show me shift tutorial\")\' class=\'chat-link\'>Yes, tell me</a><br><br><a href=\'/schedule\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>📅 View My Schedule</a>', 'bot', 1, '2026-03-03 14:15:49', '2026-03-03 14:15:49'),
(72, 2, 7, 'Show my payslip', 'user', 1, '2026-03-03 14:15:53', '2026-03-03 14:15:53'),
(73, 2, 7, '<strong>Your Latest Payslip</strong> 💰<br><br>📅 <strong>Date:</strong> Mar 03, 2026<br>💵 <strong>Gross Pay:</strong> ₱600.00<br>💰 <strong>Net Pay:</strong> ₱258.00<br><br>Want to see how to download your payslip? <a href=\'#\' onclick=\'sendMessage(\"yes, show me payslip tutorial\")\' class=\'chat-link\'>Yes, show me!</a><br><br><a href=\'/payslips\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>📄 Go to My Payslips</a>', 'bot', 1, '2026-03-03 14:15:53', '2026-03-03 14:15:53'),
(74, 2, 7, 'yes, show me shift tutorial', 'user', 1, '2026-03-03 14:17:17', '2026-03-03 14:17:17'),
(75, 2, 7, '<strong>📄 How to Access & Download Your Payslip:</strong><br><br><strong>Step 1:</strong> Click <strong>My Payslips</strong> from the sidebar menu on the left<br><strong>Step 2:</strong> Find the month you want to view<br><strong>Step 3:</strong> Click the <strong>Download PDF</strong> button or Print icon<br><strong>Step 4:</strong> Your payslip will save as a PDF file to your device 📥<br><br><a href=\'/payslips\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;\'>📄 Go to My Payslips Now</a>', 'bot', 1, '2026-03-03 14:17:17', '2026-03-03 14:17:17'),
(76, 2, 7, 'What is my shift?', 'user', 1, '2026-03-03 14:17:23', '2026-03-03 14:17:23'),
(77, 2, 7, 'You have no shift scheduled for today. 🏖️<br><br>You have no shift scheduled for tomorrow. ✨<br><br>Want to know how to request a shift change? <a href=\'#\' onclick=\'sendMessage(\"yes, show me shift tutorial\")\' class=\'chat-link\'>Yes, tell me</a><br><br><a href=\'/my-schedules\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>📅 View My Schedule</a>', 'bot', 1, '2026-03-03 14:17:23', '2026-03-03 14:17:23'),
(78, 2, 7, 'yes, show me shift tutorial', 'user', 1, '2026-03-03 14:17:25', '2026-03-03 14:17:25'),
(79, 2, 7, '<strong>📅 How to View & Manage Your Shifts:</strong><br><br><strong>Step 1:</strong> Click <strong>My Schedule</strong> from the sidebar menu<br><strong>Step 2:</strong> View your schedule for the week/month<br><strong>Step 3 (Request Change):</strong> Contact your <strong>Store Manager</strong> or Supervisor<br><strong>Step 4:</strong> Once approved, changes will appear in your schedule<br><br><a href=\'/schedule\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;\'>📅 View My Schedule Now</a>', 'bot', 1, '2026-03-03 14:17:25', '2026-03-03 14:17:25'),
(80, 2, 7, 'Next holiday?', 'user', 1, '2026-03-03 14:19:41', '2026-03-03 14:19:41'),
(81, 2, 7, 'There are no upcoming holidays scheduled. Keep working! 💪', 'bot', 1, '2026-03-03 14:19:41', '2026-03-03 14:19:41'),
(82, 2, 7, 'What is my shift?', 'user', 1, '2026-03-03 14:24:19', '2026-03-03 14:24:19'),
(83, 2, 7, 'You have no shift scheduled for today. 🏖️<br><br>You have no shift scheduled for tomorrow. ✨<br><br>Want to know how to request a shift change? <a href=\'#\' onclick=\'sendMessage(\"yes, show me shift tutorial\")\' class=\'chat-link\'>Yes, tell me</a><br><br><a href=\'/my-schedules\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>📅 View My Schedule</a>', 'bot', 1, '2026-03-03 14:24:19', '2026-03-03 14:24:19'),
(84, 2, 7, 'Next holiday?', 'user', 1, '2026-03-03 14:24:26', '2026-03-03 14:24:26'),
(85, 2, 7, 'There are no upcoming holidays scheduled. Keep working! 💪', 'bot', 1, '2026-03-03 14:24:26', '2026-03-03 14:24:26'),
(86, 2, 7, 'Show my payslip', 'user', 1, '2026-03-03 14:24:27', '2026-03-03 14:24:27'),
(87, 2, 7, '<strong>Your Latest Payslip</strong> 💰<br><br>📅 <strong>Date:</strong> Mar 03, 2026<br>💵 <strong>Gross Pay:</strong> ₱600.00<br>💰 <strong>Net Pay:</strong> ₱258.00<br><br>Want to see how to download your payslip? <a href=\'#\' onclick=\'sendMessage(\"yes, show me payslip tutorial\")\' class=\'chat-link\'>Yes, show me!</a><br><br><a href=\'/payslips\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>📄 Go to My Payslips</a>', 'bot', 1, '2026-03-03 14:24:27', '2026-03-03 14:24:27'),
(88, 2, 7, 'Leave balance?', 'user', 1, '2026-03-03 14:24:30', '2026-03-03 14:24:30'),
(89, 2, 7, '<strong>🏖️ Your Leave Balance:</strong><br><br>✅ <strong>Remaining:</strong> 15 days<br>📊 <strong>Annual Allowance:</strong> 15 days<br>📉 <strong>Days Used:</strong> 0 days<br><br>Need help applying for leave? <a href=\'#\' onclick=\'sendMessage(\"yes, show me leave tutorial\")\' class=\'chat-link\'>Yes, show me</a><br><br><a href=\'/leave\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>🏖️ Apply for Leave</a>', 'bot', 1, '2026-03-03 14:24:30', '2026-03-03 14:24:30'),
(90, 2, 7, 'attendance', 'user', 1, '2026-03-03 14:30:41', '2026-03-03 14:30:41'),
(91, 2, 7, '<strong>📅 Your Attendance This Month:</strong><br><br>✅ <strong>Days Present:</strong> 1 days<br>📊 Month: March 2026<br><br>Want to know how to time in/out? <a href=\'#\' onclick=\'sendMessage(\"yes, show me attendance tutorial\")\' class=\'chat-link\'>Yes, explain</a><br><br><a href=\'/attendance/my\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>⏰ View My Attendance</a>', 'bot', 1, '2026-03-03 14:30:41', '2026-03-03 14:30:41'),
(92, 2, 7, 'yes, show me attendance tutorial', 'user', 1, '2026-03-03 14:30:57', '2026-03-03 14:30:57'),
(93, 2, 7, '<strong>⏰ How to Time In and Time Out:</strong><br><br><strong>Step 1:</strong> Go to the <strong>Dashboard</strong><br><strong>Step 2 (Time In):</strong> Click the <strong>Clock In</strong> button when you arrive<br><strong>Step 3 (Time Out):</strong> Click the <strong>Clock Out</strong> button when you leave<br><strong>Important:</strong> Ensure your GPS/Location is enabled if required by your store 📍<br><br><strong>To view your records:</strong><br>Click <strong>My Attendance</strong> from the sidebar to see your daily records<br><br><a href=\'/attendance/my\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;\'>⏰ View My Attendance</a>', 'bot', 1, '2026-03-03 14:30:57', '2026-03-03 14:30:57'),
(94, 2, 7, 'My attendance', 'user', 1, '2026-03-03 14:40:53', '2026-03-03 14:40:53'),
(95, 2, 7, '<strong>📅 Your Attendance This Month:</strong><br><br>✅ <strong>Days Present:</strong> 1 days<br>📊 Month: March 2026<br><br>Want to know how to time in/out? <a href=\'#\' onclick=\'sendMessage(\"yes, show me attendance tutorial\")\' class=\'chat-link\'>Yes, explain</a><br><br><a href=\'/attendance/my\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>⏰ View My Attendance</a>', 'bot', 1, '2026-03-03 14:40:53', '2026-03-03 14:40:53'),
(96, 1, 1, 'My attendance', 'user', 1, '2026-03-03 14:41:20', '2026-03-03 14:41:20'),
(97, 1, 1, '<strong>📅 Your Attendance This Month:</strong><br><br>✅ <strong>Days Present:</strong> 1 days<br>📊 Month: March 2026<br><br>Want to know how to time in/out? <a href=\'#\' onclick=\'sendMessage(\"yes, show me attendance tutorial\")\' class=\'chat-link\'>Yes, explain</a><br><br><a href=\'/attendance/my\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>⏰ View My Attendance</a>', 'bot', 1, '2026-03-03 14:41:20', '2026-03-03 14:41:20'),
(98, 1, 1, 'My attendance', 'user', 1, '2026-03-03 14:41:46', '2026-03-03 14:41:46'),
(99, 1, 1, '<strong>📅 Your Attendance This Month:</strong><br><br>✅ <strong>Days Present:</strong> 1 days<br>📊 Month: March 2026<br><br>Want to know how to time in/out? <a href=\'#\' onclick=\'sendMessage(\"yes, show me attendance tutorial\")\' class=\'chat-link\'>Yes, explain</a><br><br><a href=\'/attendance/my\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>⏰ View My Attendance</a>', 'bot', 1, '2026-03-03 14:41:46', '2026-03-03 14:41:46'),
(100, 1, 1, 'yes, show me shift tutorial', 'user', 1, '2026-03-03 14:41:53', '2026-03-03 14:41:53'),
(101, 1, 1, '<strong>📅 How to View & Manage Your Shifts:</strong><br><br><strong>Step 1:</strong> Click <strong>My Schedule</strong> from the sidebar menu<br><strong>Step 2:</strong> View your schedule for the week/month<br><strong>Step 3 (Request Change):</strong> Contact your <strong>Store Manager</strong> or Supervisor<br><strong>Step 4:</strong> Once approved, changes will appear in your schedule<br><br><a href=\'/my-schedules\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;\'>📅 View My Schedule Now</a>', 'bot', 1, '2026-03-03 14:41:53', '2026-03-03 14:41:53'),
(102, 1, 1, 'What is my shift?', 'user', 1, '2026-03-03 14:41:58', '2026-03-03 14:41:58'),
(103, 1, 1, 'You have no shift scheduled for today. 🏖️<br><br>You have no shift scheduled for tomorrow. ✨<br><br>Want to know how to request a shift change? <a href=\'#\' onclick=\'sendMessage(\"yes, show me shift tutorial\")\' class=\'chat-link\'>Yes, tell me</a><br><br><a href=\'/my-schedules\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>📅 View My Schedule</a>', 'bot', 1, '2026-03-03 14:41:58', '2026-03-03 14:41:58'),
(104, 1, 1, 'yes, show me shift tutorial', 'user', 1, '2026-03-03 14:42:00', '2026-03-03 14:42:00'),
(105, 1, 1, '<strong>📅 How to View & Manage Your Shifts:</strong><br><br><strong>Step 1:</strong> Click <strong>My Schedule</strong> from the sidebar menu<br><strong>Step 2:</strong> View your schedule for the week/month<br><strong>Step 3 (Request Change):</strong> Contact your <strong>Store Manager</strong> or Supervisor<br><strong>Step 4:</strong> Once approved, changes will appear in your schedule<br><br><a href=\'/my-schedules\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;\'>📅 View My Schedule Now</a>', 'bot', 1, '2026-03-03 14:42:00', '2026-03-03 14:42:00'),
(106, 1, 1, 'Show my payslip', 'user', 1, '2026-03-03 14:42:09', '2026-03-03 14:42:09'),
(107, 1, 1, '<strong>Your Latest Payslip</strong> 💰<br><br>📅 <strong>Date:</strong> Mar 03, 2026<br>💵 <strong>Gross Pay:</strong> ₱600.00<br>💰 <strong>Net Pay:</strong> ₱258.00<br><br>Want to see how to download your payslip? <a href=\'#\' onclick=\'sendMessage(\"yes, show me payslip tutorial\")\' class=\'chat-link\'>Yes, show me!</a><br><br><a href=\'/payslips\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>📄 Go to My Payslips</a>', 'bot', 1, '2026-03-03 14:42:09', '2026-03-03 14:42:09'),
(108, 1, 1, 'yes, show me payslip tutorial', 'user', 1, '2026-03-03 14:42:11', '2026-03-03 14:42:11'),
(109, 1, 1, '<strong>📄 How to Access & Download Your Payslip:</strong><br><br><strong>Step 1:</strong> Click <strong>My Payslips</strong> from the sidebar menu on the left<br><strong>Step 2:</strong> Find the month you want to view<br><strong>Step 3:</strong> Click the <strong>Download PDF</strong> button or Print icon<br><strong>Step 4:</strong> Your payslip will save as a PDF file to your device 📥<br><br><a href=\'/payslips\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;\'>📄 Go to My Payslips Now</a>', 'bot', 1, '2026-03-03 14:42:11', '2026-03-03 14:42:11'),
(110, 1, 1, 'My overtime', 'user', 1, '2026-03-03 14:42:25', '2026-03-03 14:42:25'),
(111, 1, 1, 'You don\'t have any approved overtime hours for this month yet. 🕒', 'bot', 1, '2026-03-03 14:42:25', '2026-03-03 14:42:25'),
(112, 1, 1, 'Leave balance?', 'user', 1, '2026-03-03 14:42:28', '2026-03-03 14:42:28'),
(113, 1, 1, '<strong>🏖️ Your Leave Balance:</strong><br><br>✅ <strong>Remaining:</strong> 15 days<br>📊 <strong>Annual Allowance:</strong> 15 days<br>📉 <strong>Days Used:</strong> 0 days<br><br>Need help applying for leave? <a href=\'#\' onclick=\'sendMessage(\"yes, show me leave tutorial\")\' class=\'chat-link\'>Yes, show me</a><br><br><a href=\'/leave\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 5px;\'>🏖️ Apply for Leave</a>', 'bot', 1, '2026-03-03 14:42:28', '2026-03-03 14:42:28'),
(114, 1, 1, 'yes, show me leave tutorial', 'user', 1, '2026-03-03 14:42:30', '2026-03-03 14:42:30'),
(115, 1, 1, '<strong>🏖️ How to Apply for Leave:</strong><br><br><strong>Step 1:</strong> Click <strong>Leave Requests</strong> from the sidebar<br><strong>Step 2:</strong> Click the <strong>New Request</strong> button<br><strong>Step 3:</strong> Select <strong>Leave Type</strong> (Sick Leave, Vacation, etc.)<br><strong>Step 4:</strong> Choose your <strong>Dates</strong><br><strong>Step 5:</strong> Add a reason and click <strong>Submit</strong><br><strong>Step 6:</strong> Wait for your manager\'s approval - you\'ll get an email! 📩<br><br><a href=\'/leave\' class=\'btn btn-primary btn-sm\' style=\'color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); display: inline-block; margin-top: 10px;\'>🏖️ Apply for Leave Now</a>', 'bot', 1, '2026-03-03 14:42:30', '2026-03-03 14:42:30'),
(116, 1, 1, 'My profile info', 'user', 1, '2026-03-03 14:42:37', '2026-03-03 14:42:37'),
(117, 1, 1, '<strong>Your Profile Information:</strong><br><br>📛 <strong>Name:</strong> Christian Aring<br>📧 <strong>Email:</strong> christianaring6@gmail.com<br>📱 <strong>Phone:</strong> +639382393116<br>💼 <strong>Role:</strong> Employee<br>🆔 <strong>Employee ID:</strong> emp01', 'bot', 1, '2026-03-03 14:42:37', '2026-03-03 14:42:37');

-- --------------------------------------------------------

--
-- Table structure for table `employee_profiles`
--

CREATE TABLE `employee_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `department` varchar(255) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `hire_date` date NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('regular','special','local') NOT NULL DEFAULT 'regular',
  `country` varchar(2) NOT NULL DEFAULT 'PH',
  `region` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text NOT NULL,
  `total_days` int(11) NOT NULL,
  `supporting_document` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `manager_remarks` text DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `user_id`, `leave_type`, `start_date`, `end_date`, `reason`, `total_days`, `supporting_document`, `status`, `manager_remarks`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'sick', '2025-12-02', '2025-12-03', 'Sick', 2, NULL, 'pending', NULL, NULL, NULL, '2025-12-01 17:29:35', '2025-12-01 17:29:35');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_25_042333_create_employee_profiles_table', 1),
(5, '2025_09_25_042333_create_leave_requests_table', 1),
(6, '2025_09_25_053405_add_role_to_users_table', 1),
(7, '2025_09_25_160421_add_google_id_to_users_table', 1),
(8, '2025_09_26_042947_create_otps_table', 1),
(9, '2025_09_27_055827_create_attendances_table', 1),
(10, '2025_09_27_062142_add_approval_to_attendances_table', 1),
(11, '2025_09_28_104545_add_created_by_to_attendances', 1),
(12, '2025_10_01_170814_create_overtime_requests_table', 1),
(13, '2025_10_01_172421_add_fields_to_leave_requests_table', 1),
(14, '2025_10_02_015457_add_day_type_to_attendances_table', 1),
(15, '2025_10_02_022525_create_activity_logs_table', 1),
(16, '2025_10_05_225044_add_profile_fields_to_users_table', 1),
(17, '2025_10_09_000650_add_profile_fields_to_users_table', 1),
(18, '2025_10_09_011959_add_employee_id_to_users_table', 1),
(19, '2025_10_09_030423_add_employee_id_and_role_to_users_table', 1),
(20, '2025_10_09_030439_add_employee_id_and_role_to_users_table', 1),
(21, '2025_10_11_190400_create_holidays_table', 1),
(22, '2025_10_11_204416_add_approval_fields_to_attendances_table', 1),
(23, '2025_11_26_143103_create_payrolls_table', 1),
(24, '2025_11_26_143340_create_payslips_table', 1),
(25, '2025_11_27_190125_add_breaktime_and_deduction_to_attendances_table', 1),
(26, '2025_12_01_000001_create_stores_table', 1),
(27, '2025_12_02_create_work_schedules_table', 2),
(28, '2025_12_02_170729_modify_work_schedules_remove_breaks_add_store_id', 3),
(29, '2026_01_24_000001_create_chats_table', 4),
(30, '2026_01_24_000002_create_chat_messages_table', 4),
(31, '2026_01_24_000003_create_user_online_status_table', 4),
(32, '2026_01_24_145558_add_file_to_chat_messages_table', 5),
(33, '2026_03_03_214432_fix_chat_messages_sender_type_enum', 6);

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `otp_code` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `overtime_requests`
--

CREATE TABLE `overtime_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `overtime_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_hours` decimal(4,2) NOT NULL,
  `reason` text NOT NULL,
  `supporting_document` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `manager_remarks` text DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `overtime_requests`
--

INSERT INTO `overtime_requests` (`id`, `user_id`, `overtime_date`, `start_time`, `end_time`, `total_hours`, `reason`, `supporting_document`, `status`, `manager_remarks`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(2, 1, '2025-12-02', '01:28:00', '03:28:00', 2.00, 'ot', NULL, 'approved', NULL, 2, '2025-12-02 07:35:04', '2025-12-01 17:29:00', '2025-12-02 07:35:04'),
(3, 1, '2025-12-03', '13:33:00', '14:33:00', 1.00, 'asdasd', NULL, 'pending', NULL, NULL, NULL, '2025-12-03 05:33:15', '2025-12-03 05:33:15');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `pay_period_year` year(4) NOT NULL,
  `pay_period_month` tinyint(3) UNSIGNED NOT NULL,
  `pay_period_start` date NOT NULL,
  `pay_period_end` date NOT NULL,
  `daily_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `working_days` int(11) NOT NULL DEFAULT 0,
  `days_worked` int(11) NOT NULL DEFAULT 0,
  `basic_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `regular_overtime_hours` decimal(8,2) NOT NULL DEFAULT 0.00,
  `holiday_overtime_hours` decimal(8,2) NOT NULL DEFAULT 0.00,
  `regular_overtime_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `holiday_overtime_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_overtime_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `late_hours` decimal(8,2) NOT NULL DEFAULT 0.00,
  `undertime_hours` decimal(8,2) NOT NULL DEFAULT 0.00,
  `absent_days` decimal(8,2) NOT NULL DEFAULT 0.00,
  `late_deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `undertime_deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `absent_deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sss_contribution` decimal(10,2) NOT NULL DEFAULT 0.00,
  `philhealth_contribution` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pagibig_contribution` decimal(10,2) NOT NULL DEFAULT 0.00,
  `withholding_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_deductions_notes` text DEFAULT NULL,
  `gross_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','pending_approval','approved','paid') NOT NULL DEFAULT 'draft',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `calculation_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payrolls`
--

INSERT INTO `payrolls` (`id`, `user_id`, `pay_period_year`, `pay_period_month`, `pay_period_start`, `pay_period_end`, `daily_rate`, `working_days`, `days_worked`, `basic_pay`, `regular_overtime_hours`, `holiday_overtime_hours`, `regular_overtime_pay`, `holiday_overtime_pay`, `total_overtime_pay`, `late_hours`, `undertime_hours`, `absent_days`, `late_deductions`, `undertime_deductions`, `absent_deductions`, `sss_contribution`, `philhealth_contribution`, `pagibig_contribution`, `withholding_tax`, `other_deductions`, `other_deductions_notes`, `gross_pay`, `total_deductions`, `net_pay`, `status`, `approved_by`, `approved_at`, `paid_at`, `calculation_notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2025', 12, '2025-12-01', '2025-12-31', 600.00, 23, 1, 600.00, 2.00, 0.00, 187.50, 0.00, 187.50, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 180.00, 150.00, 15.75, 0.00, 0.00, NULL, 787.50, 345.75, 441.75, 'approved', 2, '2025-12-03 05:40:34', NULL, NULL, '2025-12-03 05:12:46', '2025-12-03 05:40:34'),
(2, 1, '2026', 3, '2026-03-01', '2026-03-31', 600.00, 22, 1, 600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 180.00, 150.00, 12.00, 0.00, 0.00, NULL, 600.00, 342.00, 258.00, 'approved', 2, '2026-03-03 13:09:14', NULL, NULL, '2026-03-03 13:08:47', '2026-03-03 13:09:14'),
(3, 7, '2026', 3, '2026-03-01', '2026-03-31', 600.00, 22, 1, 600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 180.00, 150.00, 12.00, 0.00, 0.00, NULL, 600.00, 342.00, 258.00, 'approved', 2, '2026-03-03 13:09:14', NULL, NULL, '2026-03-03 13:08:47', '2026-03-03 13:09:14'),
(4, 1, '2026', 1, '2026-01-01', '2026-01-31', 600.00, 22, 2, 1200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 180.00, 150.00, 24.00, 0.00, 0.00, NULL, 1200.00, 354.00, 846.00, 'approved', 2, '2026-03-03 13:09:14', NULL, NULL, '2026-03-03 13:08:56', '2026-03-03 13:09:14'),
(5, 7, '2026', 1, '2026-01-01', '2026-01-31', 600.00, 22, 1, 600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 180.00, 150.00, 12.00, 0.00, 0.00, NULL, 600.00, 342.00, 258.00, 'approved', 2, '2026-03-03 13:09:14', NULL, NULL, '2026-03-03 13:08:56', '2026-03-03 13:09:14');

-- --------------------------------------------------------

--
-- Table structure for table `payslips`
--

CREATE TABLE `payslips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `payslip_number` varchar(255) NOT NULL,
  `pay_period_year` year(4) NOT NULL,
  `pay_period_month` tinyint(3) UNSIGNED NOT NULL,
  `pay_period_start` date NOT NULL,
  `pay_period_end` date NOT NULL,
  `generated_date` date NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `employee_position` varchar(255) DEFAULT NULL,
  `employee_department` varchar(255) DEFAULT NULL,
  `basic_pay` decimal(10,2) NOT NULL,
  `total_overtime_pay` decimal(10,2) NOT NULL,
  `gross_pay` decimal(10,2) NOT NULL,
  `total_deductions` decimal(10,2) NOT NULL,
  `net_pay` decimal(10,2) NOT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `is_downloaded` tinyint(1) NOT NULL DEFAULT 0,
  `first_downloaded_at` timestamp NULL DEFAULT NULL,
  `download_count` int(11) NOT NULL DEFAULT 0,
  `status` enum('generated','sent','viewed') NOT NULL DEFAULT 'generated',
  `sent_at` timestamp NULL DEFAULT NULL,
  `viewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payslips`
--

INSERT INTO `payslips` (`id`, `payroll_id`, `user_id`, `payslip_number`, `pay_period_year`, `pay_period_month`, `pay_period_start`, `pay_period_end`, `generated_date`, `employee_name`, `employee_id`, `employee_position`, `employee_department`, `basic_pay`, `total_overtime_pay`, `gross_pay`, `total_deductions`, `net_pay`, `pdf_path`, `is_downloaded`, `first_downloaded_at`, `download_count`, `status`, `sent_at`, `viewed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'PS-202512-emp01-001', '2025', 12, '2025-12-01', '2025-12-31', '2025-12-03', 'Christian Aring', 'emp01', 'Employee', 'General', 600.00, 187.50, 787.50, 345.75, 441.75, NULL, 1, '2026-02-25 14:30:33', 4, 'viewed', NULL, '2025-12-03 05:40:47', '2025-12-03 05:40:34', '2026-02-25 14:40:24'),
(2, 2, 1, 'PS-202603-emp01-001', '2026', 3, '2026-03-01', '2026-03-31', '2026-03-03', 'Christian Aring', 'emp01', 'Employee', 'General', 600.00, 0.00, 600.00, 342.00, 258.00, NULL, 0, NULL, 0, 'generated', NULL, NULL, '2026-03-03 13:09:14', '2026-03-03 13:09:14'),
(3, 3, 7, 'PS-202603-emp02-002', '2026', 3, '2026-03-01', '2026-03-31', '2026-03-03', 'Channsn', 'emp02', 'Employee', 'General', 600.00, 0.00, 600.00, 342.00, 258.00, NULL, 0, NULL, 0, 'viewed', NULL, '2026-03-03 13:09:39', '2026-03-03 13:09:14', '2026-03-03 13:09:39'),
(4, 4, 1, 'PS-202601-emp01-001', '2026', 1, '2026-01-01', '2026-01-31', '2026-03-03', 'Christian Aring', 'emp01', 'Employee', 'General', 1200.00, 0.00, 1200.00, 354.00, 846.00, NULL, 0, NULL, 0, 'generated', NULL, NULL, '2026-03-03 13:09:14', '2026-03-03 13:09:14'),
(5, 5, 7, 'PS-202601-emp02-002', '2026', 1, '2026-01-01', '2026-01-31', '2026-03-03', 'Channsn', 'emp02', 'Employee', 'General', 600.00, 0.00, 600.00, 342.00, 258.00, NULL, 0, NULL, 0, 'viewed', NULL, '2026-03-03 13:09:55', '2026-03-03 13:09:14', '2026-03-03 13:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1nfhgsI4csrcmuptTl1iefjraFkyKdtl4NvFvA2i', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOVRGRWZnQnM5dWtqTThQTzg1dTZXNDk1cVJGZDVGeGQ0TWF1cUduVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly91bmZlc3RlcmluZy1jaW5lcmVvdXMtamF4Lm5ncm9rLWZyZWUuZGV2L2FwaS9jaGF0Ym90L2hpc3RvcnkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1772548957),
('2h5b9Fqrg3FP9BJMP4RecNC8WxXANLmQb9yFpEIr', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieHlBckhPeTBpaVpxU0ptOHR3aXUzbHpFSURuR1BmRlNFT1RTYmJhQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTc6Imh0dHA6Ly91bmZlc3RlcmluZy1jaW5lcmVvdXMtamF4Lm5ncm9rLWZyZWUuZGV2L2Rhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1772543518),
('F2KbZ1hZNnLlPn3I9HIHa9N6eJyx9EzQ9OZYQbIU', NULL, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Brave/1 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMDRUeG90RjJuTjA3OWlFWTJESnl1RlhjakRMdEM3U1dJRlFmUGludSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly91bmZlc3RlcmluZy1jaW5lcmVvdXMtamF4Lm5ncm9rLWZyZWUuZGV2L2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1772543630),
('g5BD41Jvc5MWJQRA96DIRWxWwYB9Yh9dFbMvjOqj', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidnllZnA1emtiWVhNZUNuOUxzWmp1RENnb0lHNHBhSm9aNG82RkRURSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly91bmZlc3RlcmluZy1jaW5lcmVvdXMtamF4Lm5ncm9rLWZyZWUuZGV2L2FwaS9jaGF0Ym90L2hpc3RvcnkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo3O30=', 1772548853),
('n6GMN9i6jCQkLl0YDWe4fGUMNCn1mXIyUD2hDdIJ', NULL, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 [FBAN/FBIOS;FBAV/550.0.0.28.106;FBBV/890844927;FBDV/iPhone12,1;FBMD/iPhone;FBSN/iOS;FBSV/18.6.2;FBSS/2;FBCR/;FBID/phone;FBLC/en_US;FBOP/80]', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ291TU45UHdHQUVMYlJuOUNKSEpEa2Zmd2Mzb0ZtQW52VlBhenlNbCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1NzoiaHR0cDovL3VuZmVzdGVyaW5nLWNpbmVyZW91cy1qYXgubmdyb2stZnJlZS5kZXYvZGFzaGJvYXJkIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly91bmZlc3RlcmluZy1jaW5lcmVvdXMtamF4Lm5ncm9rLWZyZWUuZGV2L2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1772543597);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `lat` decimal(10,7) NOT NULL,
  `lng` decimal(10,7) NOT NULL,
  `radius_meters` int(10) UNSIGNED NOT NULL DEFAULT 50,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `name`, `lat`, `lng`, `radius_meters`, `active`, `created_at`, `updated_at`) VALUES
(6, 'chan', 14.6523284, 121.0454846, 100, 1, '2026-01-04 05:03:08', '2026-01-04 05:06:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'employee',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `employee_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `gender`, `address`, `profile_photo`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `google_id`, `employee_id`) VALUES
(1, 'Christian Aring', 'christianaring6@gmail.com', '+639382393116', 'male', 'Yakal st', 'profile_photos/1764610263_1.jpg', 'employee', '2025-12-01 14:04:16', '$2y$12$QrxwAF7GZYIUtHx6ARSXM.R5IZWsXwo6mWY6jMBQW7Dc7AB/FbpNm', 'mSAeo6A5ZpBDm5J47kHHFu0fXhYb8B7YGlok6ohQPDG6IjiQKV7TcXQ9xRdA', '2025-12-01 14:04:16', '2025-12-02 13:04:13', '117961855686338940761', 'emp01'),
(2, 'HR', 'luffylines@gmail.com', '+639760078007', 'male', '20 yaksla st', 'profile_photos/1764740292_2.jpg', 'hr', '2025-12-01 14:04:17', '$2y$12$T2VULn93Hx8nPwIJgAb2gOwfFzQjrSLdvczWzChEzmccfrDOWS6YS', '3WYjF0vfPIHinA0xAyqn8rMBLj99eITybJK8S7hX7Ks6mFGmO69RCHrLmVGH', '2025-12-01 14:04:17', '2026-01-24 06:00:00', '103530698650697730442', 'hr01'),
(4, 'Admin', 'chba.aring.sjc@phinmaed.com', '+639288856264', 'male', 'yaksl st', 'profile_photos/1764598382_4.jpg', 'admin', '2025-12-01 14:11:15', '$2y$12$u0rRjg.rjKuI/8hkxk36PeFg22bSoK7hViKdT3RyHq4dnW2EdUk/.', NULL, '2025-12-01 14:11:15', '2025-12-02 10:40:27', '102552381699817374217', 'admin01'),
(7, 'Channsn', 'channics@gmail.com', '+639278856264', 'male', 'adsdsad st', NULL, 'employee', '2025-12-03 09:31:55', '$2y$12$Ys0YuCsG/hTIOIEBbIAbNOROt0klUD4E4xKa.JV8PsjpwqMOA5A7W', 'wKxiu6lludjcvrBCjomVu4EyshGTkFHNak3qDFpCape5lW7JpRE8yO3VGRPY', '2025-12-03 09:12:06', '2025-12-03 09:31:55', NULL, 'emp02');

-- --------------------------------------------------------

--
-- Table structure for table `user_online_status`
--

CREATE TABLE `user_online_status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_online` tinyint(1) NOT NULL DEFAULT 0,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `work_schedules`
--

CREATE TABLE `work_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_by` bigint(20) UNSIGNED NOT NULL,
  `schedule_date` date NOT NULL,
  `shift_start` time NOT NULL,
  `shift_end` time NOT NULL,
  `shift_type` varchar(255) NOT NULL DEFAULT 'regular',
  `location` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('assigned','acknowledged','completed','missed') NOT NULL DEFAULT 'assigned',
  `acknowledged_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `store_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `work_schedules`
--

INSERT INTO `work_schedules` (`id`, `employee_id`, `assigned_by`, `schedule_date`, `shift_start`, `shift_end`, `shift_type`, `location`, `notes`, `status`, `acknowledged_at`, `created_at`, `updated_at`, `store_id`) VALUES
(1, 1, 2, '2026-01-01', '07:00:00', '17:00:00', 'regular', 'OFFICE', NULL, 'acknowledged', '2025-12-02 08:38:54', '2025-12-02 08:36:51', '2025-12-02 08:38:54', NULL),
(2, 1, 2, '2025-12-02', '08:00:00', '17:00:00', 'regular', NULL, NULL, 'acknowledged', '2025-12-02 09:21:38', '2025-12-02 09:17:55', '2025-12-02 09:21:38', NULL),
(112, 1, 2, '2025-12-04', '08:00:00', '17:00:00', 'regular', NULL, NULL, 'assigned', NULL, '2025-12-03 07:50:31', '2025-12-03 07:50:31', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_action_type_index` (`user_id`,`action_type`),
  ADD KEY `activity_logs_created_at_index` (`created_at`);

--
-- Indexes for table `allowed_networks`
--
ALTER TABLE `allowed_networks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attendances_user_id_date_unique` (`user_id`,`date`),
  ADD KEY `attendances_created_by_foreign` (`created_by`),
  ADD KEY `attendances_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chats_user_id_status_index` (`user_id`,`status`),
  ADD KEY `chats_hr_user_id_status_index` (`hr_user_id`,`status`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_chat_id_created_at_index` (`chat_id`,`created_at`),
  ADD KEY `chat_messages_user_id_is_read_index` (`user_id`,`is_read`);

--
-- Indexes for table `employee_profiles`
--
ALTER TABLE `employee_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_profiles_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `holidays_date_country_index` (`date`,`country`),
  ADD KEY `holidays_country_is_active_index` (`country`,`is_active`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_requests_user_id_foreign` (`user_id`),
  ADD KEY `leave_requests_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otps_user_id_foreign` (`user_id`);

--
-- Indexes for table `overtime_requests`
--
ALTER TABLE `overtime_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `overtime_requests_user_id_foreign` (`user_id`),
  ADD KEY `overtime_requests_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payrolls_user_id_pay_period_year_pay_period_month_unique` (`user_id`,`pay_period_year`,`pay_period_month`),
  ADD KEY `payrolls_approved_by_foreign` (`approved_by`),
  ADD KEY `payrolls_user_id_pay_period_year_pay_period_month_index` (`user_id`,`pay_period_year`,`pay_period_month`),
  ADD KEY `payrolls_status_index` (`status`);

--
-- Indexes for table `payslips`
--
ALTER TABLE `payslips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payslips_payslip_number_unique` (`payslip_number`),
  ADD KEY `payslips_payroll_id_foreign` (`payroll_id`),
  ADD KEY `payslips_user_id_pay_period_year_pay_period_month_index` (`user_id`,`pay_period_year`,`pay_period_month`),
  ADD KEY `payslips_status_index` (`status`),
  ADD KEY `payslips_payslip_number_index` (`payslip_number`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_online_status`
--
ALTER TABLE `user_online_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_online_status_user_id_unique` (`user_id`),
  ADD KEY `user_online_status_user_id_is_online_index` (`user_id`,`is_online`);

--
-- Indexes for table `work_schedules`
--
ALTER TABLE `work_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_schedules_employee_id_schedule_date_index` (`employee_id`,`schedule_date`),
  ADD KEY `work_schedules_assigned_by_schedule_date_index` (`assigned_by`,`schedule_date`),
  ADD KEY `work_schedules_store_id_foreign` (`store_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `allowed_networks`
--
ALTER TABLE `allowed_networks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `employee_profiles`
--
ALTER TABLE `employee_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `overtime_requests`
--
ALTER TABLE `overtime_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payslips`
--
ALTER TABLE `payslips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_online_status`
--
ALTER TABLE `user_online_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_schedules`
--
ALTER TABLE `work_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `attendances_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_hr_user_id_foreign` FOREIGN KEY (`hr_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `employee_profiles`
--
ALTER TABLE `employee_profiles`
  ADD CONSTRAINT `employee_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leave_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `otps`
--
ALTER TABLE `otps`
  ADD CONSTRAINT `otps_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `overtime_requests`
--
ALTER TABLE `overtime_requests`
  ADD CONSTRAINT `overtime_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `overtime_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD CONSTRAINT `payrolls_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payrolls_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payslips`
--
ALTER TABLE `payslips`
  ADD CONSTRAINT `payslips_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `payrolls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payslips_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_online_status`
--
ALTER TABLE `user_online_status`
  ADD CONSTRAINT `user_online_status_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `work_schedules`
--
ALTER TABLE `work_schedules`
  ADD CONSTRAINT `work_schedules_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_schedules_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_schedules_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
