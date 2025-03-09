SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `communities` (
  `id` bigint UNSIGNED NOT NULL,
  `community_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `manager_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `companies` (
  `id` bigint UNSIGNED NOT NULL,
  `company_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `costs` (
  `id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `extras` (
  `id` bigint UNSIGNED NOT NULL,
  `item` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_price` double(8,2) NOT NULL,
  `commission` decimal(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `extras_by_service` (
  `id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `extra_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_06_30_180419_create_companies_table', 1),
(6, '2024_06_30_192512_create_communities_table', 1),
(7, '2024_06_30_195708_create_statuses_table', 1),
(8, '2024_06_30_200834_create_types_table', 1),
(9, '2024_06_30_201833_create_extras_table', 1),
(10, '2024_07_10_185902_create_services_table', 1),
(11, '2024_07_10_191955_create_extras_by_service_table', 1),
(13, '2024_07_12_142202_create_permission_tables', 2),
(14, '2024_07_29_111007_add_unit_number_to_services_table', 3),
(15, '2024_07_29_115814_add_commission_to_types_table', 4),
(16, '2024_07_29_115855_add_commission_to_extras_table', 4),
(17, '2024_08_02_185907_add_phone_number_to_users_table', 5),
(18, '2024_08_04_184417_create_jobs_table', 6),
(19, '2024_10_14_213654_create_costs_table', 6);

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 21),
(3, 'App\\Models\\User', 22),
(3, 'App\\Models\\User', 23),
(3, 'App\\Models\\User', 25),
(3, 'App\\Models\\User', 28),
(3, 'App\\Models\\User', 33),
(3, 'App\\Models\\User', 34),
(3, 'App\\Models\\User', 36),
(3, 'App\\Models\\User', 39),
(3, 'App\\Models\\User', 40),
(3, 'App\\Models\\User', 44),
(3, 'App\\Models\\User', 45),
(3, 'App\\Models\\User', 46),
(3, 'App\\Models\\User', 48),
(3, 'App\\Models\\User', 49),
(3, 'App\\Models\\User', 51),
(3, 'App\\Models\\User', 56),
(3, 'App\\Models\\User', 61),
(3, 'App\\Models\\User', 64),
(3, 'App\\Models\\User', 69),
(3, 'App\\Models\\User', 70),
(4, 'App\\Models\\User', 11),
(4, 'App\\Models\\User', 15),
(4, 'App\\Models\\User', 16),
(4, 'App\\Models\\User', 17),
(4, 'App\\Models\\User', 18),
(4, 'App\\Models\\User', 38),
(4, 'App\\Models\\User', 41),
(4, 'App\\Models\\User', 43),
(4, 'App\\Models\\User', 50),
(4, 'App\\Models\\User', 52),
(4, 'App\\Models\\User', 53),
(4, 'App\\Models\\User', 54),
(4, 'App\\Models\\User', 55),
(4, 'App\\Models\\User', 57),
(4, 'App\\Models\\User', 58),
(4, 'App\\Models\\User', 59),
(4, 'App\\Models\\User', 60),
(4, 'App\\Models\\User', 62),
(4, 'App\\Models\\User', 63),
(4, 'App\\Models\\User', 65),
(4, 'App\\Models\\User', 66),
(4, 'App\\Models\\User', 67),
(4, 'App\\Models\\User', 71),
(5, 'App\\Models\\User', 37);

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(121) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(121) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_community', 'web', '2024-07-25 17:15:11', '2024-07-25 17:15:11'),
(2, 'view_any_community', 'web', '2024-07-25 17:15:11', '2024-07-25 17:15:11'),
(3, 'create_community', 'web', '2024-07-25 17:15:11', '2024-07-25 17:15:11'),
(4, 'update_community', 'web', '2024-07-25 17:15:11', '2024-07-25 17:15:11'),
(5, 'restore_community', 'web', '2024-07-25 17:15:11', '2024-07-25 17:15:11'),
(6, 'restore_any_community', 'web', '2024-07-25 17:15:11', '2024-07-25 17:15:11'),
(7, 'replicate_community', 'web', '2024-07-25 17:15:11', '2024-07-25 17:15:11'),
(8, 'reorder_community', 'web', '2024-07-25 17:15:11', '2024-07-25 17:15:11'),
(9, 'delete_community', 'web', '2024-07-25 17:15:11', '2024-07-25 17:15:11'),
(10, 'delete_any_community', 'web', '2024-07-25 17:15:11', '2024-07-25 17:15:11'),
(11, 'force_delete_community', 'web', '2024-07-25 17:15:11', '2024-07-25 17:15:11'),
(12, 'force_delete_any_community', 'web', '2024-07-25 17:15:11', '2024-07-25 17:15:11'),
(13, 'view_company', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(14, 'view_any_company', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(15, 'create_company', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(16, 'update_company', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(17, 'restore_company', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(18, 'restore_any_company', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(19, 'replicate_company', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(20, 'reorder_company', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(21, 'delete_company', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(22, 'delete_any_company', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(23, 'force_delete_company', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(24, 'force_delete_any_company', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(25, 'view_extra', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(26, 'view_any_extra', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(27, 'create_extra', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(28, 'update_extra', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(29, 'restore_extra', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(30, 'restore_any_extra', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(31, 'replicate_extra', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(32, 'reorder_extra', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(33, 'delete_extra', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(34, 'delete_any_extra', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(35, 'force_delete_extra', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(36, 'force_delete_any_extra', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(37, 'view_role', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(38, 'view_any_role', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(39, 'create_role', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(40, 'update_role', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(41, 'delete_role', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(42, 'delete_any_role', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(43, 'view_service', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(44, 'view_any_service', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(45, 'create_service', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(46, 'update_service', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(47, 'restore_service', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(48, 'restore_any_service', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(49, 'replicate_service', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(50, 'reorder_service', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(51, 'delete_service', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(52, 'delete_any_service', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(53, 'force_delete_service', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(54, 'force_delete_any_service', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(55, 'view_status', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(56, 'view_any_status', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(57, 'create_status', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(58, 'update_status', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(59, 'restore_status', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(60, 'restore_any_status', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(61, 'replicate_status', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(62, 'reorder_status', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(63, 'delete_status', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(64, 'delete_any_status', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(65, 'force_delete_status', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(66, 'force_delete_any_status', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(67, 'view_type', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(68, 'view_any_type', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(69, 'create_type', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(70, 'update_type', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(71, 'restore_type', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(72, 'restore_any_type', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(73, 'replicate_type', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(74, 'reorder_type', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(75, 'delete_type', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(76, 'delete_any_type', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(77, 'force_delete_type', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(78, 'force_delete_any_type', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(79, 'view_user', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(80, 'view_any_user', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(81, 'create_user', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(82, 'update_user', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(83, 'restore_user', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(84, 'restore_any_user', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(85, 'replicate_user', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(86, 'reorder_user', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(87, 'delete_user', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(88, 'delete_any_user', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(89, 'force_delete_user', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(90, 'force_delete_any_user', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(91, 'widget_ServiceReportWidget', 'web', '2024-08-27 17:05:21', '2024-08-27 17:05:21'),
(92, 'page_Calendar', 'web', '2024-10-15 18:33:53', '2024-10-15 18:33:53'),
(93, 'view_any_cost', 'web', '2024-10-15 18:34:16', '2024-10-15 18:34:16'),
(94, 'create_cost', 'web', '2024-10-15 18:34:16', '2024-10-15 18:34:16'),
(95, 'update_cost', 'web', '2024-10-15 18:34:16', '2024-10-15 18:34:16'),
(96, 'view_cost', 'web', '2024-10-15 18:34:16', '2024-10-15 18:34:16'),
(97, 'restore_any_cost', 'web', '2024-10-15 18:34:16', '2024-10-15 18:34:16'),
(98, 'replicate_cost', 'web', '2024-10-15 18:34:16', '2024-10-15 18:34:16'),
(99, 'reorder_cost', 'web', '2024-10-15 18:34:16', '2024-10-15 18:34:16'),
(100, 'force_delete_any_cost', 'web', '2024-10-15 18:34:16', '2024-10-15 18:34:16'),
(101, 'force_delete_cost', 'web', '2024-10-15 18:34:16', '2024-10-15 18:34:16'),
(102, 'delete_any_cost', 'web', '2024-10-15 18:34:16', '2024-10-15 18:34:16'),
(103, 'delete_cost', 'web', '2024-10-15 18:34:16', '2024-10-15 18:34:16'),
(104, 'restore_cost', 'web', '2024-10-15 18:34:16', '2024-10-15 18:34:16');

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(121) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(121) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2024-07-25 17:15:11', '2024-07-25 17:15:11'),
(2, 'panel_user', 'web', '2024-07-25 17:15:12', '2024-07-25 17:15:12'),
(3, 'Manager', 'web', '2024-07-25 17:15:53', '2024-07-25 17:15:53'),
(4, 'Cleaner', 'web', '2024-07-25 17:46:55', '2024-07-25 17:46:55'),
(5, 'Cheo', 'web', '2024-08-27 17:05:21', '2024-08-27 17:05:21');

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(43, 3),
(44, 1),
(44, 3),
(44, 4),
(45, 1),
(45, 3),
(46, 1),
(46, 3),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(91, 5),
(92, 1),
(92, 5),
(93, 1),
(93, 5),
(94, 1),
(94, 5),
(95, 1),
(95, 5),
(96, 1),
(96, 5),
(97, 1),
(97, 5),
(98, 1),
(98, 5),
(99, 1),
(99, 5),
(100, 1),
(100, 5),
(101, 1),
(101, 5),
(102, 1),
(102, 5),
(103, 1),
(103, 5),
(104, 1),
(104, 5);

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `schedule` time DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `unity_size` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `community_id` bigint UNSIGNED NOT NULL,
  `type_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `cleaner_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `status_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `statuses` (`id`, `status_name`, `created_at`, `updated_at`) VALUES
(1, 'Created', '2024-07-25 17:14:57', '2024-07-25 17:14:57'),
(2, 'Pending', '2024-07-25 17:14:57', '2024-07-25 17:14:57'),
(3, 'Approved', '2024-07-25 17:14:57', '2024-07-25 17:14:57'),
(4, 'Rejected', '2024-07-25 17:14:57', '2024-07-25 17:14:57'),
(5, 'Completed', '2024-07-25 17:14:57', '2024-07-25 17:14:57'),
(6, 'Finished', '2024-07-29 16:31:37', '2024-07-29 16:31:37');

CREATE TABLE `types` (
  `id` bigint UNSIGNED NOT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cleaning_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(8,2) NOT NULL,
  `commission` decimal(8,2) NOT NULL DEFAULT '0.00',
  `community_id` bigint UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@admin.com', '22222222', NULL, '$2y$10$vaYLtyx680wdAdVps47Tbe1hwimB7bViNFiDqRVE9/bVIFBkYO7Zu', 'BqZ3pfB5FS4ITrj7mrfJjAiNsOuJLmPgZnw5gojlWICNNWZbzxAqpRHl2rcp', '2024-07-25 17:14:52', '2024-08-05 22:42:48');

ALTER TABLE `communities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `communities_manager_id_foreign` (`manager_id`),
  ADD KEY `communities_company_id_foreign` (`company_id`);

ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `costs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `extras`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `extras_by_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `extras_by_service_service_id_foreign` (`service_id`),
  ADD KEY `extras_by_service_extra_id_foreign` (`extra_id`);

ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_community_id_foreign` (`community_id`),
  ADD KEY `services_type_id_foreign` (`type_id`),
  ADD KEY `services_status_id_foreign` (`status_id`),
  ADD KEY `services_cleaner_id_foreign` (`cleaner_id`);

ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `types_community_id_foreign` (`community_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

ALTER TABLE `communities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `companies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `costs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `extras`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `extras_by_service`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
