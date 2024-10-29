-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2024 at 10:06 AM
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
-- Database: `bind_together`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 3,
  `sport_id` bigint(20) UNSIGNED DEFAULT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `venue` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `target_player` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_registrations`
--

CREATE TABLE `activity_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `height` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `emergency_contact` varchar(255) NOT NULL,
  `relationship` varchar(255) NOT NULL,
  `certificate_of_registration` varchar(255) NOT NULL,
  `photo_copy_id` varchar(255) NOT NULL,
  `other_file` varchar(255) DEFAULT NULL,
  `parent_consent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('christianlumba23@gmail.com|127.0.0.1', 'i:1;', 1730182381),
('christianlumba23@gmail.com|127.0.0.1:timer', 'i:1730182381;', 1730182381),
('eztesoro@bpsu.edu.ph|127.0.0.1', 'i:1;', 1730185764),
('eztesoro@bpsu.edu.ph|127.0.0.1:timer', 'i:1730185764;', 1730185764),
('fjmanalo@bpsu.edu.ph|127.0.0.1', 'i:1;', 1730185750),
('fjmanalo@bpsu.edu.ph|127.0.0.1:timer', 'i:1730185750;', 1730185750),
('jifreyes@bpsu.edu.ph|127.0.0.1', 'i:1;', 1730182296),
('jifreyes@bpsu.edu.ph|127.0.0.1:timer', 'i:1730182296;', 1730182296),
('mtpabiton@bpsu.edu.ph|127.0.0.1', 'i:2;', 1730182268),
('mtpabiton@bpsu.edu.ph|127.0.0.1:timer', 'i:1730182268;', 1730182268),
('student@bpsu.edu.ph|127.0.0.1', 'i:1;', 1730179457),
('student@bpsu.edu.ph|127.0.0.1:timer', 'i:1730179457;', 1730179457);

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
-- Table structure for table `campuses`
--

CREATE TABLE `campuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ch_favorites`
--

CREATE TABLE `ch_favorites` (
  `id` char(36) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `favorite_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ch_messages`
--

CREATE TABLE `ch_messages` (
  `id` char(36) NOT NULL,
  `from_id` bigint(20) NOT NULL,
  `to_id` bigint(20) NOT NULL,
  `body` varchar(5000) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `newsfeed_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `newsfeed_id`, `user_id`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 'sadasd', 1, '2024-10-29 00:03:52', '2024-10-29 00:03:52');

-- --------------------------------------------------------

--
-- Table structure for table `comment_likes`
--

