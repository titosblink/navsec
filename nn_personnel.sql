-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2026 at 10:42 AM
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
-- Database: `nn_personnel`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `action` varchar(20) NOT NULL COMMENT 'created,updated,deleted',
  `model_type` varchar(60) NOT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `action`, `model_type`, `model_id`, `description`, `old_values`, `new_values`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, NULL, 'System', 'created', 'Officer', 3, 'Created officer: Test Officer Two', NULL, '{\"svc_no\":\"NN\\/5678\",\"name\":\"Test Officer Two\",\"rank\":\"CDR\",\"branch\":\"ENG\",\"servng_rtd\":\"S\",\"updated_at\":\"2026-10-29T17:17:02.000000Z\",\"created_at\":\"2026-10-29T17:17:02.000000Z\",\"id\":3}', '127.0.0.1', '2026-10-29 16:17:02', '2026-10-29 16:17:02'),
(2, NULL, 'System', 'deleted', 'Officer', 3, 'Deleted officer: Test Officer Two', '{\"id\":3,\"servng_rtd\":\"S\",\"rank\":\"CDR\",\"initials\":null,\"name\":\"Test Officer Two\",\"photo\":null,\"svc_no\":\"NN\\/5678\",\"course\":null,\"course_cod\":null,\"tel_no\":null,\"conversion\":null,\"conve_code\":null,\"sep_code\":null,\"dolp\":null,\"pres_ship\":null,\"ship_code\":null,\"command\":null,\"cmd_code\":null,\"pre_appt\":null,\"appt_code\":null,\"appt_date\":null,\"date_joined_ship\":null,\"r_code\":null,\"snrty_date\":null,\"branch\":\"ENG\",\"bran_code\":null,\"spl\":null,\"spl_code\":null,\"sub_spl\":null,\"s_spl_code\":null,\"qual\":null,\"type_comm\":null,\"comm_code\":null,\"doe\":null,\"dt_birth\":null,\"dt_cdt_trg_started\":null,\"date_comm\":null,\"mid_ship\":null,\"ag_slt\":null,\"slt\":null,\"lt\":null,\"ltcdr\":null,\"cdr\":null,\"capt\":null,\"cdre\":null,\"radm\":null,\"vadm\":null,\"adm\":null,\"per_year\":null,\"names1\":null,\"child_1\":null,\"child_2\":null,\"child_3\":null,\"child_4\":null,\"sel_code\":null,\"gender\":null,\"blood_grup\":null,\"state_code\":null,\"state_orig\":null,\"geo_p_zone\":null,\"religion\":null,\"home_town\":null,\"lga\":null,\"pre_addr\":null,\"e_mail_add\":null,\"next_kin\":null,\"relatnship\":null,\"nok_add\":null,\"nok_photo\":null,\"nok_tel_no\":null,\"nok_email\":null,\"alt_nok\":null,\"alt_nokrel\":null,\"alt_nok_add\":null,\"alt_phone\":null,\"alt_email\":null,\"status\":null,\"rod\":null,\"exam_fail\":null,\"dis_cases\":null,\"d_ment\":null,\"sea_time\":null,\"med_cases\":null,\"r_cieling\":null,\"term_leave\":null,\"retired_dt\":null,\"msion_area\":null,\"msion_date\":null,\"msion_recd\":null,\"remarks\":null,\"created_at\":\"2026-10-29T17:17:02.000000Z\",\"updated_at\":\"2026-10-29T17:17:02.000000Z\",\"deleted_at\":null}', NULL, '127.0.0.1', '2026-10-29 16:18:32', '2026-10-29 16:18:32'),
(3, 1, 'System Administrator', 'deleted', 'Officer', 2, 'Deleted officer: Abubakar Lamido Muhammed', '{\"id\":2,\"servng_rtd\":\"S\",\"rank\":\"LT\",\"initials\":null,\"name\":\"Abubakar Lamido Muhammed\",\"photo\":\"3676c9d9-dae1-40cc-b9ee-b9bb33ea61ba.png\",\"svc_no\":\"NN\\/4546\",\"course\":null,\"course_cod\":\"25\",\"tel_no\":\"08068180350\",\"conversion\":null,\"conve_code\":null,\"sep_code\":null,\"dolp\":\"2023-12-15T00:00:00.000000Z\",\"pres_ship\":\"NWCN\",\"ship_code\":null,\"command\":\"NHQ\",\"cmd_code\":null,\"pre_appt\":\"RO\",\"appt_code\":null,\"appt_date\":\"2025-07-07T00:00:00.000000Z\",\"date_joined_ship\":\"2025-07-07T00:00:00.000000Z\",\"r_code\":null,\"snrty_date\":\"2023-12-15T00:00:00.000000Z\",\"branch\":\"EDU\",\"bran_code\":null,\"spl\":\"Maths\",\"spl_code\":null,\"sub_spl\":\"RO\",\"s_spl_code\":null,\"qual\":\"HND Computer Science\",\"type_comm\":\"DSSC\",\"comm_code\":null,\"doe\":\"2018-05-04T00:00:00.000000Z\",\"dt_birth\":\"1989-06-16T00:00:00.000000Z\",\"dt_cdt_trg_started\":null,\"date_comm\":\"2018-12-15T00:00:00.000000Z\",\"mid_ship\":null,\"ag_slt\":null,\"slt\":\"2018-12-15T00:00:00.000000Z\",\"lt\":\"2023-12-15T00:00:00.000000Z\",\"ltcdr\":null,\"cdr\":null,\"capt\":null,\"cdre\":null,\"radm\":null,\"vadm\":null,\"adm\":null,\"per_year\":null,\"names1\":\"Amina Abubakar\",\"child_1\":\"\",\"child_2\":\"\",\"child_3\":null,\"child_4\":null,\"sel_code\":null,\"gender\":\"M\",\"blood_grup\":\"O+\",\"state_code\":null,\"state_orig\":\"Ebonyi\",\"geo_p_zone\":\"South East\",\"religion\":\"Christianity\",\"home_town\":\"Katsina\",\"lga\":\"Katsina North\",\"pre_addr\":\"Kaduna \",\"e_mail_add\":\"almuha@gmail.com\",\"next_kin\":\"Amina Abubakar\",\"relatnship\":\"Wife\",\"nok_add\":\"kaduna\",\"nok_photo\":\"f03e7031-d042-4c66-b4d0-29be5ca4d5fe.jpeg\",\"nok_tel_no\":\"08066526235\",\"nok_email\":\"ami@gmail.com\",\"alt_nok\":null,\"alt_nokrel\":null,\"alt_nok_add\":null,\"alt_phone\":null,\"alt_email\":null,\"status\":null,\"rod\":\"2038-06-16T00:00:00.000000Z\",\"exam_fail\":null,\"dis_cases\":null,\"d_ment\":null,\"sea_time\":1,\"med_cases\":null,\"r_cieling\":\"4\",\"term_leave\":\"2038-06-16T00:00:00.000000Z\",\"retired_dt\":\"2038-06-16T00:00:00.000000Z\",\"msion_area\":\"NIL\",\"msion_date\":null,\"msion_recd\":null,\"remarks\":null,\"created_at\":\"2026-10-29T11:08:08.000000Z\",\"updated_at\":\"2026-10-30T06:52:01.000000Z\",\"deleted_at\":\"2026-10-30T06:52:01.000000Z\"}', NULL, '127.0.0.1', '2026-10-30 05:52:01', '2026-10-30 05:52:01'),
(4, 1, 'System Administrator', 'deleted', 'Officer', 1, 'Deleted officer: Aluu Ogbonna Clement', '{\"id\":1,\"servng_rtd\":\"S\",\"rank\":\"LT\",\"initials\":null,\"name\":\"Aluu Ogbonna Clement\",\"photo\":\"3676c9d9-dae1-40cc-b9ee-b9bb33ea61ba.png\",\"svc_no\":\"NN\\/4545\",\"course\":null,\"course_cod\":\"25\",\"tel_no\":\"08068180350\",\"conversion\":null,\"conve_code\":null,\"sep_code\":null,\"dolp\":\"2023-12-15T00:00:00.000000Z\",\"pres_ship\":\"NWCN\",\"ship_code\":null,\"command\":\"NHQ\",\"cmd_code\":null,\"pre_appt\":\"CCITO\",\"appt_code\":null,\"appt_date\":\"2025-07-07T00:00:00.000000Z\",\"date_joined_ship\":\"2025-07-07T00:00:00.000000Z\",\"r_code\":null,\"snrty_date\":\"2023-12-15T00:00:00.000000Z\",\"branch\":\"X\",\"bran_code\":null,\"spl\":\"ICT\",\"spl_code\":null,\"sub_spl\":\"RMAC\",\"s_spl_code\":null,\"qual\":\"HND Computer Science\",\"type_comm\":\"DSSC\",\"comm_code\":null,\"doe\":\"2018-05-04T00:00:00.000000Z\",\"dt_birth\":\"1989-06-16T00:00:00.000000Z\",\"dt_cdt_trg_started\":null,\"date_comm\":\"2018-12-15T00:00:00.000000Z\",\"mid_ship\":null,\"ag_slt\":null,\"slt\":\"2018-12-15T00:00:00.000000Z\",\"lt\":\"2023-12-15T00:00:00.000000Z\",\"ltcdr\":null,\"cdr\":null,\"capt\":null,\"cdre\":null,\"radm\":null,\"vadm\":null,\"adm\":null,\"per_year\":null,\"names1\":\"Aluu Nnenna Clement\",\"child_1\":\"Aluu Udeh Pearl\",\"child_2\":\"Aluu Ogeri Eliana\",\"child_3\":null,\"child_4\":null,\"sel_code\":null,\"gender\":\"M\",\"blood_grup\":\"O+\",\"state_code\":null,\"state_orig\":\"Ebonyi\",\"geo_p_zone\":\"South East\",\"religion\":\"Christianity\",\"home_town\":\"Afikpo North\",\"lga\":\"Afikpo North\",\"pre_addr\":\"2 Ogonda Amadi Close, Off T-Juntion, Rumuagholu Road, Rumuokoro, Port Harcourt,  Rivers State\",\"e_mail_add\":\"aoclems@gmail.com\",\"next_kin\":\"Aluu Nnenna Clement\",\"relatnship\":\"Wife\",\"nok_add\":\"2 Ogonda Amadi Close, T-Junction, Rumuagholu Road, Rumuokoro, Port Harcourt, Rivers State\",\"nok_photo\":\"f03e7031-d042-4c66-b4d0-29be5ca4d5fe.jpeg\",\"nok_tel_no\":\"08066526235\",\"nok_email\":\"isumme10096@gmail.com\",\"alt_nok\":null,\"alt_nokrel\":null,\"alt_nok_add\":null,\"alt_phone\":null,\"alt_email\":null,\"status\":null,\"rod\":\"2038-06-16T00:00:00.000000Z\",\"exam_fail\":null,\"dis_cases\":null,\"d_ment\":null,\"sea_time\":1,\"med_cases\":null,\"r_cieling\":\"4\",\"term_leave\":\"2038-06-16T00:00:00.000000Z\",\"retired_dt\":\"2038-06-16T00:00:00.000000Z\",\"msion_area\":\"NIL\",\"msion_date\":null,\"msion_recd\":null,\"remarks\":null,\"created_at\":\"2026-10-29T11:08:08.000000Z\",\"updated_at\":\"2026-10-30T06:52:20.000000Z\",\"deleted_at\":\"2026-10-30T06:52:20.000000Z\"}', NULL, '127.0.0.1', '2026-10-30 05:52:20', '2026-10-30 05:52:20');

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `key`, `value`, `group`, `label`, `created_at`, `updated_at`) VALUES
(1, 'min_years_MIDN', '1', 'promotion', 'Midshipman', '2026-10-29 13:34:22', '2026-10-29 13:34:22'),
(2, 'min_years_AG/SLT', '1', 'promotion', 'Acting Sub-Lieutenant', '2026-10-29 13:34:22', '2026-10-29 13:34:22'),
(3, 'min_years_SLT', '3', 'promotion', 'Sub-Lieutenant', '2026-10-29 13:34:22', '2026-10-29 13:34:22'),
(4, 'min_years_LT', '5', 'promotion', 'Lieutenant', '2026-10-29 13:34:22', '2026-10-29 13:34:22'),
(5, 'min_years_LT/CDR', '5', 'promotion', 'Lieutenant Commander', '2026-10-29 13:34:22', '2026-10-29 13:34:22'),
(6, 'min_years_CDR', '5', 'promotion', 'Commander', '2026-10-29 13:34:22', '2026-10-29 13:34:22'),
(7, 'min_years_CAPT', '5', 'promotion', 'Captain', '2026-10-29 13:34:22', '2026-10-29 13:34:22'),
(8, 'min_years_CDRE', '3', 'promotion', 'Commodore', '2026-10-29 13:34:22', '2026-10-29 13:34:22'),
(9, 'min_years_RADM', '3', 'promotion', 'Rear Admiral', '2026-10-29 13:34:22', '2026-10-29 13:34:22'),
(10, 'min_years_VADM', '3', 'promotion', 'Vice Admiral', '2026-10-29 13:34:22', '2026-10-29 13:34:22'),
(11, 'app_name', 'Nigerian Navy Personnel Management System', 'general', 'Application Name', '2026-10-29 13:34:22', '2026-10-29 13:34:22'),
(12, 'retirement_age', '60', 'general', 'Mandatory Retirement Age', '2026-10-29 13:34:22', '2026-10-29 13:34:22');

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
-- Table structure for table `leave_entitlements`
--

