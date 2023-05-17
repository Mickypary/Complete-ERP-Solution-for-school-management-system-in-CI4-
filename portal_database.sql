-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2023 at 03:29 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE IF NOT EXISTS `attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `type_id` int(11) NOT NULL,
  `uploader_id` varchar(20) NOT NULL,
  `class_id` varchar(20) DEFAULT 'unfiltered',
  `file_name` varchar(255) NOT NULL,
  `enc_name` varchar(255) NOT NULL,
  `subject_id` varchar(200) DEFAULT 'unfiltered',
  `session_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attachments_type`
--

CREATE TABLE IF NOT EXISTS `attachments_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE IF NOT EXISTS `book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `author` varchar(255) NOT NULL,
  `isbn_no` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `edition` varchar(255) NOT NULL,
  `purchase_date` date NOT NULL,
  `description` text NOT NULL,
  `price` decimal(18,2) NOT NULL,
  `total_stock` varchar(20) NOT NULL,
  `issued_copies` varchar(20) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_category`
--

CREATE TABLE IF NOT EXISTS `book_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_issues`
--

CREATE TABLE IF NOT EXISTS `book_issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `date_of_issue` date DEFAULT NULL,
  `date_of_expiry` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `fine_amount` decimal(18,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = pending, 1 = accepted, 2 = rejected, 3 = returned',
  `issued_by` varchar(255) DEFAULT NULL,
  `return_by` int(11) DEFAULT NULL,
  `session_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE IF NOT EXISTS `branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `school_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mobileno` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `symbol` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`id`, `name`, `school_name`, `email`, `mobileno`, `currency`, `symbol`, `city`, `state`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Ladoke Akintola ', 'Grenville Schools', 'mikipary@gmail.com', '09062684833', 'USD', '$', 'Ikeja', 'Lagos', '18 Ladoke Akintola Street GRA\r\n', '2023-04-16 16:17:54', '2023-04-19 15:58:04'),
(2, 'Joel', 'Grenville Schools', 'eathorne@yahoo.com', '09062684833', 'NGN', 'NGN', 'Ikeja', 'Lagos', '15b Joel Ogunaike Street', '2023-05-11 22:17:27', '2023-05-11 22:17:27');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE IF NOT EXISTS `class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `name_numeric` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `name`, `name_numeric`, `created_at`, `updated_at`, `branch_id`) VALUES
(1, 'Year 7', '7', '2023-05-12 20:25:53', '2023-05-12 20:25:53', 1),
(2, 'Year 8', '8', '2023-05-12 20:26:08', '2023-05-12 20:26:08', 1),
(3, 'Year 9', '9', '2023-05-13 11:53:16', '2023-05-13 11:53:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `custom_field`
--

CREATE TABLE IF NOT EXISTS `custom_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_to` varchar(50) DEFAULT NULL,
  `field_label` varchar(100) NOT NULL,
  `default_value` text DEFAULT NULL,
  `field_type` enum('text','textarea','dropdown','date','checkbox','number','url','email') NOT NULL,
  `required` varchar(5) NOT NULL DEFAULT 'false',
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `show_on_table` varchar(5) DEFAULT NULL,
  `field_order` int(11) NOT NULL,
  `bs_column` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields_values`
--

CREATE TABLE IF NOT EXISTS `custom_fields_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `relid` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `relid` (`relid`),
  KEY `fieldid` (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates_details`
--

CREATE TABLE IF NOT EXISTS `email_templates_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `template_body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT 1,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_templates_details`
--

INSERT INTO `email_templates_details` (`id`, `template_id`, `subject`, `template_body`, `notified`, `branch_id`) VALUES
(1, 2, 'Forgot Password', 'Hi {name}\n\nYou have decided to reset your password. Click on the link below to change your password\n{reset_url}\n\nThanks\n{institute_name}', 1, 1),
(2, 1, 'Registration Info', 'Hi {user_role} {name}\n\nAn account was created for you at {institute_name}.\n\nYour username is {login_email} and password is {password}\n\nYour login url is {login_url}\nThanks\n{institute_name}', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `enroll`
--

CREATE TABLE IF NOT EXISTS `enroll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `roll` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `branch_id` tinyint(3) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `enroll`
--

INSERT INTO `enroll` (`id`, `student_id`, `class_id`, `section_id`, `roll`, `session_id`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 563, 2, 1, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(2, 2, 1, 1, 903, 2, 1, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(3, 3, 1, 1, 315, 2, 1, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(4, 4, 1, 1, 411, 2, 1, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(5, 5, 1, 1, 126, 2, 1, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(6, 6, 1, 1, 593, 2, 1, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(7, 7, 1, 1, 617, 2, 1, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(8, 8, 1, 1, 654, 2, 1, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(9, 9, 1, 1, 748, 2, 1, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(10, 10, 1, 1, 801, 2, 1, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(11, 11, 1, 1, 499, 2, 1, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(12, 12, 1, 1, 802, 2, 1, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(13, 13, 1, 1, 620, 2, 1, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(14, 14, 1, 1, 399, 2, 1, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(15, 15, 1, 1, 730, 2, 1, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(16, 16, 1, 1, 400, 2, 1, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(17, 17, 1, 1, 756, 2, 1, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(18, 18, 1, 1, 745, 2, 1, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(19, 19, 1, 1, 378, 2, 1, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(20, 20, 1, 1, 777, 2, 1, '2023-05-16 20:29:39', '2023-05-16 20:29:39');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `remark` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `type` text NOT NULL,
  `audition` longtext NOT NULL,
  `selected_list` longtext NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_by` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_types`
--

CREATE TABLE IF NOT EXISTS `event_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(200) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE IF NOT EXISTS `exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `term_id` int(11) DEFAULT NULL,
  `type_id` tinyint(4) NOT NULL COMMENT '1=mark,2=gpa,3=both',
  `session_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `remark` text NOT NULL,
  `mark_distribution` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees_type`
--

CREATE TABLE IF NOT EXISTS `fees_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `fee_code` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `branch_id` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_allocation`
--

CREATE TABLE IF NOT EXISTS `fee_allocation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_fine`
--

CREATE TABLE IF NOT EXISTS `fee_fine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `fine_value` varchar(20) NOT NULL,
  `fine_type` varchar(20) NOT NULL,
  `fee_frequency` varchar(20) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_groups_details`
--

CREATE TABLE IF NOT EXISTS `fee_groups_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_groups_id` int(11) NOT NULL,
  `fee_type_id` int(11) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_payment_history`
--

CREATE TABLE IF NOT EXISTS `fee_payment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `allocation_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `collect_by` varchar(20) DEFAULT NULL,
  `amount` decimal(18,2) NOT NULL,
  `discount` decimal(18,2) NOT NULL,
  `fine` decimal(18,2) NOT NULL,
  `pay_via` varchar(20) NOT NULL,
  `remarks` longtext NOT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `global_settings`
--

CREATE TABLE IF NOT EXISTS `global_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `institute_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `institution_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `reg_prefix` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `institute_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mobileno` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `currency_symbol` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sms_service_provider` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `session_id` int(11) NOT NULL,
  `translation` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `footer_text` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `animations` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timezone` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date_format` varchar(100) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `facebook_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `twitter_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `linkedin_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `youtube_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cron_secret_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `global_settings`
--

INSERT INTO `global_settings` (`id`, `institute_name`, `institution_code`, `reg_prefix`, `institute_email`, `address`, `mobileno`, `currency`, `currency_symbol`, `sms_service_provider`, `session_id`, `translation`, `footer_text`, `animations`, `timezone`, `date_format`, `facebook_url`, `twitter_url`, `linkedin_url`, `youtube_url`, `cron_secret_key`, `created_at`, `updated_at`) VALUES
(1, 'Grenville Schoools', 'GVS', 'on', 'info@grenvilleschool.com', '18 Ladoke Akintola Street GRA Ikeja Lagos', '09062684833', 'USD', '$', '	\r\ndisabled', 1, 'english', '© 2023 Grenville School Management - Developed by MrichCode', 'fadeIn', 'Pacific/Midway', 'm-d-Y', 'https://www.facebook.com/username', 'https://www.twitter.com/username', 'https://www.linkedin.com/username', 'https://www.youtube.com/username', '340fe292903d1bdc2eb79298a71ebda6', '2023-04-02 15:52:54', '2023-05-05 14:45:57');

-- --------------------------------------------------------

--
-- Table structure for table `hostel`
--

CREATE TABLE IF NOT EXISTS `hostel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `category_id` int(11) NOT NULL,
  `address` longtext NOT NULL,
  `watchman` longtext NOT NULL,
  `remarks` longtext DEFAULT NULL,
  `branch_id` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hostel_category`
--

CREATE TABLE IF NOT EXISTS `hostel_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `description` longtext DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `type` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hostel_room`
--

CREATE TABLE IF NOT EXISTS `hostel_room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `hostel_id` int(11) NOT NULL,
  `no_beds` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `bed_fee` decimal(18,2) NOT NULL,
  `remarks` longtext NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(255) NOT NULL,
  `english` varchar(255) NOT NULL,
  `bengali` longtext DEFAULT '',
  `arabic` longtext DEFAULT '',
  `french` longtext DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2185 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `word`, `english`, `bengali`, `arabic`, `french`) VALUES
(1, 'language', 'Language', '', '', 'La langue'),
(2, 'attendance_overview', 'Attendance Overview', '', '', 'Aperçu de la fréquentation'),
(3, 'annual_fee_summary', 'Annual Fee Summary', '', '', 'Résumé des frais annuels'),
(4, 'my_annual_attendance_overview', 'My Annual Attendance Overview', '', '', 'Mon assiduité annuelle'),
(5, 'schedule', 'Schedule', '', '', 'des horaires'),
(6, 'student_admission', 'Student Admission', '', '', 'Admission des étudiants'),
(7, 'returned', 'Returned', '', '', 'Revenu'),
(8, 'user_name', 'User Name', '', '', 'Nom d\'utilisateur'),
(9, 'rejected', 'Rejected', '', '', 'Rejeté'),
(10, 'route_name', 'Route Name', '', '', 'Nom de l\'itinéraire'),
(11, 'route_fare', 'Route Fare', '', '', 'Tarif d\'itinéraire'),
(12, 'edit_route', 'Edit Route', '', '', 'Modifier la route'),
(13, 'this_value_is_required', 'This value is required.', '', '', 'Cette valeur est requise'),
(14, 'vehicle_no', 'Vehicle No', '', '', 'Numéro de véhicule'),
(15, 'insurance_renewal_date', 'Insurance Renewal Date', '', '', 'Date de renouvellement de l\'assurance'),
(16, 'driver_name', 'Driver Name', '', '', 'Nom du conducteur'),
(17, 'driver_license', 'Driver License', '', '', 'Permis de conduire'),
(18, 'select_route', 'Select Route', '', '', 'Sélectionnez l\'itinéraire'),
(19, 'edit_vehicle', 'Edit Vehicle', '', '', 'Modifier le véhicule'),
(20, 'add_students', 'Add Students', '', '', 'Ajouter des étudiants'),
(21, 'vehicle_number', 'Vehicle Number', '', '', 'Numéro de véhicule'),
(22, 'select_route_first', 'Select Route First', '', '', 'Sélectionnez l\'itinéraire d\'abord'),
(23, 'transport_fee', 'Transport Fee', '', '', 'Frais de transport'),
(24, 'control', 'Control', '', '', 'contrôle'),
(25, 'set_students', 'Set Students', '', '', 'Mettre les élèves'),
(26, 'hostel_list', 'Hostel List', '', '', 'Liste d\'auberges'),
(27, 'watchman_name', 'Watchman Name', '', '', 'Nom du gardien'),
(28, 'hostel_address', 'Hostel Address', '', '', 'Adresse de l\'auberge'),
(29, 'edit_hostel', 'Edit Hostel', '', '', 'Modifier hostel'),
(30, 'room_name', 'Room Name', '', '', 'Nom de la salle'),
(31, 'no_of_beds', 'No Of Beds', '', '', 'Nombre de lits'),
(32, 'select_hostel_first', 'Select Hostel First', '', '', 'Sélectionnez l\'auberge en premier'),
(33, 'remaining', 'Remaining', '', '', 'Restant'),
(34, 'hostel_fee', 'Hostel Fee', '', '', 'Tarif de l\'auberge'),
(35, 'accountant_list', 'Accountant List', '', '', 'Liste comptable'),
(36, 'students_fees', 'Students Fees', '', '', 'Frais d\'étudiants'),
(37, 'fees_status', 'Fees Status', '', '', 'Statut des frais'),
(38, 'books', 'Books', '', '', 'livres'),
(39, 'home_page', 'Home Page', '', '', 'Page d\'accueil'),
(40, 'collected', 'Collected', '', '', 'collecté'),
(41, 'student_mark', 'Student Mark', '', '', 'Marque étudiante'),
(42, 'select_exam_first', 'Select Exam First', '', '', 'Sélectionnez l\'examen en premier'),
(43, 'transport_details', 'Transport Details', '', '', 'Détails de transport'),
(44, 'no_of_teacher', 'No of Teacher', '', '', 'Nombre de professeurs'),
(45, 'basic_details', 'Basic Details', '', '', 'Détails de base'),
(46, 'fee_progress', 'Fee Progress', '', '', 'Progression des frais'),
(47, 'word', 'Word', '', '', 'mot'),
(48, 'book_category', 'Book Category', '', '', 'Catégorie livre'),
(49, 'driver_phone', 'Driver Phone', '', '', 'Driver Phone'),
(50, 'invalid_csv_file', 'Invalid / Corrupted CSV File', '', '', 'fichier CSV invalide / corrompu'),
(51, 'requested_book_list', 'Requested Book List', '', '', ''),
(52, 'request_status', 'Request Status', '', '', ''),
(53, 'book_request', 'Book Request', '', '', ''),
(54, 'logout', 'Logout', '', '', ''),
(55, 'select_payment_method', 'Select Payment Method', '', '', ''),
(56, 'select_method', 'Select Method', '', '', ''),
(57, 'payment', 'Payment', '', '', ''),
(58, 'filter', 'Filter', '', '', ''),
(59, 'status', 'Status', '', '', ''),
(60, 'paid', 'Paid', '', '', ''),
(61, 'unpaid', 'Unpaid', '', '', ''),
(62, 'method', 'Method', '', '', ''),
(63, 'cash', 'Cash', '', '', ''),
(64, 'check', 'Check', '', '', ''),
(65, 'card', 'Card', '', '', ''),
(66, 'payment_history', 'Payment History', '', '', ''),
(67, 'category', 'Category', '', '', ''),
(68, 'book_list', 'Book List', '', '', ''),
(69, 'author', 'Author', '', '', ''),
(70, 'price', 'Price', '', '', ''),
(71, 'available', 'Available', '', '', ''),
(72, 'unavailable', 'Unavailable', '', '', ''),
(73, 'transport_list', 'Transport List', '', '', ''),
(74, 'edit_transport', 'Edit Transport', '', '', ''),
(75, 'hostel_name', 'Hostel Name', '', '', ''),
(76, 'number_of_room', 'Hostel Of Room', '', '', 'Nombre de chambres'),
(77, 'yes', 'Yes', '', '', 'Oui'),
(78, 'no', 'No', '', '', 'Non'),
(79, 'messages', 'Messages', '', '', 'messages'),
(80, 'compose', 'Compose', '', '', 'Ecrire un nouveau message'),
(81, 'recipient', 'Recipient', '', '', 'Bénéficiaire'),
(82, 'select_a_user', 'Select A User', '', '', 'Sélectionnez un utilisateur'),
(83, 'send', 'Send', '', '', 'Envoyer'),
(84, 'global_settings', 'Global Settings', '', '', 'Les paramètres du système'),
(85, 'currency', 'Currency', '', '', 'Devise'),
(86, 'system_email', 'System Email', '', '', 'système Email'),
(87, 'create', 'Create', '', '', 'créer'),
(88, 'save', 'Save', '', '', 'sauvegarder'),
(89, 'file', 'File', '', '', 'Fichier'),
(90, 'theme_settings', 'Theme Settings', '', '', 'Réglage des thèmes'),
(91, 'default', 'Default', '', '', 'Défaut'),
(92, 'select_theme', 'Select Theme', '', '', 'Sélectionne un thème'),
(93, 'upload_logo', 'Upload Logo', '', '', 'Télécharger Logo'),
(94, 'upload', 'Upload', '', '', 'Télécharger'),
(95, 'remember', 'Remember', '', '', 'Rappelles toi'),
(96, 'not_selected', 'Not Selected', '', '', 'Non séléctionné'),
(97, 'disabled', 'Disabled', '', '', 'désactivé'),
(98, 'inactive_account', 'Inactive Account', '', '', 'Compte inactif'),
(99, 'update_translations', 'Update Translations', '', '', 'actualiser les traductions'),
(100, 'language_list', 'Language List', '', '', 'Liste des langues'),
(101, 'option', 'Option', '', '', ''),
(102, 'edit_word', 'Edit Word', '', '', ''),
(103, 'update_profile', 'Update Profile', '', '', ''),
(104, 'current_password', 'Current Password', '', '', ''),
(105, 'new_password', 'New Password', '', '', ''),
(106, 'login', 'Login', '', '', ''),
(107, 'reset_password', 'Reset Password', '', '', ''),
(108, 'present', 'Present', '', '', ''),
(109, 'absent', 'Absent', '', '', ''),
(110, 'update_attendance', 'Update Attendance', '', '', ''),
(111, 'undefined', 'Undefined', '', '', ''),
(112, 'back', 'Back', '', '', ''),
(113, 'save_changes', 'Save Changes', '', '', ''),
(114, 'uploader', 'Uploader', '', '', ''),
(115, 'download', 'Download', '', '', ''),
(116, 'remove', 'Remove', '', '', ''),
(117, 'print', 'Print', '', '', ''),
(118, 'select_file_type', 'Select File Type', '', '', ''),
(119, 'excel', 'Excel', '', '', ''),
(120, 'other', 'Other', '', '', ''),
(121, 'students_of_class', 'Students Of Class', '', '', ''),
(122, 'marks_obtained', 'Marks Obtained', '', '', ''),
(123, 'attendance_for_class', 'Attendance For Class', '', '', ''),
(124, 'receiver', 'Receiver', '', '', ''),
(125, 'please_select_receiver', 'Please Select Receiver', '', '', ''),
(126, 'session_changed', 'Session Changed', '', '', ''),
(127, 'exam_marks', 'Exam Marks', '', '', ''),
(128, 'total_mark', 'Total Mark', '', '', ''),
(129, 'mark_obtained', 'Mark Obtained', '', '', ''),
(130, 'invoice/payment_list', 'Invoice / Payment List', '', '', ''),
(131, 'obtained_marks', 'Obtained Marks', '', '', ''),
(132, 'highest_mark', 'Highest Mark', '', '', ''),
(133, 'grade', 'Grade (GPA)', '', '', ''),
(134, 'dashboard', 'Dashboard', '', '', ''),
(135, 'student', 'Student', '', '', ''),
(136, 'rename', 'Rename', '', '', ''),
(137, 'class', 'Class', '', '', ''),
(138, 'teacher', 'Teacher', '', '', ''),
(139, 'parents', 'Parents', '', '', ''),
(140, 'subject', 'Subject', '', '', ''),
(141, 'student_attendance', 'Student Attendance', '', '', ''),
(142, 'exam_list', 'Exam List', '', '', ''),
(143, 'grades_range', 'Grades Range', '', '', ''),
(144, 'loading', 'Loading', '', '', ''),
(145, 'library', 'Library', '', '', ''),
(146, 'hostel', 'Hostel', '', '', ''),
(147, 'events', 'Events', '', '', ''),
(148, 'message', 'Message', '', '', ''),
(149, 'translations', 'Translations', '', '', ''),
(150, 'account', 'Account', '', '', ''),
(151, 'selected_session', 'Selected Session', '', '', ''),
(152, 'change_password', 'Change Password', '', '', ''),
(153, 'section', 'Section', '', '', ''),
(154, 'edit', 'Edit', '', '', ''),
(155, 'delete', 'Delete', '', '', ''),
(156, 'cancel', 'Cancel', '', '', ''),
(157, 'parent', 'Parent', '', '', ''),
(158, 'attendance', 'Attendance', '', '', ''),
(159, 'addmission_form', 'Admission Form', '', '', ''),
(160, 'name', 'Name', '', '', ''),
(161, 'select', 'Select', '', '', ''),
(162, 'roll', 'Roll', '', '', ''),
(163, 'birthday', 'Date Of Birth', '', '', ''),
(164, 'gender', 'Gender', '', '', ''),
(165, 'male', 'Male', '', '', ''),
(166, 'female', 'Female', '', '', ''),
(167, 'address', 'Address', '', '', ''),
(168, 'phone', 'Phone', '', '', ''),
(169, 'email', 'Email', '', '', ''),
(170, 'password', 'Password', '', '', ''),
(171, 'transport_route', 'Transport Route', '', '', ''),
(172, 'photo', 'Photo', '', '', ''),
(173, 'select_class', 'Select Class', '', '', ''),
(174, 'username_password_incorrect', 'Username Or Password Is Incorrect', '', '', ''),
(175, 'select_section', 'Select Section', '', '', ''),
(176, 'options', 'Options', '', '', ''),
(177, 'mark_sheet', 'Mark Sheet', '', '', ''),
(178, 'profile', 'Profile', '', '', ''),
(179, 'select_all', 'Select All', '', '', ''),
(180, 'select_none', 'Select None', '', '', ''),
(181, 'average', 'Average', '', '', ''),
(182, 'transfer', 'Transfer', '', '', ''),
(183, 'edit_teacher', 'Edit Teacher', '', '', ''),
(184, 'sex', 'Sex', '', '', ''),
(185, 'marksheet_for', 'Marksheet For', '', '', ''),
(186, 'total_marks', 'Total Marks', '', '', ''),
(187, 'parent_phone', 'Parent Phone', '', '', ''),
(188, 'subject_author', 'Subject Author', '', '', ''),
(189, 'update', 'Update', '', '', ''),
(190, 'class_list', 'Class List', '', '', ''),
(191, 'class_name', 'Class Name', '', '', ''),
(192, 'name_numeric', 'Name Numeric', '', '', ''),
(193, 'select_teacher', 'Select Teacher', '', '', ''),
(194, 'edit_class', 'Edit Class', '', '', ''),
(195, 'section_name', 'Section Name', '', '', ''),
(196, 'add_section', 'Add Section', '', '', ''),
(197, 'subject_list', 'Subject List', '', '', ''),
(198, 'subject_name', 'Subject Name', '', '', ''),
(199, 'edit_subject', 'Edit Subject', '', '', ''),
(200, 'day', 'Day', '', '', ''),
(201, 'starting_time', 'Starting Time', '', '', ''),
(202, 'hour', 'Hour', '', '', ''),
(203, 'minutes', 'Minutes', '', '', ''),
(204, 'ending_time', 'Ending Time', '', '', ''),
(205, 'select_subject', 'Select Subject', '', '', ''),
(206, 'select_date', 'Select Date', '', '', ''),
(207, 'select_month', 'Select Month', '', '', ''),
(208, 'select_year', 'Select Year', '', '', ''),
(209, 'add_language', 'Add Language', '', '', ''),
(210, 'exam_name', 'Exam Name', '', '', ''),
(211, 'date', 'Date', '', '', ''),
(212, 'comment', 'Comment', '', '', ''),
(213, 'edit_exam', 'Edit Exam', '', '', ''),
(214, 'grade_list', 'Grade List', '', '', ''),
(215, 'grade_name', 'Grade Name', '', '', ''),
(216, 'grade_point', 'Grade Point', '', '', ''),
(217, 'select_exam', 'Select Exam', '', '', ''),
(218, 'students', 'Students', '', '', ''),
(219, 'subjects', 'Subjects', '', '', ''),
(220, 'total', 'Total', '', '', ''),
(221, 'select_academic_session', 'Select Academic Session', '', '', ''),
(222, 'invoice_informations', 'Invoice Informations', '', '', ''),
(223, 'title', 'Title', '', '', ''),
(224, 'description', 'Description', '', '', ''),
(225, 'payment_informations', 'Payment Informations', '', '', ''),
(226, 'view_invoice', 'View Invoice', '', '', ''),
(227, 'payment_to', 'Payment To', '', '', ''),
(228, 'bill_to', 'Bill To', '', '', ''),
(229, 'total_amount', 'Total Amount', '', '', ''),
(230, 'paid_amount', 'Paid Amount', '', '', ''),
(231, 'due', 'Due', '', '', ''),
(232, 'amount_paid', 'Amount Paid', '', '', ''),
(233, 'payment_successfull', 'Payment has been successful', '', '', ''),
(234, 'add_invoice/payment', 'Add Invoice/payment', '', '', ''),
(235, 'invoices', 'Invoices', '', '', ''),
(236, 'action', 'Action', '', '', ''),
(237, 'required', 'Required', '', '', ''),
(238, 'info', 'Info', '', '', ''),
(239, 'month', 'Month', '', '', ''),
(240, 'details', 'Details', '', '', ''),
(241, 'new', 'New', '', '', ''),
(242, 'reply_message', 'Reply Message', '', '', ''),
(243, 'message_sent', 'Message Sent', '', '', ''),
(244, 'search', 'Search', '', '', ''),
(245, 'religion', 'Religion', '', '', ''),
(246, 'blood_group', 'Blood group', '', '', ''),
(247, 'database_backup', 'Database Backup', '', '', ''),
(248, 'search', 'Search', '', '', ''),
(249, 'payments_history', 'Fees Pay / Invoice', '', '', ''),
(250, 'message_restore', 'Message Restore', '', '', ''),
(251, 'write_new_message', 'Write New Message', '', '', ''),
(252, 'attendance_sheet', 'Attendance Sheet', '', '', ''),
(253, 'holiday', 'Holiday', '', '', ''),
(254, 'exam', 'Exam', '', '', ''),
(255, 'successfully', 'Successfully', '', '', ''),
(256, 'admin', 'Admin', '', '', ''),
(257, 'inbox', 'Inbox', '', '', ''),
(258, 'sent', 'Sent', '', '', ''),
(259, 'important', 'Important', '', '', ''),
(260, 'trash', 'Trash', '', '', ''),
(261, 'error', 'Unsuccessful', '', '', ''),
(262, 'sessions_list', 'Sessions List', '', '', ''),
(263, 'session_settings', 'Session Settings', '', '', ''),
(264, 'add_designation', 'Add Designation', '', '', ''),
(265, 'users', 'Users', '', '', ''),
(266, 'librarian', 'Librarian', '', '', ''),
(267, 'accountant', 'Accountant', '', '', ''),
(268, 'academics', 'Academics', '', '', ''),
(269, 'employees_attendance', 'Employees Attendance', '', '', ''),
(270, 'set_exam_term', 'Set Exam Term', '', '', ''),
(271, 'set_attendance', 'Set Attendance', '', '', ''),
(272, 'marks', 'Marks', '', '', ''),
(273, 'books_category', 'Books Category', '', '', ''),
(274, 'transport', 'Transport', '', '', ''),
(275, 'fees', 'Fees', '', '', ''),
(276, 'fees_allocation', 'Fees Allocation', '', '', ''),
(277, 'fee_category', 'Fee Category', '', '', ''),
(278, 'report', 'Report', '', '', ''),
(279, 'employee', 'Employee', '', '', ''),
(280, 'invoice', 'Invoice', '', '', ''),
(281, 'event_catalogue', 'Event Catalogue', '', '', ''),
(282, 'total_paid', 'Total Paid', '', '', ''),
(283, 'total_due', 'Total Due', '', '', ''),
(284, 'fees_collect', 'Fees Collect', '', '', ''),
(285, 'total_school_students_attendance', 'Total School Students Attendance', '', '', ''),
(286, 'overview', 'Overview', '', '', ''),
(287, 'currency_symbol', 'Currency Symbol', '', '', ''),
(288, 'enable', 'Enable', '', '', ''),
(289, 'disable', 'Disable', '', '', ''),
(290, 'payment_settings', 'Payment Settings', '', '', ''),
(291, 'student_attendance_report', 'Student Attendance Report', '', '', ''),
(292, 'attendance_type', 'Attendance Type', '', '', ''),
(293, 'late', 'Late', '', '', ''),
(294, 'employees_attendance_report', 'Employees Attendance Report', '', '', ''),
(295, 'attendance_report_of', 'Attendance Report Of', '', '', ''),
(296, 'fee_paid_report', 'Fee Paid Report', '', '', ''),
(297, 'invoice_no', 'Invoice No', '', '', ''),
(298, 'payment_mode', 'Payment Mode', '', '', ''),
(299, 'payment_type', 'Payment Type', '', '', ''),
(300, 'done', 'Done', '', '', ''),
(301, 'select_fee_category', 'Select Fee Category', '', '', ''),
(302, 'discount', 'Discount', '', '', ''),
(303, 'enter_discount_amount', 'Enter Discount Amount', '', '', ''),
(304, 'online_payment', 'Online Payment', '', '', ''),
(305, 'student_name', 'Student Name', '', '', ''),
(306, 'invoice_history', 'Invoice History', '', '', ''),
(307, 'discount_amount', 'Discount Amount', '', '', ''),
(308, 'invoice_list', 'Invoice List', '', '', ''),
(309, 'partly_paid', 'Partly Paid', '', '', ''),
(310, 'fees_list', 'Fees List', '', '', ''),
(311, 'voucher_id', 'Voucher ID', '', '', ''),
(312, 'transaction_date', 'Transaction Date', '', '', ''),
(313, 'admission_date', 'Admission Date', '', '', ''),
(314, 'user_status', 'User Status', '', '', ''),
(315, 'nationality', 'Nationality', '', '', ''),
(316, 'register_no', 'Register No', '', '', ''),
(317, 'first_name', 'First Name', '', '', ''),
(318, 'last_name', 'Last Name', '', '', ''),
(319, 'state', 'State', '', '', ''),
(320, 'transport_vehicle_no', 'Transport Vehicle No', '', '', ''),
(321, 'percent', 'Percent', '', '', ''),
(322, 'average_result', 'Average Result', '', '', ''),
(323, 'student_category', 'Student Category', '', '', ''),
(324, 'category_name', 'Category Name', '', '', ''),
(325, 'category_list', 'Category List', '', '', ''),
(326, 'please_select_student_first', 'Please Select Students First', '', '', ''),
(327, 'designation', 'Designation', '', '', ''),
(328, 'qualification', 'Qualification', '', '', ''),
(329, 'account_deactivated', 'Account Deactivated', '', '', ''),
(330, 'account_activated', 'Account Activated', '', '', ''),
(331, 'designation_list', 'Designation List', '', '', ''),
(332, 'joining_date', 'Joining Date', '', '', ''),
(333, 'relation', 'Relation', '', '', ''),
(334, 'father_name', 'Father Name', '', '', ''),
(335, 'librarian_list', 'Librarian List', '', '', ''),
(336, 'class_numeric', 'Class Numeric', '', '', ''),
(337, 'maximum_students', 'Maximum Students', '', '', ''),
(338, 'class_room', 'Class Room', '', '', ''),
(339, 'pass_mark', 'Pass Mark', '', '', ''),
(340, 'exam_time', 'Exam Time (Min)', '', '', ''),
(341, 'time', 'Time', '', '', ''),
(342, 'subject_code', 'Subject Code', '', '', ''),
(343, 'full_mark', 'Full Mark', '', '', ''),
(344, 'subject_type', 'Subject Type', '', '', ''),
(345, 'date_of_publish', 'Date Of Publish', '', '', ''),
(346, 'file_name', 'File Name', '', '', ''),
(347, 'students_list', 'Students List', '', '', ''),
(348, 'start_date', 'Start Date', '', '', ''),
(349, 'end_date', 'End Date', '', '', ''),
(350, 'term_name', 'Term Name', '', '', ''),
(351, 'grand_total', 'Grand Total', '', '', ''),
(352, 'result', 'Result', '', '', ''),
(353, 'books_list', 'Books List', '', '', ''),
(354, 'book_isbn_no', 'Book ISBN No', '', '', ''),
(355, 'total_stock', 'Total Stock', '', '', ''),
(356, 'issued_copies', 'Issued Copies', '', '', ''),
(357, 'publisher', 'Publisher', '', '', ''),
(358, 'books_issue', 'Books Issue', '', '', ''),
(359, 'user', 'User', '', '', ''),
(360, 'fine', 'Fine', '', '', ''),
(361, 'pending', 'Pending', '', '', ''),
(362, 'return_date', 'Return Date', '', '', ''),
(363, 'accept', 'Accept', '', '', ''),
(364, 'reject', 'Reject', '', '', ''),
(365, 'issued', 'Issued', '', '', ''),
(366, 'return', 'Return', '', '', ''),
(367, 'renewal', 'Renewal', '', '', ''),
(368, 'fine_amount', 'Fine Amount', '', '', ''),
(369, 'password_mismatch', 'Password Mismatch', '', '', ''),
(370, 'settings_updated', 'Settings Update', '', '', ''),
(371, 'pass', 'Pass', '', '', ''),
(372, 'event_to', 'Event To', '', '', ''),
(373, 'all_users', 'All Users', '', '', ''),
(374, 'employees_list', 'Employees List', '', '', ''),
(375, 'on', 'On', '', '', ''),
(376, 'timezone', 'Timezone', '', '', ''),
(377, 'get_result', 'Get Result', '', '', ''),
(378, 'apply', 'Apply', '', '', ''),
(379, 'hrm', 'Human Resource', '', '', ''),
(380, 'payroll', 'Payroll', '', '', ''),
(381, 'salary_assign', 'Salary Assign', '', '', ''),
(382, 'employee_salary', 'Payment Salary', '', '', ''),
(383, 'application', 'Application', '', '', ''),
(384, 'award', 'Award', '', '', ''),
(385, 'basic_salary', 'Basic Salary', '', '', ''),
(386, 'employee_name', 'Employee Name', '', '', ''),
(387, 'name_of_allowance', 'Name Of Allowance', '', '', ''),
(388, 'name_of_deductions', 'Name Of Deductions', '', '', ''),
(389, 'all_employees', 'All Employees', '', '', ''),
(390, 'total_allowance', 'Total Allowance', '', '', ''),
(391, 'total_deduction', 'Total Deductions', '', '', ''),
(392, 'net_salary', 'Net Salary', '', '', ''),
(393, 'payslip', 'Payslip', '', '', ''),
(394, 'days', 'Days', '', '', ''),
(395, 'category_name_already_used', 'Category Name Already Used', '', '', ''),
(396, 'leave_list', 'Leave List', '', '', ''),
(397, 'leave_category', 'Leave Category', '', '', ''),
(398, 'applied_on', 'Applied On', '', '', ''),
(399, 'accepted', 'Accepted', '', '', ''),
(400, 'leave_statistics', 'Leave Statistics', '', '', ''),
(401, 'leave_type', 'Leave Type', '', '', ''),
(402, 'reason', 'Reason', '', '', ''),
(403, 'close', 'Close', '', '', ''),
(404, 'give_award', 'Give Award', '', '', ''),
(405, 'list', 'List', '', '', ''),
(406, 'award_name', 'Award Name', '', '', ''),
(407, 'gift_item', 'Gift Item', '', '', ''),
(408, 'cash_price', 'Cash Price', '', '', ''),
(409, 'award_reason', 'Award Reason', '', '', ''),
(410, 'given_date', 'Given Date', '', '', ''),
(411, 'apply_leave', 'Apply Leave', '', '', ''),
(412, 'leave_application', 'Leave Application', '', '', ''),
(413, 'allowances', 'Allowances', '', '', ''),
(414, 'add_more', 'Add More', '', '', ''),
(415, 'deductions', 'Deductions', '', '', ''),
(416, 'salary_details', 'Salary Details', '', '', ''),
(417, 'salary_month', 'Salary Month', '', '', ''),
(418, 'leave_data_update_successfully', 'Leave Data Updated Successfully', '', '', ''),
(419, 'fees_history', 'Fees History', '', '', ''),
(420, 'bank_name', 'Bank Name', '', '', ''),
(421, 'branch', 'Branch', '', '', ''),
(422, 'bank_address', 'Bank Address', '', '', ''),
(423, 'ifsc_code', 'IFSC Code', '', '', ''),
(424, 'account_no', 'Account No', '', '', ''),
(425, 'add_bank', 'Add Bank', '', '', ''),
(426, 'account_name', 'Account Holder', '', '', ''),
(427, 'database_backup_completed', 'Database Backup Completed', '', '', ''),
(428, 'restore_database', 'Restore Database', '', '', ''),
(429, 'template', 'Template', '', '', ''),
(430, 'time_and_date', 'Time And Date', '', '', ''),
(431, 'everyone', 'Everyone', '', '', ''),
(432, 'invalid_amount', 'Invalid Amount', '', '', ''),
(433, 'leaving_date_is_not_available_for_you', 'Leaving Date Is Not Available For You', '', '', ''),
(434, 'animations', 'Animations', '', '', ''),
(435, 'email_settings', 'Email Settings', '', '', ''),
(436, 'deduct_month', 'Deduct Month', '', '', ''),
(437, 'no_employee_available', 'No Employee Available', '', '', ''),
(438, 'advance_salary_application_submitted', 'Advance Salary Application Submitted', '', '', ''),
(439, 'date_format', 'Date Format', '', '', ''),
(440, 'id_card_generate', 'ID Card Generate', '', '', ''),
(441, 'issue_salary', 'Issue Salary', '', '', ''),
(442, 'advance_salary', 'Advance Salary', '', '', ''),
(443, 'logo', 'Logo', '', '', ''),
(444, 'book_request', 'Book Request', '', '', ''),
(445, 'reporting', 'Reporting', '', '', ''),
(446, 'paid_salary', 'Paid Salary', '', '', ''),
(447, 'due_salary', 'Due Salary', '', '', ''),
(448, 'route', 'Route', '', '', ''),
(449, 'academic_details', 'Academic Details', '', '', ''),
(450, 'guardian_details', 'Guardian Details', '', '', ''),
(451, 'due_amount', 'Due Amount', '', '', ''),
(452, 'fee_due_report', 'Fee Due Report', '', '', ''),
(453, 'other_details', 'Other Details', '', '', ''),
(454, 'last_exam_report', 'Last Exam Report', '', '', ''),
(455, 'book_issued', 'Book Issued', '', '', ''),
(456, 'interval_month', 'Interval 30 Days', '', '', ''),
(457, 'attachments', 'Attachments', '', '', ''),
(458, 'fees_payment', 'Fees Payment', '', '', ''),
(459, 'fees_summary', 'Fees Summary', '', '', ''),
(460, 'total_fees', 'Total Fees', '', '', ''),
(461, 'weekend_attendance_inspection', 'Weekend Attendance Inspection', '', '', ''),
(462, 'book_issued_list', 'Book Issued List', '', '', ''),
(463, 'lose_your_password', 'Lose Your Password?', '', '', ''),
(464, 'all_branch_dashboard', 'All Branch Dashboard', '', '', ''),
(465, 'academic_session', 'Academic Session', '', '', ''),
(466, 'all_branches', 'All Branches', '', '', ''),
(467, 'admission', 'Admission', '', '', ''),
(468, 'create_admission', 'Create Admission', '', '', ''),
(469, 'multiple_import', 'Multiple Import', '', '', ''),
(470, 'student_details', 'Student Details', '', '', ''),
(471, 'student_list', 'Student List', '', '', ''),
(472, 'login_deactivate', 'Login Deactivate', '', '', ''),
(473, 'parents_list', 'Parents List', '', '', ''),
(474, 'add_parent', 'Add Parent', '', '', ''),
(475, 'employee_list', 'Employee List', '', '', ''),
(476, 'add_department', 'Add Department', '', '', ''),
(477, 'add_employee', 'Add Employee', '', '', ''),
(478, 'salary_template', 'Salary Template', '', '', ''),
(479, 'salary_payment', 'Salary Payment', '', '', ''),
(480, 'payroll_summary', 'Payroll Summary', '', '', ''),
(481, 'academic', 'Academic', '', '', ''),
(482, 'control_classes', 'Control Classes', '', '', ''),
(483, 'assign_class_teacher', 'Assign Class Teacher', '', '', ''),
(484, 'class_assign', 'Class Assign', '', '', ''),
(485, 'assign', 'Assign', '', '', ''),
(486, 'promotion', 'Promotion', '', '', ''),
(487, 'attachments_book', 'Attachments Book', '', '', ''),
(488, 'upload_content', 'Upload Content', '', '', ''),
(489, 'attachment_type', 'Attachment Type', '', '', ''),
(490, 'exam_master', 'Exam Master', '', '', ''),
(491, 'exam_hall', 'Exam Hall', '', '', ''),
(492, 'mark_entries', 'Mark Entries', '', '', ''),
(493, 'tabulation_sheet', 'Tabulation Sheet', '', '', ''),
(494, 'supervision', 'Supervision', '', '', ''),
(495, 'hostel_master', 'Hostel Master', '', '', ''),
(496, 'hostel_room', 'Hostel Room', '', '', ''),
(497, 'allocation_report', 'Allocation Report', '', '', ''),
(498, 'route_master', 'Route Master', '', '', ''),
(499, 'vehicle_master', 'Vehicle Master', '', '', ''),
(500, 'stoppage', 'Stoppage', '', '', ''),
(501, 'assign_vehicle', 'Assign Vehicle', '', '', ''),
(502, 'reports', 'Reports', '', '', ''),
(503, 'books_entry', 'Books Entry', '', '', ''),
(504, 'event_type', 'Event Type', '', '', ''),
(505, 'add_events', 'Add Events', '', '', ''),
(506, 'student_accounting', 'Student Accounting', '', '', ''),
(507, 'create_single_invoice', 'Create Single Invoice', '', '', ''),
(508, 'create_multi_invoice', 'Create Multi Invoice', '', '', ''),
(509, 'summary_report', 'Summary Report', '', '', ''),
(510, 'office_accounting', 'Office Accounting', '', '', ''),
(511, 'under_group', 'Under Group', '', '', ''),
(512, 'bank_account', 'Bank Account', '', '', ''),
(513, 'ledger_account', 'Ledger Account', '', '', ''),
(514, 'create_voucher', 'Create Voucher', '', '', ''),
(515, 'day_book', 'Day Book', '', '', ''),
(516, 'cash_book', 'Cash Book', '', '', ''),
(517, 'bank_book', 'Bank Book', '', '', ''),
(518, 'ledger_book', 'Ledger Book', '', '', ''),
(519, 'trial_balance', 'Trial Balance', '', '', ''),
(520, 'settings', 'Settings', '', '', ''),
(521, 'sms_settings', 'Sms Settings', '', '', ''),
(522, 'cash_book_of', 'Cash Book Of', '', '', ''),
(523, 'by_cash', 'By Cash', '', '', ''),
(524, 'by_bank', 'By Bank', '', '', ''),
(525, 'total_strength', 'Total Strength', '', '', ''),
(526, 'teachers', 'Teachers', '', '', ''),
(527, 'student_quantity', 'Student Quantity', '', '', ''),
(528, 'voucher', 'Voucher', '', '', ''),
(529, 'total_number', 'Total Number', '', '', ''),
(530, 'total_route', 'Total Route', '', '', ''),
(531, 'total_room', 'Total Room', '', '', ''),
(532, 'amount', 'Amount', '', '', ''),
(533, 'branch_dashboard', 'Branch Dashboard', '', '', ''),
(534, 'branch_list', 'Branch List', '', '', ''),
(535, 'create_branch', 'Create Branch', '', '', ''),
(536, 'branch_name', 'Branch Name', '', '', ''),
(537, 'school_name', 'School Name', '', '', ''),
(538, 'mobile_no', 'Mobile No', '', '', ''),
(539, 'symbol', 'Symbol', '', '', ''),
(540, 'city', 'City', '', '', ''),
(541, 'academic_year', 'Academic Year', '', '', ''),
(542, 'select_branch_first', 'First Select The Branch', '', '', ''),
(543, 'select_class_first', 'Select Class First', '', '', ''),
(544, 'select_country', 'Select Country', '', '', ''),
(545, 'mother_tongue', 'Mother Tongue', '', '', ''),
(546, 'caste', 'Caste', '', '', ''),
(547, 'present_address', 'Present Address', '', '', ''),
(548, 'permanent_address', 'Permanent Address', '', '', ''),
(549, 'profile_picture', 'Profile Picture', '', '', ''),
(550, 'login_details', 'Login Details', '', '', ''),
(551, 'retype_password', 'Retype Password', '', '', ''),
(552, 'occupation', 'Occupation', '', '', ''),
(553, 'income', 'Income', '', '', ''),
(554, 'education', 'Education', '', '', ''),
(555, 'first_select_the_route', 'First Select The Route', '', '', ''),
(556, 'hostel_details', 'Hostel Details', '', '', ''),
(557, 'first_select_the_hostel', 'First Select The Hostel', '', '', ''),
(558, 'previous_school_details', 'Previous School Details', '', '', ''),
(559, 'book_name', 'Book Name', '', '', ''),
(560, 'select_ground', 'Select Ground', '', '', ''),
(561, 'import', 'Import', '', '', ''),
(562, 'add_student_category', 'Add Student Category', '', '', ''),
(563, 'id', 'Id', '', '', ''),
(564, 'edit_category', 'Edit Category', '', '', ''),
(565, 'deactivate_account', 'Deactivate Account', '', '', ''),
(566, 'all_sections', 'All Sections', '', '', ''),
(567, 'authentication_activate', 'Authentication Activate', '', '', ''),
(568, 'department', 'Department', '', '', ''),
(569, 'salary_grades', 'Salary Grades', '', '', ''),
(570, 'overtime', 'Overtime Rate (Per Hour)', '', '', ''),
(571, 'salary_grade', 'Salary Grade', '', '', ''),
(572, 'payable_type', 'Payable Type', '', '', ''),
(573, 'edit_type', 'Edit Type', '', '', ''),
(574, 'role', 'Role', '', '', ''),
(575, 'remuneration_info_for', 'Remuneration Info For', '', '', ''),
(576, 'salary_paid', 'Salary Paid', '', '', ''),
(577, 'salary_unpaid', 'Salary Unpaid', '', '', ''),
(578, 'pay_now', 'Pay Now', '', '', ''),
(579, 'employee_role', 'Employee Role', '', '', ''),
(580, 'create_at', 'Create At', '', '', ''),
(581, 'select_employee', 'Select Employee', '', '', ''),
(582, 'review', 'Review', '', '', ''),
(583, 'reviewed_by', 'Reviewed By', '', '', ''),
(584, 'submitted_by', 'Submitted By', '', '', ''),
(585, 'employee_type', 'Employee Type', '', '', ''),
(586, 'approved', 'Approved', '', '', ''),
(587, 'unreviewed', 'Unreviewed', '', '', ''),
(588, 'creation_date', 'Creation Date', '', '', ''),
(589, 'no_information_available', 'No Information Available', '', '', ''),
(590, 'continue_to_payment', 'Continue To Payment', '', '', ''),
(591, 'overtime_total_hour', 'Overtime Total Hour', '', '', ''),
(592, 'overtime_amount', 'Overtime Amount', '', '', ''),
(593, 'remarks', 'Remarks', '', '', ''),
(594, 'view', 'View', '', '', ''),
(595, 'leave_appeal', 'Leave Appeal', '', '', ''),
(596, 'create_leave', 'Create Leave', '', '', ''),
(597, 'user_role', 'User Role', '', '', ''),
(598, 'date_of_start', 'Date Of Start', '', '', ''),
(599, 'date_of_end', 'Date Of End', '', '', ''),
(600, 'winner', 'Winner', '', '', ''),
(601, 'select_user', 'Select User', '', '', ''),
(602, 'create_class', 'Create Class', '', '', ''),
(603, 'class_teacher_allocation', 'Class Teacher Allocation', '', '', ''),
(604, 'class_teacher', 'Class Teacher', '', '', ''),
(605, 'create_subject', 'Create Subject', '', '', ''),
(606, 'select_multiple_subject', 'Select Multiple Subject', '', '', ''),
(607, 'teacher_assign', 'Teacher Assign', '', '', ''),
(608, 'teacher_assign_list', 'Teacher Assign List', '', '', ''),
(609, 'select_department_first', 'Select Department First', '', '', ''),
(610, 'create_book', 'Create Book', '', '', ''),
(611, 'book_title', 'Book Title', '', '', ''),
(612, 'cover', 'Cover', '', '', ''),
(613, 'edition', 'Edition', '', '', ''),
(614, 'isbn_no', 'ISBN No', '', '', ''),
(615, 'purchase_date', 'Purchase Date', '', '', ''),
(616, 'cover_image', 'Cover Image', '', '', ''),
(617, 'book_issue', 'Book Issue', '', '', ''),
(618, 'date_of_issue', 'Date Of Issue', '', '', ''),
(619, 'date_of_expiry', 'Date Of Expiry', '', '', ''),
(620, 'select_category_first', 'Select Category First', '', '', ''),
(621, 'type_name', 'Type Name', '', '', ''),
(622, 'type_list', 'Type List', '', '', ''),
(623, 'icon', 'Icon', '', '', ''),
(624, 'event_list', 'Event List', '', '', ''),
(625, 'create_event', 'Create Event', '', '', ''),
(626, 'type', 'Type', '', '', ''),
(627, 'audience', 'Audience', '', '', ''),
(628, 'created_by', 'Created By', '', '', ''),
(629, 'publish', 'Publish', '', '', ''),
(630, 'everybody', 'Everybody', '', '', ''),
(631, 'selected_class', 'Selected Class', '', '', ''),
(632, 'selected_section', 'Selected Section', '', '', ''),
(633, 'information_has_been_updated_successfully', 'Information Has Been Updated Successfully', '', '', ''),
(634, 'create_invoice', 'Create Invoice', '', '', ''),
(635, 'invoice_entry', 'Invoice Entry', '', '', ''),
(636, 'quick_payment', 'Quick Payment', '', '', ''),
(637, 'write_your_remarks', 'Write Your Remarks', '', '', ''),
(638, 'reset', 'Reset', '', '', ''),
(639, 'fees_payment_history', 'Fees Payment History', '', '', ''),
(640, 'fees_summary_report', 'Fees Summary Report', '', '', ''),
(641, 'add_account_group', 'Add Account Group', '', '', ''),
(642, 'account_group', 'Account Group', '', '', ''),
(643, 'account_group_list', 'Account Group List', '', '', ''),
(644, 'mailbox', 'Mailbox', '', '', ''),
(645, 'refresh_mail', 'Refresh Mail', '', '', ''),
(646, 'sender', 'Sender', '', '', ''),
(647, 'general_settings', 'General Settings', '', '', ''),
(648, 'institute_name', 'Institute Name', '', '', ''),
(649, 'institution_code', 'Institution Code', '', '', ''),
(650, 'sms_service_provider', 'Sms Service Provider', '', '', ''),
(651, 'footer_text', 'Footer Text', '', '', ''),
(652, 'payment_control', 'Payment Control', '', '', ''),
(653, 'sms_config', 'Sms Config', '', '', ''),
(654, 'sms_triggers', 'Sms Triggers', '', '', ''),
(655, 'authentication_token', 'Authentication Token', '', '', ''),
(656, 'sender_number', 'Sender Number', '', '', ''),
(657, 'username', 'Username', '', '', ''),
(658, 'api_key', 'Api Key', '', '', ''),
(659, 'authkey', 'Authkey', '', '', ''),
(660, 'sender_id', 'Sender Id', '', '', ''),
(661, 'sender_name', 'Sender Name', '', '', ''),
(662, 'hash_key', 'Hash Key', '', '', ''),
(663, 'notify_enable', 'Notify Enable', '', '', ''),
(664, 'exam_attendance', 'Exam Attendance', '', '', ''),
(665, 'exam_results', 'Exam Results', '', '', ''),
(666, 'email_config', 'Email Config', '', '', ''),
(667, 'email_triggers', 'Email Triggers', '', '', ''),
(668, 'account_registered', 'Account Registered', '', '', ''),
(669, 'forgot_password', 'Forgot Password', '', '', ''),
(670, 'new_message_received', 'New Message Received', '', '', ''),
(671, 'payslip_generated', 'Payslip Generated', '', '', ''),
(672, 'leave_approve', 'Leave Approve', '', '', ''),
(673, 'leave_reject', 'Leave Reject', '', '', ''),
(674, 'advance_salary_approve', 'Advance Salary Approve', '', '', ''),
(675, 'advance_salary_reject', 'Advance Salary Reject', '', '', ''),
(676, 'add_session', 'Add Session', '', '', ''),
(677, 'session', 'Session', '', '', ''),
(678, 'created_at', 'Created At', '', '', ''),
(679, 'sessions', 'Sessions', '', '', ''),
(680, 'flag', 'Flag', '', '', ''),
(681, 'stats', 'Stats', '', '', ''),
(682, 'updated_at', 'Updated At', '', '', ''),
(683, 'flag_icon', 'Flag Icon', '', '', ''),
(684, 'password_restoration', 'Password Restoration', '', '', ''),
(685, 'forgot', 'Forgot', '', '', ''),
(686, 'back_to_login', 'Back To Login', '', '', ''),
(687, 'database_list', 'Database List', '', '', ''),
(688, 'create_backup', 'Create Backup', '', '', ''),
(689, 'backup', 'Backup', '', '', ''),
(690, 'backup_size', 'Backup Size', '', '', ''),
(691, 'file_upload', 'File Upload', '', '', ''),
(692, 'parents_details', 'Parents Details', '', '', ''),
(693, 'social_links', 'Social Links', '', '', ''),
(694, 'create_hostel', 'Create Hostel', '', '', ''),
(695, 'allocation_list', 'Allocation List', '', '', ''),
(696, 'payslip_history', 'Payslip History', '', '', ''),
(697, 'my_attendance_overview', 'My Attendance Overview', '', '', ''),
(698, 'total_present', 'Total Present', '', '', ''),
(699, 'total_absent', 'Total Absent', '', '', ''),
(700, 'total_late', 'Total Late', '', '', ''),
(701, 'class_teacher_list', 'Class Teacher List', '', '', ''),
(702, 'section_control', 'Section Control', '', '', ''),
(703, 'capacity ', 'Capacity ', '', '', ''),
(704, 'request', 'Request', '', '', ''),
(705, 'salary_year', 'Salary Year', '', '', ''),
(706, 'create_attachments', 'Create Attachments', '', '', ''),
(707, 'publish_date', 'Publish Date', '', '', ''),
(708, 'attachment_file', 'Attachment File', '', '', ''),
(709, 'age', 'Age', '', '', ''),
(710, 'student_profile', 'Student Profile', '', '', ''),
(711, 'authentication', 'Authentication', '', '', ''),
(712, 'parent_information', 'Parent Information', '', '', ''),
(713, 'full_marks', 'Full Marks', '', '', ''),
(714, 'passing_marks', 'Passing Marks', '', '', ''),
(715, 'highest_marks', 'Highest Marks', '', '', ''),
(716, 'unknown', 'Unknown', '', '', ''),
(717, 'unpublish', 'Unpublish', '', '', ''),
(718, 'login_authentication_deactivate', 'Login Authentication Deactivate', '', '', ''),
(719, 'employee_profile', 'Employee Profile', '', '', ''),
(720, 'employee_details', 'Employee Details', '', '', ''),
(721, 'salary_transaction', 'Salary Transaction', '', '', ''),
(722, 'documents', 'Documents', '', '', ''),
(723, 'actions', 'Actions', '', '', ''),
(724, 'activity', 'Activity', '', '', ''),
(725, 'department_list', 'Department List', '', '', ''),
(726, 'manage_employee_salary', 'Manage Employee Salary', '', '', ''),
(727, 'the_configuration_has_been_updated', 'The Configuration Has Been Updated', '', '', ''),
(728, 'add', 'Add', '', '', ''),
(729, 'create_exam', 'Create Exam', '', '', ''),
(730, 'term', 'Term', '', '', ''),
(731, 'add_term', 'Add Term', '', '', ''),
(732, 'create_grade', 'Create Grade', '', '', ''),
(733, 'mark_starting', 'Mark Starting', '', '', ''),
(734, 'mark_until', 'Mark Until', '', '', ''),
(735, 'room_list', 'Room List', '', '', ''),
(736, 'room', 'Room', '', '', ''),
(737, 'route_list', 'Route List', '', '', ''),
(738, 'create_route', 'Create Route', '', '', ''),
(739, 'vehicle_list', 'Vehicle List', '', '', ''),
(740, 'create_vehicle', 'Create Vehicle', '', '', ''),
(741, 'stoppage_list', 'Stoppage List', '', '', ''),
(742, 'create_stoppage', 'Create Stoppage', '', '', ''),
(743, 'stop_time', 'Stop Time', '', '', ''),
(744, 'employee_attendance', 'Employee Attendance', '', '', ''),
(745, 'attendance_report', 'Attendance Report', '', '', ''),
(746, 'opening_balance', 'Opening Balance', '', '', ''),
(747, 'add_opening_balance', 'Add Opening Balance', '', '', ''),
(748, 'credit', 'Credit', '', '', ''),
(749, 'debit', 'Debit', '', '', ''),
(750, 'opening_balance_list', 'Opening Balance List', '', '', ''),
(751, 'voucher_list', 'Voucher List', '', '', ''),
(752, 'voucher_head', 'Voucher Head', '', '', ''),
(753, 'payment_method', 'Payment Method', '', '', ''),
(754, 'credit_ledger_account', 'Credit Ledger Account', '', '', ''),
(755, 'debit_ledger_account', 'Debit Ledger Account', '', '', ''),
(756, 'voucher_no', 'Voucher No', '', '', ''),
(757, 'balance', 'Balance', '', '', ''),
(758, 'event_details', 'Event Details', '', '', ''),
(759, 'welcome_to', 'Welcome To', '', '', ''),
(760, 'report_card', 'Report Card', '', '', ''),
(761, 'online_pay', 'Online Pay', '', '', ''),
(762, 'annual_fees_summary', 'Annual Fees Summary', '', '', ''),
(763, 'my_children', 'My Children', '', '', ''),
(764, 'assigned', 'Assigned', '', '', ''),
(765, 'confirm_password', 'Confirm Password', '', '', ''),
(766, 'searching_results', 'Searching Results', '', '', ''),
(767, 'information_has_been_saved_successfully', 'Information Has Been Saved Successfully', '', '', ''),
(768, 'information_deleted', 'The information has been successfully deleted', '', '', ''),
(769, 'deleted_note', '*Note : This data will be permanently deleted', '', '', ''),
(770, 'are_you_sure', 'Are You Sure?', '', '', ''),
(771, 'delete_this_information', 'Do You Want To Delete This Information?', '', '', ''),
(772, 'yes_continue', 'Yes, Continue', '', '', ''),
(773, 'deleted', 'Deleted', '', '', ''),
(774, 'collect', 'Collect', '', '', ''),
(775, 'school_setting', 'School Setting', '', '', ''),
(776, 'set', 'Set', '', '', ''),
(777, 'quick_view', 'Quick View', '', '', ''),
(778, 'due_fees_invoice', 'Due Fees Invoice', '', '', ''),
(779, 'my_application', 'My Application', '', '', ''),
(780, 'manage_application', 'Manage Application', '', '', ''),
(781, 'leave', 'Leave', '', '', ''),
(782, 'live_class_rooms', 'Live Class Rooms', '', '', ''),
(783, 'homework', 'Homework', '', '', ''),
(784, 'evaluation_report', 'Evaluation Report', '', '', ''),
(785, 'exam_term', 'Exam Term', '', '', ''),
(786, 'distribution', 'Distribution', '', '', ''),
(787, 'exam_setup', 'Exam Setup', '', '', ''),
(788, 'sms', 'Sms', '', '', ''),
(789, 'fees_type', 'Fees Type', '', '', ''),
(790, 'fees_group', 'Fees Group', '', '', ''),
(791, 'fine_setup', 'Fine Setup', '', '', ''),
(792, 'fees_reminder', 'Fees Reminder', '', '', ''),
(793, 'new_deposit', 'New Deposit', '', '', ''),
(794, 'new_expense', 'New Expense', '', '', ''),
(795, 'all_transactions', 'All Transactions', '', '', ''),
(796, 'head', 'Head', '', '', ''),
(797, 'fees_reports', 'Fees Reports', '', '', ''),
(798, 'fees_report', 'Fees Report', '', '', ''),
(799, 'receipts_report', 'Receipts Report', '', '', ''),
(800, 'due_fees_report', 'Due Fees Report', '', '', ''),
(801, 'fine_report', 'Fine Report', '', '', ''),
(802, 'financial_reports', 'Financial Reports', '', '', ''),
(803, 'statement', 'Statement', '', '', ''),
(804, 'repots', 'Repots', '', '', ''),
(805, 'expense', 'Expense', '', '', ''),
(806, 'transitions', 'Transitions', '', '', ''),
(807, 'sheet', 'Sheet', '', '', ''),
(808, 'income_vs_expense', 'Income Vs Expense', '', '', ''),
(809, 'attendance_reports', 'Attendance Reports', '', '', ''),
(810, 'examination', 'Examination', '', '', ''),
(811, 'school_settings', 'School Settings', '', '', ''),
(812, 'role_permission', 'Role Permission', '', '', ''),
(813, 'cron_job', 'Cron Job', '', '', ''),
(814, 'custom_field', 'Custom Field', '', '', ''),
(815, 'enter_valid_email', 'Enter Valid Email', '', '', ''),
(816, 'lessons', 'Lessons', '', '', ''),
(817, 'live_class', 'Live Class', '', '', ''),
(818, 'sl', 'Sl', '', '', ''),
(819, 'meeting_id', 'Meeting Id', '', '', ''),
(820, 'start_time', 'Start Time', '', '', ''),
(821, 'end_time', 'End Time', '', '', ''),
(822, 'zoom_meeting_id', 'Zoom Meeting Id', '', '', ''),
(823, 'zoom_meeting_password', 'Zoom Meeting Password', '', '', ''),
(824, 'time_slot', 'Time Slot', '', '', ''),
(825, 'send_notification_sms', 'Send Notification Sms', '', '', ''),
(826, 'host', 'Host', '', '', ''),
(827, 'school', 'School', '', '', ''),
(828, 'accounting_links', 'Accounting Links', '', '', ''),
(829, 'applicant', 'Applicant', '', '', ''),
(830, 'apply_date', 'Apply Date', '', '', ''),
(831, 'add_leave', 'Add Leave', '', '', ''),
(832, 'leave_date', 'Leave Date', '', '', ''),
(833, 'attachment', 'Attachment', '', '', ''),
(834, 'comments', 'Comments', '', '', ''),
(835, 'staff_id', 'Staff Id', '', '', ''),
(836, 'income_vs_expense_of', 'Income Vs Expense Of', '', '', ''),
(837, 'designation_name', 'Designation Name', '', '', ''),
(838, 'already_taken', 'This %s already exists.', '', '', ''),
(839, 'department_name', 'Department Name', '', '', ''),
(840, 'date_of_birth', 'Date Of Birth', '', '', ''),
(841, 'bulk_delete', 'Bulk Delete', '', '', ''),
(842, 'guardian_name', 'Guardian Name', '', '', ''),
(843, 'fees_progress', 'Fees Progress', '', '', ''),
(844, 'evaluate', 'Evaluate', '', '', ''),
(845, 'date_of_homework', 'Date Of Homework', '', '', ''),
(846, 'date_of_submission', 'Date Of Submission', '', '', ''),
(847, 'student_fees_report', 'Student Fees Report', '', '', ''),
(848, 'student_fees_reports', 'Student Fees Reports', '', '', ''),
(849, 'due_date', 'Due Date', '', '', ''),
(850, 'payment_date', 'Payment Date', '', '', ''),
(851, 'payment_via', 'Payment Via', '', '', ''),
(852, 'generate', 'Generate', '', '', ''),
(853, 'print_date', 'Print Date', '', '', ''),
(854, 'bulk_sms_and_email', 'Bulk Sms And Email', '', '', ''),
(855, 'campaign_type', 'Campaign Type', '', '', ''),
(856, 'both', 'Both', '', '', ''),
(857, 'regular', 'Regular', '', '', ''),
(858, 'Scheduled', 'Scheduled', '', '', ''),
(859, 'campaign', 'Campaign', '', '', ''),
(860, 'campaign_name', 'Campaign Name', '', '', ''),
(861, 'sms_gateway', 'Sms Gateway', '', '', ''),
(862, 'recipients_type', 'Recipients Type', '', '', ''),
(863, 'recipients_count', 'Recipients Count', '', '', ''),
(864, 'body', 'Body', '', '', ''),
(865, 'guardian_already_exist', 'Guardian Already Exist', '', '', ''),
(866, 'guardian', 'Guardian', '', '', ''),
(867, 'mother_name', 'Mother Name', '', '', ''),
(868, 'bank_details', 'Bank Details', '', '', ''),
(869, 'skipped_bank_details', 'Skipped Bank Details', '', '', ''),
(870, 'bank', 'Bank', '', '', ''),
(871, 'holder_name', 'Holder Name', '', '', ''),
(872, 'bank_branch', 'Bank Branch', '', '', ''),
(873, 'custom_field_for', 'Custom Field For', '', '', ''),
(874, 'label', 'Label', '', '', ''),
(875, 'order', 'Order', '', '', ''),
(876, 'online_admission', 'Online Admission', '', '', ''),
(877, 'field_label', 'Field Label', '', '', ''),
(878, 'field_type', 'Field Type', '', '', ''),
(879, 'default_value', 'Default Value', '', '', ''),
(880, 'checked', 'Checked', '', '', ''),
(881, 'unchecked', 'Unchecked', '', '', ''),
(882, 'roll_number', 'Roll Number', '', '', ''),
(883, 'add_rows', 'Add Rows', '', '', ''),
(884, 'salary', 'Salary', '', '', ''),
(885, 'basic', 'Basic', '', '', ''),
(886, 'allowance', 'Allowance', '', '', ''),
(887, 'deduction', 'Deduction', '', '', ''),
(888, 'net', 'Net', '', '', ''),
(889, 'activated_sms_gateway', 'Activated Sms Gateway', '', '', ''),
(890, 'account_sid', 'Account Sid', '', '', ''),
(891, 'roles', 'Roles', '', '', ''),
(892, 'system_role', 'System Role', '', '', ''),
(893, 'permission', 'Permission', '', '', ''),
(894, 'edit_session', 'Edit Session', '', '', ''),
(895, 'transactions', 'Transactions', '', '', ''),
(896, 'default_account', 'Default Account', '', '', ''),
(897, 'deposit', 'Deposit', '', '', ''),
(898, 'acccount', 'Acccount', '', '', ''),
(899, 'role_permission_for', 'Role Permission For', '', '', ''),
(900, 'feature', 'Feature', '', '', ''),
(901, 'access_denied', 'Access Denied', '', '', ''),
(902, 'time_start', 'Time Start', '', '', ''),
(903, 'time_end', 'Time End', '', '', ''),
(904, 'month_of_salary', 'Month Of Salary', '', '', ''),
(905, 'add_documents', 'Add Documents', '', '', ''),
(906, 'document_type', 'Document Type', '', '', ''),
(907, 'document', 'Document', '', '', ''),
(908, 'document_title', 'Document Title', '', '', ''),
(909, 'document_category', 'Document Category', '', '', ''),
(910, 'exam_result', 'Exam Result', '', '', ''),
(911, 'my_annual_fee_summary', 'My Annual Fee Summary', '', '', ''),
(912, 'book_manage', 'Book Manage', '', '', ''),
(913, 'add_leave_category', 'Add Leave Category', '', '', ''),
(914, 'edit_leave_category', 'Edit Leave Category', '', '', ''),
(915, 'staff_role', 'Staff Role', '', '', ''),
(916, 'edit_assign', 'Edit Assign', '', '', ''),
(917, 'view_report', 'View Report', '', '', ''),
(918, 'rank_out_of_5', 'Rank Out Of 5', '', '', ''),
(919, 'hall_no', 'Hall No', '', '', ''),
(920, 'no_of_seats', 'No Of Seats', '', '', ''),
(921, 'mark_distribution', 'Mark Distribution', '', '', ''),
(922, 'exam_type', 'Exam Type', '', '', ''),
(923, 'marks_and_grade', 'Marks And Grade', '', '', ''),
(924, 'min_percentage', 'Min Percentage', '', '', ''),
(925, 'max_percentage', 'Max Percentage', '', '', ''),
(926, 'cost_per_bed', 'Cost Per Bed', '', '', ''),
(927, 'add_category', 'Add Category', '', '', ''),
(928, 'category_for', 'Category For', '', '', ''),
(929, 'start_place', 'Start Place', '', '', ''),
(930, 'stop_place', 'Stop Place', '', '', ''),
(931, 'vehicle', 'Vehicle', '', '', ''),
(932, 'select_multiple_vehicle', 'Select Multiple Vehicle', '', '', ''),
(933, 'book_details', 'Book Details', '', '', ''),
(934, 'issued_by', 'Issued By', '', '', ''),
(935, 'return_by', 'Return By', '', '', ''),
(936, 'group', 'Group', '', '', ''),
(937, 'individual', 'Individual', '', '', ''),
(938, 'recipients', 'Recipients', '', '', ''),
(939, 'group_name', 'Group Name', '', '', ''),
(940, 'fee_code', 'Fee Code', '', '', ''),
(941, 'fine_type', 'Fine Type', '', '', ''),
(942, 'fine_value', 'Fine Value', '', '', ''),
(943, 'late_fee_frequency', 'Late Fee Frequency', '', '', ''),
(944, 'fixed_amount', 'Fixed Amount', '', '', ''),
(945, 'fixed', 'Fixed', '', '', ''),
(946, 'daily', 'Daily', '', '', ''),
(947, 'weekly', 'Weekly', '', '', ''),
(948, 'monthly', 'Monthly', '', '', ''),
(949, 'annually', 'Annually', '', '', ''),
(950, 'first_select_the_group', 'First Select The Group', '', '', ''),
(951, 'percentage', 'Percentage', '', '', ''),
(952, 'value', 'Value', '', '', ''),
(953, 'fee_group', 'Fee Group', '', '', ''),
(954, 'due_invoice', 'Due Invoice', '', '', ''),
(955, 'reminder', 'Reminder', '', '', ''),
(956, 'frequency', 'Frequency', '', '', ''),
(957, 'notify', 'Notify', '', '', ''),
(958, 'before', 'Before', '', '', ''),
(959, 'after', 'After', '', '', ''),
(960, 'number', 'Number', '', '', ''),
(961, 'ref_no', 'Ref No', '', '', ''),
(962, 'pay_via', 'Pay Via', '', '', ''),
(963, 'ref', 'Ref', '', '', ''),
(964, 'dr', 'Dr', '', '', ''),
(965, 'cr', 'Cr', '', '', ''),
(966, 'edit_book', 'Edit Book', '', '', ''),
(967, 'leaves', 'Leaves', '', '', ''),
(968, 'leave_request', 'Leave Request', '', '', ''),
(969, 'this_file_type_is_not_allowed', 'This File Type Is Not Allowed', '', '', ''),
(970, 'error_reading_the_file', 'Error Reading The File', '', '', ''),
(971, 'staff', 'Staff', '', '', ''),
(972, 'waiting', 'Waiting', '', '', ''),
(973, 'live', 'Live', '', '', ''),
(974, 'by', 'By', '', '', ''),
(975, 'host_live_class', 'Host Live Class', '', '', ''),
(976, 'join_live_class', 'Join Live Class', '', '', ''),
(977, 'system_logo', 'System Logo', '', '', ''),
(978, 'text_logo', 'Text Logo', '', '', ''),
(979, 'printing_logo', 'Printing Logo', '', '', ''),
(980, 'expired', 'Expired', '', '', ''),
(981, 'not_found_anything', 'Not Found Anything', '', '', ''),
(982, 'change', 'Change', '', '', ''),
(983, 'edit_branch', 'Edit Branch', '', '', ''),
(984, '', '', '', '', ''),
(985, 'current_password_is_invalid', 'Current Password Is Invalid', '', '', ''),
(986, 'create_section', 'Create Section', '', '', ''),
(987, 'section_list', 'Section List', '', '', ''),
(988, 'select_for_everyone', 'Select For Everyone', '', '', ''),
(989, 'belongs_to', 'Belongs To', '', '', ''),
(990, 'bs_column', 'Bs Column', '', '', ''),
(991, 'field_order', 'Field Order', '', '', ''),
(992, 'class_schedule', 'Class Schedule', '', '', ''),
(993, 'all', 'All', '', '', ''),
(994, 'write_message', 'Write Message', '', '', ''),
(995, 'discard', 'Discard', '', '', ''),
(996, 'email_subject', 'Email Subject', '', '', ''),
(997, 'language_unpublished', 'Language Unpublished', '', '', ''),
(998, 'language_published', 'Language Published', '', '', ''),
(999, 'search', 'Search', '', '', ''),
(1000, 'search', 'Search', '', '', ''),
(1001, 'search', 'Search', '', '', ''),
(1002, 'search', 'Search', '', '', ''),
(1003, 'search', 'Search', '', '', ''),
(1004, 'search', 'Search', '', '', ''),
(1005, 'search', 'Search', '', '', ''),
(1006, 'search', 'Search', '', '', ''),
(1007, 'search', 'Search', '', '', ''),
(1008, 'search', 'Search', '', '', ''),
(1009, 'search', 'Search', '', '', ''),
(1010, 'search', 'Search', '', '', ''),
(1011, 'search', 'Search', '', '', ''),
(1012, 'search', 'Search', '', '', '');
INSERT INTO `languages` (`id`, `word`, `english`, `bengali`, `arabic`, `french`) VALUES
(1013, 'translation_update', 'Translation Update', '', '', ''),
(1014, 'search', 'Search', '', '', ''),
(1015, 'search', 'Search', '', '', ''),
(1016, 'search', 'Search', '', '', ''),
(1017, 'search', 'Search', '', '', ''),
(1018, 'search', 'Search', '', '', ''),
(1019, 'search', 'Search', '', '', ''),
(1020, 'search', 'Search', '', '', ''),
(1021, 'search', 'Search', '', '', ''),
(1022, 'search', 'Search', '', '', ''),
(1023, 'search', 'Search', '', '', ''),
(1024, 'search', 'Search', '', '', ''),
(1025, 'search', 'Search', '', '', ''),
(1026, 'search', 'Search', '', '', ''),
(1027, 'search', 'Search', '', '', ''),
(1028, 'search', 'Search', '', '', ''),
(1029, 'search', 'Search', '', '', ''),
(1030, 'search', 'Search', '', '', ''),
(1031, 'search', 'Search', '', '', ''),
(1032, 'search', 'Search', '', '', ''),
(1033, 'search', 'Search', '', '', ''),
(1034, 'search', 'Search', '', '', ''),
(1035, 'search', 'Search', '', '', ''),
(1036, 'search', 'Search', '', '', ''),
(1037, 'search', 'Search', '', '', ''),
(1038, 'search', 'Search', '', '', ''),
(1039, 'search', 'Search', '', '', ''),
(1040, 'search', 'Search', '', '', ''),
(1041, 'search', 'Search', '', '', ''),
(1042, 'search', 'Search', '', '', ''),
(1043, 'search', 'Search', '', '', ''),
(1044, 'search', 'Search', '', '', ''),
(1045, 'search', 'Search', '', '', ''),
(1046, 'search', 'Search', '', '', ''),
(1047, 'search', 'Search', '', '', ''),
(1048, 'search', 'Search', '', '', ''),
(1049, 'search', 'Search', '', '', ''),
(1050, 'search', 'Search', '', '', ''),
(1051, 'search', 'Search', '', '', ''),
(1052, 'search', 'Search', '', '', ''),
(1053, 'search', 'Search', '', '', ''),
(1054, 'username_has_already_been_used', 'Username Has Already Been Used', '', '', ''),
(1055, 'search', 'Search', '', '', ''),
(1056, 'search', 'Search', '', '', ''),
(1057, 'search', 'Search', '', '', ''),
(1058, 'search', 'Search', '', '', ''),
(1059, 'search', 'Search', '', '', ''),
(1060, 'search', 'Search', '', '', ''),
(1061, 'search', 'Search', '', '', ''),
(1062, 'search', 'Search', '', '', ''),
(1063, 'search', 'Search', '', '', ''),
(1064, 'search', 'Search', '', '', ''),
(1065, 'search', 'Search', '', '', ''),
(1066, 'search', 'Search', '', '', ''),
(1067, 'search', 'Search', '', '', ''),
(1068, 'search', 'Search', '', '', ''),
(1069, 'search', 'Search', '', '', ''),
(1070, 'search', 'Search', '', '', ''),
(1071, 'search', 'Search', '', '', ''),
(1072, 'search', 'Search', '', '', ''),
(1073, 'search', 'Search', '', '', ''),
(1074, 'search', 'Search', '', '', ''),
(1075, 'search', 'Search', '', '', ''),
(1076, 'search', 'Search', '', '', ''),
(1077, 'search', 'Search', '', '', ''),
(1078, 'search', 'Search', '', '', ''),
(1079, 'search', 'Search', '', '', ''),
(1080, 'search', 'Search', '', '', ''),
(1081, 'search', 'Search', '', '', ''),
(1082, 'search', 'Search', '', '', ''),
(1083, 'search', 'Search', '', '', ''),
(1084, 'search', 'Search', '', '', ''),
(1085, 'search', 'Search', '', '', ''),
(1086, 'search', 'Search', '', '', ''),
(1087, 'search', 'Search', '', '', ''),
(1088, 'search', 'Search', '', '', ''),
(1089, 'search', 'Search', '', '', ''),
(1090, 'search', 'Search', '', '', ''),
(1091, 'search', 'Search', '', '', ''),
(1092, 'search', 'Search', '', '', ''),
(1093, 'search', 'Search', '', '', ''),
(1094, 'search', 'Search', '', '', ''),
(1095, 'search', 'Search', '', '', ''),
(1096, 'search', 'Search', '', '', ''),
(1097, 'search', 'Search', '', '', ''),
(1098, 'search', 'Search', '', '', ''),
(1099, 'search', 'Search', '', '', ''),
(1100, 'search', 'Search', '', '', ''),
(1101, 'search', 'Search', '', '', ''),
(1102, 'search', 'Search', '', '', ''),
(1103, 'search', 'Search', '', '', ''),
(1104, 'search', 'Search', '', '', ''),
(1105, 'search', 'Search', '', '', ''),
(1106, 'search', 'Search', '', '', ''),
(1107, 'search', 'Search', '', '', ''),
(1108, 'search', 'Search', '', '', ''),
(1109, 'search', 'Search', '', '', ''),
(1110, 'search', 'Search', '', '', ''),
(1111, 'search', 'Search', '', '', ''),
(1112, 'search', 'Search', '', '', ''),
(1113, 'search', 'Search', '', '', ''),
(1114, 'search', 'Search', '', '', ''),
(1115, 'search', 'Search', '', '', ''),
(1116, 'search', 'Search', '', '', ''),
(1117, 'search', 'Search', '', '', ''),
(1118, 'search', 'Search', '', '', ''),
(1119, 'search', 'Search', '', '', ''),
(1120, 'search', 'Search', '', '', ''),
(1121, 'search', 'Search', '', '', ''),
(1122, 'search', 'Search', '', '', ''),
(1123, 'search', 'Search', '', '', ''),
(1124, 'search', 'Search', '', '', ''),
(1125, 'search', 'Search', '', '', ''),
(1126, 'search', 'Search', '', '', ''),
(1127, 'search', 'Search', '', '', ''),
(1128, 'search', 'Search', '', '', ''),
(1129, 'search', 'Search', '', '', ''),
(1130, 'search', 'Search', '', '', ''),
(1131, 'search', 'Search', '', '', ''),
(1132, 'search', 'Search', '', '', ''),
(1133, 'search', 'Search', '', '', ''),
(1134, 'search', 'Search', '', '', ''),
(1135, 'search', 'Search', '', '', ''),
(1136, 'search', 'Search', '', '', ''),
(1137, 'search', 'Search', '', '', ''),
(1138, 'search', 'Search', '', '', ''),
(1139, 'search', 'Search', '', '', ''),
(1140, 'search', 'Search', '', '', ''),
(1141, 'search', 'Search', '', '', ''),
(1142, 'search', 'Search', '', '', ''),
(1143, 'search', 'Search', '', '', ''),
(1144, 'search', 'Search', '', '', ''),
(1145, 'search', 'Search', '', '', ''),
(1146, 'search', 'Search', '', '', ''),
(1147, 'search', 'Search', '', '', ''),
(1148, 'search', 'Search', '', '', ''),
(1149, 'search', 'Search', '', '', ''),
(1150, 'search', 'Search', '', '', ''),
(1151, 'search', 'Search', '', '', ''),
(1152, 'search', 'Search', '', '', ''),
(1153, 'search', 'Search', '', '', ''),
(1154, 'search', 'Search', '', '', ''),
(1155, 'search', 'Search', '', '', ''),
(1156, 'search', 'Search', '', '', ''),
(1157, 'search', 'Search', '', '', ''),
(1158, 'search', 'Search', '', '', ''),
(1159, 'search', 'Search', '', '', ''),
(1160, 'search', 'Search', '', '', ''),
(1161, 'search', 'Search', '', '', ''),
(1162, 'search', 'Search', '', '', ''),
(1163, 'search', 'Search', '', '', ''),
(1164, 'search', 'Search', '', '', ''),
(1165, 'search', 'Search', '', '', ''),
(1166, 'search', 'Search', '', '', ''),
(1167, 'search', 'Search', '', '', ''),
(1168, 'search', 'Search', '', '', ''),
(1169, 'search', 'Search', '', '', ''),
(1170, 'search', 'Search', '', '', ''),
(1171, 'search', 'Search', '', '', ''),
(1172, 'search', 'Search', '', '', ''),
(1173, 'search', 'Search', '', '', ''),
(1174, 'search', 'Search', '', '', ''),
(1175, 'search', 'Search', '', '', ''),
(1176, 'search', 'Search', '', '', ''),
(1177, 'search', 'Search', '', '', ''),
(1178, 'search', 'Search', '', '', ''),
(1179, 'search', 'Search', '', '', ''),
(1180, 'search', 'Search', '', '', ''),
(1181, 'search', 'Search', '', '', ''),
(1182, 'search', 'Search', '', '', ''),
(1183, 'search', 'Search', '', '', ''),
(1184, 'search', 'Search', '', '', ''),
(1185, 'search', 'Search', '', '', ''),
(1186, 'search', 'Search', '', '', ''),
(1187, 'search', 'Search', '', '', ''),
(1188, 'search', 'Search', '', '', ''),
(1189, 'search', 'Search', '', '', ''),
(1190, 'search', 'Search', '', '', ''),
(1191, 'search', 'Search', '', '', ''),
(1192, 'search', 'Search', '', '', ''),
(1193, 'search', 'Search', '', '', ''),
(1194, 'search', 'Search', '', '', ''),
(1195, 'search', 'Search', '', '', ''),
(1196, 'search', 'Search', '', '', ''),
(1197, 'search', 'Search', '', '', ''),
(1198, 'search', 'Search', '', '', ''),
(1199, 'search', 'Search', '', '', ''),
(1200, 'search', 'Search', '', '', ''),
(1201, 'search', 'Search', '', '', ''),
(1202, 'search', 'Search', '', '', ''),
(1203, 'search', 'Search', '', '', ''),
(1204, 'search', 'Search', '', '', ''),
(1205, 'search', 'Search', '', '', ''),
(1206, 'search', 'Search', '', '', ''),
(1207, 'search', 'Search', '', '', ''),
(1208, 'search', 'Search', '', '', ''),
(1209, 'search', 'Search', '', '', ''),
(1210, 'search', 'Search', '', '', ''),
(1211, 'selectssssss', 'Selectssssss', '', '', ''),
(1212, 'search', 'Search', '', '', ''),
(1213, 'search', 'Search', '', '', ''),
(1214, 'search', 'Search', '', '', ''),
(1215, 'search', 'Search', '', '', ''),
(1216, 'search', 'Search', '', '', ''),
(1217, 'search', 'Search', '', '', ''),
(1218, 'search', 'Search', '', '', ''),
(1219, 'search', 'Search', '', '', ''),
(1220, 'search', 'Search', '', '', ''),
(1221, 'search', 'Search', '', '', ''),
(1222, 'search', 'Search', '', '', ''),
(1223, 'search', 'Search', '', '', ''),
(1224, 'search', 'Search', '', '', ''),
(1225, 'search', 'Search', '', '', ''),
(1226, 'search', 'Search', '', '', ''),
(1227, 'search', 'Search', '', '', ''),
(1228, 'search', 'Search', '', '', ''),
(1229, 'search', 'Search', '', '', ''),
(1230, 'search', 'Search', '', '', ''),
(1231, 'search', 'Search', '', '', ''),
(1232, 'search', 'Search', '', '', ''),
(1233, 'search', 'Search', '', '', ''),
(1234, 'search', 'Search', '', '', ''),
(1235, 'search', 'Search', '', '', ''),
(1236, 'search', 'Search', '', '', ''),
(1237, 'search', 'Search', '', '', ''),
(1238, 'search', 'Search', '', '', ''),
(1239, 'search', 'Search', '', '', ''),
(1240, 'search', 'Search', '', '', ''),
(1241, 'search', 'Search', '', '', ''),
(1242, 'search', 'Search', '', '', ''),
(1243, 'search', 'Search', '', '', ''),
(1244, 'search', 'Search', '', '', ''),
(1245, 'search', 'Search', '', '', ''),
(1246, 'search', 'Search', '', '', ''),
(1247, 'search', 'Search', '', '', ''),
(1248, 'search', 'Search', '', '', ''),
(1249, 'search', 'Search', '', '', ''),
(1250, 'search', 'Search', '', '', ''),
(1251, 'search', 'Search', '', '', ''),
(1252, 'search', 'Search', '', '', ''),
(1253, 'search', 'Search', '', '', ''),
(1254, 'search', 'Search', '', '', ''),
(1255, 'search', 'Search', '', '', ''),
(1256, 'search', 'Search', '', '', ''),
(1257, 'search', 'Search', '', '', ''),
(1258, 'search', 'Search', '', '', ''),
(1259, 'search', 'Search', '', '', ''),
(1260, 'search', 'Search', '', '', ''),
(1261, 'search', 'Search', '', '', ''),
(1262, 'search', 'Search', '', '', ''),
(1263, 'search', 'Search', '', '', ''),
(1264, 'search', 'Search', '', '', ''),
(1265, 'search', 'Search', '', '', ''),
(1266, 'search', 'Search', '', '', ''),
(1267, 'search', 'Search', '', '', ''),
(1268, 'search', 'Search', '', '', ''),
(1269, 'search', 'Search', '', '', ''),
(1270, 'search', 'Search', '', '', ''),
(1271, 'search', 'Search', '', '', ''),
(1272, 'search', 'Search', '', '', ''),
(1273, 'search', 'Search', '', '', ''),
(1274, 'search', 'Search', '', '', ''),
(1275, 'search', 'Search', '', '', ''),
(1276, 'search', 'Search', '', '', ''),
(1277, 'search', 'Search', '', '', ''),
(1278, 'search', 'Search', '', '', ''),
(1279, 'search', 'Search', '', '', ''),
(1280, 'search', 'Search', '', '', ''),
(1281, 'search', 'Search', '', '', ''),
(1282, 'search', 'Search', '', '', ''),
(1283, 'search', 'Search', '', '', ''),
(1284, 'search', 'Search', '', '', ''),
(1285, 'search', 'Search', '', '', ''),
(1286, 'search', 'Search', '', '', ''),
(1287, 'edit_section', 'Edit Section', '', '', ''),
(1288, 'search', 'Search', '', '', ''),
(1289, 'search', 'Search', '', '', ''),
(1290, 'search', 'Search', '', '', ''),
(1291, 'search', 'Search', '', '', ''),
(1292, 'search', 'Search', '', '', ''),
(1293, 'search', 'Search', '', '', ''),
(1294, 'search', 'Search', '', '', ''),
(1295, 'search', 'Search', '', '', ''),
(1296, 'search', 'Search', '', '', ''),
(1297, 'search', 'Search', '', '', ''),
(1298, 'search', 'Search', '', '', ''),
(1299, 'search', 'Search', '', '', ''),
(1300, 'search', 'Search', '', '', ''),
(1301, 'search', 'Search', '', '', ''),
(1302, 'search', 'Search', '', '', ''),
(1303, 'search', 'Search', '', '', ''),
(1304, 'search', 'Search', '', '', ''),
(1305, 'search', 'Search', '', '', ''),
(1306, 'search', 'Search', '', '', ''),
(1307, 'search', 'Search', '', '', ''),
(1308, 'search', 'Search', '', '', ''),
(1309, 'search', 'Search', '', '', ''),
(1310, 'search', 'Search', '', '', ''),
(1311, 'search', 'Search', '', '', ''),
(1312, 'search', 'Search', '', '', ''),
(1313, 'search', 'Search', '', '', ''),
(1314, 'search', 'Search', '', '', ''),
(1315, 'search', 'Search', '', '', ''),
(1316, 'search', 'Search', '', '', ''),
(1317, 'search', 'Search', '', '', ''),
(1318, 'search', 'Search', '', '', ''),
(1319, 'search', 'Search', '', '', ''),
(1320, 'search', 'Search', '', '', ''),
(1321, 'search', 'Search', '', '', ''),
(1322, 'search', 'Search', '', '', ''),
(1323, 'search', 'Search', '', '', ''),
(1324, 'search', 'Search', '', '', ''),
(1325, 'search', 'Search', '', '', ''),
(1326, 'search', 'Search', '', '', ''),
(1327, 'search', 'Search', '', '', ''),
(1328, 'search', 'Search', '', '', ''),
(1329, 'search', 'Search', '', '', ''),
(1330, 'search', 'Search', '', '', ''),
(1331, 'search', 'Search', '', '', ''),
(1332, 'search', 'Search', '', '', ''),
(1333, 'search', 'Search', '', '', ''),
(1334, 'search', 'Search', '', '', ''),
(1335, 'search', 'Search', '', '', ''),
(1336, 'search', 'Search', '', '', ''),
(1337, 'search', 'Search', '', '', ''),
(1338, 'search', 'Search', '', '', ''),
(1339, 'search', 'Search', '', '', ''),
(1340, 'search', 'Search', '', '', ''),
(1341, 'search', 'Search', '', '', ''),
(1342, 'search', 'Search', '', '', ''),
(1343, 'search', 'Search', '', '', ''),
(1344, 'search', 'Search', '', '', ''),
(1345, 'search', 'Search', '', '', ''),
(1346, 'search', 'Search', '', '', ''),
(1347, 'search', 'Search', '', '', ''),
(1348, 'search', 'Search', '', '', ''),
(1349, 'search', 'Search', '', '', ''),
(1350, 'search', 'Search', '', '', ''),
(1351, 'search', 'Search', '', '', ''),
(1352, 'search', 'Search', '', '', ''),
(1353, 'search', 'Search', '', '', ''),
(1354, 'search', 'Search', '', '', ''),
(1355, 'search', 'Search', '', '', ''),
(1356, 'search', 'Search', '', '', ''),
(1357, 'search', 'Search', '', '', ''),
(1358, 'search', 'Search', '', '', ''),
(1359, 'search', 'Search', '', '', ''),
(1360, 'search', 'Search', '', '', ''),
(1361, 'search', 'Search', '', '', ''),
(1362, 'search', 'Search', '', '', ''),
(1363, 'search', 'Search', '', '', ''),
(1364, 'search', 'Search', '', '', ''),
(1365, 'search', 'Search', '', '', ''),
(1366, 'search', 'Search', '', '', ''),
(1367, 'search', 'Search', '', '', ''),
(1368, 'search', 'Search', '', '', ''),
(1369, 'search', 'Search', '', '', ''),
(1370, 'search', 'Search', '', '', ''),
(1371, 'search', 'Search', '', '', ''),
(1372, 'search', 'Search', '', '', ''),
(1373, 'search', 'Search', '', '', ''),
(1374, 'search', 'Search', '', '', ''),
(1375, 'search', 'Search', '', '', ''),
(1376, 'search', 'Search', '', '', ''),
(1377, 'search', 'Search', '', '', ''),
(1378, 'search', 'Search', '', '', ''),
(1379, 'search', 'Search', '', '', ''),
(1380, 'search', 'Search', '', '', ''),
(1381, 'search', 'Search', '', '', ''),
(1382, 'this_class_teacher_already_assigned', 'This Class Teacher Already Assigned', '', '', ''),
(1383, 'class_teachers_are_already_allocated_for_this_class', 'Class Teachers Are Already Allocated For This Class', '', '', ''),
(1384, 'search', 'Search', '', '', ''),
(1385, 'search', 'Search', '', '', ''),
(1386, 'search', 'Search', '', '', ''),
(1387, 'search', 'Search', '', '', ''),
(1388, 'search', 'Search', '', '', ''),
(1389, 'search', 'Search', '', '', ''),
(1390, 'search', 'Search', '', '', ''),
(1391, 'search', 'Search', '', '', ''),
(1392, 'search', 'Search', '', '', ''),
(1393, 'search', 'Search', '', '', ''),
(1394, 'search', 'Search', '', '', ''),
(1395, 'search', 'Search', '', '', ''),
(1396, 'search', 'Search', '', '', ''),
(1397, 'search', 'Search', '', '', ''),
(1398, 'search', 'Search', '', '', ''),
(1399, 'search', 'Search', '', '', ''),
(1400, 'search', 'Search', '', '', ''),
(1401, 'search', 'Search', '', '', ''),
(1402, 'search', 'Search', '', '', ''),
(1403, 'search', 'Search', '', '', ''),
(1404, 'search', 'Search', '', '', ''),
(1405, 'search', 'Search', '', '', ''),
(1406, 'search', 'Search', '', '', ''),
(1407, 'search', 'Search', '', '', ''),
(1408, 'search', 'Search', '', '', ''),
(1409, 'search', 'Search', '', '', ''),
(1410, 'search', 'Search', '', '', ''),
(1411, 'search', 'Search', '', '', ''),
(1412, 'search', 'Search', '', '', ''),
(1413, 'search', 'Search', '', '', ''),
(1414, 'search', 'Search', '', '', ''),
(1415, 'search', 'Search', '', '', ''),
(1416, 'search', 'Search', '', '', ''),
(1417, 'search', 'Search', '', '', ''),
(1418, 'search', 'Search', '', '', ''),
(1419, 'search', 'Search', '', '', ''),
(1420, 'search', 'Search', '', '', ''),
(1421, 'search', 'Search', '', '', ''),
(1422, 'search', 'Search', '', '', ''),
(1423, 'search', 'Search', '', '', ''),
(1424, 'search', 'Search', '', '', ''),
(1425, 'search', 'Search', '', '', ''),
(1426, 'search', 'Search', '', '', ''),
(1427, 'search', 'Search', '', '', ''),
(1428, 'search', 'Search', '', '', ''),
(1429, 'search', 'Search', '', '', ''),
(1430, 'search', 'Search', '', '', ''),
(1431, 'search', 'Search', '', '', ''),
(1432, 'search', 'Search', '', '', ''),
(1433, 'search', 'Search', '', '', ''),
(1434, 'search', 'Search', '', '', ''),
(1435, 'search', 'Search', '', '', ''),
(1436, 'search', 'Search', '', '', ''),
(1437, 'search', 'Search', '', '', ''),
(1438, 'search', 'Search', '', '', ''),
(1439, 'search', 'Search', '', '', ''),
(1440, 'search', 'Search', '', '', ''),
(1441, 'search', 'Search', '', '', ''),
(1442, 'search', 'Search', '', '', ''),
(1443, 'search', 'Search', '', '', ''),
(1444, 'search', 'Search', '', '', ''),
(1445, 'search', 'Search', '', '', ''),
(1446, 'search', 'Search', '', '', ''),
(1447, 'search', 'Search', '', '', ''),
(1448, 'search', 'Search', '', '', ''),
(1449, 'search', 'Search', '', '', ''),
(1450, 'search', 'Search', '', '', ''),
(1451, 'search', 'Search', '', '', ''),
(1452, 'search', 'Search', '', '', ''),
(1453, 'search', 'Search', '', '', ''),
(1454, 'search', 'Search', '', '', ''),
(1455, 'search', 'Search', '', '', ''),
(1456, 'search', 'Search', '', '', ''),
(1457, 'search', 'Search', '', '', ''),
(1458, 'search', 'Search', '', '', ''),
(1459, 'search', 'Search', '', '', ''),
(1460, 'search', 'Search', '', '', ''),
(1461, 'search', 'Search', '', '', ''),
(1462, 'search', 'Search', '', '', ''),
(1463, 'search', 'Search', '', '', ''),
(1464, 'search', 'Search', '', '', ''),
(1465, 'search', 'Search', '', '', ''),
(1466, 'search', 'Search', '', '', ''),
(1467, 'search', 'Search', '', '', ''),
(1468, 'search', 'Search', '', '', ''),
(1469, 'search', 'Search', '', '', ''),
(1470, 'search', 'Search', '', '', ''),
(1471, 'search', 'Search', '', '', ''),
(1472, 'search', 'Search', '', '', ''),
(1473, 'search', 'Search', '', '', ''),
(1474, 'search', 'Search', '', '', ''),
(1475, 'search', 'Search', '', '', ''),
(1476, 'search', 'Search', '', '', ''),
(1477, 'search', 'Search', '', '', ''),
(1478, 'search', 'Search', '', '', ''),
(1479, 'search', 'Search', '', '', ''),
(1480, 'search', 'Search', '', '', ''),
(1481, 'search', 'Search', '', '', ''),
(1482, 'search', 'Search', '', '', ''),
(1483, 'search', 'Search', '', '', ''),
(1484, 'search', 'Search', '', '', ''),
(1485, 'search', 'Search', '', '', ''),
(1486, 'search', 'Search', '', '', ''),
(1487, 'search', 'Search', '', '', ''),
(1488, 'search', 'Search', '', '', ''),
(1489, 'search', 'Search', '', '', ''),
(1490, 'search', 'Search', '', '', ''),
(1491, 'search', 'Search', '', '', ''),
(1492, 'search', 'Search', '', '', ''),
(1493, 'search', 'Search', '', '', ''),
(1494, 'search', 'Search', '', '', ''),
(1495, 'search', 'Search', '', '', ''),
(1496, 'search', 'Search', '', '', ''),
(1497, 'search', 'Search', '', '', ''),
(1498, 'search', 'Search', '', '', ''),
(1499, 'search', 'Search', '', '', ''),
(1500, 'search', 'Search', '', '', ''),
(1501, 'search', 'Search', '', '', ''),
(1502, 'search', 'Search', '', '', ''),
(1503, 'search', 'Search', '', '', ''),
(1504, 'search', 'Search', '', '', ''),
(1505, 'search', 'Search', '', '', ''),
(1506, 'search', 'Search', '', '', ''),
(1507, 'search', 'Search', '', '', ''),
(1508, 'search', 'Search', '', '', ''),
(1509, 'search', 'Search', '', '', ''),
(1510, 'search', 'Search', '', '', ''),
(1511, 'search', 'Search', '', '', ''),
(1512, 'search', 'Search', '', '', ''),
(1513, 'search', 'Search', '', '', ''),
(1514, 'search', 'Search', '', '', ''),
(1515, 'search', 'Search', '', '', ''),
(1516, 'search', 'Search', '', '', ''),
(1517, 'search', 'Search', '', '', ''),
(1518, 'search', 'Search', '', '', ''),
(1519, 'search', 'Search', '', '', ''),
(1520, 'search', 'Search', '', '', ''),
(1521, 'search', 'Search', '', '', ''),
(1522, 'search', 'Search', '', '', ''),
(1523, 'search', 'Search', '', '', ''),
(1524, 'search', 'Search', '', '', ''),
(1525, 'search', 'Search', '', '', ''),
(1526, 'search', 'Search', '', '', ''),
(1527, 'search', 'Search', '', '', ''),
(1528, 'search', 'Search', '', '', ''),
(1529, 'search', 'Search', '', '', ''),
(1530, 'search', 'Search', '', '', ''),
(1531, 'search', 'Search', '', '', ''),
(1532, 'search', 'Search', '', '', ''),
(1533, 'search', 'Search', '', '', ''),
(1534, 'search', 'Search', '', '', ''),
(1535, 'search', 'Search', '', '', ''),
(1536, 'search', 'Search', '', '', ''),
(1537, 'search', 'Search', '', '', ''),
(1538, 'search', 'Search', '', '', ''),
(1539, 'search', 'Search', '', '', ''),
(1540, 'search', 'Search', '', '', ''),
(1541, 'search', 'Search', '', '', ''),
(1542, 'search', 'Search', '', '', ''),
(1543, 'search', 'Search', '', '', ''),
(1544, 'search', 'Search', '', '', ''),
(1545, 'search', 'Search', '', '', ''),
(1546, 'search', 'Search', '', '', ''),
(1547, 'search', 'Search', '', '', ''),
(1548, 'search', 'Search', '', '', ''),
(1549, 'search', 'Search', '', '', ''),
(1550, 'search', 'Search', '', '', ''),
(1551, 'search', 'Search', '', '', ''),
(1552, 'search', 'Search', '', '', ''),
(1553, 'search', 'Search', '', '', ''),
(1554, 'search', 'Search', '', '', ''),
(1555, 'search', 'Search', '', '', ''),
(1556, 'search', 'Search', '', '', ''),
(1557, 'search', 'Search', '', '', ''),
(1558, 'search', 'Search', '', '', ''),
(1559, 'search', 'Search', '', '', ''),
(1560, 'search', 'Search', '', '', ''),
(1561, 'search', 'Search', '', '', ''),
(1562, 'search', 'Search', '', '', ''),
(1563, 'search', 'Search', '', '', ''),
(1564, 'search', 'Search', '', '', ''),
(1565, 'search', 'Search', '', '', ''),
(1566, 'search', 'Search', '', '', ''),
(1567, 'search', 'Search', '', '', ''),
(1568, 'search', 'Search', '', '', ''),
(1569, 'search', 'Search', '', '', ''),
(1570, 'search', 'Search', '', '', ''),
(1571, 'search', 'Search', '', '', ''),
(1572, 'search', 'Search', '', '', ''),
(1573, 'search', 'Search', '', '', ''),
(1574, 'search', 'Search', '', '', ''),
(1575, 'search', 'Search', '', '', ''),
(1576, 'search', 'Search', '', '', ''),
(1577, 'search', 'Search', '', '', ''),
(1578, 'search', 'Search', '', '', ''),
(1579, 'search', 'Search', '', '', ''),
(1580, 'search', 'Search', '', '', ''),
(1581, 'search', 'Search', '', '', ''),
(1582, 'search', 'Search', '', '', ''),
(1583, 'search', 'Search', '', '', ''),
(1584, 'search', 'Search', '', '', ''),
(1585, 'search', 'Search', '', '', ''),
(1586, 'search', 'Search', '', '', ''),
(1587, 'search', 'Search', '', '', ''),
(1588, 'search', 'Search', '', '', ''),
(1589, 'search', 'Search', '', '', ''),
(1590, 'search', 'Search', '', '', ''),
(1591, 'search', 'Search', '', '', ''),
(1592, 'search', 'Search', '', '', ''),
(1593, 'search', 'Search', '', '', ''),
(1594, 'search', 'Search', '', '', ''),
(1595, 'search', 'Search', '', '', ''),
(1596, 'search', 'Search', '', '', ''),
(1597, 'search', 'Search', '', '', ''),
(1598, 'search', 'Search', '', '', ''),
(1599, 'search', 'Search', '', '', ''),
(1600, 'search', 'Search', '', '', ''),
(1601, 'search', 'Search', '', '', ''),
(1602, 'search', 'Search', '', '', ''),
(1603, 'search', 'Search', '', '', ''),
(1604, 'search', 'Search', '', '', ''),
(1605, 'search', 'Search', '', '', ''),
(1606, 'search', 'Search', '', '', ''),
(1607, 'search', 'Search', '', '', ''),
(1608, 'search', 'Search', '', '', ''),
(1609, 'search', 'Search', '', '', ''),
(1610, 'search', 'Search', '', '', ''),
(1611, 'search', 'Search', '', '', ''),
(1612, 'search', 'Search', '', '', ''),
(1613, 'search', 'Search', '', '', ''),
(1614, 'search', 'Search', '', '', ''),
(1615, 'search', 'Search', '', '', ''),
(1616, 'search', 'Search', '', '', ''),
(1617, 'search', 'Search', '', '', ''),
(1618, 'search', 'Search', '', '', ''),
(1619, 'search', 'Search', '', '', ''),
(1620, 'search', 'Search', '', '', ''),
(1621, 'search', 'Search', '', '', ''),
(1622, 'search', 'Search', '', '', ''),
(1623, 'search', 'Search', '', '', ''),
(1624, 'search', 'Search', '', '', ''),
(1625, 'search', 'Search', '', '', ''),
(1626, 'search', 'Search', '', '', ''),
(1627, 'search', 'Search', '', '', ''),
(1628, 'search', 'Search', '', '', ''),
(1629, 'search', 'Search', '', '', ''),
(1630, 'search', 'Search', '', '', ''),
(1631, 'search', 'Search', '', '', ''),
(1632, 'search', 'Search', '', '', ''),
(1633, 'search', 'Search', '', '', ''),
(1634, 'search', 'Search', '', '', ''),
(1635, 'search', 'Search', '', '', ''),
(1636, 'search', 'Search', '', '', ''),
(1637, 'search', 'Search', '', '', ''),
(1638, 'search', 'Search', '', '', ''),
(1639, 'search', 'Search', '', '', ''),
(1640, 'search', 'Search', '', '', ''),
(1641, 'search', 'Search', '', '', ''),
(1642, 'search', 'Search', '', '', ''),
(1643, 'search', 'Search', '', '', ''),
(1644, 'search', 'Search', '', '', ''),
(1645, 'search', 'Search', '', '', ''),
(1646, 'search', 'Search', '', '', ''),
(1647, 'search', 'Search', '', '', ''),
(1648, 'search', 'Search', '', '', ''),
(1649, 'search', 'Search', '', '', ''),
(1650, 'search', 'Search', '', '', ''),
(1651, 'search', 'Search', '', '', ''),
(1652, 'search', 'Search', '', '', ''),
(1653, 'search', 'Search', '', '', ''),
(1654, 'search', 'Search', '', '', ''),
(1655, 'search', 'Search', '', '', ''),
(1656, 'search', 'Search', '', '', ''),
(1657, 'search', 'Search', '', '', ''),
(1658, 'search', 'Search', '', '', ''),
(1659, 'search', 'Search', '', '', ''),
(1660, 'search', 'Search', '', '', ''),
(1661, 'search', 'Search', '', '', ''),
(1662, 'search', 'Search', '', '', ''),
(1663, 'search', 'Search', '', '', ''),
(1664, 'search', 'Search', '', '', ''),
(1665, 'search', 'Search', '', '', ''),
(1666, 'search', 'Search', '', '', ''),
(1667, 'search', 'Search', '', '', ''),
(1668, 'search', 'Search', '', '', ''),
(1669, 'search', 'Search', '', '', ''),
(1670, 'search', 'Search', '', '', ''),
(1671, 'search', 'Search', '', '', ''),
(1672, 'search', 'Search', '', '', ''),
(1673, 'search', 'Search', '', '', ''),
(1674, 'search', 'Search', '', '', ''),
(1675, 'search', 'Search', '', '', ''),
(1676, 'search', 'Search', '', '', ''),
(1677, 'search', 'Search', '', '', ''),
(1678, 'search', 'Search', '', '', ''),
(1679, 'search', 'Search', '', '', ''),
(1680, 'search', 'Search', '', '', ''),
(1681, 'search', 'Search', '', '', ''),
(1682, 'search', 'Search', '', '', ''),
(1683, 'search', 'Search', '', '', ''),
(1684, 'search', 'Search', '', '', ''),
(1685, 'search', 'Search', '', '', ''),
(1686, 'search', 'Search', '', '', ''),
(1687, 'search', 'Search', '', '', ''),
(1688, 'search', 'Search', '', '', ''),
(1689, 'search', 'Search', '', '', ''),
(1690, 'search', 'Search', '', '', ''),
(1691, 'search', 'Search', '', '', ''),
(1692, 'search', 'Search', '', '', ''),
(1693, 'search', 'Search', '', '', ''),
(1694, 'search', 'Search', '', '', ''),
(1695, 'search', 'Search', '', '', ''),
(1696, 'search', 'Search', '', '', ''),
(1697, 'search', 'Search', '', '', ''),
(1698, 'search', 'Search', '', '', ''),
(1699, 'search', 'Search', '', '', ''),
(1700, 'search', 'Search', '', '', ''),
(1701, 'search', 'Search', '', '', ''),
(1702, 'search', 'Search', '', '', ''),
(1703, 'search', 'Search', '', '', ''),
(1704, 'search', 'Search', '', '', ''),
(1705, 'search', 'Search', '', '', ''),
(1706, 'search', 'Search', '', '', ''),
(1707, 'search', 'Search', '', '', ''),
(1708, 'search', 'Search', '', '', ''),
(1709, 'search', 'Search', '', '', ''),
(1710, 'search', 'Search', '', '', ''),
(1711, 'search', 'Search', '', '', ''),
(1712, 'search', 'Search', '', '', ''),
(1713, 'search', 'Search', '', '', ''),
(1714, 'search', 'Search', '', '', ''),
(1715, 'search', 'Search', '', '', ''),
(1716, 'search', 'Search', '', '', ''),
(1717, 'search', 'Search', '', '', ''),
(1718, 'search', 'Search', '', '', ''),
(1719, 'search', 'Search', '', '', ''),
(1720, 'search', 'Search', '', '', ''),
(1721, 'search', 'Search', '', '', ''),
(1722, 'search', 'Search', '', '', ''),
(1723, 'search', 'Search', '', '', ''),
(1724, 'search', 'Search', '', '', ''),
(1725, 'search', 'Search', '', '', ''),
(1726, 'search', 'Search', '', '', ''),
(1727, 'search', 'Search', '', '', ''),
(1728, 'search', 'Search', '', '', ''),
(1729, 'search', 'Search', '', '', ''),
(1730, 'search', 'Search', '', '', ''),
(1731, 'search', 'Search', '', '', ''),
(1732, 'search', 'Search', '', '', ''),
(1733, 'search', 'Search', '', '', ''),
(1734, 'search', 'Search', '', '', ''),
(1735, 'search', 'Search', '', '', ''),
(1736, 'search', 'Search', '', '', ''),
(1737, 'search', 'Search', '', '', ''),
(1738, 'search', 'Search', '', '', ''),
(1739, 'search', 'Search', '', '', ''),
(1740, 'search', 'Search', '', '', ''),
(1741, 'search', 'Search', '', '', ''),
(1742, 'search', 'Search', '', '', ''),
(1743, 'search', 'Search', '', '', ''),
(1744, 'search', 'Search', '', '', ''),
(1745, 'search', 'Search', '', '', ''),
(1746, 'search', 'Search', '', '', ''),
(1747, 'search', 'Search', '', '', ''),
(1748, 'search', 'Search', '', '', ''),
(1749, 'search', 'Search', '', '', ''),
(1750, 'search', 'Search', '', '', ''),
(1751, 'search', 'Search', '', '', ''),
(1752, 'search', 'Search', '', '', ''),
(1753, 'search', 'Search', '', '', ''),
(1754, 'search', 'Search', '', '', ''),
(1755, 'search', 'Search', '', '', ''),
(1756, 'search', 'Search', '', '', ''),
(1757, 'search', 'Search', '', '', ''),
(1758, 'search', 'Search', '', '', ''),
(1759, 'search', 'Search', '', '', ''),
(1760, 'search', 'Search', '', '', ''),
(1761, 'search', 'Search', '', '', ''),
(1762, 'search', 'Search', '', '', ''),
(1763, 'search', 'Search', '', '', ''),
(1764, 'search', 'Search', '', '', ''),
(1765, 'search', 'Search', '', '', ''),
(1766, 'search', 'Search', '', '', ''),
(1767, 'search', 'Search', '', '', ''),
(1768, 'search', 'Search', '', '', ''),
(1769, 'search', 'Search', '', '', ''),
(1770, 'search', 'Search', '', '', ''),
(1771, 'search', 'Search', '', '', ''),
(1772, 'search', 'Search', '', '', ''),
(1773, 'search', 'Search', '', '', ''),
(1774, 'search', 'Search', '', '', ''),
(1775, 'search', 'Search', '', '', ''),
(1776, 'search', 'Search', '', '', ''),
(1777, 'search', 'Search', '', '', ''),
(1778, 'search', 'Search', '', '', ''),
(1779, 'search', 'Search', '', '', ''),
(1780, 'search', 'Search', '', '', ''),
(1781, 'search', 'Search', '', '', ''),
(1782, 'search', 'Search', '', '', ''),
(1783, 'search', 'Search', '', '', ''),
(1784, 'search', 'Search', '', '', ''),
(1785, 'search', 'Search', '', '', ''),
(1786, 'search', 'Search', '', '', ''),
(1787, 'search', 'Search', '', '', ''),
(1788, 'search', 'Search', '', '', ''),
(1789, 'search', 'Search', '', '', ''),
(1790, 'search', 'Search', '', '', ''),
(1791, 'search', 'Search', '', '', ''),
(1792, 'search', 'Search', '', '', ''),
(1793, 'search', 'Search', '', '', ''),
(1794, 'search', 'Search', '', '', ''),
(1795, 'search', 'Search', '', '', ''),
(1796, 'search', 'Search', '', '', ''),
(1797, 'search', 'Search', '', '', ''),
(1798, 'search', 'Search', '', '', ''),
(1799, 'search', 'Search', '', '', ''),
(1800, 'search', 'Search', '', '', ''),
(1801, 'search', 'Search', '', '', ''),
(1802, 'search', 'Search', '', '', ''),
(1803, 'student_promotion', 'Student Promotion', '', '', ''),
(1804, 'search', 'Search', '', '', ''),
(1805, 'the_next_session_was_transferred_to_the_students', 'The Next Session Was Transferred To The Students', '', '', ''),
(1806, 'promote_to_session', 'Promote To Session', '', '', ''),
(1807, 'promote_to_class', 'Promote To Class', '', '', ''),
(1808, 'promote_to_section', 'Promote To Section', '', '', ''),
(1809, 'mark_summary', 'Mark Summary', '', '', ''),
(1810, 'search', 'Search', '', '', ''),
(1811, 'search', 'Search', '', '', ''),
(1812, 'search', 'Search', '', '', ''),
(1813, 'search', 'Search', '', '', ''),
(1814, 'search', 'Search', '', '', ''),
(1815, 'search', 'Search', '', '', ''),
(1816, 'search', 'Search', '', '', ''),
(1817, 'search', 'Search', '', '', ''),
(1818, 'search', 'Search', '', '', ''),
(1819, 'promote_section_id', 'Promote Section Id', '', '', ''),
(1820, 'the_%s_is_already_exists.', 'The %s Is Already Exists.', '', '', ''),
(1821, 'search', 'Search', '', '', ''),
(1822, 'search', 'Search', '', '', ''),
(1823, 'search', 'Search', '', '', ''),
(1824, 'search', 'Search', '', '', ''),
(1825, 'search', 'Search', '', '', ''),
(1826, 'search', 'Search', '', '', ''),
(1827, 'search', 'Search', '', '', ''),
(1828, 'search', 'Search', '', '', ''),
(1829, 'search', 'Search', '', '', ''),
(1830, 'search', 'Search', '', '', ''),
(1831, 'search', 'Search', '', '', ''),
(1832, 'search', 'Search', '', '', ''),
(1833, 'search', 'Search', '', '', ''),
(1834, 'search', 'Search', '', '', ''),
(1835, 'search', 'Search', '', '', ''),
(1836, 'search', 'Search', '', '', ''),
(1837, 'search', 'Search', '', '', ''),
(1838, 'search', 'Search', '', '', ''),
(1839, 'search', 'Search', '', '', ''),
(1840, 'search', 'Search', '', '', ''),
(1841, 'search', 'Search', '', '', ''),
(1842, 'search', 'Search', '', '', ''),
(1843, 'search', 'Search', '', '', ''),
(1844, 'search', 'Search', '', '', ''),
(1845, 'search', 'Search', '', '', ''),
(1846, 'search', 'Search', '', '', ''),
(1847, 'search', 'Search', '', '', ''),
(1848, 'search', 'Search', '', '', ''),
(1849, 'search', 'Search', '', '', ''),
(1850, 'search', 'Search', '', '', ''),
(1851, 'search', 'Search', '', '', ''),
(1852, 'search', 'Search', '', '', ''),
(1853, 'search', 'Search', '', '', ''),
(1854, 'search', 'Search', '', '', ''),
(1855, 'search', 'Search', '', '', ''),
(1856, 'search', 'Search', '', '', ''),
(1857, 'search', 'Search', '', '', ''),
(1858, 'search', 'Search', '', '', ''),
(1859, 'search', 'Search', '', '', ''),
(1860, 'search', 'Search', '', '', ''),
(1861, 'search', 'Search', '', '', ''),
(1862, 'search', 'Search', '', '', ''),
(1863, 'search', 'Search', '', '', ''),
(1864, 'search', 'Search', '', '', ''),
(1865, 'search', 'Search', '', '', ''),
(1866, 'search', 'Search', '', '', ''),
(1867, 'search', 'Search', '', '', ''),
(1868, 'search', 'Search', '', '', ''),
(1869, 'search', 'Search', '', '', ''),
(1870, 'search', 'Search', '', '', ''),
(1871, 'search', 'Search', '', '', ''),
(1872, 'search', 'Search', '', '', ''),
(1873, 'search', 'Search', '', '', ''),
(1874, 'search', 'Search', '', '', ''),
(1875, 'search', 'Search', '', '', ''),
(1876, 'search', 'Search', '', '', ''),
(1877, 'search', 'Search', '', '', ''),
(1878, 'search', 'Search', '', '', ''),
(1879, 'search', 'Search', '', '', ''),
(1880, 'search', 'Search', '', '', ''),
(1881, 'search', 'Search', '', '', ''),
(1882, 'search', 'Search', '', '', ''),
(1883, 'search', 'Search', '', '', ''),
(1884, 'search', 'Search', '', '', ''),
(1885, 'search', 'Search', '', '', ''),
(1886, 'search', 'Search', '', '', ''),
(1887, 'search', 'Search', '', '', ''),
(1888, 'search', 'Search', '', '', ''),
(1889, 'search', 'Search', '', '', ''),
(1890, 'search', 'Search', '', '', ''),
(1891, 'search', 'Search', '', '', ''),
(1892, 'search', 'Search', '', '', ''),
(1893, 'search', 'Search', '', '', ''),
(1894, 'search', 'Search', '', '', ''),
(1895, 'search', 'Search', '', '', ''),
(1896, 'search', 'Search', '', '', ''),
(1897, 'search', 'Search', '', '', ''),
(1898, 'search', 'Search', '', '', ''),
(1899, 'search', 'Search', '', '', ''),
(1900, 'search', 'Search', '', '', ''),
(1901, 'search', 'Search', '', '', ''),
(1902, 'search', 'Search', '', '', ''),
(1903, 'search', 'Search', '', '', ''),
(1904, 'search', 'Search', '', '', ''),
(1905, 'search', 'Search', '', '', ''),
(1906, 'search', 'Search', '', '', ''),
(1907, 'search', 'Search', '', '', ''),
(1908, 'search', 'Search', '', '', ''),
(1909, 'search', 'Search', '', '', ''),
(1910, 'search', 'Search', '', '', ''),
(1911, 'search', 'Search', '', '', ''),
(1912, 'search', 'Search', '', '', ''),
(1913, 'search', 'Search', '', '', ''),
(1914, 'search', 'Search', '', '', ''),
(1915, 'search', 'Search', '', '', ''),
(1916, 'search', 'Search', '', '', ''),
(1917, 'search', 'Search', '', '', ''),
(1918, 'search', 'Search', '', '', ''),
(1919, 'search', 'Search', '', '', ''),
(1920, 'search', 'Search', '', '', ''),
(1921, 'search', 'Search', '', '', ''),
(1922, 'search', 'Search', '', '', ''),
(1923, 'search', 'Search', '', '', ''),
(1924, 'search', 'Search', '', '', ''),
(1925, 'search', 'Search', '', '', ''),
(1926, 'search', 'Search', '', '', ''),
(1927, 'search', 'Search', '', '', ''),
(1928, 'search', 'Search', '', '', ''),
(1929, 'search', 'Search', '', '', ''),
(1930, 'search', 'Search', '', '', ''),
(1931, 'search', 'Search', '', '', ''),
(1932, 'search', 'Search', '', '', ''),
(1933, 'search', 'Search', '', '', ''),
(1934, 'search', 'Search', '', '', ''),
(1935, 'search', 'Search', '', '', ''),
(1936, 'search', 'Search', '', '', ''),
(1937, 'search', 'Search', '', '', ''),
(1938, 'search', 'Search', '', '', ''),
(1939, 'search', 'Search', '', '', ''),
(1940, 'search', 'Search', '', '', ''),
(1941, 'search', 'Search', '', '', ''),
(1942, 'search', 'Search', '', '', ''),
(1943, 'search', 'Search', '', '', ''),
(1944, 'search', 'Search', '', '', ''),
(1945, 'search', 'Search', '', '', ''),
(1946, 'search', 'Search', '', '', ''),
(1947, 'search', 'Search', '', '', ''),
(1948, 'search', 'Search', '', '', ''),
(1949, 'search', 'Search', '', '', ''),
(1950, 'search', 'Search', '', '', ''),
(1951, 'search', 'Search', '', '', ''),
(1952, 'search', 'Search', '', '', ''),
(1953, 'search', 'Search', '', '', ''),
(1954, 'search', 'Search', '', '', ''),
(1955, 'search', 'Search', '', '', ''),
(1956, 'search', 'Search', '', '', ''),
(1957, 'search', 'Search', '', '', ''),
(1958, 'search', 'Search', '', '', ''),
(1959, 'search', 'Search', '', '', ''),
(1960, 'search', 'Search', '', '', ''),
(1961, 'search', 'Search', '', '', ''),
(1962, 'search', 'Search', '', '', ''),
(1963, 'search', 'Search', '', '', ''),
(1964, 'search', 'Search', '', '', ''),
(1965, 'search', 'Search', '', '', ''),
(1966, 'search', 'Search', '', '', ''),
(1967, 'search', 'Search', '', '', ''),
(1968, 'search', 'Search', '', '', ''),
(1969, 'search', 'Search', '', '', ''),
(1970, 'search', 'Search', '', '', ''),
(1971, 'search', 'Search', '', '', ''),
(1972, 'search', 'Search', '', '', ''),
(1973, 'search', 'Search', '', '', ''),
(1974, 'search', 'Search', '', '', ''),
(1975, 'search', 'Search', '', '', ''),
(1976, 'search', 'Search', '', '', ''),
(1977, 'search', 'Search', '', '', ''),
(1978, 'search', 'Search', '', '', ''),
(1979, 'search', 'Search', '', '', ''),
(1980, 'search', 'Search', '', '', ''),
(1981, 'search', 'Search', '', '', ''),
(1982, 'search', 'Search', '', '', ''),
(1983, 'search', 'Search', '', '', ''),
(1984, 'search', 'Search', '', '', ''),
(1985, 'search', 'Search', '', '', ''),
(1986, 'search', 'Search', '', '', ''),
(1987, 'search', 'Search', '', '', ''),
(1988, 'search', 'Search', '', '', ''),
(1989, 'search', 'Search', '', '', ''),
(1990, 'search', 'Search', '', '', ''),
(1991, 'search', 'Search', '', '', ''),
(1992, 'search', 'Search', '', '', ''),
(1993, 'search', 'Search', '', '', ''),
(1994, 'search', 'Search', '', '', ''),
(1995, 'search', 'Search', '', '', ''),
(1996, 'search', 'Search', '', '', ''),
(1997, 'search', 'Search', '', '', ''),
(1998, 'search', 'Search', '', '', ''),
(1999, 'search', 'Search', '', '', ''),
(2000, 'search', 'Search', '', '', ''),
(2001, 'search', 'Search', '', '', ''),
(2002, 'search', 'Search', '', '', ''),
(2003, 'search', 'Search', '', '', ''),
(2004, 'search', 'Search', '', '', ''),
(2005, 'search', 'Search', '', '', ''),
(2006, 'search', 'Search', '', '', ''),
(2007, 'search', 'Search', '', '', ''),
(2008, 'search', 'Search', '', '', ''),
(2009, 'search', 'Search', '', '', ''),
(2010, 'search', 'Search', '', '', ''),
(2011, 'search', 'Search', '', '', ''),
(2012, 'search', 'Search', '', '', ''),
(2013, 'search', 'Search', '', '', ''),
(2014, 'search', 'Search', '', '', ''),
(2015, 'search', 'Search', '', '', ''),
(2016, 'search', 'Search', '', '', ''),
(2017, 'search', 'Search', '', '', ''),
(2018, 'search', 'Search', '', '', ''),
(2019, 'search', 'Search', '', '', ''),
(2020, 'search', 'Search', '', '', ''),
(2021, 'search', 'Search', '', '', ''),
(2022, 'search', 'Search', '', '', ''),
(2023, 'search', 'Search', '', '', ''),
(2024, 'search', 'Search', '', '', ''),
(2025, 'search', 'Search', '', '', ''),
(2026, 'search', 'Search', '', '', ''),
(2027, 'search', 'Search', '', '', ''),
(2028, 'search', 'Search', '', '', ''),
(2029, 'search', 'Search', '', '', ''),
(2030, 'search', 'Search', '', '', ''),
(2031, 'search', 'Search', '', '', ''),
(2032, 'search', 'Search', '', '', ''),
(2033, 'search', 'Search', '', '', ''),
(2034, 'search', 'Search', '', '', ''),
(2035, 'search', 'Search', '', '', ''),
(2036, 'search', 'Search', '', '', ''),
(2037, 'search', 'Search', '', '', ''),
(2038, 'search', 'Search', '', '', ''),
(2039, 'search', 'Search', '', '', ''),
(2040, 'search', 'Search', '', '', ''),
(2041, 'search', 'Search', '', '', ''),
(2042, 'search', 'Search', '', '', ''),
(2043, 'search', 'Search', '', '', ''),
(2044, 'search', 'Search', '', '', ''),
(2045, 'search', 'Search', '', '', ''),
(2046, 'search', 'Search', '', '', ''),
(2047, 'search', 'Search', '', '', ''),
(2048, 'search', 'Search', '', '', ''),
(2049, 'search', 'Search', '', '', ''),
(2050, 'search', 'Search', '', '', ''),
(2051, 'parents_profile', 'Parents Profile', '', '', ''),
(2052, 'childs', 'Childs', '', '', ''),
(2053, 'search', 'Search', '', '', ''),
(2054, 'search', 'Search', '', '', ''),
(2055, 'search', 'Search', '', '', ''),
(2056, 'search', 'Search', '', '', ''),
(2057, 'search', 'Search', '', '', ''),
(2058, 'search', 'Search', '', '', ''),
(2059, 'search', 'Search', '', '', ''),
(2060, 'search', 'Search', '', '', ''),
(2061, 'search', 'Search', '', '', ''),
(2062, 'search', 'Search', '', '', ''),
(2063, 'search', 'Search', '', '', ''),
(2064, 'search', 'Search', '', '', ''),
(2065, 'search', 'Search', '', '', ''),
(2066, 'search', 'Search', '', '', ''),
(2067, 'search', 'Search', '', '', ''),
(2068, 'search', 'Search', '', '', ''),
(2069, 'search', 'Search', '', '', ''),
(2070, 'search', 'Search', '', '', ''),
(2071, 'search', 'Search', '', '', ''),
(2072, 'search', 'Search', '', '', ''),
(2073, 'search', 'Search', '', '', ''),
(2074, 'search', 'Search', '', '', ''),
(2075, 'search', 'Search', '', '', ''),
(2076, 'search', 'Search', '', '', ''),
(2077, 'search', 'Search', '', '', ''),
(2078, 'search', 'Search', '', '', ''),
(2079, 'search', 'Search', '', '', ''),
(2080, 'search', 'Search', '', '', ''),
(2081, 'search', 'Search', '', '', ''),
(2082, 'search', 'Search', '', '', ''),
(2083, 'search', 'Search', '', '', ''),
(2084, 'search', 'Search', '', '', ''),
(2085, 'search', 'Search', '', '', ''),
(2086, 'search', 'Search', '', '', ''),
(2087, 'search', 'Search', '', '', ''),
(2088, 'search', 'Search', '', '', ''),
(2089, 'search', 'Search', '', '', ''),
(2090, 'search', 'Search', '', '', ''),
(2091, 'search', 'Search', '', '', ''),
(2092, 'search', 'Search', '', '', ''),
(2093, 'search', 'Search', '', '', ''),
(2094, 'search', 'Search', '', '', ''),
(2095, 'search', 'Search', '', '', ''),
(2096, 'search', 'Search', '', '', ''),
(2097, 'search', 'Search', '', '', ''),
(2098, 'search', 'Search', '', '', ''),
(2099, 'search', 'Search', '', '', ''),
(2100, 'search', 'Search', '', '', ''),
(2101, 'search', 'Search', '', '', ''),
(2102, 'search', 'Search', '', '', ''),
(2103, 'search', 'Search', '', '', ''),
(2104, 'search', 'Search', '', '', ''),
(2105, 'search', 'Search', '', '', ''),
(2106, 'search', 'Search', '', '', ''),
(2107, 'search', 'Search', '', '', ''),
(2108, 'search', 'Search', '', '', ''),
(2109, 'search', 'Search', '', '', ''),
(2110, 'search', 'Search', '', '', ''),
(2111, 'search', 'Search', '', '', ''),
(2112, 'search', 'Search', '', '', ''),
(2113, 'search', 'Search', '', '', ''),
(2114, 'search', 'Search', '', '', ''),
(2115, 'search', 'Search', '', '', ''),
(2116, 'search', 'Search', '', '', ''),
(2117, 'search', 'Search', '', '', ''),
(2118, 'search', 'Search', '', '', ''),
(2119, 'search', 'Search', '', '', ''),
(2120, 'search', 'Search', '', '', ''),
(2121, 'search', 'Search', '', '', ''),
(2122, 'search', 'Search', '', '', ''),
(2123, 'search', 'Search', '', '', ''),
(2124, 'search', 'Search', '', '', ''),
(2125, 'search', 'Search', '', '', ''),
(2126, 'search', 'Search', '', '', ''),
(2127, 'search', 'Search', '', '', ''),
(2128, 'search', 'Search', '', '', ''),
(2129, 'search', 'Search', '', '', ''),
(2130, 'search', 'Search', '', '', ''),
(2131, 'search', 'Search', '', '', ''),
(2132, 'search', 'Search', '', '', ''),
(2133, 'search', 'Search', '', '', ''),
(2134, 'search', 'Search', '', '', ''),
(2135, 'search', 'Search', '', '', ''),
(2136, 'search', 'Search', '', '', ''),
(2137, 'search', 'Search', '', '', ''),
(2138, 'search', 'Search', '', '', ''),
(2139, 'search', 'Search', '', '', ''),
(2140, 'search', 'Search', '', '', ''),
(2141, 'search', 'Search', '', '', ''),
(2142, 'search', 'Search', '', '', ''),
(2143, 'search', 'Search', '', '', ''),
(2144, 'search', 'Search', '', '', ''),
(2145, 'search', 'Search', '', '', ''),
(2146, 'search', 'Search', '', '', ''),
(2147, 'search', 'Search', '', '', ''),
(2148, 'search', 'Search', '', '', ''),
(2149, 'search', 'Search', '', '', ''),
(2150, 'search', 'Search', '', '', ''),
(2151, 'search', 'Search', '', '', ''),
(2152, 'search', 'Search', '', '', ''),
(2153, 'search', 'Search', '', '', ''),
(2154, 'search', 'Search', '', '', ''),
(2155, 'search', 'Search', '', '', ''),
(2156, 'search', 'Search', '', '', ''),
(2157, 'search', 'Search', '', '', ''),
(2158, 'search', 'Search', '', '', ''),
(2159, 'search', 'Search', '', '', ''),
(2160, 'search', 'Search', '', '', ''),
(2161, 'search', 'Search', '', '', ''),
(2162, 'search', 'Search', '', '', ''),
(2163, 'search', 'Search', '', '', ''),
(2164, 'search', 'Search', '', '', ''),
(2165, 'search', 'Search', '', '', ''),
(2166, 'search', 'Search', '', '', ''),
(2167, 'search', 'Search', '', '', ''),
(2168, 'search', 'Search', '', '', ''),
(2169, 'search', 'Search', '', '', ''),
(2170, 'search', 'Search', '', '', ''),
(2171, 'search', 'Search', '', '', ''),
(2172, 'search', 'Search', '', '', ''),
(2173, 'search', 'Search', '', '', ''),
(2174, 'search', 'Search', '', '', ''),
(2175, 'search', 'Search', '', '', ''),
(2176, 'this_class_and_section_is_already_ assigned.', 'This Class And Section Is Already  Assigned.', '', '', ''),
(2177, 'this_field_is_required', 'This Field Is Required', '', '', ''),
(2178, 'search', 'Search', '', '', ''),
(2179, 'search', 'Search', '', '', ''),
(2180, 'search', 'Search', '', '', ''),
(2181, 'search', 'Search', '', '', ''),
(2182, 'search', 'Search', '', '', ''),
(2183, 'search', 'Search', '', '', ''),
(2184, 'search', 'Search', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `language_list`
--

CREATE TABLE IF NOT EXISTS `language_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lang_field` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `language_list`
--

INSERT INTO `language_list` (`id`, `name`, `lang_field`, `status`, `created_at`, `updated_at`) VALUES
(1, 'English', 'english', 1, '2023-05-05 14:36:17', '2023-05-05 14:36:17'),
(2, 'Bengali', 'bengali', 1, '2023-05-05 14:37:47', '2023-05-05 14:37:47'),
(3, 'Arabic', 'arabic', 1, '2023-05-05 16:29:04', '2023-05-05 16:29:04'),
(4, 'French', 'french', 1, '2023-05-05 16:29:41', '2023-05-10 13:45:43');

-- --------------------------------------------------------

--
-- Table structure for table `leave_application`
--

CREATE TABLE IF NOT EXISTS `leave_application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `category_id` int(2) NOT NULL,
  `reason` longtext CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `leave_days` varchar(20) NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT 1 COMMENT '1=pending,2=accepted 3=rejected',
  `apply_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `approved_by` int(11) NOT NULL,
  `orig_file_name` varchar(255) NOT NULL,
  `enc_file_name` varchar(255) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_category`
--

CREATE TABLE IF NOT EXISTS `leave_category` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `role_id` tinyint(1) NOT NULL,
  `days` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_credential`
--

CREATE TABLE IF NOT EXISTS `login_credential` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `role` tinyint(2) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1(active) 0(deactivate)',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `login_credential`
--

INSERT INTO `login_credential` (`id`, `user_id`, `username`, `password`, `role`, `active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin@admin.com', '$2y$10$A2Q8iSy0IXmkzayG31JXpu4D1b3mKy3tHQ.VOAf2mGrtESV8GI.mK', 1, 1, '2023-04-24 19:32:36', '2020-05-31 13:06:26', '2023-04-25 07:32:36'),
(2, 2, 'cpetruskevich0@paypal.com', '$2y$10$djiaFJl/SrSPomlLAmfDsOZK3eLvYG0nvCx8.ROpVS1ZHUkwwo4.K', 3, 1, NULL, '2023-05-16 20:19:22', '2023-05-16 20:19:22'),
(3, 3, 'badney1@jigsy.com', '$2y$10$qhlMIeLsQwwlix7Rv4tu0eVGhGGKtB3QXcp6Wydca523nJSh54rgO', 3, 1, NULL, '2023-05-16 20:19:22', '2023-05-16 20:19:22'),
(4, 4, 'khowes2@hp.com', '$2y$10$KKzKBJCtsLfyEM42rIMdXefVMS/FAVF9.bnlON/8Be8ppeDjh8pjO', 3, 1, NULL, '2023-05-16 20:19:22', '2023-05-16 20:19:22'),
(5, 5, 'hdyett3@sogou.com', '$2y$10$/Y0dVKZ1.F4YzKa76.STEOmSpH3m9q5bjK5lOS/FNpnDZnSeaC.Y2', 3, 1, NULL, '2023-05-16 20:19:22', '2023-05-16 20:19:22'),
(6, 6, 'jrobion4@gmpg.org', '$2y$10$jcqhe0lY1q98hbKZ4NsMKeJm3SKPG.W/TYaeNFytkTJXHk4asvgD2', 3, 1, NULL, '2023-05-16 20:19:22', '2023-05-16 20:19:22'),
(7, 2, 'bmattholie0@irs.gov', '$2y$10$CzDI5HZsBdmNTnXzemUa7ec/mdYNeujWG5fINLHeKR1jmtIIUe6BO', 6, 1, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(8, 1, 'bmattholie0@icq.com', '$2y$10$DhpHN/F5cAonWn1ttuJ3wulVpdkrTfX7BNif5GXdUA639hLm7mCcC', 7, 1, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(9, 3, 'nkonmann1@pinterest.com', '$2y$10$YPG8yo1hyC9oO7lcwVSzb.VxbIgTkrtRdFfmf1I2ABHhePq.mUfQO', 6, 1, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(10, 2, 'nkonmann1@edublogs.org', '$2y$10$/KLKUddaVTGmKXO4vMA9heOMcfxzw4A.qpGMHb6DYI2FBk8xk/5oK', 7, 1, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(11, 4, 'labberley2@unblog.fr', '$2y$10$nW6yz0lflgGdQtLeFrweyOABoBtS4UsuAesT.l5x46ki/JmrQdI5G', 6, 1, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(12, 3, 'labberley2@ycombinator.com', '$2y$10$WUqgylejuAeYV1ngqCeZgukJx10o6n.DocrkeAEVAg7UsqFAJEtr2', 7, 1, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(13, 5, 'gtooting3@nymag.com', '$2y$10$zkZgm3ly18WLXxquXGeRAuLhxJgm66D0BmQ.b7d0.KMAeeK0Pwzfu', 6, 1, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(14, 4, 'gtooting3@bloglovin.com', '$2y$10$zxurJPJAXMP6X79PPRIYC.U4ciCYSF0Z2jY9eZ7EhMqZGBpQQ1y/O', 7, 1, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(15, 6, 'bbodker4@ameblo.jp', '$2y$10$t5KtRqJBfgtiuaL2U48DE.8Oo6AAP8CnIAG88mbeCus/hwhnszNae', 6, 1, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(16, 5, 'bbodker4@pinterest.com', '$2y$10$XfSA.Aoj2qVdo7BkK5vV1OyMapu1H55yQfLY4laa8cWNa7FTEW6aa', 7, 1, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(17, 7, 'rdowdeswell5@blogtalkradio.com', '$2y$10$bgtqz/uQpA8s/L0/eC8tQ.9JrJVtCEOhz9FjEqI1xsMQ40dIeQ9kW', 6, 1, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(18, 6, 'rdowdeswell5@fastcompany.com', '$2y$10$0rg/Y7e4ukCQOBxD2hIPHOulb2r4iTZmQwzPqr.KZzuw9hN15rAS.', 7, 1, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37'),
(19, 8, 'mwalkinshaw6@answers.com', '$2y$10$pTYhYeAvTYNmR/I4mmknp.4l6Mww0AyryPHHHT3ptPhp0BbmZzbCW', 6, 1, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(20, 7, 'mwalkinshaw6@ox.ac.uk', '$2y$10$HBH2YDSUIMCmZWFOfSMgo.5LYEAlt5mxJOSDgTHuyTYkYAEhpe8OG', 7, 1, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(21, 9, 'evanetti7@mozilla.org', '$2y$10$.ANhrYzi4yAS6xGYbzAEyOzxwESuoQqDfsEaBo92gHIRJEjIs3JIS', 6, 1, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(22, 8, 'evanetti7@tamu.edu', '$2y$10$Kwg.EVLn2QKy2mwdNEg36.wvMAjbl3njtmdHrzasQubCoEGSexty6', 7, 1, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(23, 10, 'lkneeland8@facebook.com', '$2y$10$gFb8k9DTkIrL0MFuIM5bWuUhgW3uOsDdHpD9ouJ386AA/i4gJjNGm', 6, 1, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(24, 9, 'lkneeland8@nih.gov', '$2y$10$eVXNDyqUK.W8H5H/aiF1qeJ1rofE2uuXonDfInVm082xf2LH3Y5Jq', 7, 1, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(25, 11, 'eouterbridge9@yahoo.co.jp', '$2y$10$BeMkyXuA0mgY1KmoGqtOpeKzT1neu6xfKIzf6Dnw.dTRlJv3wLUey', 6, 1, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(26, 10, 'eouterbridge9@harvard.edu', '$2y$10$SnLjEig4Xz8qb6vup4/bN.nXEW673kgw7KkojJeFpg6YHMLj63kCq', 7, 1, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(27, 12, 'mjoshama@histats.com', '$2y$10$KqzMebC21SKGcj1hz4/Eye9PzTrQxFdJPoaj/2F8qHvgs4BoSr4ha', 6, 1, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(28, 11, 'mjoshama@salon.com', '$2y$10$O04x4vonD3ICh.lZ4HNiRuq.OuTBAXbjMITVH2WTfoiKnzLQ4e1n2', 7, 1, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(29, 13, 'mreinersb@prweb.com', '$2y$10$O.ibWWkq4pVplfyZ8gRR8ujYWR8xCbE8ezUqtuel71QB.3ANneyde', 6, 1, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(30, 12, 'mreinersb@ifeng.com', '$2y$10$dv1FKuQSnguh1D./SogDv.a/crqh0uAdNRNr4LwxJLsAV3zXXRkla', 7, 1, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(31, 14, 'imatoninc@netlog.com', '$2y$10$XxbVKJZBLUA.zbIPzNIkeu0Rqyp3TAFYFFiuzbVIlY32wYik.nL0u', 6, 1, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(32, 13, 'imatoninc@lulu.com', '$2y$10$NkU0Menb.tB9j2mAsaEn4.ru7f/nuEBFhU87fRqYKQfwfzVOwj/aC', 7, 1, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38'),
(33, 15, 'lsommerledd@ebay.co.uk', '$2y$10$/UA2idYvECHMw59i1FtTCO8xXLlBGzTLzUSudRSRaQFAJKD2w6mAq', 6, 1, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(34, 14, 'lsommerledd@histats.com', '$2y$10$mGJ1Ja9iPWf3quFNUtbEveEeLxt.40aZTiDbCLFhcDYwEztjRu4dq', 7, 1, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(35, 16, 'ftrimee@pbs.org', '$2y$10$ciuQrq8XdwMCMh.K8lvxNej06dHn7h5GemWksbhvVRSnXzfuMOxLO', 6, 1, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(36, 15, 'ftrimee@opensource.org', '$2y$10$lveGJwQvLyXVn1P5avmqRe3OlTuljA6xjRcOwoccneFqlIXpUlxPy', 7, 1, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(37, 17, 'covitzf@wp.com', '$2y$10$cDWqv3Gxn0x/W0L4UuJIb.F26DH/oPrm666klVcGkcaQVwQ25p/Eq', 6, 1, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(38, 16, 'covitzf@webmd.com', '$2y$10$jcFzVsVDTxjEnST.lm9TBeAlNRiprqadeDtjQmvTQz709IcAEmDJu', 7, 1, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(39, 18, 'hconnerryg@topsy.com', '$2y$10$UYDAmWG4VWwcl8jyErnZ8eDp68.oG652mSlRrr707njjTND8Halt6', 6, 1, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(40, 17, 'hconnerryg@wsj.com', '$2y$10$wPw8Lz8625y01/05KfMn2u6igE8pmxcGbaRYig9sC5nXDngnLEK46', 7, 1, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(41, 19, 'cpillingtonh@salon.com', '$2y$10$0kOOS5dZyFxs77qvQH.nUu0aZglaSvoqM/R8VQ/qljMOQefFEn33W', 6, 1, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(42, 18, 'cpillingtonh@mashable.com', '$2y$10$9Zkh3MIZ1fFxuBBHQitmN.uIXRd/oiSFX8n9Ah/fHCLcxxLiq5s3a', 7, 1, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(43, 20, 'jmccolleyi@discovery.com', '$2y$10$hnBk4bSTlK1xpPDSKY1DzuFMKFSayr6RG4baWgmJQLUS.ojbwv8S2', 6, 1, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(44, 19, 'jmccolleyi@mlb.com', '$2y$10$RvDuwu2Wgw1fQT.zqPvKmO9t4SLA4JKSXSwwalz89CLSOJyf1U5GS', 7, 1, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(45, 21, 'meldrittj@wikispaces.com', '$2y$10$ngz8BxWBnJ6a6peFxjsgpec4JUAZYjM7p8goSKEJshQ9KpQAaM06e', 6, 1, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39'),
(46, 20, 'meldrittj@plala.or.jp', '$2y$10$SfbWUi5rvxskVIhQrOx9q.kzeqxyZdlvOeM5fc/ap2D.62rrlhrN.', 7, 1, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39');

-- --------------------------------------------------------

--
-- Table structure for table `mark`
--

CREATE TABLE IF NOT EXISTS `mark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `mark` text DEFAULT NULL,
  `absent` varchar(4) DEFAULT NULL,
  `session_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `subject` varchar(255) NOT NULL,
  `file_name` text DEFAULT NULL,
  `enc_name` text DEFAULT NULL,
  `trash_sent` tinyint(1) NOT NULL,
  `trash_inbox` int(11) NOT NULL,
  `fav_inbox` tinyint(1) NOT NULL,
  `fav_sent` tinyint(1) NOT NULL,
  `reciever` varchar(100) NOT NULL,
  `sender` varchar(100) NOT NULL,
  `read_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 unread 1 read',
  `reply_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 unread 1 read',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_reply`
--

CREATE TABLE IF NOT EXISTS `message_reply` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `message_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `file_name` text NOT NULL,
  `enc_name` text NOT NULL,
  `identity` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parent`
--

CREATE TABLE IF NOT EXISTS `parent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `relation` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `father_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `mother_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `occupation` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `income` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `education` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mobileno` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `facebook_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `linkedin_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` tinyint(2) NOT NULL DEFAULT 0 COMMENT '0(active) 1(deactivate)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parent`
--

INSERT INTO `parent` (`id`, `name`, `relation`, `father_name`, `mother_name`, `occupation`, `income`, `education`, `email`, `mobileno`, `address`, `city`, `state`, `branch_id`, `photo`, `facebook_url`, `linkedin_url`, `twitter_url`, `created_at`, `updated_at`, `active`) VALUES
(1, 'Mr Kingsley', 'Father', 'Kingsley', '', 'Police Officer', '1230000', '', 'kingsley@parent.com', '', '', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 19:48:53', '2023-05-16 19:48:53', 0),
(2, 'Brandie Mattholie', 'Sibling', 'Brandie Mattholie', 'Brandie Mattholie', 'VP Sales', '', '', 'bmattholie0@irs.gov', '972-206-3523', '040 Melvin Way', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37', 0),
(3, 'Natalee Konmann', 'Mother', 'Natalee Konmann', 'Natalee Konmann', 'Accountant II', '', '', 'nkonmann1@pinterest.com', '342-792-3389', '5471 Buell Point', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37', 0),
(4, 'Luisa Abberley', 'Sibling', 'Luisa Abberley', 'Luisa Abberley', 'Cost Accountant', '', '', 'labberley2@unblog.fr', '811-814-9453', '334 Tennyson Crossing', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37', 0),
(5, 'Gilberte Tooting', 'Grandparent', 'Gilberte Tooting', 'Gilberte Tooting', 'Software Consultant', '', '', 'gtooting3@nymag.com', '729-499-4537', '7340 South Junction', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37', 0),
(6, 'Brigida Bodker', 'Guardian', 'Brigida Bodker', 'Brigida Bodker', 'Administrative Officer', '', '', 'bbodker4@ameblo.jp', '541-774-5313', '74 Springs Alley', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37', 0),
(7, 'Rufus Dowdeswell', 'Sibling', 'Rufus Dowdeswell', 'Rufus Dowdeswell', 'VP Accounting', '', '', 'rdowdeswell5@blogtalkradio.com', '429-283-7476', '6 Schiller Avenue', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37', 0),
(8, 'Marlee Walkinshaw', 'Father', 'Marlee Walkinshaw', 'Marlee Walkinshaw', 'Accounting Assistant III', '', '', 'mwalkinshaw6@answers.com', '636-479-7053', '479 Kingsford Hill', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:37', '2023-05-16 20:29:37', 0),
(9, 'Elvin Vanetti', 'Father', 'Elvin Vanetti', 'Elvin Vanetti', 'Web Developer II', '', '', 'evanetti7@mozilla.org', '148-173-9865', '0 Eagan Point', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38', 0),
(10, 'Lea Kneeland', 'Mother', 'Lea Kneeland', 'Lea Kneeland', 'Quality Engineer', '', '', 'lkneeland8@facebook.com', '465-897-1582', '9565 Esker Center', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38', 0),
(11, 'Eyde Outerbridge', 'Mother', 'Eyde Outerbridge', 'Eyde Outerbridge', 'Geological Engineer', '', '', 'eouterbridge9@yahoo.co.jp', '659-773-8462', '8381 Farwell Way', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38', 0),
(12, 'Marinna Josham', 'Father', 'Marinna Josham', 'Marinna Josham', 'Help Desk Operator', '', '', 'mjoshama@histats.com', '352-301-0873', '58 Clove Way', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38', 0),
(13, 'Moselle Reiners', 'Father', 'Moselle Reiners', 'Moselle Reiners', 'Marketing Assistant', '', '', 'mreinersb@prweb.com', '294-250-8806', '9 Muir Park', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38', 0),
(14, 'Izaak Matonin', 'Father', 'Izaak Matonin', 'Izaak Matonin', 'Automation Specialist II', '', '', 'imatoninc@netlog.com', '210-790-8196', '0 Ludington Crossing', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38', 0),
(15, 'Leigh Sommerled', 'Grandparent', 'Leigh Sommerled', 'Leigh Sommerled', 'Environmental Specialist', '', '', 'lsommerledd@ebay.co.uk', '445-996-7899', '1 Pawling Center', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:38', '2023-05-16 20:29:38', 0),
(16, 'Fredek Trime', 'Father', 'Fredek Trime', 'Fredek Trime', 'Professor', '', '', 'ftrimee@pbs.org', '353-652-8730', '16229 Transport Center', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39', 0),
(17, 'Cammy Ovitz', 'Father', 'Cammy Ovitz', 'Cammy Ovitz', 'Help Desk Technician', '', '', 'covitzf@wp.com', '500-530-3549', '09 Pennsylvania Trail', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39', 0),
(18, 'Hollis Connerry', 'Mother', 'Hollis Connerry', 'Hollis Connerry', 'Design Engineer', '', '', 'hconnerryg@topsy.com', '754-478-3982', '6 Derek Crossing', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39', 0),
(19, 'Chen Pillington', 'Sibling', 'Chen Pillington', 'Chen Pillington', 'Environmental Tech', '', '', 'cpillingtonh@salon.com', '335-138-2560', '9276 Manitowish Parkway', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39', 0),
(20, 'Judy McColley', 'Grandparent', 'Judy McColley', 'Judy McColley', 'Clinical Specialist', '', '', 'jmccolleyi@discovery.com', '677-382-4898', '03685 Pankratz Plaza', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39', 0),
(21, 'Mason Eldritt', 'Grandparent', 'Mason Eldritt', 'Mason Eldritt', 'Programmer Analyst II', '', '', 'meldrittj@wikispaces.com', '433-105-0730', '592 Oxford Way', '', '', 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:29:39', '2023-05-16 20:29:39', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment_config`
--

CREATE TABLE IF NOT EXISTS `payment_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_username` varchar(255) DEFAULT NULL,
  `paypal_password` varchar(255) DEFAULT NULL,
  `paypal_signature` varchar(255) DEFAULT NULL,
  `paypal_email` varchar(255) DEFAULT NULL,
  `paypal_sandbox` tinyint(4) DEFAULT NULL,
  `paypal_status` tinyint(4) DEFAULT NULL,
  `stripe_secret` varchar(255) DEFAULT NULL,
  `stripe_demo` varchar(255) DEFAULT NULL,
  `stripe_status` tinyint(4) DEFAULT NULL,
  `payumoney_key` varchar(255) DEFAULT NULL,
  `payumoney_salt` varchar(255) DEFAULT NULL,
  `payumoney_demo` tinyint(4) DEFAULT NULL,
  `payumoney_status` tinyint(4) DEFAULT NULL,
  `paystack_secret_key` varchar(255) NOT NULL,
  `paystack_status` tinyint(4) NOT NULL,
  `razorpay_key_id` varchar(255) NOT NULL,
  `razorpay_key_secret` varchar(255) NOT NULL,
  `razorpay_demo` tinyint(4) NOT NULL,
  `razorpay_status` tinyint(4) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_salary_stipend`
--

CREATE TABLE IF NOT EXISTS `payment_salary_stipend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payslip_id` int(11) NOT NULL,
  `name` longtext NOT NULL,
  `amount` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_types`
--

CREATE TABLE IF NOT EXISTS `payment_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `branch_id` int(11) NOT NULL DEFAULT 0,
  `timestamp` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `payment_types`
--

INSERT INTO `payment_types` (`id`, `name`, `branch_id`, `timestamp`) VALUES
(1, 'Cash', 0, '2019-07-27 18:12:21'),
(2, 'Card', 0, '2019-07-27 18:12:31'),
(3, 'Cheque', 0, '2019-12-21 10:07:59'),
(4, 'Bank Transfer', 0, '2019-12-21 10:08:36'),
(5, 'Other', 0, '2019-12-21 10:08:45'),
(6, 'Paypal', 0, '2019-12-21 10:08:45'),
(7, 'Stripe', 0, '2019-12-21 10:08:45'),
(8, 'PayUmoney', 0, '2019-12-21 10:08:45'),
(9, 'Paystack', 0, '2019-12-21 10:08:45'),
(10, 'Razorpay', 0, '2019-12-21 10:08:45');

-- --------------------------------------------------------

--
-- Table structure for table `payslip`
--

CREATE TABLE IF NOT EXISTS `payslip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `month` varchar(200) DEFAULT NULL,
  `year` varchar(20) NOT NULL,
  `basic_salary` decimal(18,2) NOT NULL,
  `total_allowance` decimal(18,2) NOT NULL,
  `total_deduction` decimal(18,2) NOT NULL,
  `net_salary` decimal(18,2) NOT NULL,
  `bill_no` varchar(25) NOT NULL,
  `remarks` text NOT NULL,
  `pay_via` tinyint(1) NOT NULL,
  `hash` varchar(200) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `paid_by` varchar(200) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payslip_details`
--

CREATE TABLE IF NOT EXISTS `payslip_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payslip_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `prefix` varchar(100) NOT NULL,
  `show_view` tinyint(1) DEFAULT 1,
  `show_add` tinyint(1) DEFAULT 1,
  `show_edit` tinyint(1) DEFAULT 1,
  `show_delete` tinyint(1) DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`) VALUES
(1, 2, 'Student', 'student', 1, 1, 1, 1, '2020-01-22 17:45:47'),
(2, 2, 'Multiple Import', 'multiple_import', 0, 1, 0, 0, '2020-01-22 17:45:47'),
(3, 2, 'Student Category', 'student_category', 1, 1, 1, 1, '2020-01-22 17:45:47'),
(4, 2, 'Student Id Card', 'student_id_card', 1, 0, 0, 0, '2020-01-22 17:45:47'),
(5, 2, 'Disable Authentication', 'student_disable_authentication', 1, 1, 0, 0, '2020-01-22 17:45:47'),
(6, 4, 'Employee', 'employee', 1, 1, 1, 1, '2020-01-22 17:55:19'),
(7, 3, 'Parent', 'parent', 1, 1, 1, 1, '2020-01-22 19:24:05'),
(8, 3, 'Disable Authentication', 'parent_disable_authentication', 1, 1, 0, 0, '2020-01-22 20:22:21'),
(9, 4, 'Department', 'department', 1, 1, 1, 1, '2020-01-22 23:41:39'),
(10, 4, 'Designation', 'designation', 1, 1, 1, 1, '2020-01-22 23:41:39'),
(11, 4, 'Disable Authentication', 'employee_disable_authentication', 1, 1, 0, 0, '2020-01-22 23:41:39'),
(12, 5, 'Salary Template', 'salary_template', 1, 1, 1, 1, '2020-01-23 11:13:57'),
(13, 5, 'Salary Assign', 'salary_assign', 1, 1, 0, 0, '2020-01-23 11:14:05'),
(14, 5, 'Salary Payment', 'salary_payment', 1, 1, 0, 0, '2020-01-24 12:45:40'),
(15, 5, 'Salary Summary Report', 'salary_summary_report', 1, 0, 0, 0, '2020-03-14 23:09:17'),
(16, 5, 'Advance Salary', 'advance_salary', 1, 1, 1, 1, '2020-01-29 00:23:39'),
(17, 5, 'Advance Salary Manage', 'advance_salary_manage', 1, 1, 1, 1, '2020-01-25 10:57:12'),
(18, 5, 'Advance Salary Request', 'advance_salary_request', 1, 1, 0, 1, '2020-01-28 23:49:58'),
(19, 5, 'Leave Category', 'leave_category', 1, 1, 1, 1, '2020-01-29 08:46:23'),
(20, 5, 'Leave Request', 'leave_request', 1, 1, 1, 1, '2020-01-30 18:06:33'),
(21, 5, 'Leave Manage', 'leave_manage', 1, 1, 1, 1, '2020-01-29 13:27:15'),
(22, 5, 'Award', 'award', 1, 1, 1, 1, '2020-02-01 00:49:11'),
(23, 6, 'Classes', 'classes', 1, 1, 1, 1, '2020-02-02 00:10:00'),
(24, 6, 'Section', 'section', 1, 1, 1, 1, '2020-02-02 03:06:44'),
(25, 6, 'Assign Class Teacher', 'assign_class_teacher', 1, 1, 1, 1, '2020-02-02 13:09:22'),
(26, 6, 'Subject', 'subject', 1, 1, 1, 1, '2020-02-03 10:32:39'),
(27, 6, 'Subject Class Assign ', 'subject_class_assign', 1, 1, 1, 1, '2020-02-03 23:43:19'),
(28, 6, 'Subject Teacher Assign', 'subject_teacher_assign', 1, 1, 0, 1, '2020-02-04 01:05:11'),
(29, 6, 'Class Timetable', 'class_timetable', 1, 1, 1, 1, '2020-02-04 11:50:37'),
(30, 2, 'Student Promotion', 'student_promotion', 1, 1, 0, 0, '2020-02-06 00:20:30'),
(31, 8, 'Attachments', 'attachments', 1, 1, 1, 1, '2020-02-06 23:59:43'),
(32, 7, 'Homework', 'homework', 1, 1, 1, 1, '2020-02-07 11:40:08'),
(33, 8, 'Attachment Type', 'attachment_type', 1, 1, 1, 1, '2020-02-07 13:16:28'),
(34, 9, 'Exam', 'exam', 1, 1, 1, 1, '2020-02-07 15:59:29'),
(35, 9, 'Exam Term', 'exam_term', 1, 1, 1, 1, '2020-02-07 18:09:28'),
(36, 9, 'Exam Hall', 'exam_hall', 1, 1, 1, 1, '2020-02-07 20:31:04'),
(37, 9, 'Exam Timetable', 'exam_timetable', 1, 1, 0, 1, '2020-02-08 23:04:31'),
(38, 9, 'Exam Mark', 'exam_mark', 1, 1, 1, 1, '2020-02-10 18:53:41'),
(39, 9, 'Exam Grade', 'exam_grade', 1, 1, 1, 1, '2020-02-10 23:29:16'),
(40, 10, 'Hostel', 'hostel', 1, 1, 1, 1, '2020-02-11 10:41:36'),
(41, 10, 'Hostel Category', 'hostel_category', 1, 1, 1, 1, '2020-02-11 13:52:31'),
(42, 10, 'Hostel Room', 'hostel_room', 1, 1, 1, 1, '2020-02-11 17:50:09'),
(43, 10, 'Hostel Allocation', 'hostel_allocation', 1, 0, 0, 1, '2020-02-11 19:06:15'),
(44, 11, 'Transport Route', 'transport_route', 1, 1, 1, 1, '2020-02-12 11:26:19'),
(45, 11, 'Transport Vehicle', 'transport_vehicle', 1, 1, 1, 1, '2020-02-12 11:57:30'),
(46, 11, 'Transport Stoppage', 'transport_stoppage', 1, 1, 1, 1, '2020-02-12 12:49:20'),
(47, 11, 'Transport Assign', 'transport_assign', 1, 1, 1, 1, '2020-02-12 15:55:21'),
(48, 11, 'Transport Allocation', 'transport_allocation', 1, 0, 0, 1, '2020-02-13 01:33:05'),
(49, 12, 'Student Attendance', 'student_attendance', 0, 1, 0, 0, '2020-02-13 11:25:53'),
(50, 12, 'Employee Attendance', 'employee_attendance', 0, 1, 0, 0, '2020-02-13 16:04:16'),
(51, 12, 'Exam Attendance', 'exam_attendance', 0, 1, 0, 0, '2020-02-13 17:08:14'),
(52, 12, 'Student Attendance Report', 'student_attendance_report', 1, 0, 0, 0, '2020-02-14 01:20:56'),
(53, 12, 'Employee Attendance Report', 'employee_attendance_report', 1, 0, 0, 0, '2020-02-14 12:08:53'),
(54, 12, 'Exam Attendance Report', 'exam_attendance_report', 1, 0, 0, 0, '2020-02-14 12:21:40'),
(55, 13, 'Book', 'book', 1, 1, 1, 1, '2020-02-14 12:40:42'),
(56, 13, 'Book Category', 'book_category', 1, 1, 1, 1, '2020-02-15 10:11:41'),
(57, 13, 'Book Manage', 'book_manage', 1, 1, 0, 1, '2020-02-15 17:13:24'),
(58, 13, 'Book Request', 'book_request', 1, 1, 0, 1, '2020-02-17 12:45:19'),
(59, 14, 'Event', 'event', 1, 1, 1, 1, '2020-02-18 00:02:15'),
(60, 14, 'Event Type', 'event_type', 1, 1, 1, 1, '2020-02-18 10:40:33'),
(61, 15, 'Sendsmsmail', 'sendsmsmail', 1, 1, 0, 1, '2020-02-22 13:19:57'),
(62, 15, 'Sendsmsmail Template', 'sendsmsmail_template', 1, 1, 1, 1, '2020-02-22 16:14:57'),
(63, 17, 'Account', 'account', 1, 1, 1, 1, '2020-02-25 15:34:43'),
(64, 17, 'Deposit', 'deposit', 1, 1, 1, 1, '2020-02-25 18:56:11'),
(65, 17, 'Expense', 'expense', 1, 1, 1, 1, '2020-02-26 12:35:57'),
(66, 17, 'All Transactions', 'all_transactions', 1, 0, 0, 0, '2020-02-26 19:35:05'),
(67, 17, 'Voucher Head', 'voucher_head', 1, 1, 1, 1, '2020-02-25 16:50:56'),
(68, 17, 'Accounting Reports', 'accounting_reports', 1, 1, 1, 1, '2020-02-25 19:36:24'),
(69, 16, 'Fees Type', 'fees_type', 1, 1, 1, 1, '2020-02-27 16:11:03'),
(70, 16, 'Fees Group', 'fees_group', 1, 1, 1, 1, '2020-02-26 11:49:09'),
(71, 16, 'Fees Fine Setup', 'fees_fine_setup', 1, 1, 1, 1, '2020-03-05 08:59:27'),
(72, 16, 'Fees Allocation', 'fees_allocation', 1, 1, 1, 1, '2020-03-01 19:47:43'),
(73, 16, 'Collect Fees', 'collect_fees', 0, 1, 0, 0, '2020-03-15 10:23:58'),
(74, 16, 'Fees Reminder', 'fees_reminder', 1, 1, 1, 1, '2020-03-15 10:29:58'),
(75, 16, 'Due Invoice', 'due_invoice', 1, 0, 0, 0, '2020-03-15 10:33:36'),
(76, 16, 'Invoice', 'invoice', 1, 0, 0, 1, '2020-03-15 10:38:06'),
(77, 9, 'Mark Distribution', 'mark_distribution', 1, 1, 1, 1, '2020-03-19 19:02:54'),
(78, 9, 'Report Card', 'report_card', 1, 0, 0, 0, '2020-03-20 18:20:28'),
(79, 9, 'Tabulation Sheet', 'tabulation_sheet', 1, 0, 0, 0, '2020-03-21 13:12:38'),
(80, 15, 'Sendsmsmail Reports', 'sendsmsmail_reports', 1, 0, 0, 0, '2020-03-21 23:02:02'),
(81, 18, 'Global Settings', 'global_settings', 1, 0, 1, 0, '2020-03-22 11:05:41'),
(82, 18, 'Payment Settings', 'payment_settings', 1, 1, 0, 0, '2020-03-22 11:08:57'),
(83, 18, 'Sms Settings', 'sms_settings', 1, 1, 1, 1, '2020-03-22 11:08:57'),
(84, 18, 'Email Settings', 'email_settings', 1, 1, 1, 1, '2020-03-22 11:10:39'),
(85, 18, 'Translations', 'translations', 1, 1, 1, 1, '2020-03-22 11:18:33'),
(86, 18, 'Backup', 'backup', 1, 1, 1, 1, '2020-03-22 13:09:33'),
(87, 18, 'Backup Restore', 'backup_restore', 0, 1, 0, 0, '2020-03-22 13:09:34'),
(88, 7, 'Homework Evaluate', 'homework_evaluate', 1, 1, 0, 0, '2020-03-28 10:20:29'),
(89, 7, 'Evaluation Report', 'evaluation_report', 1, 0, 0, 0, '2020-03-28 15:56:04'),
(90, 18, 'School Settings', 'school_settings', 1, 0, 1, 0, '2020-03-30 23:36:37'),
(91, 1, 'Monthly Income Vs Expense Pie Chart', 'monthly_income_vs_expense_chart', 1, 0, 0, 0, '2020-03-31 12:15:31'),
(92, 1, 'Annual Student Fees Summary Chart', 'annual_student_fees_summary_chart', 1, 0, 0, 0, '2020-03-31 12:15:31'),
(93, 1, 'Employee Count Widget', 'employee_count_widget', 1, 0, 0, 0, '2020-03-31 12:31:56'),
(94, 1, 'Student Count Widget', 'student_count_widget', 1, 0, 0, 0, '2020-03-31 12:31:56'),
(95, 1, 'Parent Count Widget', 'parent_count_widget', 1, 0, 0, 0, '2020-03-31 12:31:56'),
(96, 1, 'Teacher Count Widget', 'teacher_count_widget', 1, 0, 0, 0, '2020-03-31 12:31:56'),
(97, 1, 'Student Quantity Pie Chart', 'student_quantity_pie_chart', 1, 0, 0, 0, '2020-03-31 13:14:07'),
(98, 1, 'Weekend Attendance Inspection Chart', 'weekend_attendance_inspection_chart', 1, 0, 0, 0, '2020-03-31 13:14:07'),
(99, 1, 'Admission Count Widget', 'admission_count_widget', 1, 0, 0, 0, '2020-03-31 13:22:05'),
(100, 1, 'Voucher Count Widget', 'voucher_count_widget', 1, 0, 0, 0, '2020-03-31 13:22:05'),
(101, 1, 'Transport Count Widget', 'transport_count_widget', 1, 0, 0, 0, '2020-03-31 13:22:05'),
(102, 1, 'Hostel Count Widget', 'hostel_count_widget', 1, 0, 0, 0, '2020-03-31 13:22:05'),
(103, 18, 'Accounting Links', 'accounting_links', 1, 0, 1, 0, '2020-03-31 15:46:30'),
(104, 16, 'Fees Reports', 'fees_reports', 1, 0, 0, 0, '2020-04-01 21:52:19'),
(105, 18, 'Cron Job', 'cron_job', 1, 0, 1, 0, '2020-03-31 15:46:30'),
(106, 18, 'Custom Field', 'custom_field', 1, 1, 1, 1, '2020-03-31 15:46:30'),
(107, 5, 'Leave Reports', 'leave_reports', 1, 0, 0, 0, '2020-03-31 15:46:30'),
(108, 18, 'Live Class Config', 'live_class_config', 1, 0, 1, 0, '2020-03-31 15:46:30'),
(109, 19, 'Live Class', 'live_class', 1, 1, 1, 1, '2020-03-31 15:46:30');

-- --------------------------------------------------------

--
-- Table structure for table `permission_modules`
--

CREATE TABLE IF NOT EXISTS `permission_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `prefix` varchar(50) NOT NULL,
  `system` tinyint(1) NOT NULL,
  `sorted` tinyint(10) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `permission_modules`
--

INSERT INTO `permission_modules` (`id`, `name`, `prefix`, `system`, `sorted`, `created_at`) VALUES
(1, 'Dashboard', 'dashboard', 1, 1, '2019-05-27 04:23:00'),
(2, 'Student', 'student', 1, 1, '2019-05-27 04:23:00'),
(3, 'Parents', 'parents', 1, 2, '2019-05-27 04:23:00'),
(4, 'Employee', 'employee', 1, 3, '2019-05-27 04:23:00'),
(5, 'Human Resource', 'human_resource', 1, 4, '2019-05-27 04:23:00'),
(6, 'Academic', 'academic', 1, 5, '2019-05-27 04:23:00'),
(7, 'Homework', 'homework', 1, 7, '2019-05-27 04:23:00'),
(8, 'Attachments Book', 'attachments_book', 1, 8, '2019-05-27 04:23:00'),
(9, 'Exam Master', 'exam_master', 1, 9, '2019-05-27 04:23:00'),
(10, 'Hostel', 'hostel', 1, 10, '2019-05-27 04:23:00'),
(11, 'Transport', 'transport', 1, 11, '2019-05-27 04:23:00'),
(12, 'Attendance', 'attendance', 1, 12, '2019-05-27 04:23:00'),
(13, 'Library', 'library', 1, 13, '2019-05-27 04:23:00'),
(14, 'Events', 'events', 1, 14, '2019-05-27 04:23:00'),
(15, 'Bulk Sms And Email', 'bulk_sms_and_email', 1, 15, '2019-05-27 04:23:00'),
(16, 'Student Accounting', 'student_accounting', 1, 16, '2019-05-27 04:23:00'),
(17, 'Office Accounting', 'office_accounting', 1, 17, '2019-05-27 04:23:00'),
(18, 'Settings', 'settings', 1, 18, '2019-05-27 04:23:00'),
(19, 'Live Class', 'live_class', 1, 6, '2019-05-27 04:23:00');

-- --------------------------------------------------------

--
-- Table structure for table `reset_password`
--

CREATE TABLE IF NOT EXISTS `reset_password` (
  `key` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `login_credential_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reset_password`
--

INSERT INTO `reset_password` (`key`, `username`, `login_credential_id`, `created_at`) VALUES
('71e504ecbd5fcc0f45b934a54361eb910c460f7b10cb706b50ce9795fc1859f9d6f7007b6edc2b9502b7a51d0e9932f8ec4ff248e2ce652d781fd3ef93e63ddd', 'mikipary@gmail.com', '1', '2023-04-23 18:55:17');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `prefix` varchar(50) DEFAULT NULL,
  `is_system` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `prefix`, `is_system`) VALUES
(1, 'Super Admin', 'superadmin', '1'),
(2, 'Admin', 'admin', '1'),
(3, 'Teacher', 'teacher', '1'),
(4, 'Accountant', 'accountant', '1'),
(5, 'Librarian', 'librarian', '1'),
(6, 'Parent', 'parent', '1'),
(7, 'Student', 'student', '1'),
(8, 'Reception', 'reception', '0');

-- --------------------------------------------------------

--
-- Table structure for table `schoolyear`
--

CREATE TABLE IF NOT EXISTS `schoolyear` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_year` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schoolyear`
--

INSERT INTO `schoolyear` (`id`, `school_year`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '2019-2020', 1, '2023-04-10 10:36:41', '2023-04-10 10:36:41'),
(2, '2020-2021', 1, '2023-04-10 11:49:45', '2023-04-10 11:49:45'),
(3, '2021-2022', 1, '2023-04-10 17:10:50', '2023-04-22 18:46:35'),
(4, '2022-2023', 1, '2023-04-10 17:13:04', '2023-04-10 17:13:04'),
(7, '2024-2025', 1, '2023-04-10 18:15:37', '2023-04-22 11:27:02');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE IF NOT EXISTS `section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `capacity` varchar(20) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `name`, `capacity`, `branch_id`) VALUES
(1, 'A', '15', 1),
(2, 'B', '15', 1),
(3, 'A', '15', 2),
(4, 'B', '15', 2),
(5, 'C', '30', 2),
(6, 'C', '15', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sections_allocation`
--

CREATE TABLE IF NOT EXISTS `sections_allocation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sections_allocation`
--

INSERT INTO `sections_allocation` (`id`, `class_id`, `section_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1),
(4, 2, 2),
(5, 2, 6),
(6, 3, 1),
(7, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sms_api`
--

CREATE TABLE IF NOT EXISTS `sms_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sms_api`
--

INSERT INTO `sms_api` (`id`, `name`) VALUES
(1, 'twilio'),
(2, 'clickatell'),
(3, 'msg91'),
(4, 'bulksms'),
(5, 'textlocal');

-- --------------------------------------------------------

--
-- Table structure for table `sms_credential`
--

CREATE TABLE IF NOT EXISTS `sms_credential` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sms_api_id` int(11) NOT NULL,
  `field_one` varchar(300) NOT NULL,
  `field_two` varchar(300) NOT NULL,
  `field_three` varchar(300) NOT NULL,
  `field_four` varchar(300) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_template`
--

CREATE TABLE IF NOT EXISTS `sms_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tags` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sms_template`
--

INSERT INTO `sms_template` (`id`, `name`, `tags`) VALUES
(1, 'admission', '{name}, {class}, {section}, {admission_date}, {roll}, {register_no}'),
(2, 'fee_collection', '{name}, {class}, {section}, {admission_date}, {roll}, {register_no}, {paid_amount}, {paid_date} '),
(3, 'attendance', '{name}, {class}, {section}, {admission_date}, {roll}, {register_no}'),
(4, 'exam_attendance', '{name}, {class}, {section}, {admission_date}, {roll}, {register_no}, {exam_name}, {term_name}, {subject}'),
(5, 'exam_results', '{name}, {class}, {section}, {admission_date}, {roll}, {register_no}, {exam_name}, {term_name}, {subject}, {marks}'),
(6, 'homework', '{name}, {class}, {section}, {admission_date}, {roll}, {register_no}, {subject}, {date_of_homework}, {date_of_submission}'),
(7, 'live_class', '{name}, {class}, {section}, {admission_date}, {roll}, {register_no}, {date_of_live_class}, {start_time}, {end_time}, {host_by}');

-- --------------------------------------------------------

--
-- Table structure for table `sms_template_details`
--

CREATE TABLE IF NOT EXISTS `sms_template_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `notify_student` tinyint(3) NOT NULL DEFAULT 1,
  `notify_parent` tinyint(3) NOT NULL DEFAULT 1,
  `template_body` longtext NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sms_template_details`
--

INSERT INTO `sms_template_details` (`id`, `template_id`, `notify_student`, `notify_parent`, `template_body`, `branch_id`, `created_at`) VALUES
(1, 1, 1, 1, 'Hi {user_role} {name}\r\n\r\nAn account was created for you at {institute_name}.\r\n\r\nYour username is {login_email} and password is {password}\r\n\r\nYour login url is {login_url}\r\nThanks\r\n{institute_name}', 1, '2023-05-13 19:53:10');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` varchar(25) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` int(11) NOT NULL,
  `qualification` varchar(255) NOT NULL,
  `designation` int(11) NOT NULL,
  `joining_date` varchar(100) NOT NULL,
  `birthday` varchar(100) NOT NULL,
  `sex` varchar(20) NOT NULL,
  `religion` varchar(100) NOT NULL,
  `blood_group` varchar(20) NOT NULL,
  `present_address` text NOT NULL,
  `permanent_address` text NOT NULL,
  `mobileno` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `salary_template_id` int(11) DEFAULT 0,
  `branch_id` int(11) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `staff_id`, `name`, `department`, `qualification`, `designation`, `joining_date`, `birthday`, `sex`, `religion`, `blood_group`, `present_address`, `permanent_address`, `mobileno`, `email`, `salary_template_id`, `branch_id`, `photo`, `facebook_url`, `linkedin_url`, `twitter_url`, `created_at`, `updated_at`, `hash`) VALUES
(1, '3597c7f', 'admin', 2, '', 0, '2020-05-31', '', '', '', '', 'Ikeja', '', '09062684833', 'admin@admin.com', 0, NULL, 'defualt.png', 'facebook.com/user', 'linkedin.com/user', 'twitter.com/user', '2020-05-31 13:06:26', '2023-04-24 16:18:09', ''),
(2, 'a7de598', 'Cletis Petruskevich', 2, 'Bsc.', 1, '1938-09-16', '1964-12-19', 'male', 'Buddhism', 'AB-', '81 Sachtjen Hill', '63 Esch Place', '169-119-3681', 'cpetruskevich0@paypal.com', 0, 1, 'defualt.png', 'https://facebook.com/user', 'https://linkedin.com/user', 'https://twitter.com/user', '2023-05-16 20:19:22', '2023-05-16 20:20:05', ''),
(3, 'fb4c632', 'Bourke Adney', 2, 'PhD', 1, '2015-01-25', '1994-01-02', 'Male', 'Christianity', 'O-', '8 Del Sol Junction', '4 Dakota Lane', '512-683-6222', 'badney1@jigsy.com', 0, 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:19:22', '2023-05-16 20:19:22', ''),
(4, 'f2e5cf1', 'Karleen Howes', 2, 'NCE', 1, '1927-12-30', '1909-11-22', 'Female', 'Christianity', 'O+', '6003 Bunker Hill Way', '765 Shopko Point', '946-619-1313', 'khowes2@hp.com', 0, 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:19:22', '2023-05-16 20:19:22', ''),
(5, 'a6265b9', 'Hervey Dyett', 2, 'NCE', 1, '1975-04-30', '1963-02-08', 'Male', 'Judaism', 'AB-', '369 Commercial Trail', '98102 Brentwood Park', '952-864-4314', 'hdyett3@sogou.com', 0, 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:19:22', '2023-05-16 20:19:22', ''),
(6, '9711719', 'Jena Robion', 2, 'PhD', 1, '2013-09-25', '1959-05-18', 'Female', 'Christianity', 'AB+', '17860 Westport Center', '3 Superior Drive', '116-625-5664', 'jrobion4@gmpg.org', 0, 1, 'defualt.png', NULL, NULL, NULL, '2023-05-16 20:19:22', '2023-05-16 20:19:22', '');

-- --------------------------------------------------------

--
-- Table structure for table `staff_attendance`
--

CREATE TABLE IF NOT EXISTS `staff_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `status` varchar(11) DEFAULT NULL COMMENT 'P=Present, A=Absent, H=Holiday, L=Late',
  `remark` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_bank_account`
--

CREATE TABLE IF NOT EXISTS `staff_bank_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `bank_name` varchar(200) NOT NULL,
  `holder_name` varchar(255) NOT NULL,
  `bank_branch` varchar(255) NOT NULL,
  `bank_address` varchar(255) NOT NULL,
  `ifsc_code` varchar(200) NOT NULL,
  `account_no` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `staff_bank_account`
--

INSERT INTO `staff_bank_account` (`id`, `staff_id`, `bank_name`, `holder_name`, `bank_branch`, `bank_address`, `ifsc_code`, `account_no`, `created_at`, `updated_at`) VALUES
(1, 2, 'Zenith Bank', 'Afolabi Olalekan', 'Ikeja', 'Ikeja', '', '2098786756', '2023-04-26 12:50:33', '2023-04-26 12:50:33');

-- --------------------------------------------------------

--
-- Table structure for table `staff_department`
--

CREATE TABLE IF NOT EXISTS `staff_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_department`
--

INSERT INTO `staff_department` (`id`, `name`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 'IT', 1, '2023-04-24 20:23:58', '2023-04-24 20:23:58'),
(2, 'Teaching', 1, '2023-04-24 20:35:42', '2023-04-24 20:35:42'),
(3, 'IT', 7, '2023-04-24 20:36:11', '2023-04-27 18:23:53'),
(4, 'Teaching', 7, '2023-04-24 20:37:23', '2023-04-24 20:37:23');

-- --------------------------------------------------------

--
-- Table structure for table `staff_designation`
--

CREATE TABLE IF NOT EXISTS `staff_designation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_designation`
--

INSERT INTO `staff_designation` (`id`, `name`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 'HOD', 1, '2023-04-24 21:02:08', '2023-04-24 21:02:08'),
(2, 'IT Manager', 1, '2023-04-27 23:07:03', '2023-04-27 23:07:03'),
(3, 'HOD', 7, '2023-04-29 17:15:29', '2023-04-29 17:15:29'),
(4, 'IT Manager', 7, '2023-04-29 17:15:42', '2023-04-29 17:15:42'),
(5, 'Teacher', 1, '2023-04-29 17:44:05', '2023-04-29 17:44:05'),
(6, 'Class Teacher', 1, '2023-04-29 17:44:17', '2023-04-29 17:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `staff_documents`
--

CREATE TABLE IF NOT EXISTS `staff_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category_id` varchar(20) NOT NULL,
  `remarks` text NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `enc_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `staff_documents`
--

INSERT INTO `staff_documents` (`id`, `staff_id`, `title`, `category_id`, `remarks`, `file_name`, `enc_name`, `created_at`, `updated_at`) VALUES
(1, 2, 'My Pics', '6', 'A student Picture', 'Hoda.png', '1682675573_da63eac4d1f2b1385ca0.png', '2023-04-28 10:52:53', '2023-04-28 10:52:53');

-- --------------------------------------------------------

--
-- Table structure for table `staff_privileges`
--

CREATE TABLE IF NOT EXISTS `staff_privileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `is_add` tinyint(1) NOT NULL,
  `is_edit` tinyint(1) NOT NULL,
  `is_view` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_privileges`
--

INSERT INTO `staff_privileges` (`id`, `role_id`, `permission_id`, `is_add`, `is_edit`, `is_view`, `is_delete`) VALUES
(1, 3, 1, 0, 1, 1, 1),
(2, 3, 91, 0, 0, 1, 0),
(3, 3, 92, 0, 0, 1, 0),
(4, 3, 93, 0, 0, 1, 0),
(5, 3, 94, 0, 0, 1, 0),
(6, 3, 95, 0, 0, 1, 0),
(7, 3, 96, 0, 0, 1, 0),
(8, 3, 97, 0, 0, 1, 0),
(9, 3, 98, 0, 0, 1, 0),
(10, 3, 99, 0, 0, 1, 0),
(11, 3, 100, 0, 0, 1, 0),
(12, 3, 101, 0, 0, 1, 0),
(13, 3, 102, 0, 0, 1, 0),
(14, 3, 2, 1, 0, 0, 0),
(15, 3, 3, 0, 0, 1, 0),
(16, 3, 4, 0, 0, 0, 0),
(17, 3, 5, 0, 0, 0, 0),
(18, 3, 30, 0, 0, 0, 0),
(19, 3, 7, 0, 0, 0, 0),
(20, 3, 8, 0, 0, 0, 0),
(21, 3, 6, 0, 0, 1, 0),
(22, 3, 9, 0, 0, 0, 0),
(23, 3, 10, 0, 0, 0, 0),
(24, 3, 11, 0, 0, 0, 0),
(25, 3, 12, 0, 0, 0, 0),
(26, 3, 13, 0, 0, 0, 0),
(27, 3, 14, 0, 0, 0, 0),
(28, 3, 15, 0, 0, 0, 0),
(29, 3, 16, 0, 0, 0, 0),
(30, 3, 17, 0, 0, 0, 0),
(31, 3, 18, 0, 0, 0, 0),
(32, 3, 19, 0, 0, 0, 0),
(33, 3, 20, 0, 0, 0, 0),
(34, 3, 21, 0, 0, 0, 0),
(35, 3, 22, 0, 0, 0, 0),
(36, 3, 107, 0, 0, 0, 0),
(37, 3, 23, 0, 0, 0, 0),
(38, 3, 24, 0, 0, 0, 0),
(39, 3, 25, 0, 0, 0, 0),
(40, 3, 26, 0, 0, 0, 0),
(41, 3, 27, 0, 0, 0, 0),
(42, 3, 28, 0, 0, 0, 0),
(43, 3, 29, 0, 0, 0, 0),
(44, 3, 109, 0, 0, 0, 0),
(45, 3, 32, 0, 0, 0, 0),
(46, 3, 88, 0, 0, 0, 0),
(47, 3, 89, 0, 0, 0, 0),
(48, 3, 31, 0, 0, 0, 0),
(49, 3, 33, 0, 0, 0, 0),
(50, 3, 34, 0, 0, 0, 0),
(51, 3, 35, 0, 0, 0, 0),
(52, 3, 36, 0, 0, 0, 0),
(53, 3, 37, 0, 0, 0, 0),
(54, 3, 38, 0, 0, 0, 0),
(55, 3, 39, 0, 0, 0, 0),
(56, 3, 77, 0, 0, 0, 0),
(57, 3, 78, 0, 0, 0, 0),
(58, 3, 79, 0, 0, 0, 0),
(59, 3, 40, 0, 0, 0, 0),
(60, 3, 41, 0, 0, 0, 0),
(61, 3, 42, 0, 0, 0, 0),
(62, 3, 43, 0, 0, 0, 0),
(63, 3, 44, 0, 0, 0, 0),
(64, 3, 45, 0, 0, 0, 0),
(65, 3, 46, 0, 0, 0, 0),
(66, 3, 47, 0, 0, 0, 0),
(67, 3, 48, 0, 0, 0, 0),
(68, 3, 49, 0, 0, 0, 0),
(69, 3, 50, 0, 0, 0, 0),
(70, 3, 51, 0, 0, 0, 0),
(71, 3, 52, 0, 0, 0, 0),
(72, 3, 53, 0, 0, 0, 0),
(73, 3, 54, 0, 0, 0, 0),
(74, 3, 55, 0, 0, 0, 0),
(75, 3, 56, 0, 0, 0, 0),
(76, 3, 57, 0, 0, 0, 0),
(77, 3, 58, 0, 0, 0, 0),
(78, 3, 59, 0, 0, 0, 0),
(79, 3, 60, 0, 0, 0, 0),
(80, 3, 61, 0, 0, 0, 0),
(81, 3, 62, 0, 0, 0, 0),
(82, 3, 80, 0, 0, 0, 0),
(83, 3, 69, 0, 0, 0, 0),
(84, 3, 70, 0, 0, 0, 0),
(85, 3, 71, 0, 0, 0, 0),
(86, 3, 72, 0, 0, 0, 0),
(87, 3, 73, 0, 0, 0, 0),
(88, 3, 74, 0, 0, 0, 0),
(89, 3, 75, 0, 0, 0, 0),
(90, 3, 76, 0, 0, 0, 0),
(91, 3, 104, 0, 0, 0, 0),
(92, 3, 63, 0, 0, 0, 0),
(93, 3, 64, 0, 0, 0, 0),
(94, 3, 65, 0, 0, 0, 0),
(95, 3, 66, 0, 0, 0, 0),
(96, 3, 67, 0, 0, 0, 0),
(97, 3, 68, 0, 0, 0, 0),
(98, 3, 81, 0, 0, 0, 0),
(99, 3, 82, 0, 0, 0, 0),
(100, 3, 83, 0, 0, 0, 0),
(101, 3, 84, 0, 0, 0, 0),
(102, 3, 85, 0, 0, 0, 0),
(103, 3, 86, 0, 0, 0, 0),
(104, 3, 87, 0, 0, 0, 0),
(105, 3, 90, 0, 0, 0, 0),
(106, 3, 103, 0, 0, 0, 0),
(107, 3, 105, 0, 0, 0, 0),
(108, 3, 106, 0, 0, 0, 0),
(109, 3, 108, 0, 0, 0, 0),
(110, 2, 91, 0, 0, 1, 0),
(111, 2, 92, 0, 0, 0, 0),
(112, 2, 93, 0, 0, 0, 0),
(113, 2, 94, 0, 0, 0, 0),
(114, 2, 95, 0, 0, 0, 0),
(115, 2, 96, 0, 0, 0, 0),
(116, 2, 97, 0, 0, 0, 0),
(117, 2, 98, 0, 0, 0, 0),
(118, 2, 99, 0, 0, 0, 0),
(119, 2, 100, 0, 0, 0, 0),
(120, 2, 101, 0, 0, 0, 0),
(121, 2, 102, 0, 0, 0, 0),
(122, 2, 1, 0, 0, 0, 0),
(123, 2, 2, 0, 0, 0, 0),
(124, 2, 3, 0, 0, 0, 0),
(125, 2, 4, 0, 0, 0, 0),
(126, 2, 5, 0, 0, 0, 0),
(127, 2, 30, 0, 0, 0, 0),
(128, 2, 7, 0, 0, 0, 0),
(129, 2, 8, 0, 0, 0, 0),
(130, 2, 6, 0, 0, 0, 0),
(131, 2, 9, 0, 0, 0, 0),
(132, 2, 10, 0, 0, 0, 0),
(133, 2, 11, 0, 0, 0, 0),
(134, 2, 12, 0, 0, 0, 0),
(135, 2, 13, 0, 0, 0, 0),
(136, 2, 14, 0, 0, 0, 0),
(137, 2, 15, 0, 0, 0, 0),
(138, 2, 16, 0, 0, 0, 0),
(139, 2, 17, 0, 0, 0, 0),
(140, 2, 18, 0, 0, 0, 0),
(141, 2, 19, 0, 0, 0, 0),
(142, 2, 20, 0, 0, 0, 0),
(143, 2, 21, 0, 0, 0, 0),
(144, 2, 22, 0, 0, 0, 0),
(145, 2, 107, 0, 0, 0, 0),
(146, 2, 23, 0, 0, 0, 0),
(147, 2, 24, 0, 0, 0, 0),
(148, 2, 25, 0, 0, 0, 0),
(149, 2, 26, 0, 0, 0, 0),
(150, 2, 27, 0, 0, 0, 0),
(151, 2, 28, 0, 0, 0, 0),
(152, 2, 29, 0, 0, 0, 0),
(153, 2, 109, 0, 0, 0, 0),
(154, 2, 32, 0, 0, 0, 0),
(155, 2, 88, 0, 0, 0, 0),
(156, 2, 89, 0, 0, 0, 0),
(157, 2, 31, 0, 0, 0, 0),
(158, 2, 33, 0, 0, 0, 0),
(159, 2, 34, 0, 0, 0, 0),
(160, 2, 35, 0, 0, 0, 0),
(161, 2, 36, 0, 0, 0, 0),
(162, 2, 37, 0, 0, 0, 0),
(163, 2, 38, 0, 0, 0, 0),
(164, 2, 39, 0, 0, 0, 0),
(165, 2, 77, 0, 0, 0, 0),
(166, 2, 78, 0, 0, 0, 0),
(167, 2, 79, 0, 0, 0, 0),
(168, 2, 40, 0, 0, 0, 0),
(169, 2, 41, 0, 0, 0, 0),
(170, 2, 42, 0, 0, 0, 0),
(171, 2, 43, 0, 0, 0, 0),
(172, 2, 44, 0, 0, 0, 0),
(173, 2, 45, 0, 0, 0, 0),
(174, 2, 46, 0, 0, 0, 0),
(175, 2, 47, 0, 0, 0, 0),
(176, 2, 48, 0, 0, 0, 0),
(177, 2, 49, 0, 0, 0, 0),
(178, 2, 50, 0, 0, 0, 0),
(179, 2, 51, 0, 0, 0, 0),
(180, 2, 52, 0, 0, 0, 0),
(181, 2, 53, 0, 0, 0, 0),
(182, 2, 54, 0, 0, 0, 0),
(183, 2, 55, 0, 0, 0, 0),
(184, 2, 56, 0, 0, 0, 0),
(185, 2, 57, 0, 0, 0, 0),
(186, 2, 58, 0, 0, 0, 0),
(187, 2, 59, 0, 0, 0, 0),
(188, 2, 60, 0, 0, 0, 0),
(189, 2, 61, 0, 0, 0, 0),
(190, 2, 62, 0, 0, 0, 0),
(191, 2, 80, 0, 0, 0, 0),
(192, 2, 69, 0, 0, 0, 0),
(193, 2, 70, 0, 0, 0, 0),
(194, 2, 71, 0, 0, 0, 0),
(195, 2, 72, 0, 0, 0, 0),
(196, 2, 73, 0, 0, 0, 0),
(197, 2, 74, 0, 0, 0, 0),
(198, 2, 75, 0, 0, 0, 0),
(199, 2, 76, 0, 0, 0, 0),
(200, 2, 104, 0, 0, 0, 0),
(201, 2, 63, 0, 0, 0, 0),
(202, 2, 64, 0, 0, 0, 0),
(203, 2, 65, 0, 0, 0, 0),
(204, 2, 66, 0, 0, 0, 0),
(205, 2, 67, 0, 0, 0, 0),
(206, 2, 68, 0, 0, 0, 0),
(207, 2, 81, 0, 0, 0, 0),
(208, 2, 82, 0, 0, 0, 0),
(209, 2, 83, 0, 0, 0, 0),
(210, 2, 84, 0, 0, 0, 0),
(211, 2, 85, 0, 0, 0, 0),
(212, 2, 86, 0, 0, 0, 0),
(213, 2, 87, 0, 0, 0, 0),
(214, 2, 90, 0, 0, 0, 0),
(215, 2, 103, 0, 0, 0, 0),
(216, 2, 105, 0, 0, 0, 0),
(217, 2, 106, 0, 0, 0, 0),
(218, 2, 108, 0, 0, 0, 0),
(219, 4, 91, 0, 0, 0, 0),
(220, 4, 92, 0, 0, 0, 0),
(221, 4, 93, 0, 0, 0, 0),
(222, 4, 94, 0, 0, 0, 0),
(223, 4, 95, 0, 0, 0, 0),
(224, 4, 96, 0, 0, 0, 0),
(225, 4, 97, 0, 0, 0, 0),
(226, 4, 98, 0, 0, 0, 0),
(227, 4, 99, 0, 0, 0, 0),
(228, 4, 100, 0, 0, 0, 0),
(229, 4, 101, 0, 0, 0, 0),
(230, 4, 102, 0, 0, 0, 0),
(231, 4, 1, 1, 0, 1, 0),
(232, 4, 2, 0, 0, 0, 0),
(233, 4, 3, 0, 0, 0, 0),
(234, 4, 4, 0, 0, 0, 0),
(235, 4, 5, 0, 0, 0, 0),
(236, 4, 30, 0, 0, 0, 0),
(237, 4, 7, 0, 0, 0, 0),
(238, 4, 8, 0, 0, 0, 0),
(239, 4, 6, 0, 0, 0, 0),
(240, 4, 9, 0, 0, 0, 0),
(241, 4, 10, 0, 0, 0, 0),
(242, 4, 11, 0, 0, 0, 0),
(243, 4, 12, 0, 0, 0, 0),
(244, 4, 13, 0, 0, 0, 0),
(245, 4, 14, 0, 0, 0, 0),
(246, 4, 15, 0, 0, 0, 0),
(247, 4, 16, 0, 0, 0, 0),
(248, 4, 17, 0, 0, 0, 0),
(249, 4, 18, 0, 0, 0, 0),
(250, 4, 19, 0, 0, 0, 0),
(251, 4, 20, 0, 0, 0, 0),
(252, 4, 21, 0, 0, 0, 0),
(253, 4, 22, 0, 0, 0, 0),
(254, 4, 107, 0, 0, 0, 0),
(255, 4, 23, 0, 0, 0, 0),
(256, 4, 24, 0, 0, 0, 0),
(257, 4, 25, 0, 0, 0, 0),
(258, 4, 26, 0, 0, 0, 0),
(259, 4, 27, 0, 0, 0, 0),
(260, 4, 28, 0, 0, 0, 0),
(261, 4, 29, 0, 0, 0, 0),
(262, 4, 109, 0, 0, 0, 0),
(263, 4, 32, 0, 0, 0, 0),
(264, 4, 88, 0, 0, 0, 0),
(265, 4, 89, 0, 0, 0, 0),
(266, 4, 31, 0, 0, 0, 0),
(267, 4, 33, 0, 0, 0, 0),
(268, 4, 34, 0, 0, 0, 0),
(269, 4, 35, 0, 0, 0, 0),
(270, 4, 36, 0, 0, 0, 0),
(271, 4, 37, 0, 0, 0, 0),
(272, 4, 38, 0, 0, 0, 0),
(273, 4, 39, 0, 0, 0, 0),
(274, 4, 77, 0, 0, 0, 0),
(275, 4, 78, 0, 0, 0, 0),
(276, 4, 79, 0, 0, 0, 0),
(277, 4, 40, 0, 0, 0, 0),
(278, 4, 41, 0, 0, 0, 0),
(279, 4, 42, 0, 0, 0, 0),
(280, 4, 43, 0, 0, 0, 0),
(281, 4, 44, 0, 0, 0, 0),
(282, 4, 45, 0, 0, 0, 0),
(283, 4, 46, 0, 0, 0, 0),
(284, 4, 47, 0, 0, 0, 0),
(285, 4, 48, 0, 0, 0, 0),
(286, 4, 49, 0, 0, 0, 0),
(287, 4, 50, 0, 0, 0, 0),
(288, 4, 51, 0, 0, 0, 0),
(289, 4, 52, 0, 0, 0, 0),
(290, 4, 53, 0, 0, 0, 0),
(291, 4, 54, 0, 0, 0, 0),
(292, 4, 55, 0, 0, 0, 0),
(293, 4, 56, 0, 0, 0, 0),
(294, 4, 57, 0, 0, 0, 0),
(295, 4, 58, 0, 0, 0, 0),
(296, 4, 59, 0, 0, 0, 0),
(297, 4, 60, 0, 0, 0, 0),
(298, 4, 61, 0, 0, 0, 0),
(299, 4, 62, 0, 0, 0, 0),
(300, 4, 80, 0, 0, 0, 0),
(301, 4, 69, 0, 0, 0, 0),
(302, 4, 70, 0, 0, 0, 0),
(303, 4, 71, 0, 0, 0, 0),
(304, 4, 72, 0, 0, 0, 0),
(305, 4, 73, 0, 0, 0, 0),
(306, 4, 74, 0, 0, 0, 0),
(307, 4, 75, 0, 0, 0, 0),
(308, 4, 76, 0, 0, 0, 0),
(309, 4, 104, 0, 0, 0, 0),
(310, 4, 63, 0, 0, 0, 0),
(311, 4, 64, 0, 0, 0, 0),
(312, 4, 65, 0, 0, 0, 0),
(313, 4, 66, 0, 0, 0, 0),
(314, 4, 67, 0, 0, 0, 0),
(315, 4, 68, 0, 0, 0, 0),
(316, 4, 81, 0, 0, 0, 0),
(317, 4, 82, 0, 0, 0, 0),
(318, 4, 83, 0, 0, 0, 0),
(319, 4, 84, 0, 0, 0, 0),
(320, 4, 85, 0, 0, 0, 0),
(321, 4, 86, 0, 0, 0, 0),
(322, 4, 87, 0, 0, 0, 0),
(323, 4, 90, 0, 0, 0, 0),
(324, 4, 103, 0, 0, 0, 0),
(325, 4, 105, 0, 0, 0, 0),
(326, 4, 106, 0, 0, 0, 0),
(327, 4, 108, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `register_no` varchar(100) NOT NULL,
  `admission_date` varchar(100) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `birthday` varchar(100) DEFAULT NULL,
  `religion` varchar(100) NOT NULL,
  `caste` varchar(100) NOT NULL,
  `blood_group` varchar(100) NOT NULL,
  `mother_tongue` varchar(100) DEFAULT NULL,
  `current_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `mobileno` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `email` varchar(100) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL DEFAULT 0,
  `vehicle_id` int(11) NOT NULL DEFAULT 0,
  `hostel_id` int(11) NOT NULL DEFAULT 0,
  `room_id` int(11) NOT NULL DEFAULT 0,
  `previous_details` text NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `register_no`, `admission_date`, `first_name`, `last_name`, `gender`, `birthday`, `religion`, `caste`, `blood_group`, `mother_tongue`, `current_address`, `permanent_address`, `city`, `state`, `mobileno`, `category_id`, `email`, `parent_id`, `route_id`, `vehicle_id`, `hostel_id`, `room_id`, `previous_details`, `photo`, `created_at`, `updated_at`) VALUES
(1, 'GVS202000001', '1936-01-27', 'Brandie', 'Mattholie', 'Female', '2000-03-20', 'Sikhism', 'bike', 'B+', 'Burmese', '9683 Towne Place', '9326 Prairie Rose Alley', 'Huagu', '', '668-243-6817', 2, 'bmattholie0@icq.com', 2, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:37', '2023-05-16 19:29:37'),
(2, 'GVS202000002', '1958-05-09', 'Natalee', 'Konmann', 'Female', '2009-01-12', 'Judaism', 'bike', 'O+', 'Gagauz', '373 Oneill Park', '071 Ruskin Way', 'Valjevo', '', '627-561-0283', 3, 'nkonmann1@edublogs.org', 3, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:37', '2023-05-16 19:29:37'),
(3, 'GVS202000003', '2012-07-31', 'Luisa', 'Abberley', 'Female', '1994-06-16', 'Christianity', 'car', 'A+', 'Indonesian', '05555 Hudson Street', '53155 Del Sol Junction', 'Bader', '', '722-754-4414', 1, 'labberley2@ycombinator.com', 4, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:37', '2023-05-16 19:29:37'),
(4, 'GVS202000004', '1918-10-24', 'Gilberte', 'Tooting', 'Female', '2020-12-12', 'Sikhism', 'bus', 'A+', 'Malay', '0 Kennedy Plaza', '674 Elka Junction', 'Chimboy Shahri', '', '359-516-1894', 1, 'gtooting3@bloglovin.com', 5, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:37', '2023-05-16 19:29:37'),
(5, 'GVS202000005', '1954-04-16', 'Brigida', 'Bodker', 'Female', '2009-09-10', 'Sikhism', 'car', 'B-', 'Kyrgyz', '9 Stang Lane', '58 Toban Lane', 'Loujiadian', '', '464-560-0017', 3, 'bbodker4@pinterest.com', 6, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:37', '2023-05-16 19:29:37'),
(6, 'GVS202000006', '1935-06-27', 'Rufus', 'Dowdeswell', 'Male', '1937-12-25', 'Islam', 'bike', 'A-', 'Tajik', '9787 Steensland Junction', '538 Reinke Lane', 'Conception Bay South', 'Newfoundland and Labrador', '876-130-6315', 3, 'rdowdeswell5@fastcompany.com', 7, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:37', '2023-05-16 19:29:37'),
(7, 'GVS202000007', '1940-04-17', 'Marlee', 'Walkinshaw', 'Polygender', '1992-11-25', 'Islam', 'train', 'AB+', 'Mongolian', '130 Wayridge Street', '44172 Longview Parkway', 'Yangqing', '', '486-798-5571', 3, 'mwalkinshaw6@ox.ac.uk', 8, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:38', '2023-05-16 19:29:38'),
(8, 'GVS202000008', '1983-04-08', 'Elvin', 'Vanetti', 'Non-binary', '1943-02-19', 'Hinduism', 'airplane', 'AB+', 'Burmese', '2 Utah Parkway', '84 Loftsgordon Drive', 'Fengshan', '', '831-167-8967', 3, 'evanetti7@tamu.edu', 9, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:38', '2023-05-16 19:29:38'),
(9, 'GVS202000009', '1944-09-20', 'Lea', 'Kneeland', 'Female', '2022-05-08', 'Hinduism', 'airplane', 'A+', 'Korean', '41 Pennsylvania Pass', '83 Sutteridge Drive', 'Araci', '', '914-731-0087', 2, 'lkneeland8@nih.gov', 10, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:38', '2023-05-16 19:29:38'),
(10, 'GVS202000010', '1913-12-02', 'Eyde', 'Outerbridge', 'Female', '1963-05-16', 'Buddhism', 'car', 'B-', 'Khmer', '62741 Sundown Place', '926 Scofield Point', 'Сарај', '', '441-787-9851', 3, 'eouterbridge9@harvard.edu', 11, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:38', '2023-05-16 19:29:38'),
(11, 'GVS202000011', '1937-10-11', 'Marinna', 'Josham', 'Agender', '1992-05-01', 'Islam', 'bike', 'O-', 'Lao', '38 Roth Court', '288 Jenifer Point', 'Cacocum', '', '918-499-3900', 3, 'mjoshama@salon.com', 12, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:38', '2023-05-16 19:29:38'),
(12, 'GVS202000012', '1948-09-14', 'Moselle', 'Reiners', 'Female', '1921-08-10', 'Christianity', 'car', 'AB+', 'Spanish', '87588 Walton Drive', '483 Clove Point', 'Anyu', '', '852-588-1930', 1, 'mreinersb@ifeng.com', 13, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:38', '2023-05-16 19:29:38'),
(13, 'GVS202000013', '1909-07-13', 'Izaak', 'Matonin', 'Male', '2008-10-17', 'Buddhism', 'truck', 'A-', 'Indonesian', '5654 Lunder Point', '8 Fairview Road', 'Tabanan', '', '863-585-9604', 3, 'imatoninc@lulu.com', 14, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:38', '2023-05-16 19:29:38'),
(14, 'GVS202000014', '1904-02-17', 'Leigh', 'Sommerled', 'Male', '1918-07-29', 'Islam', 'truck', 'O+', 'Estonian', '5916 Elgar Pass', '20 Melody Trail', 'Gaojian', '', '998-912-0837', 2, 'lsommerledd@histats.com', 15, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:39', '2023-05-16 19:29:39'),
(15, 'GVS202000015', '2005-02-24', 'Fredek', 'Trime', 'Male', '1913-09-27', 'Christianity', 'train', 'O+', 'Tamil', '05 Dottie Crossing', '585 Fair Oaks Point', 'Morelos', 'San Luis Potosi', '284-994-3669', 3, 'ftrimee@opensource.org', 16, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:39', '2023-05-16 19:29:39'),
(16, 'GVS202000016', '1967-09-23', 'Cammy', 'Ovitz', 'Male', '1924-03-03', 'Sikhism', 'bike', 'O+', 'Kashmiri', '409 Anhalt Avenue', '63973 Eggendart Terrace', 'Zeya', '', '443-276-0823', 1, 'covitzf@webmd.com', 17, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:39', '2023-05-16 19:29:39'),
(17, 'GVS202000017', '1942-08-14', 'Hollis', 'Connerry', 'Male', '2010-10-31', 'Buddhism', 'train', 'A-', 'Assamese', '2 Waywood Hill', '36 Fisk Park', 'Pitangueiras', '', '845-715-6069', 3, 'hconnerryg@wsj.com', 18, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:39', '2023-05-16 19:29:39'),
(18, 'GVS202000018', '2021-12-10', 'Chen', 'Pillington', 'Male', '1911-06-05', 'Buddhism', 'truck', 'A+', 'Gagauz', '66 Marquette Alley', '8 Jana Parkway', 'Roissy Charles-de-Gaulle', 'Île-de-France', '694-456-8930', 1, 'cpillingtonh@mashable.com', 19, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:39', '2023-05-16 19:29:39'),
(19, 'GVS202000019', '1949-12-09', 'Judy', 'McColley', 'Female', '1922-06-12', 'Buddhism', 'car', 'B+', 'Dhivehi', '55 Corben Avenue', '686 Sage Hill', 'Pančevo', '', '555-535-8483', 3, 'jmccolleyi@mlb.com', 20, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:39', '2023-05-16 19:29:39'),
(20, 'GVS202000020', '2000-10-02', 'Mason', 'Eldritt', 'Male', '1958-02-03', 'Sikhism', 'car', 'O+', 'Tsonga', '459 Rigney Crossing', '7 Commercial Place', 'Huayuan', '', '436-445-5435', 1, 'meldrittj@plala.or.jp', 21, 0, 0, 0, 0, '', 'defualt.png', '2023-05-16 19:29:39', '2023-05-16 19:29:39');

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance`
--

CREATE TABLE IF NOT EXISTS `student_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(4) DEFAULT NULL COMMENT 'P=Present, A=Absent, H=Holiday, L=Late',
  `remark` text DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_category`
--

CREATE TABLE IF NOT EXISTS `student_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `student_category`
--

INSERT INTO `student_category` (`id`, `branch_id`, `name`) VALUES
(1, 1, 'Science'),
(2, 1, 'Commercial'),
(3, 1, 'Arts');

-- --------------------------------------------------------

--
-- Table structure for table `student_documents`
--

CREATE TABLE IF NOT EXISTS `student_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `remarks` text NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `enc_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `student_documents`
--

INSERT INTO `student_documents` (`id`, `student_id`, `title`, `type`, `remarks`, `file_name`, `enc_name`, `created_at`, `updated_at`) VALUES
(1, 5, 'Result', 'PDF', 'Student Result', 'Xmas Term REPORT 2022-2023 Eniola Akinosho.pdf', '1684096889_a5d0e18e03eb529f9851.pdf', '2023-05-14 21:41:29', '2023-05-14 21:41:29');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE IF NOT EXISTS `subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `subject_code` varchar(200) NOT NULL,
  `subject_type` varchar(255) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `subject_author` varchar(255) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `name`, `subject_code`, `subject_type`, `subject_author`, `branch_id`) VALUES
(1, 'Mathematics', 'Maths', 'Practical', 'Tope ', 1),
(2, 'English Language', 'Eng', 'Mandatory', 'Adenubi', 1),
(3, 'Chemistry', 'Chem', 'Practical', 'Afolabi Olalekan', 1),
(4, 'Physics', 'Phy', 'Practical', 'Odeyemi Samuel', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subject_assign`
--

CREATE TABLE IF NOT EXISTS `subject_assign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` longtext NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `subject_assign`
--

INSERT INTO `subject_assign` (`id`, `class_id`, `section_id`, `subject_id`, `teacher_id`, `branch_id`, `session_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '1', 2, 1, 1, '2023-05-16 22:24:08', '2023-05-16 22:24:08'),
(2, 1, 1, '2', 2, 1, 1, '2023-05-16 22:24:08', '2023-05-16 22:24:08'),
(3, 2, 2, '1', 0, 1, 1, '2023-05-16 22:41:45', '2023-05-16 22:41:45'),
(4, 2, 2, '2', 0, 1, 1, '2023-05-16 22:41:45', '2023-05-16 22:41:45'),
(5, 2, 2, '3', 0, 1, 1, '2023-05-16 22:41:45', '2023-05-16 22:41:45'),
(6, 2, 2, '4', 0, 1, 1, '2023-05-16 22:41:45', '2023-05-16 22:41:45');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_allocation`
--

CREATE TABLE IF NOT EXISTS `teacher_allocation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `teacher_allocation`
--

INSERT INTO `teacher_allocation` (`id`, `class_id`, `section_id`, `teacher_id`, `session_id`, `branch_id`) VALUES
(1, 1, 1, 2, 1, 1),
(2, 1, 1, 2, 2, 1),
(3, 2, 2, 3, 2, 1),
(4, 1, 2, 4, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `theme_settings`
--

CREATE TABLE IF NOT EXISTS `theme_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `border_mode` varchar(200) NOT NULL,
  `dark_skin` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `theme_settings`
--

INSERT INTO `theme_settings` (`id`, `border_mode`, `dark_skin`, `created_at`, `updated_at`) VALUES
(1, 'true', 'false', '2018-10-23 22:59:38', '2023-04-22 13:01:49');

-- --------------------------------------------------------

--
-- Table structure for table `timetable_class`
--

CREATE TABLE IF NOT EXISTS `timetable_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `break` varchar(11) DEFAULT 'false',
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_room` varchar(100) DEFAULT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `day` varchar(20) NOT NULL,
  `session_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timetable_exam`
--

CREATE TABLE IF NOT EXISTS `timetable_exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `time_start` varchar(20) NOT NULL,
  `time_end` varchar(20) NOT NULL,
  `mark_distribution` text NOT NULL,
  `hall_id` int(11) NOT NULL,
  `exam_date` date NOT NULL,
  `branch_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` varchar(20) NOT NULL,
  `voucher_head_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `category` varchar(20) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `dr` decimal(18,2) NOT NULL DEFAULT 0.00,
  `cr` decimal(18,2) NOT NULL DEFAULT 0.00,
  `bal` decimal(18,2) NOT NULL DEFAULT 0.00,
  `date` date NOT NULL,
  `pay_via` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `attachments` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `system` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transport_assign`
--

CREATE TABLE IF NOT EXISTS `transport_assign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_id` int(11) NOT NULL,
  `stoppage_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transport_route`
--

CREATE TABLE IF NOT EXISTS `transport_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `start_place` longtext NOT NULL,
  `remarks` longtext NOT NULL,
  `stop_place` longtext NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transport_vehicle`
--

CREATE TABLE IF NOT EXISTS `transport_vehicle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_no` longtext NOT NULL,
  `capacity` longtext NOT NULL,
  `insurance_renewal` longtext NOT NULL,
  `driver_name` longtext NOT NULL,
  `driver_phone` longtext NOT NULL,
  `driver_license` longtext NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_head`
--

CREATE TABLE IF NOT EXISTS `voucher_head` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `system` tinyint(1) DEFAULT 0,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
