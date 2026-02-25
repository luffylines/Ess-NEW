-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 09:41 AM
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

-- Note: Table structures are already created by migrations, so we only import data

--
-- Dumping data for table `activity_logs`
--

TRUNCATE TABLE `activity_logs`;
INSERT INTO `activity_logs` (`id`, `user_id`, `action_type`, `description`, `ip_address`, `user_agent`, `properties`, `created_at`, `updated_at`) VALUES
(1, 1, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 14:05:01', '2025-12-01 14:05:01'),
(2, 4, 'login', 'User logged in', '136.158.37.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '[]', '2025-12-01 14:11:59', '2025-12-01 14:11:59');

--
-- Dumping data for table `allowed_networks`
--

TRUNCATE TABLE `allowed_networks`;
INSERT INTO `allowed_networks` (`id`, `name`, `ip_ranges`, `active`, `created_at`, `updated_at`) VALUES
(10, 'Chan 2i', '[\"209.35.9.1.1\"]', 1, '2025-12-01 18:18:04', '2025-12-01 18:20:49');

--
-- Dumping data for table `users`
--

TRUNCATE TABLE `users`;
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `gender`, `address`, `profile_photo`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `google_id`, `employee_id`) VALUES
(1, 'Christian Aring', 'christianaring6@gmail.com', '+639382393116', 'male', 'Yakal st', 'profile_photos/1764610263_1.jpg', 'employee', '2025-12-01 14:04:16', '$2y$12$QrxwAF7GZYIUtHx6ARSXM.R5IZWsXwo6mWY6jMBQW7Dc7AB/FbpNm', '2Q0kOAkU4kRqYGLSQECSKak9NLxAFTzkucwP14VzSvu6ovi2YOlCa6GDaRYO', '2025-12-01 14:04:16', '2025-12-02 13:04:13', '117961855686338940761', 'emp01'),
(2, 'HR', 'luffylines@gmail.com', '+639760078007', 'male', '20 yaksla st', 'profile_photos/1764740292_2.jpg', 'hr', '2025-12-01 14:04:17', '$2y$12$T2VULn93Hx8nPwIJgAb2gOwfFzQjrSLdvczWzChEzmccfrDOWS6YS', 'to3EeumjmxpjVYlWE0DmbLMtIOkqJbmouW0o1EUU1m3JJyZuf3Q9a041wkuZ', '2025-12-01 14:04:17', '2025-12-03 05:38:13', NULL, 'hr01'),
(4, 'Admin', 'chba.aring.sjc@phinmaed.com', '+639288856264', 'male', 'yaksl st', 'profile_photos/1764598382_4.jpg', 'admin', '2025-12-01 14:11:15', '$2y$12$u0rRjg.rjKuI/8hkxk36PeFg22bSoK7hViKdT3RyHq4dnW2EdUk/.', NULL, '2025-12-01 14:11:15', '2025-12-02 10:40:27', '102552381699817374217', 'admin01'),
(7, 'Channsn', 'channics@gmail.com', '+639278856264', 'male', 'adsdsad st', NULL, 'employee', '2025-12-03 09:31:55', '$2y$12$Ys0YuCsG/hTIOIEBbIAbNOROt0klUD4E4xKa.JV8PsjpwqMOA5A7W', 'K8k89SxSldR3g5jJb3recVFD8avPmCxNOvj1uo7Irmyf1h5XyyjZQICL6XKI', '2025-12-03 09:12:06', '2025-12-03 09:31:55', NULL, 'emp02');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
