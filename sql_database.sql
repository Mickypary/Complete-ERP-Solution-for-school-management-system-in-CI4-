-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2023 at 04:02 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`id`, `name`, `school_name`, `email`, `mobileno`, `currency`, `symbol`, `city`, `state`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Ladoke Akintola ', 'Grenville Schools', 'mikipary@gmail.com', '09062684833', 'USD', '$', 'Ikeja', 'Lagos', '18 Ladoke Akintola Street GRA\r\n', '2023-04-16 16:17:54', '2023-04-19 15:58:04'),
(7, 'Joel', 'Grenville Schools', 'eathorne@yahoo.com', '0812345676', 'USD', '$', 'Ikeja', 'Lagos', '15b Joel Ogunaike', '2023-04-18 14:41:22', '2023-04-18 14:42:36');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(1, 'Grenville Schoools', 'GVS', 'on', 'info@grenvilleschool.com', '18 Ladoke Akintola Street GRA Ikeja Lagos', '09062684833', 'USD', '$', '	\r\ndisabled', 1, 'bengali', '© 2023 Grenville School Management - Developed by MrichCode', 'fadeIn', 'Pacific/Midway', 'm-d-Y', 'https://www.facebook.com/username', 'https://www.twitter.com/username', 'https://www.linkedin.com/username', 'https://www.youtube.com/username', '340fe292903d1bdc2eb79298a71ebda6', '2023-04-02 15:52:54', '2023-04-19 17:59:14');

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
  `word` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `english` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bengali` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `arabic` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `french` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `hindi` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `indonesian` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `italian` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `japanese` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `korean` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dutch` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `portuguese` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `thai` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `turkish` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `urdu` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `chinese` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=331 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `word`, `english`, `bengali`, `arabic`, `french`, `hindi`, `indonesian`, `italian`, `japanese`, `korean`, `dutch`, `portuguese`, `thai`, `turkish`, `urdu`, `chinese`) VALUES