CREATE TABLE `leave_entitlements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `year` year(4) NOT NULL,
  `leave_type` varchar(30) NOT NULL,
  `entitled_days` int(11) NOT NULL DEFAULT 30,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_28_195254_create_officers_table', 1),
(5, '2026_10_28_195255_add_role_to_users_table', 1),
(6, '2026_10_28_195255_create_ships_table', 1),
(7, '2026_10_28_195257_create_officer_appointments_table', 1),
(8, '2026_10_28_195258_create_officer_courses_table', 1),
(9, '2026_10_29_101045_add_photos_to_officers_table', 2),
(10, '2026_10_29_114041_create_officer_acrs_table', 3),
(11, '2026_10_29_114041_create_officer_promotions_table', 3),
(12, '2026_10_29_114042_create_officer_disciplines_table', 3),
(13, '2026_10_29_114042_create_officer_leaves_table', 3),
(14, '2026_10_29_114043_create_activity_logs_table', 3),
(15, '2026_10_29_114043_create_officer_documents_table', 3),
(16, '2026_10_29_114044_create_officer_medicals_table', 3),
(17, '2026_10_29_114044_create_officer_qualifications_table', 3),
(18, '2026_10_29_122746_add_otp_to_users_table', 4),
(19, '2026_10_29_122746_create_leave_entitlements_table', 4),
(20, '2026_10_29_122747_add_password_reset_tokens_table', 4),
(21, '2026_10_29_122747_add_two_factor_to_users_table', 4),
(22, '2026_10_29_210000_create_app_settings_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE `officers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `servng_rtd` varchar(10) DEFAULT NULL COMMENT 'S=Serving, R=Retired',
  `rank` varchar(20) DEFAULT NULL,
  `initials` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `svc_no` varchar(30) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL,
  `course_cod` varchar(20) DEFAULT NULL,
  `tel_no` varchar(30) DEFAULT NULL,
  `conversion` varchar(50) DEFAULT NULL,
  `conve_code` varchar(20) DEFAULT NULL,
  `sep_code` varchar(20) DEFAULT NULL,
  `dolp` date DEFAULT NULL COMMENT 'Date of Last Promotion',
  `pres_ship` varchar(100) DEFAULT NULL,
  `ship_code` varchar(20) DEFAULT NULL,
  `command` varchar(100) DEFAULT NULL,
  `cmd_code` varchar(20) DEFAULT NULL,
  `pre_appt` varchar(100) DEFAULT NULL,
  `appt_code` varchar(20) DEFAULT NULL,
  `appt_date` date DEFAULT NULL,
  `date_joined_ship` date DEFAULT NULL,
  `r_code` varchar(20) DEFAULT NULL,
  `snrty_date` date DEFAULT NULL,
  `branch` varchar(50) DEFAULT NULL,
  `bran_code` varchar(20) DEFAULT NULL,
  `spl` varchar(50) DEFAULT NULL,
  `spl_code` varchar(20) DEFAULT NULL,
  `sub_spl` varchar(50) DEFAULT NULL,
  `s_spl_code` varchar(20) DEFAULT NULL,
  `qual` varchar(100) DEFAULT NULL,
  `type_comm` varchar(50) DEFAULT NULL,
  `comm_code` varchar(20) DEFAULT NULL,
  `doe` date DEFAULT NULL COMMENT 'Date of Entry',
  `dt_birth` date DEFAULT NULL,
  `dt_cdt_trg_started` date DEFAULT NULL,
  `date_comm` date DEFAULT NULL COMMENT 'Date Commissioned',
  `mid_ship` date DEFAULT NULL,
  `ag_slt` date DEFAULT NULL,
  `slt` date DEFAULT NULL,
  `lt` date DEFAULT NULL,
  `ltcdr` date DEFAULT NULL,
  `cdr` date DEFAULT NULL,
  `capt` date DEFAULT NULL,
  `cdre` date DEFAULT NULL,
  `radm` date DEFAULT NULL,
  `vadm` date DEFAULT NULL,
  `adm` date DEFAULT NULL,
  `per_year` int(11) DEFAULT NULL,
  `names1` varchar(100) DEFAULT NULL COMMENT 'Spouse name',
  `child_1` varchar(100) DEFAULT NULL,
  `child_2` varchar(100) DEFAULT NULL,
  `child_3` varchar(100) DEFAULT NULL,
  `child_4` varchar(100) DEFAULT NULL,
  `sel_code` varchar(20) DEFAULT NULL,
  `gender` enum('M','F') DEFAULT NULL,
  `blood_grup` varchar(5) DEFAULT NULL,
  `state_code` varchar(20) DEFAULT NULL,
  `state_orig` varchar(50) DEFAULT NULL,
  `geo_p_zone` varchar(50) DEFAULT NULL,
  `religion` varchar(30) DEFAULT NULL,
  `home_town` varchar(100) DEFAULT NULL,
  `lga` varchar(100) DEFAULT NULL,
  `pre_addr` text DEFAULT NULL COMMENT 'Present Address',
  `e_mail_add` varchar(100) DEFAULT NULL,
  `next_kin` varchar(100) DEFAULT NULL,
  `relatnship` varchar(50) DEFAULT NULL,
  `nok_add` text DEFAULT NULL,
  `nok_photo` varchar(255) DEFAULT NULL,
  `nok_tel_no` varchar(30) DEFAULT NULL,
  `nok_email` varchar(100) DEFAULT NULL,
  `alt_nok` varchar(100) DEFAULT NULL,
  `alt_nokrel` varchar(50) DEFAULT NULL,
  `alt_nok_add` text DEFAULT NULL,
  `alt_phone` varchar(30) DEFAULT NULL,
  `alt_email` varchar(100) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `rod` date DEFAULT NULL COMMENT 'Run Out Date',
  `exam_fail` text DEFAULT NULL,
  `dis_cases` text DEFAULT NULL,
  `d_ment` text DEFAULT NULL,
  `sea_time` int(11) DEFAULT NULL COMMENT 'Sea time in months',
  `med_cases` text DEFAULT NULL,
  `r_cieling` varchar(20) DEFAULT NULL,
  `term_leave` date DEFAULT NULL,
  `retired_dt` date DEFAULT NULL,
  `msion_area` varchar(100) DEFAULT NULL,
  `msion_date` date DEFAULT NULL,
  `msion_recd` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `officers`
