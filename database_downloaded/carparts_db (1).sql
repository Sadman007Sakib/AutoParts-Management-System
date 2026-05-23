-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2026 at 07:57 AM
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
-- Database: `carparts_db`
--

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_10_28_041743_add_role_to_users_table', 1),
(6, '2025_10_28_045712_create_parts_table', 1),
(7, '2025_10_30_051012_create_part_images_table', 1),
(8, '2025_11_05_052149_create_sales_tables', 1),
(9, '2025_11_05_052346_create_sale_items_table', 1),
(10, '2025_11_08_034236_add_tax_to_sales_table', 1),
(11, '2025_11_08_062852_add_soft_deletes_to_users_table', 1),
(12, '2025_11_08_133228_add_discount_to_sales_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `cost_price` decimal(12,2) DEFAULT NULL,
  `sell_price` decimal(12,2) DEFAULT NULL,
  `current_quantity` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`id`, `sku`, `name`, `brand`, `description`, `cost_price`, `sell_price`, `current_quantity`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'TOY-20251115-0001', 'Engine Air Filter', 'Toyota', 'This is for 2007-2018 models.', 110.00, 120.00, 82, 1, NULL, '2025-11-14 23:25:49', '2026-05-06 21:30:27'),
(2, 'MER-20260507-0001', 'Tire', 'Mercedes', NULL, 90.00, 100.00, 120, 2, NULL, '2026-05-07 06:04:13', '2026-05-07 06:04:13'),
(3, 'MER-20260507-2000', 'Tire 2', 'Mercedes', NULL, 11.00, 111.00, 111, 2, NULL, '2026-05-07 06:28:38', '2026-05-07 06:28:38'),
(4, 'TOY-20260509-0001', 'Shock Absorber', 'Toyota - Prius', 'This is a Shock absorber for Toyota Prius', 65.00, 80.00, 15, 2, NULL, '2026-05-08 23:23:25', '2026-05-08 23:24:39'),
(5, 'ASD-20260509-0001', 'asdad', 'asdadad', NULL, 18.00, 21.00, 12, 2, NULL, '2026-05-08 23:32:50', '2026-05-08 23:32:50');

-- --------------------------------------------------------

--
-- Table structure for table `part_images`
--

CREATE TABLE `part_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `part_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `part_images`
--