(1, 'username_password_incorrect', 'Username Password Incorrect', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(2, 'username_or_password_incorrect', 'Username Or Password Incorrect', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(3, 'inactive_account', 'Inactive Account', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(4, 'profile', 'Profile', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(5, 'password', 'Password', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(6, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(7, 'dashboard', 'Dashboard', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(8, 'global_settings', 'Global Settings', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(9, 'school_settings', 'School Settings', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(10, 'reset_password', 'Reset Password', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(11, 'login', 'Login', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(12, 'welcome_back', 'Welcome Back', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(13, 'Login_to_your_admin_panel', 'Login To Your Admin Panel', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(14, 'social_network', 'Social Network', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(15, 'sign_up', 'Sign Up', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(16, 'remember_me ?', 'Remember Me ?', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(17, 'forgot_your_password ?', 'Forgot Your Password ?', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(18, 'password_has_been_changed', 'Password Has Been Changed', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(19, 'branch', 'Branch', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(20, 'sl', 'Sl', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(21, 'branch_name', 'Branch Name', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(22, 'school_name', 'School Name', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(23, 'email', 'Email', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(24, 'mobile_no', 'Mobile No', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(25, 'currency', 'Currency', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(26, 'symbol', 'Symbol', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(27, 'city', 'City', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(28, 'state', 'State', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(29, 'address', 'Address', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(30, 'action', 'Action', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(31, 'currency_symbol', 'Currency Symbol', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(32, 'save', 'Save', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(33, 'are_you_sure', 'Are You Sure', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(34, 'delete_this_information', 'Delete This Information', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(35, 'yes_continue', 'Yes Continue', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(36, 'cancel', 'Cancel', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(37, 'deleted_note', 'Deleted Note', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(38, 'deleted', 'Deleted', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(39, 'information_deleted', 'Information Deleted', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(40, 'do_delete_this_information', 'Do Delete This Information', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(41, 'you_want_to_delete_this_information', 'You Want To Delete This Information', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(42, 'are_you_sure?', 'Are You Sure?', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(43, 'do_you_want_to_delete_this_information?', 'Do You Want To Delete This Information?', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(44, 'information_has_been_saved_successfully', 'Information Has Been Saved Successfully', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(45, 'edit_branch', 'Edit Branch', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(46, 'branch_list', 'Branch List', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(47, 'information_has_been_updated_successfully', 'Information Has Been Updated Successfully', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(48, 'update', 'Update', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(49, 'branch_dashboard', 'Branch Dashboard', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(50, 'all_branch_dashboard', 'All Branch Dashboard', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(51, 'settings', 'Settings', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(52, 'role_permission', 'Role Permission', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(53, 'session_settings', 'Session Settings', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(54, 'translations', 'Translations', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(55, 'cron_job', 'Cron Job', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(56, 'custom_field', 'Custom Field', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(57, 'database_backup', 'Database Backup', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(58, 'general_settings', 'General Settings', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(59, 'theme_settings', 'Theme Settings', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(60, 'logo', 'Logo', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(61, 'institute_name', 'Institute Name', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(62, 'institution_code', 'Institution Code', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(63, 'language', 'Language', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(64, 'academic_session', 'Academic Session', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(65, 'select', 'Select', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(66, 'timezone', 'Timezone', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(67, 'add_session', 'Add Session', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(68, 'session', 'Session', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(69, 'sessions_list', 'Sessions List', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(70, 'status', 'Status', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(71, 'created_at', 'Created At', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(72, 'selected_session', 'Selected Session', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(73, 'edit_session', 'Edit Session', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(74, 'sessions', 'Sessions', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(75, 'forgot_password?', 'Forgot Password?', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(76, 'remember_me?', 'Remember Me?', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(77, 'remember_me', 'Remember Me', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(78, 'login ?', 'Login ?', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(79, 'change_password', 'Change Password', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(80, 'all_branches', 'All Branches', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(81, 'create_branch', 'Create Branch', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(82, 'edit', 'Edit', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(83, 'my_profile', 'My Profile', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(84, 'logout', 'Logout', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(85, 'old_password', 'Old Password', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(86, 'new_password', 'New Password', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(87, 'confirm_password', 'Confirm Password', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(88, 'name', 'Name', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(89, 'present_address', 'Present Address', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(90, 'failed_to_update_password', 'Failed To Update Password', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(91, 'please_check_current_password', 'Please Check Current Password', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(92, 'institute_code', 'Institute Code', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(93, 'the_configuration_has_been_updated', 'The Configuration Has Been Updated', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(94, 'animations', 'Animations', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(95, 'date_format', 'Date Format', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(96, 'footer_text', 'Footer Text', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(97, 'facebook_url', 'Facebook Url', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(98, 'twitter_url', 'Twitter Url', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(99, 'linkedin_url', 'Linkedin Url', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(100, 'youtube_url', 'Youtube Url', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(101, 'admission', 'Admission', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(102, 'create_admission', 'Create Admission', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(103, 'multiple_import', 'Multiple Import', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(104, 'category', 'Category', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(105, 'access_denied', 'Access Denied', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(106, 'school_setting', 'School Setting', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(107, 'school_details', 'School Details', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(108, 'welcome_to', 'Welcome To', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(109, 'remember', 'Remember', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(110, 'lose_your_password', 'Lose Your Password', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(111, 'income_vs_expense_of', 'Income Vs Expense Of', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(112, 'income', 'Income', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(113, 'expense', 'Expense', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(114, 'annual_fee_summary', 'Annual Fee Summary', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(115, 'employee', 'Employee', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(116, 'total_strength', 'Total Strength', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(117, 'students', 'Students', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(118, 'parents', 'Parents', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(119, 'teachers', 'Teachers', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(120, 'student_quantity', 'Student Quantity', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(121, 'weekend_attendance_inspection', 'Weekend Attendance Inspection', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(122, 'interval_month', 'Interval Month', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(123, 'voucher', 'Voucher', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(124, 'total_number', 'Total Number', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(125, 'transport', 'Transport', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(126, 'total_route', 'Total Route', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(127, 'hostel', 'Hostel', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(128, 'total_room', 'Total Room', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(129, 'event_details', 'Event Details', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(130, 'close', 'Close', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(131, 'this_value_is_required', 'This Value Is Required', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(132, 'enter_valid_email', 'Enter Valid Email', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(133, 'student_admission', 'Student Admission', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(134, 'salary_payment', 'Salary Payment', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(135, 'leave_application', 'Leave Application', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(136, 'live_class_rooms', 'Live Class Rooms', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(137, 'due_fees_invoice', 'Due Fees Invoice', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(138, 'payments_history', 'Payments History', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(139, 'search', 'Search', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(140, 'mailbox', 'Mailbox', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(141, 'student_details', 'Student Details', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(142, 'student_list', 'Student List', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(143, 'id_card_generate', 'Id Card Generate', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(144, 'login_deactivate', 'Login Deactivate', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(145, 'parents_list', 'Parents List', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(146, 'add_parent', 'Add Parent', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(147, 'employee_list', 'Employee List', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(148, 'add_department', 'Add Department', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(149, 'add_designation', 'Add Designation', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(150, 'add_employee', 'Add Employee', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(151, 'hrm', 'Hrm', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(152, 'payroll', 'Payroll', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(153, 'salary_template', 'Salary Template', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(154, 'salary_assign', 'Salary Assign', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(155, 'advance_salary', 'Advance Salary', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(156, 'my_application', 'My Application', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(157, 'manage_application', 'Manage Application', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(158, 'leave', 'Leave', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(159, 'award', 'Award', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(160, 'academic', 'Academic', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(161, 'class', 'Class', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(162, 'section', 'Section', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(163, 'control_classes', 'Control Classes', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(164, 'assign_class_teacher', 'Assign Class Teacher', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(165, 'subject', 'Subject', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(166, 'class_assign', 'Class Assign', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(167, 'teacher', 'Teacher', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(168, 'assign', 'Assign', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(169, 'schedule', 'Schedule', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(170, 'promotion', 'Promotion', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(171, 'attachments_book', 'Attachments Book', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(172, 'upload_content', 'Upload Content', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(173, 'attachment_type', 'Attachment Type', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(174, 'homework', 'Homework', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(175, 'evaluation_report', 'Evaluation Report', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(176, 'exam_master', 'Exam Master', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(177, 'exam', 'Exam', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(178, 'exam_term', 'Exam Term', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(179, 'exam_hall', 'Exam Hall', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(180, 'distribution', 'Distribution', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(181, 'exam_setup', 'Exam Setup', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(182, 'add', 'Add', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(183, 'marks', 'Marks', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(184, 'mark_entries', 'Mark Entries', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(185, 'grades_range', 'Grades Range', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(186, 'supervision', 'Supervision', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(187, 'hostel_master', 'Hostel Master', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(188, 'hostel_room', 'Hostel Room', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(189, 'allocation_report', 'Allocation Report', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(190, 'route_master', 'Route Master', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(191, 'vehicle_master', 'Vehicle Master', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(192, 'stoppage', 'Stoppage', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(193, 'assign_vehicle', 'Assign Vehicle', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(194, 'attendance', 'Attendance', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(195, 'student', 'Student', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(196, 'library', 'Library', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(197, 'books', 'Books', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(198, 'books_category', 'Books Category', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(199, 'events', 'Events', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(200, 'event_type', 'Event Type', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(201, 'sms', 'Sms', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(202, 'template', 'Template', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(203, 'student_accounting', 'Student Accounting', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(204, 'fees_type', 'Fees Type', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(205, 'fees_group', 'Fees Group', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(206, 'fine_setup', 'Fine Setup', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(207, 'fees_allocation', 'Fees Allocation', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(208, 'fees_reminder', 'Fees Reminder', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(209, 'office_accounting', 'Office Accounting', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(210, 'account', 'Account', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(211, 'new_deposit', 'New Deposit', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(212, 'new_expense', 'New Expense', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(213, 'all_transactions', 'All Transactions', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(214, 'head', 'Head', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(215, 'message', 'Message', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(216, 'reports', 'Reports', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(217, 'fees_reports', 'Fees Reports', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(218, 'fees_report', 'Fees Report', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(219, 'receipts_report', 'Receipts Report', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(220, 'due_fees_report', 'Due Fees Report', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(221, 'fine_report', 'Fine Report', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(222, 'financial_reports', 'Financial Reports', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(223, 'statement', 'Statement', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(224, 'repots', 'Repots', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(225, 'transitions', 'Transitions', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(226, 'balance', 'Balance', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(227, 'sheet', 'Sheet', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(228, 'income_vs_expense', 'Income Vs Expense', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(229, 'attendance_reports', 'Attendance Reports', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(230, 'payroll_summary', 'Payroll Summary', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(231, 'examination', 'Examination', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(232, 'report_card', 'Report Card', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(233, 'tabulation_sheet', 'Tabulation Sheet', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(234, 'not_found_anything', 'Not Found Anything', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(235, 'total', 'Total', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(236, 'collected', 'Collected', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(237, 'remaining', 'Remaining', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(238, 'system_logo', 'System Logo', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(239, 'text_logo', 'Text Logo', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(240, 'printing_logo', 'Printing Logo', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(241, 'upload', 'Upload', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(242, 'school', 'School', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(243, 'list', 'List', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(244, 'details', 'Details', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(245, 'live_class', 'Live Class', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(246, 'payment_settings', 'Payment Settings', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(247, 'sms_settings', 'Sms Settings', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(248, 'email_settings', 'Email Settings', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(249, 'accounting_links', 'Accounting Links', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(250, 'department', 'Department', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(251, 'birthday', 'Birthday', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(252, 'joining_date', 'Joining Date', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(253, 'employee_details', 'Employee Details', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(254, 'gender', 'Gender', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(255, 'male', 'Male', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(256, 'female', 'Female', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(257, 'religion', 'Religion', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(258, 'blood_group', 'Blood Group', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(259, 'permanent_address', 'Permanent Address', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(260, 'profile_picture', 'Profile Picture', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(261, 'login_details', 'Login Details', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(262, 'social_links', 'Social Links', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(263, 'change', 'Change', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(264, 'current_password', 'Current Password', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(265, 'failed_to_update_password. incorrect_old_password', 'Failed To Update Password. Incorrect Old Password', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(266, 'already_taken', 'Already Taken', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(267, 'reset_my_password', 'Reset My Password', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(268, 'password_restoration', 'Password Restoration', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(269, 'forgot', 'Forgot', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(270, 'back_to_login', 'Back To Login', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(271, 'photo', 'Photo', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(272, 'staff_id', 'Staff Id', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(273, 'designation', 'Designation', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(274, 'academic_details', 'Academic Details', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(275, 'role', 'Role', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(276, 'select_branch_first', 'Select Branch First', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(277, 'qualification', 'Qualification', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(278, 'retype_password', 'Retype Password', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(279, 'bank_details', 'Bank Details', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(280, 'skipped_bank_details', 'Skipped Bank Details', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(281, 'bank_name', 'Bank Name', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(282, 'account_name', 'Account Name', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(283, 'bank', 'Bank', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(284, 'bank_address', 'Bank Address', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(285, 'ifsc_code', 'Ifsc Code', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(286, 'account_no', 'Account No', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(287, 'import', 'Import', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(288, 'department_name', 'Department Name', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(289, 'no_information_available', 'No Information Available', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(290, 'designation_name', 'Designation Name', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(291, 'holder_name', 'Holder Name', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(292, 'bank_branch', 'Bank Branch', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(293, 'username_has_already_been_used', 'Username Has Already Been Used', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(294, 'roles', 'Roles', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(295, 'create', 'Create', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(296, 'system_role', 'System Role', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(297, 'yes', 'Yes', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(298, 'permission', 'Permission', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(299, 'role_name', 'Role Name', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(300, 'role_permission_for', 'Role Permission For', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(301, 'feature', 'Feature', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(302, 'view', 'View', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(303, 'delete', 'Delete', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(304, 'employee_profile', 'Employee Profile', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(305, 'authentication', 'Authentication', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(306, 'basic_details', 'Basic Details', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(307, 'salary_transaction', 'Salary Transaction', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(308, 'month_of_salary', 'Month Of Salary', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(309, 'basic_salary', 'Basic Salary', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(310, 'allowances', 'Allowances', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(311, 'deductions', 'Deductions', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(312, 'paid_amount', 'Paid Amount', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(313, 'payment_type', 'Payment Type', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(314, 'payslip', 'Payslip', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(315, 'bank_account', 'Bank Account', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(316, 'add_bank', 'Add Bank', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(317, 'actions', 'Actions', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(318, 'documents', 'Documents', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(319, 'add_documents', 'Add Documents', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(320, 'title', 'Title', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(321, 'document_type', 'Document Type', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(322, 'file', 'File', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(323, 'remarks', 'Remarks', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(324, 'class_schedule', 'Class Schedule', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(325, 'class_room', 'Class Room', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(326, 'time_start', 'Time Start', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(327, 'time_end', 'Time End', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(328, 'document', 'Document', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(329, 'login_authentication_deactivate', 'Login Authentication Deactivate', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(330, 'no', 'No', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `language_list`
--

INSERT INTO `language_list` (`id`, `name`, `lang_field`, `status`, `created_at`, `updated_at`) VALUES
(1, 'English', 'english', 1, '2023-04-10 09:40:10', '2023-04-10 09:40:10'),
(2, 'Bengali', 'bengali', 1, '2023-04-10 09:40:10', '2023-04-10 09:40:10');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `login_credential`
--

INSERT INTO `login_credential` (`id`, `user_id`, `username`, `password`, `role`, `active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin@admin.com', '$2y$10$A2Q8iSy0IXmkzayG31JXpu4D1b3mKy3tHQ.VOAf2mGrtESV8GI.mK', 1, 1, '2023-04-27 08:53:40', '2020-05-31 13:06:26', '2023-04-27 08:53:40'),
(2, 2, 'michael.kobru@grenvilleschool.com', '$2y$10$CGStvs.8Si5AdlmVJgj5wuWoEQHcP9mFUge0SC/wl7UJjRxFsvyPa', 3, 1, '2023-04-27 14:24:33', '2023-04-26 12:50:33', '2023-04-27 14:24:33');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `staff_id`, `name`, `department`, `qualification`, `designation`, `joining_date`, `birthday`, `sex`, `religion`, `blood_group`, `present_address`, `permanent_address`, `mobileno`, `email`, `salary_template_id`, `branch_id`, `photo`, `facebook_url`, `linkedin_url`, `twitter_url`, `created_at`, `updated_at`, `hash`) VALUES
(1, '3597c7f', 'admin', 2, '', 0, '2020-05-31', '', '', '', '', 'Ikeja', '', '09062684833', 'admin@admin.com', 0, NULL, 'defualt.png', 'facebook.com/user', 'linkedin.com/user', 'twitter.com/user', '2020-05-31 13:06:26', '2023-04-24 16:18:09', ''),
(2, '1ab7b7d', 'Afolabi Olalekan', 2, 'B.sc', 1, '2023-04-04', '2019-12-30', 'male', 'Christianity', 'A+', 'Ikorodu', 'Ikorodu', '0812345675', 'michael.kobru@grenvilleschool.com', 0, 1, '1682509833_c3664fb2a6e4c2dd0e65.png', 'facebook.com/user', 'linkedin.com/user', 'twitter.com/user', '2023-04-26 12:50:33', '2023-04-26 12:50:33', '');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(3, 'IT', 7, '2023-04-24 20:36:11', '2023-04-24 20:36:11'),
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_designation`
--

INSERT INTO `staff_designation` (`id`, `name`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 'HOD', 1, '2023-04-24 21:02:08', '2023-04-24 21:02:08');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