--

INSERT INTO `officers` (`id`, `servng_rtd`, `rank`, `initials`, `name`, `photo`, `svc_no`, `course`, `course_cod`, `tel_no`, `conversion`, `conve_code`, `sep_code`, `dolp`, `pres_ship`, `ship_code`, `command`, `cmd_code`, `pre_appt`, `appt_code`, `appt_date`, `date_joined_ship`, `r_code`, `snrty_date`, `branch`, `bran_code`, `spl`, `spl_code`, `sub_spl`, `s_spl_code`, `qual`, `type_comm`, `comm_code`, `doe`, `dt_birth`, `dt_cdt_trg_started`, `date_comm`, `mid_ship`, `ag_slt`, `slt`, `lt`, `ltcdr`, `cdr`, `capt`, `cdre`, `radm`, `vadm`, `adm`, `per_year`, `names1`, `child_1`, `child_2`, `child_3`, `child_4`, `sel_code`, `gender`, `blood_grup`, `state_code`, `state_orig`, `geo_p_zone`, `religion`, `home_town`, `lga`, `pre_addr`, `e_mail_add`, `next_kin`, `relatnship`, `nok_add`, `nok_photo`, `nok_tel_no`, `nok_email`, `alt_nok`, `alt_nokrel`, `alt_nok_add`, `alt_phone`, `alt_email`, `status`, `rod`, `exam_fail`, `dis_cases`, `d_ment`, `sea_time`, `med_cases`, `r_cieling`, `term_leave`, `retired_dt`, `msion_area`, `msion_date`, `msion_recd`, `remarks`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'S', 'LT', NULL, 'Aluu Ogbonna Clement', '3676c9d9-dae1-40cc-b9ee-b9bb33ea61ba.png', 'NN/4545', NULL, '25', '08068180350', NULL, NULL, NULL, '2023-12-15', 'NWCN', NULL, 'NHQ', NULL, 'CCITO', NULL, '2025-07-07', '2025-07-07', NULL, '2023-12-15', 'X', NULL, 'ICT', NULL, 'RMAC', NULL, 'HND Computer Science', 'DSSC', NULL, '2018-05-04', '1989-06-16', NULL, '2018-12-15', NULL, NULL, '2018-12-15', '2023-12-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aluu Nnenna Clement', 'Aluu Udeh Pearl', 'Aluu Ogeri Eliana', NULL, NULL, NULL, 'M', 'O+', NULL, 'Ebonyi', 'South East', 'Christianity', 'Afikpo North', 'Afikpo North', '2 Ogonda Amadi Close, Off T-Juntion, Rumuagholu Road, Rumuokoro, Port Harcourt,  Rivers State', 'aoclems@gmail.com', 'Aluu Nnenna Clement', 'Wife', '2 Ogonda Amadi Close, T-Junction, Rumuagholu Road, Rumuokoro, Port Harcourt, Rivers State', 'f03e7031-d042-4c66-b4d0-29be5ca4d5fe.jpeg', '08066526235', 'isumme10096@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '2038-06-16', NULL, NULL, NULL, 1, NULL, '4', '2038-06-16', '2038-06-16', 'NIL', NULL, NULL, NULL, '2026-10-29 10:08:08', '2026-10-30 05:52:20', '2026-10-30 05:52:20'),
(2, 'S', 'LT', NULL, 'Abubakar Lamido Muhammed', '3676c9d9-dae1-40cc-b9ee-b9bb33ea61ba.png', 'NN/4546', NULL, '25', '08068180350', NULL, NULL, NULL, '2023-12-15', 'NWCN', NULL, 'NHQ', NULL, 'RO', NULL, '2025-07-07', '2025-07-07', NULL, '2023-12-15', 'EDU', NULL, 'Maths', NULL, 'RO', NULL, 'HND Computer Science', 'DSSC', NULL, '2018-05-04', '1989-06-16', NULL, '2018-12-15', NULL, NULL, '2018-12-15', '2023-12-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Amina Abubakar', '', '', NULL, NULL, NULL, 'M', 'O+', NULL, 'Ebonyi', 'South East', 'Christianity', 'Katsina', 'Katsina North', 'Kaduna ', 'almuha@gmail.com', 'Amina Abubakar', 'Wife', 'kaduna', 'f03e7031-d042-4c66-b4d0-29be5ca4d5fe.jpeg', '08066526235', 'ami@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '2038-06-16', NULL, NULL, NULL, 1, NULL, '4', '2038-06-16', '2038-06-16', 'NIL', NULL, NULL, NULL, '2026-10-29 10:08:08', '2026-10-30 05:52:01', '2026-10-30 05:52:01');

-- --------------------------------------------------------

--
-- Table structure for table `officer_acrs`
--

CREATE TABLE `officer_acrs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `report_year` year(4) NOT NULL,
  `reporting_officer` varchar(100) DEFAULT NULL,
  `countersigning_officer` varchar(100) DEFAULT NULL,
  `overall_rating` tinyint(4) DEFAULT NULL COMMENT '1-9 scale',
  `fitness_category` varchar(10) DEFAULT NULL COMMENT 'A1,A2,B1,B2,C,D',
  `strengths` text DEFAULT NULL,
  `weaknesses` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `date_submitted` date DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officer_appointments`
--

CREATE TABLE `officer_appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `appointment` varchar(150) NOT NULL,
  `appt_code` varchar(30) DEFAULT NULL,
  `ship` varchar(100) DEFAULT NULL,
  `ship_code` varchar(20) DEFAULT NULL,
  `command` varchar(100) DEFAULT NULL,
  `cmd_code` varchar(20) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT 0,
  `type` varchar(30) NOT NULL DEFAULT 'posting' COMMENT 'posting, transfer, secondment',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officer_courses`
--

CREATE TABLE `officer_courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `course_type` varchar(20) NOT NULL COMMENT 'OLC,JD,SD,NWC,NIPS,STC,LC,PRO,OTHER',
  `course_name` varchar(150) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `grade` varchar(20) DEFAULT NULL,
  `received` varchar(50) DEFAULT NULL COMMENT 'Certificate received status',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officer_disciplines`
--

CREATE TABLE `officer_disciplines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `case_no` varchar(50) DEFAULT NULL,
  `charge` varchar(255) NOT NULL,
  `date_of_offence` date DEFAULT NULL,
  `date_of_trial` date DEFAULT NULL,
  `severity` enum('minor','major') NOT NULL DEFAULT 'minor',
  `outcome` varchar(100) DEFAULT NULL,
  `punishment` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officer_documents`
--

CREATE TABLE `officer_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `document_type` varchar(50) NOT NULL COMMENT 'commission_cert,academic_cert,posting_letter,medical_report,id_document,other',
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `file_size` int(10) UNSIGNED DEFAULT NULL COMMENT 'bytes',
  `remarks` text DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officer_leaves`
--

CREATE TABLE `officer_leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type` varchar(30) NOT NULL COMMENT 'annual,medical,study,compassionate,maternity,paternity,other',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `approved_by` varchar(100) DEFAULT NULL,
  `approved_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officer_medicals`
--

CREATE TABLE `officer_medicals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `exam_date` date NOT NULL,
  `medical_category` varchar(10) DEFAULT NULL COMMENT 'A1,A2,B1,B2,C,D',
  `fitness_status` enum('fit','unfit','restricted') NOT NULL DEFAULT 'fit',
  `examining_officer` varchar(100) DEFAULT NULL,
  `hospital_facility` varchar(150) DEFAULT NULL,
  `findings` text DEFAULT NULL,
  `next_exam_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officer_promotions`
--

CREATE TABLE `officer_promotions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `from_rank` varchar(20) DEFAULT NULL,
  `to_rank` varchar(20) NOT NULL,
  `effective_date` date NOT NULL,
  `approved_by` varchar(100) DEFAULT NULL,
  `authority` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officer_qualifications`
--

CREATE TABLE `officer_qualifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `qual_type` varchar(30) NOT NULL COMMENT 'degree,diploma,certificate,professional,trade',
  `title` varchar(150) NOT NULL,
  `institution` varchar(150) DEFAULT NULL,
  `country` varchar(60) DEFAULT 'Nigeria',
  `year_obtained` year(4) DEFAULT NULL,
  `grade_class` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('CIFGKlodkp6Y2pE94nJIUYRrrWVPn1V8KsrMQDdd', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUHFSMlNWdWdEbTdibFVldVhqYkcyM0FqYkkzU3BHRVhyWGNKT2g5bSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793353168);

-- --------------------------------------------------------

--
-- Table structure for table `ships`
--

CREATE TABLE `ships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  `command` varchar(255) DEFAULT NULL,
  `command_code` varchar(20) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ships`
--

INSERT INTO `ships` (`id`, `name`, `code`, `command`, `command_code`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'NWCN', '5NA79', 'NHQ', NULL, 'Headquarters', 1, '2026-10-29 11:23:21', '2026-10-29 11:23:21'),
(2, 'NHQ', '5NA01', 'NHQ', NULL, 'Headquarters', 1, '2026-10-29 11:23:50', '2026-10-29 11:23:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','hr','viewer') NOT NULL DEFAULT 'viewer',
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `otp_verified` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `otp_code`, `otp_expires_at`, `otp_verified`, `two_factor_enabled`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'System Administrator', 'admin@navy.mil.ng', 'admin', NULL, NULL, 0, 0, NULL, '$2y$12$2c6l3.nLO2M5ZHXzLWayT.i9L1IjxtOFoKo6LUmnSCNRvUYruNIMu', 'ylMTSVXTayXXPY5xRjx2rS64gZowy2EJB3lwHGPgrrVrhwy2IHZF2KOUTK8m', '2026-10-29 07:22:07', '2026-10-29 07:22:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_settings_key_unique` (`key`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `leave_entitlements`
--
ALTER TABLE `leave_entitlements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_entitlements_officer_id_year_leave_type_unique` (`officer_id`,`year`,`leave_type`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `officers_svc_no_unique` (`svc_no`);

--
-- Indexes for table `officer_acrs`
--
ALTER TABLE `officer_acrs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `officer_acrs_officer_id_foreign` (`officer_id`);

--
-- Indexes for table `officer_appointments`
--
ALTER TABLE `officer_appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `officer_appointments_officer_id_foreign` (`officer_id`);

--
-- Indexes for table `officer_courses`
--
ALTER TABLE `officer_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `officer_courses_officer_id_foreign` (`officer_id`);

--
-- Indexes for table `officer_disciplines`
--
ALTER TABLE `officer_disciplines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `officer_disciplines_officer_id_foreign` (`officer_id`);

--
-- Indexes for table `officer_documents`
--
ALTER TABLE `officer_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `officer_documents_officer_id_foreign` (`officer_id`);

--
-- Indexes for table `officer_leaves`
--
ALTER TABLE `officer_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `officer_leaves_officer_id_foreign` (`officer_id`);

--
-- Indexes for table `officer_medicals`
--
ALTER TABLE `officer_medicals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `officer_medicals_officer_id_foreign` (`officer_id`);

--
-- Indexes for table `officer_promotions`
--
ALTER TABLE `officer_promotions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `officer_promotions_officer_id_foreign` (`officer_id`);

--
-- Indexes for table `officer_qualifications`
--
ALTER TABLE `officer_qualifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `officer_qualifications_officer_id_foreign` (`officer_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `ships`
--
ALTER TABLE `ships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ships_code_unique` (`code`);

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
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_entitlements`
--
ALTER TABLE `leave_entitlements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `officer_acrs`
--
ALTER TABLE `officer_acrs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `officer_appointments`
--
ALTER TABLE `officer_appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `officer_courses`
--
ALTER TABLE `officer_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `officer_disciplines`
--
ALTER TABLE `officer_disciplines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `officer_documents`
--
ALTER TABLE `officer_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `officer_leaves`
--
ALTER TABLE `officer_leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `officer_medicals`
--
ALTER TABLE `officer_medicals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `officer_promotions`
--
ALTER TABLE `officer_promotions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `officer_qualifications`
--
ALTER TABLE `officer_qualifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ships`
--
ALTER TABLE `ships`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leave_entitlements`
--
ALTER TABLE `leave_entitlements`
  ADD CONSTRAINT `leave_entitlements_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `officer_acrs`
--
ALTER TABLE `officer_acrs`
  ADD CONSTRAINT `officer_acrs_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `officer_appointments`
--
ALTER TABLE `officer_appointments`
  ADD CONSTRAINT `officer_appointments_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `officer_courses`
--
ALTER TABLE `officer_courses`
  ADD CONSTRAINT `officer_courses_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `officer_disciplines`
--
ALTER TABLE `officer_disciplines`
  ADD CONSTRAINT `officer_disciplines_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `officer_documents`
--
ALTER TABLE `officer_documents`
  ADD CONSTRAINT `officer_documents_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `officer_leaves`
--
ALTER TABLE `officer_leaves`
  ADD CONSTRAINT `officer_leaves_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `officer_medicals`
--
ALTER TABLE `officer_medicals`
  ADD CONSTRAINT `officer_medicals_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `officer_promotions`
--
ALTER TABLE `officer_promotions`
  ADD CONSTRAINT `officer_promotions_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `officer_qualifications`
--
ALTER TABLE `officer_qualifications`
  ADD CONSTRAINT `officer_qualifications_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