CREATE TABLE `comment_likes` (
  `comments_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deleted_comments`
--

CREATE TABLE `deleted_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `comments_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `other_reason` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deleted_posts`
--

CREATE TABLE `deleted_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `newsfeed_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `other_reason` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
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
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
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
(1, '0001_01_01_000000_create_sports_table', 1),
(2, '0001_01_01_000001_create_organizations_table', 1),
(3, '0001_01_01_000002_create_courses_table', 1),
(4, '0001_01_01_000003_create_campuses_table', 1),
(5, '0001_01_01_000004_create_programs_table', 1),
(6, '0001_01_01_000006_create_users_table', 1),
(7, '0001_01_01_000011_create_cache_table', 1),
(8, '0001_01_01_000012_create_jobs_table', 1),
(9, '2024_10_14_231259_create_permission_tables', 1),
(10, '2024_10_16_011347_create_newsfeeds_table', 1),
(11, '2024_10_16_035153_create_newsfeed_files_table', 1),
(12, '2024_10_16_055722_create_comments_table', 1),
(13, '2024_10_16_064051_create_comment_likes_table', 1),
(14, '2024_10_16_075203_create_reported_comments_table', 1),
(15, '2024_10_17_072658_create_activities_table', 1),
(16, '2024_10_18_002250_create_activity_registrations_table', 1),
(17, '2024_10_18_062115_add_status_to_activity_registrations_table', 1),
(18, '2024_10_20_073846_create_deleted_posts_table', 1),
(19, '2024_10_20_080708_create_deleted_comments_table', 1),
(20, '2024_10_20_081457_create_reported_posts_table', 1),
(21, '2024_10_20_141203_create_feedback_table', 1),
(22, '2024_10_21_062656_make_attachment_nullable_activities_table', 1),
(23, '2024_10_21_071242_add_decline_reason_to_reported_comments_table', 1),
(24, '2024_10_22_020152_change_description_to_nullable_in_newsfeeds_table', 1),
(25, '2024_10_22_021029_create_newsfeed_likes_table', 1),
(26, '2024_10_22_043358_create_practices_table', 1),
(27, '2024_10_23_032158_add_campus_id_and_type_to_newsfeeds_table', 1),
(28, '2024_10_27_133531_add_profile_completion_to_users_table', 1),
(29, '2024_10_27_999999_add_active_status_to_users', 1),
(30, '2024_10_27_999999_add_avatar_to_users', 1),
(31, '2024_10_27_999999_add_dark_mode_to_users', 1),
(32, '2024_10_27_999999_add_messenger_color_to_users', 1),
(33, '2024_10_27_999999_create_chatify_favorites_table', 1),
(34, '2024_10_27_999999_create_chatify_messages_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 2),
(4, 'App\\Models\\User', 5),
(5, 'App\\Models\\User', 4),
(6, 'App\\Models\\User', 6),
(6, 'App\\Models\\User', 8);

-- --------------------------------------------------------

--
-- Table structure for table `newsfeeds`
--

CREATE TABLE `newsfeeds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `campus_id` bigint(20) UNSIGNED DEFAULT NULL,
  `target_player` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `newsfeeds`
--

INSERT INTO `newsfeeds` (`id`, `description`, `user_id`, `status`, `created_at`, `updated_at`, `campus_id`, `target_player`) VALUES
(1, 'dsadsa', 5, 1, '2024-10-29 00:03:39', '2024-10-29 00:03:39', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `newsfeed_files`
--

CREATE TABLE `newsfeed_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `newsfeed_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsfeed_likes`
--

CREATE TABLE `newsfeed_likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `newsfeed_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'BPSU Chorale', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(2, 'Griffin Prime Groovers', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(3, 'Stallion Dance Squad', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(4, 'BPSU Chorale', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(5, 'Griffin Prime Groovers', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(6, 'Stallion Dance Squad', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(7, 'BPSU Chorale', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(8, 'Griffin Prime Groovers', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(9, 'Stallion Dance Squad', '2024-10-28 23:19:25', '2024-10-28 23:19:25');

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `practices`
--

CREATE TABLE `practices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `campus_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reported_comments`
--

CREATE TABLE `reported_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `comments_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) NOT NULL,
  `other_reason` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `declined_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reported_posts`
--

CREATE TABLE `reported_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `newsfeed_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) NOT NULL,
  `other_reason` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2024-10-28 19:25:22', '2024-10-28 19:25:22'),