INSERT INTO `part_images` (`id`, `part_id`, `path`, `filename`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'parts/KkoOoZVkDuQ30jrFtLy6JCvIdyiEbPQXMy3gwIQl.webp', '17801YZZ03-3.webp', 1, '2025-11-14 23:25:50', '2025-11-14 23:25:50'),
(3, 2, 'parts/ea03zOF87vCpU7tugQ2xuyKJZmE8izczPeymf495.jpg', 'Screenshot 2026-05-07 180544.jpg', 2, '2026-05-07 06:06:08', '2026-05-07 06:06:08'),
(4, 3, 'parts/ZoW3vocYz0h44VnIXqPz7wctHTS4346ALpT6O2nO.jpg', 'Screenshot 2026-05-07 180544.jpg', 2, '2026-05-07 06:28:38', '2026-05-07 06:28:38'),
(5, 4, 'parts/UcsKGgQt43ZlS56t8D8ZNODvsJqQNDSouTIiwuKk.png', '1_36026360-8caa-4285-aef1-63c35b8b906a.png', 2, '2026-05-08 23:23:26', '2026-05-08 23:23:26'),
(6, 5, 'parts/6vxSggxqQw2EzyQJuSCZN1kDv8TkM6ropzDzMBRb.png', '1_36026360-8caa-4285-aef1-63c35b8b906a.png', 2, '2026-05-08 23:32:50', '2026-05-08 23:32:50');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slip_no` varchar(255) NOT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `discount_type` varchar(10) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `sold_by` bigint(20) UNSIGNED NOT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `slip_no`, `subtotal`, `discount_amount`, `discount_value`, `discount_type`, `customer_name`, `sold_by`, `total_amount`, `notes`, `created_at`, `updated_at`, `tax_rate`, `tax_amount`) VALUES
(1, 'SLIP-20251115-0001', 960.00, 19.20, 2.00, 'percent', 'Kanye West', 1, 1011.36, 'Famous DJ', '2025-11-14 23:26:19', '2025-11-14 23:26:41', 7.50, 70.56),
(2, 'SLIP-20260506-0001', 960.00, 120.00, 120.00, 'fixed', 'Kanye West', 2, 903.00, NULL, '2026-05-06 08:58:08', '2026-05-06 08:58:08', 7.50, 63.00),
(3, 'SLIP-20260507-0001', 1080.00, 108.00, 10.00, 'percent', 'Harris Jackson', 2, 1044.90, NULL, '2026-05-06 21:24:09', '2026-05-06 21:24:09', 7.50, 72.90),
(5, 'SLIP-20260507-0003', 120.00, 2.40, 2.00, 'percent', 'Michael Jackson', 2, 126.42, NULL, '2026-05-06 21:30:15', '2026-05-06 21:30:15', 7.50, 8.82),
(6, 'SLIP-20260509-0001', 400.00, 30.00, 30.00, 'fixed', 'Abul', 2, 397.75, NULL, '2026-05-08 23:24:39', '2026-05-08 23:24:39', 7.50, 27.75),
(8, 'SLIP-20260509-0002', 0.00, 0.00, 20.00, 'fixed', NULL, 2, 0.00, NULL, '2026-05-08 23:51:19', '2026-05-08 23:51:19', 0.00, 0.00),
(9, 'SLIP-20260509-0003', 0.00, 0.00, 0.00, NULL, NULL, 2, 0.00, NULL, '2026-05-08 23:52:07', '2026-05-08 23:52:07', 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `part_id` bigint(20) UNSIGNED NOT NULL,
  `sold_price` decimal(12,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `line_total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `part_id`, `sold_price`, `quantity`, `line_total`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 120.00, 8, 960.00, '2025-11-14 23:26:41', '2025-11-14 23:26:41'),
(3, 2, 1, 120.00, 8, 960.00, '2026-05-06 08:58:08', '2026-05-06 08:58:08'),
(4, 3, 1, 120.00, 5, 600.00, '2026-05-06 21:24:09', '2026-05-06 21:24:09'),
(5, 3, 1, 120.00, 3, 360.00, '2026-05-06 21:24:09', '2026-05-06 21:24:09'),
(6, 3, 1, 120.00, 1, 120.00, '2026-05-06 21:24:09', '2026-05-06 21:24:09'),
(10, 5, 1, 120.00, 1, 120.00, '2026-05-06 21:30:15', '2026-05-06 21:30:15'),
(11, 6, 4, 80.00, 5, 400.00, '2026-05-08 23:24:39', '2026-05-08 23:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'staff',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Mohammad Sadman Chowdhury', 'ssadman633@gmail.com', 'staff', NULL, '$2y$10$PQHXjUge/ZHQv2AZhZGyKOyqbW7icpglxmlm6Cjz9enj05H6nhAxm', NULL, '2025-11-14 23:24:45', '2026-05-08 23:13:36', NULL),
(2, 'Mohammad Sadman Chowdhury', 'ssadman634@gmail.com', 'admin', NULL, '$2y$10$hjKsLAzpxsthnOPDIgTVd.NotDL6Syf7B4cQWnE43gglN56fJf6DK', NULL, '2026-05-06 08:56:23', '2026-05-07 06:29:51', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parts_sku_unique` (`sku`),
  ADD KEY `parts_created_by_foreign` (`created_by`);

--
-- Indexes for table `part_images`
--
ALTER TABLE `part_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `part_images_part_id_foreign` (`part_id`),
  ADD KEY `part_images_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_slip_no_unique` (`slip_no`),
  ADD KEY `sales_sold_by_foreign` (`sold_by`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_items_part_id_foreign` (`part_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `part_images`
--
ALTER TABLE `part_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `parts`
--
ALTER TABLE `parts`
  ADD CONSTRAINT `parts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `part_images`
--
ALTER TABLE `part_images`
  ADD CONSTRAINT `part_images_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `part_images_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_sold_by_foreign` FOREIGN KEY (`sold_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