(2, 'admin_org', 'web', '2024-10-28 19:25:22', '2024-10-28 19:25:22'),
(3, 'admin_sport', 'web', '2024-10-28 19:25:22', '2024-10-28 19:25:22'),
(4, 'adviser', 'web', '2024-10-28 19:25:22', '2024-10-28 19:25:22'),
(5, 'coach', 'web', '2024-10-28 19:25:22', '2024-10-28 19:25:22'),
(6, 'student', 'web', '2024-10-28 19:25:22', '2024-10-28 19:25:22');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('qwcwzfOAq5EnKEXzGPYwKdE172W1hTGwgDa4TdXc', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiajY0cDFtNVpTR0VQZmZmQTRBTmpnYmZXYVFDbWVBUlR4N0FKV1VWZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9maWxlIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3MzAxODczNzc7fXM6NToiYWxlcnQiO2E6MDp7fX0=', 1730191305);

-- --------------------------------------------------------

--
-- Table structure for table `sports`
--

CREATE TABLE `sports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sports`
--

INSERT INTO `sports` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Volleyball', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(2, 'Basketball', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(3, 'Swimming', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(4, 'Badminton', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(5, 'Table Tennis', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(6, 'Chess', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(7, 'Sepak Takraw', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(8, 'Taekwondo', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(9, 'Arnis', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(10, 'Running', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(11, 'Darts', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(12, 'Javelin Throw', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(13, 'Shot put', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(14, 'E-Sports (MLBB)', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(15, 'Dance Sports', '2024-10-28 21:20:40', '2024-10-28 21:20:40'),
(16, 'Volleyball', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(17, 'Basketball', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(18, 'Swimming', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(19, 'Badminton', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(20, 'Table Tennis', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(21, 'Chess', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(22, 'Sepak Takraw', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(23, 'Taekwondo', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(24, 'Arnis', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(25, 'Running', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(26, 'Darts', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(27, 'Javelin Throw', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(28, 'Shot put', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(29, 'E-Sports (MLBB)', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(30, 'Dance Sports', '2024-10-28 21:21:12', '2024-10-28 21:21:12'),
(31, 'Volleyball', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(32, 'Basketball', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(33, 'Swimming', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(34, 'Badminton', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(35, 'Table Tennis', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(36, 'Chess', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(37, 'Sepak Takraw', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(38, 'Taekwondo', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(39, 'Arnis', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(40, 'Running', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(41, 'Darts', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(42, 'Javelin Throw', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(43, 'Shot put', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(44, 'E-Sports (MLBB)', '2024-10-28 23:19:25', '2024-10-28 23:19:25'),
(45, 'Dance Sports', '2024-10-28 23:19:25', '2024-10-28 23:19:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_number` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `birthdate` varchar(255) DEFAULT NULL,
  `gender` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` int(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `year_level` int(11) DEFAULT NULL,
  `sport_id` bigint(20) UNSIGNED DEFAULT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `campus_id` bigint(20) UNSIGNED DEFAULT NULL,
  `program_id` bigint(20) UNSIGNED DEFAULT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_completed` int(11) DEFAULT 0,
  `active_status` tinyint(1) NOT NULL DEFAULT 0,
  `dark_mode` tinyint(1) NOT NULL DEFAULT 0,
  `messenger_color` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `student_number`, `firstname`, `middlename`, `lastname`, `suffix`, `birthdate`, `gender`, `address`, `contact`, `email`, `year_level`, `sport_id`, `course_id`, `campus_id`, `program_id`, `organization_id`, `avatar`, `email_verified_at`, `password`, `is_active`, `remember_token`, `created_at`, `updated_at`, `is_completed`, `active_status`, `dark_mode`, `messenger_color`) VALUES
(1, NULL, 'Vincenzo', 'Burdette', 'Huel', NULL, '2023-12-01 04:26:06', 'Male', '5566 Schmidt Via Suite 788\nLake Sheridan, WY 89103-0398', 2147483647, 'superadmin@bpsu.edu.ph', 1, 6, NULL, NULL, NULL, 3, 'asd', '2024-10-28 21:20:42', '$2y$12$cutvKMgqfEwJSqKGWcMvv.Z7FIemyEaN/7E0L8u6IA8K3CtuEAZW.', 1, 'GUtYh6sZ3M', '2024-10-28 21:20:42', '2024-10-28 21:20:42', 0, 0, 0, NULL),
(2, NULL, 'Una', 'Skye', 'Goldner', NULL, '2023-05-18', 'Male', '20850 Feeney LandKeelingstad, MA 64520', 1, 'adminsport@bpsu.edu.ph', NULL, NULL, NULL, NULL, NULL, NULL, 'asd', '2024-10-28 21:20:43', '$2y$12$4q.9xRMSwq4NlX5CB5Bmw.mVJ0InXtDYwLqzpAg6z00pU/cENffiu', 1, 'cpJPRha04dOzug6NFHc1hNGfdRVanyQjeZK3A5pRS2Ajn6rToObcdUmCP9k3', '2024-10-28 21:20:43', '2024-10-28 22:58:22', 1, 0, 0, NULL),
(3, NULL, 'Deion', 'Burley', 'Feeney', NULL, '2023-06-06 22:01:02', 'Male', '4543 Nedra Union\nWest Kirstenview, HI 34139-9913', 1, 'adminorg@bpsu.edu.ph', 4, 14, NULL, NULL, NULL, 3, 'asd', '2024-10-28 21:20:43', '$2y$12$m355xLFY42A/oazXHoW90uVgdbhRGAtxTMXirIGR4XMcexXftWt7O', 1, 'yXqhHmIGx0', '2024-10-28 21:20:43', '2024-10-28 21:20:43', 0, 0, 0, NULL),
(4, NULL, 'Edyth', 'Waylon', 'Emmerich', NULL, '2023-01-02', 'Male', '160 Witting Lodge Apt. 491North Ivy, ID 12280-1462', 1213123123, 'coach@bpsu.edu.ph', NULL, NULL, NULL, NULL, NULL, NULL, 'asd', '2024-10-28 21:20:44', '$2y$12$Ns8bEpavKck1a7eDxLQu7OQzlwPssbp97IUdR69CkxrCK5Nh5jcY.', 1, 'U5UxjqeA220eH9LelW6NHKMj8Lzw6SwxFQHiPwEnOKCTVteXRVfPaTK4HStG', '2024-10-28 21:20:44', '2024-10-28 23:15:59', 1, 0, 0, NULL),
(5, NULL, 'Matteo', 'Heidi', 'Mayer', NULL, '2024-01-23', 'Male', '32905 Nader Grove Suite 505Bufordside, MN 80576-8981', 2147483647, 'adviser@bpsu.edu.ph', NULL, NULL, NULL, NULL, NULL, NULL, 'avatar/z68pCPJWFrQdutrIWJGGfRidajS5n4oDFw9qgINM.jpg', '2024-10-28 21:20:44', '$2y$12$/0/KTtHT2wp1yUdfatMrNeLAoZI3n8KqqDAOh30QzEOjkc70RZQty', 1, 'vBzfVMMXguee9doftmPaKQPmFIS5UcPcuCg0iTs8x0ZzlYVt2DAf5ZcpsZH6', '2024-10-28 21:20:44', '2024-10-29 00:36:22', 1, 0, 0, NULL),
(6, NULL, 'Sage', 'Autumn', 'Hickle', NULL, '2023-12-10 09:20:45', 'Male', '540 Devyn Forks\nNew Cathyland, TN 96955', 920, 'student@bpsu.edu.ph', 4, 10, NULL, NULL, NULL, 1, 'asd', '2024-10-28 21:20:45', '$2y$12$r2qPw5F31bMAz4vEt9UvKuSyHJo6j4GsNsU9Nh9ytWv5pf4yExFsq', 1, '12UjFLqkEw', '2024-10-28 21:20:45', '2024-10-28 21:20:45', 0, 0, 0, NULL),
(8, '2132121', 'test', 'sample', 'del mar', NULL, '2001-10-09', 'Male', 'sadsa', 2147483647, 'christianlumba23@bpsu.edu.ph', 1, NULL, NULL, NULL, NULL, NULL, 'avatars/f1zJyEfEQN7OgTOVj2ITPjWCOtYCiVdqYI48Ms2T.png', '2024-10-28 21:20:45', '$2y$12$Uz4SUjKNULUFtg0piRuND.Ok45vsyXpxjNZjJaRzcEWsUY0THwIzu', 1, 'YUGHYXIAgo8wi1xpAskuEfVP1qB5bUmhc79bgjK0RcIGdjoKD25m8fB2hjQf', '2024-10-28 21:24:20', '2024-10-28 23:35:12', 1, 0, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_user_id_foreign` (`user_id`),
  ADD KEY `activities_sport_id_foreign` (`sport_id`),
  ADD KEY `activities_organization_id_foreign` (`organization_id`);

--
-- Indexes for table `activity_registrations`
--
ALTER TABLE `activity_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_registrations_activity_id_foreign` (`activity_id`),
  ADD KEY `activity_registrations_user_id_foreign` (`user_id`);

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
-- Indexes for table `campuses`
--
ALTER TABLE `campuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_favorites`
--
ALTER TABLE `ch_favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_messages`
--
ALTER TABLE `ch_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_newsfeed_id_foreign` (`newsfeed_id`),
  ADD KEY `comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD KEY `comment_likes_comments_id_foreign` (`comments_id`),
  ADD KEY `comment_likes_user_id_foreign` (`user_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deleted_comments`
--
ALTER TABLE `deleted_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deleted_comments_comments_id_foreign` (`comments_id`),
  ADD KEY `deleted_comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `deleted_posts`
--
ALTER TABLE `deleted_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deleted_posts_newsfeed_id_foreign` (`newsfeed_id`),
  ADD KEY `deleted_posts_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_user_id_foreign` (`user_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `newsfeeds`
--
ALTER TABLE `newsfeeds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `newsfeeds_user_id_foreign` (`user_id`),
  ADD KEY `newsfeeds_campus_id_foreign` (`campus_id`);

--
-- Indexes for table `newsfeed_files`
--
ALTER TABLE `newsfeed_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `newsfeed_files_newsfeed_id_foreign` (`newsfeed_id`);

--
-- Indexes for table `newsfeed_likes`
--
ALTER TABLE `newsfeed_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `newsfeed_likes_newsfeed_id_foreign` (`newsfeed_id`),
  ADD KEY `newsfeed_likes_user_id_foreign` (`user_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `practices`
--
ALTER TABLE `practices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `practices_activity_id_foreign` (`activity_id`),
  ADD KEY `practices_user_id_foreign` (`user_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `programs_campus_id_foreign` (`campus_id`);

--
-- Indexes for table `reported_comments`
--
ALTER TABLE `reported_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reported_comments_comments_id_foreign` (`comments_id`),
  ADD KEY `reported_comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `reported_posts`
--
ALTER TABLE `reported_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reported_posts_newsfeed_id_foreign` (`newsfeed_id`),
  ADD KEY `reported_posts_user_id_foreign` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sports`
--
ALTER TABLE `sports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_sport_id_foreign` (`sport_id`),
  ADD KEY `users_course_id_foreign` (`course_id`),
  ADD KEY `users_campus_id_foreign` (`campus_id`),
  ADD KEY `users_program_id_foreign` (`program_id`),
  ADD KEY `users_organization_id_foreign` (`organization_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_registrations`
--
ALTER TABLE `activity_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campuses`
--
ALTER TABLE `campuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deleted_comments`
--
ALTER TABLE `deleted_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deleted_posts`
--
ALTER TABLE `deleted_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `newsfeeds`
--
ALTER TABLE `newsfeeds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `newsfeed_files`
--
ALTER TABLE `newsfeed_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newsfeed_likes`
--
ALTER TABLE `newsfeed_likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `practices`
--
ALTER TABLE `practices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reported_comments`
--
ALTER TABLE `reported_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reported_posts`
--
ALTER TABLE `reported_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sports`
--
ALTER TABLE `sports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activities_sport_id_foreign` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_registrations`
--
ALTER TABLE `activity_registrations`
  ADD CONSTRAINT `activity_registrations_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_newsfeed_id_foreign` FOREIGN KEY (`newsfeed_id`) REFERENCES `newsfeeds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD CONSTRAINT `comment_likes_comments_id_foreign` FOREIGN KEY (`comments_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deleted_comments`
--
ALTER TABLE `deleted_comments`
  ADD CONSTRAINT `deleted_comments_comments_id_foreign` FOREIGN KEY (`comments_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deleted_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deleted_posts`
--
ALTER TABLE `deleted_posts`
  ADD CONSTRAINT `deleted_posts_newsfeed_id_foreign` FOREIGN KEY (`newsfeed_id`) REFERENCES `newsfeeds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deleted_posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `newsfeeds`
--
ALTER TABLE `newsfeeds`
  ADD CONSTRAINT `newsfeeds_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `newsfeeds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `newsfeed_files`
--
ALTER TABLE `newsfeed_files`
  ADD CONSTRAINT `newsfeed_files_newsfeed_id_foreign` FOREIGN KEY (`newsfeed_id`) REFERENCES `newsfeeds` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `newsfeed_likes`
--
ALTER TABLE `newsfeed_likes`
  ADD CONSTRAINT `newsfeed_likes_newsfeed_id_foreign` FOREIGN KEY (`newsfeed_id`) REFERENCES `newsfeeds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `newsfeed_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `practices`
--
ALTER TABLE `practices`
  ADD CONSTRAINT `practices_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `practices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reported_comments`
--
ALTER TABLE `reported_comments`
  ADD CONSTRAINT `reported_comments_comments_id_foreign` FOREIGN KEY (`comments_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reported_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reported_posts`
--
ALTER TABLE `reported_posts`
  ADD CONSTRAINT `reported_posts_newsfeed_id_foreign` FOREIGN KEY (`newsfeed_id`) REFERENCES `newsfeeds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reported_posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_sport_id_foreign` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
