-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 21, 2016 at 07:28 PM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `topup_card_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_card`
--

CREATE TABLE IF NOT EXISTS `tbl_card` (
  `card_id` int(11) NOT NULL AUTO_INCREMENT,
  `card_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `participant_id` int(11) NOT NULL,
  `card_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `card_issued_datetime` datetime DEFAULT NULL,
  `card_expired_datetime` datetime DEFAULT NULL,
  `current_card_amt` double NOT NULL,
  `card_status_id` int(11) NOT NULL,
  `card_data_modified_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`card_id`),
  KEY `student_id` (`participant_id`),
  KEY `card_status_id` (`card_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_card_status`
--

CREATE TABLE IF NOT EXISTS `tbl_card_status` (
  `card_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `card_status_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `card_status_created_datetime` datetime DEFAULT NULL,
  `card_status_modified_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`card_status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_card_status`
--

INSERT INTO `tbl_card_status` (`card_status_id`, `card_status_name`, `card_status_created_datetime`, `card_status_modified_datetime`) VALUES
(1, 'Active', '2016-10-17 12:35:15', '2016-10-17 12:35:15'),
(3, 'in-active', '2016-10-17 13:17:06', '2016-10-21 11:51:31');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category_type`
--

CREATE TABLE IF NOT EXISTS `tbl_category_type` (
  `category_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category_type_created_datetime` datetime DEFAULT NULL,
  `category_type_modified_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`category_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_category_type`
--

INSERT INTO `tbl_category_type` (`category_type_id`, `category_type_name`, `category_type_created_datetime`, `category_type_modified_datetime`) VALUES
(3, 'Lunch', '2016-10-17 17:16:11', '2016-10-17 17:16:11'),
(4, 'Dinner1', '2016-10-17 17:16:31', '2016-10-21 11:54:43');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_eventlog`
--

CREATE TABLE IF NOT EXISTS `tbl_eventlog` (
  `eventlog_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action_table` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `changes_mode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `original_mode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action_user_id` int(11) NOT NULL,
  `ip_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`eventlog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=155 ;

--
-- Dumping data for table `tbl_eventlog`
--

INSERT INTO `tbl_eventlog` (`eventlog_id`, `action_name`, `action_table`, `changes_mode`, `original_mode`, `action_user_id`, `ip_address`, `action_datetime`) VALUES
(1, ' Insert', ' tbl_user_type', 'user_type_id=>11,user_type_name=>test user type 2,user_type_description=>test user type 2 desc', '', 1, '::1', '2016-10-13 14:43:35'),
(2, 'Insert', 'tbl_user_type', 'user_type_id=>19,user_type_name=>ee,user_type_description=>ddeeee', NULL, 1, '::1', '2016-10-13 15:05:31'),
(3, 'Insert', 'tbl_user_type', 'user_type_id=>13,user_type_name=>gue gue,user_type_description=>uuuhh', NULL, 1, '::1', '2016-10-13 15:49:35'),
(4, 'Insert', 'tbl_user_type', 'user_type_id=>13,user_type_name=>gue gue2,user_type_description=>uuuhh', NULL, 1, '::1', '2016-10-13 15:50:38'),
(5, 'Insert', 'tbl_user_type', 'user_type_id=>14,user_type_name=>jue jue,user_type_description=>uuuhh', NULL, 1, '::1', '2016-10-13 15:52:18'),
(6, 'Insert', 'tbl_user_type', 'user_type_id=>17,user_type_name=>bu bu,user_type_description=>llkkkk', NULL, 1, '::1', '2016-10-13 15:53:57'),
(7, 'Update', 'tbl_user_type', 'user_type_id=>18,user_type_name=>mu mu,user_type_description=>kuuuu', 'user_type_id=>18,user_type_name=>mjkl,user_type_desc=>kuuuu', 1, '::1', '2016-10-13 15:58:45'),
(8, 'Update', 'tbl_user_type', 'user_type_id=>16,user_type_name=>ei ei,user_type_description=>jtyrtyt', 'user_type_id=>16,user_type_name=>eheee,user_type_desc=>jtyrtyt', 1, '::1', '2016-10-13 15:58:57'),
(9, 'Delete', 'tbl_user_type', 'user_type_id=>12', NULL, 1, '::1', '2016-10-13 16:37:04'),
(10, 'Delete', 'tbl_user_type', 'user_type_id=>17', NULL, 1, '::1', '2016-10-13 16:42:40'),
(11, 'Delete', 'tbl_user_type', 'user_type_id=>16', NULL, 1, '::1', '2016-10-13 16:42:46'),
(12, 'Delete', 'tbl_user_type', 'user_type_id=>13', NULL, 1, '::1', '2016-10-13 16:42:55'),
(13, 'Delete', 'tbl_user_type', 'user_type_id=>14', NULL, 1, '::1', '2016-10-13 16:43:00'),
(14, 'Delete', 'tbl_user_type', 'user_type_id=>18', NULL, 1, '::1', '2016-10-13 16:43:05'),
(15, 'Delete', 'tbl_user_type', 'user_type_id=>11', NULL, 1, '::1', '2016-10-13 16:43:12'),
(16, 'Delete', 'tbl_user_type', 'user_type_id=>10', NULL, 1, '::1', '2016-10-13 16:43:17'),
(17, 'Insert', 'tbl_user_type', 'user_type_id=>20,user_type_name=>dfeere,user_type_description=>dddd', NULL, 1, '::1', '2016-10-13 17:32:51'),
(18, 'Delete', 'tbl_user_type', 'user_type_id=>20', NULL, 1, '::1', '2016-10-13 17:32:59'),
(19, 'Update', 'tbl_user_type', 'user_type_id=>2,user_type_name=>parent,user_type_description=>For student parents', 'user_type_id=>2,user_type_name=>parent,user_type_desc=>this is for student parents', 1, '::1', '2016-10-13 18:43:55'),
(20, 'Update', 'tbl_user_type', 'user_type_id=>3,user_type_name=>Staff,user_type_description=>For casher staff in canteen', 'user_type_id=>3,user_type_name=>staff,user_type_desc=>this is for casher staff in canteen', 1, '::1', '2016-10-13 18:44:05'),
(21, 'Update', 'tbl_user_type', 'user_type_id=>1,user_type_name=>Admin,user_type_description=>administratior', 'user_type_id=>1,user_type_name=>admin,user_type_desc=>administratior', 1, '::1', '2016-10-13 18:44:14'),
(22, 'Update', 'tbl_user_type', 'user_type_id=>2,user_type_name=>Parent,user_type_description=>For student parents', 'user_type_id=>2,user_type_name=>parent,user_type_desc=>For student parents', 1, '::1', '2016-10-13 18:44:24'),
(23, 'Update', 'tbl_user_type', 'user_type_id=>1,user_type_name=>Admin,user_type_description=>Administratior', 'user_type_id=>1,user_type_name=>Admin,user_type_desc=>administratior', 1, '::1', '2016-10-13 18:45:10'),
(24, 'Update', 'tbl_user_type', 'user_type_id=>2,user_type_name=>Parent,user_type_description=>For student parents', 'user_type_id=>2,user_type_name=>Parent,user_type_desc=>For student parents', 1, '::1', '2016-10-13 18:46:11'),
(25, 'Update', 'tbl_user_type', 'user_type_id=>2,user_type_name=>admin,user_type_description=>For student parents', 'user_type_id=>2,user_type_name=>Parent,user_type_desc=>For student parents', 1, '::1', '2016-10-14 10:35:05'),
(26, 'Update', 'tbl_user_type', 'user_type_id=>2,user_type_name=>Parent,user_type_description=>For student parents', 'user_type_id=>2,user_type_name=>admin,user_type_desc=>For student parents', 1, '::1', '2016-10-14 10:47:55'),
(27, 'Update', 'tbl_user', 'user_id=>5,is_active=>0', 'user_id=>5,is_active=>1', 1, '::1', '2016-10-14 15:08:12'),
(28, 'Insert', 'tbl_school', 'school_name=>school1,school_address=>no:1, street1,school_description=>primary school', NULL, 1, '::1', '2016-10-14 16:09:40'),
(29, 'Insert', 'tbl_school', 'school_name=>school2,school_address=>no:2, sonedddddddd,school_description=>erererere', NULL, 1, '::1', '2016-10-14 16:11:21'),
(30, 'Update', 'tbl_school', 'school_id=>1,school_name=>school-one,school_address=>no:1, street1,school_description=>primary school', 'school_name=>school1,school_address=>no:1, street1,school_description=>primary school', 1, '::1', '2016-10-14 16:54:14'),
(31, 'Update', 'tbl_school', 'school_id=>2,school_name=>school-two,school_address=>no:2, sonedddddddd,school_description=>erererere', 'school_name=>school2,school_address=>no:2, sonedddddddd,school_description=>erererere', 1, '::1', '2016-10-14 16:54:23'),
(32, 'Delete', 'tbl_school', 'school_id=>2', NULL, 1, '::1', '2016-10-14 17:20:16'),
(33, 'Insert', 'tbl_school', 'school_name=>S school,school_address=>no:1223,school_description=>Secondary school', NULL, 1, '::1', '2016-10-14 17:20:47'),
(34, 'Insert', 'tbl_food_allergy', 'food_allergy_name=>Tomato', NULL, 1, '::1', '2016-10-17 10:42:45'),
(35, 'Insert', 'tbl_food_allergy', 'food_allergy_name=>Peanut', NULL, 1, '::1', '2016-10-17 10:42:57'),
(36, 'Update', 'tbl_food_allergy', 'food_allergy_id=>2,food_allergy_name=>red beans', 'food_allergy_id=>2,food_allergy_name=>Peanut', 1, '::1', '2016-10-17 11:28:37'),
(37, 'Delete', 'tbl_food_allergy', 'food_allergy_id=>1', NULL, 1, '::1', '2016-10-17 11:46:54'),
(38, 'Insert', 'tbl_food_allergy', 'food_allergy_name=>mushroom', NULL, 1, '::1', '2016-10-17 11:47:25'),
(39, 'Insert', 'tbl_card_status', 'card_status_name=>Active', NULL, 1, '::1', '2016-10-17 12:35:15'),
(40, 'Insert', 'tbl_card_status', 'card_status_name=>Inactive', NULL, 1, '::1', '2016-10-17 12:36:05'),
(41, 'Update', 'tbl_card_status', 'card_status_id=>2,card_status_name=>In-active', 'card_status_id=>2,card_status_name=>Inactive', 1, '::1', '2016-10-17 13:04:17'),
(42, 'Delete', 'tbl_card_status', 'card_status_id=>2', NULL, 1, '::1', '2016-10-17 13:16:35'),
(43, 'Delete', 'tbl_card_status', 'card_status_id=>2', NULL, 1, '::1', '2016-10-17 13:16:57'),
(44, 'Insert', 'tbl_card_status', 'card_status_name=>Inactive', NULL, 1, '::1', '2016-10-17 13:17:06'),
(45, 'Update', 'tbl_card_status', 'card_status_id=>3,card_status_name=>In-active', 'card_status_id=>3,card_status_name=>Inactive', 1, '::1', '2016-10-17 14:46:56'),
(46, 'Insert', 'tbl_category_type', 'category_type_name=>breakfast', NULL, 1, '::1', '2016-10-17 15:40:38'),
(47, 'Insert', 'tbl_category_type', 'category_type_name=>Lunch', NULL, 1, '::1', '2016-10-17 16:40:10'),
(48, 'Update', 'tbl_category_type', 'category_type_id=>1,category_type_name=>Breakfast', 'category_type_id=>1,category_type_name=>breakfast', 1, '::1', '2016-10-17 16:40:17'),
(49, 'Delete', 'tbl_category_type', 'category_type_id=>2', NULL, 1, '::1', '2016-10-17 17:13:50'),
(50, 'Insert', 'tbl_category_type', 'category_type_name=>Lunch', NULL, 1, '::1', '2016-10-17 17:16:11'),
(51, 'Insert', 'tbl_category_type', 'category_type_name=>Dinner', NULL, 1, '::1', '2016-10-17 17:16:31'),
(52, 'Insert', 'tbl_category_type', 'category_type_name=>test', NULL, 1, '::1', '2016-10-17 18:39:44'),
(53, 'Update', 'tbl_category_type', 'category_type_id=>1,category_type_name=>Breakfast1', 'category_type_id=>1,category_type_name=>Breakfast', 1, '::1', '2016-10-17 18:40:02'),
(54, 'Delete', 'tbl_category_type', 'category_type_id=>1', NULL, 1, '::1', '2016-10-17 18:40:27'),
(55, 'Insert', 'tbl_organization', 'org_name=>test,org_address=>eereee,org_description=>eeeewwww', NULL, 1, '::1', '2016-10-18 12:05:29'),
(56, 'Update', 'tbl_organization', 'org_id=>4,org_name=>test,org_address=>eereee,org_description=>eeeewwww', 'org_name=>test,org_address=>eereee,org_description=>eeeewwww', 1, '::1', '2016-10-18 12:07:11'),
(57, 'Update', 'tbl_organization', 'org_id=>4,org_name=>test11111,org_address=>eereee,org_description=>eeeewwww', 'org_name=>test,org_address=>eereee,org_description=>eeeewwww', 1, '::1', '2016-10-18 12:07:31'),
(58, 'Delete', 'tbl_organization', 'org_id=>4', NULL, 1, '::1', '2016-10-18 12:08:45'),
(59, 'Insert', 'tbl_food_allergy', 'food_allergy_name=>coffee', NULL, 1, '::1', '2016-10-18 12:12:07'),
(60, 'Update', 'tbl_food_allergy', 'food_allergy_id=>4,food_allergy_name=>coffeedddd', 'food_allergy_id=>4,food_allergy_name=>coffee', 1, '::1', '2016-10-18 12:12:14'),
(61, 'Delete', 'tbl_food_allergy', 'food_allergy_id=>4', NULL, 1, '::1', '2016-10-18 12:12:22'),
(62, 'Insert', 'tbl_user_type', 'user_type_id=>4,user_type_name=>testdddddd,user_type_description=>eeeeedddddddddddd', NULL, 1, '::1', '2016-10-18 12:12:42'),
(63, 'Update', 'tbl_user_type', 'user_type_id=>4,user_type_name=>testdddddd1111111,user_type_description=>eeeeedddddddddddd', 'user_type_id=>4,user_type_name=>testdddddd,user_type_desc=>eeeeedddddddddddd', 1, '::1', '2016-10-18 12:12:50'),
(64, 'Delete', 'tbl_user_type', 'user_type_id=>4', NULL, 1, '::1', '2016-10-18 12:12:55'),
(65, 'Insert', 'tbl_card_status', 'card_status_name=>dddd', NULL, 1, '::1', '2016-10-18 12:13:08'),
(66, 'Update', 'tbl_card_status', 'card_status_id=>4,card_status_name=>dddd111', 'card_status_id=>4,card_status_name=>dddd', 1, '::1', '2016-10-18 12:13:57'),
(67, 'Delete', 'tbl_card_status', 'card_status_id=>4', NULL, 1, '::1', '2016-10-18 12:14:06'),
(68, 'Update', 'tbl_category_type', 'category_type_id=>5,category_type_name=>testeee', 'category_type_id=>5,category_type_name=>test', 1, '::1', '2016-10-18 12:14:15'),
(69, 'Delete', 'tbl_category_type', 'category_type_id=>5', NULL, 1, '::1', '2016-10-18 12:17:22'),
(70, 'Insert', 'tbl_user_type', 'user_type_id=>4,user_type_name=>rttt,user_type_description=>dfrrr', NULL, 1, '::1', '2016-10-19 11:05:31'),
(71, 'Update', 'tbl_user_type', 'user_type_id=>4,user_type_name=>deee,user_type_description=>dfrrr', 'user_type_id=>4,user_type_name=>rttt,user_type_desc=>dfrrr', 1, '::1', '2016-10-19 11:13:32'),
(72, 'Delete', 'tbl_user_type', 'user_type_id=>4', NULL, 1, '::1', '2016-10-19 11:13:40'),
(73, 'Update', 'tbl_user', 'user_id=>6,is_active=>0', 'user_id=>6,is_active=>1', 1, '::1', '2016-10-19 12:41:21'),
(74, 'Update', 'tbl_user', 'user_id=>6,is_active=>1', 'user_id=>6,is_active=>0', 1, '::1', '2016-10-19 12:42:16'),
(75, 'Insert', 'tbl_organization', 'org_name=>eeree,org_address=>deee,org_description=>erereerere', NULL, 1, '::1', '2016-10-19 14:59:30'),
(76, 'Insert', 'tbl_organization', 'org_name=>vvvvdddd,org_address=>dfdffefe,org_description=>ferererere', NULL, 1, '::1', '2016-10-19 15:12:26'),
(77, 'Update', 'tbl_organization', 'org_id=>5,org_name=>vvvvdddd,org_address=>dfdffefe22222,org_description=>ferererere2222', 'org_name=>vvvvdddd,org_address=>dfdffefe,org_description=>ferererere', 1, '::1', '2016-10-19 15:12:59'),
(78, 'Delete', 'tbl_organization', 'org_id=>5', NULL, 1, '::1', '2016-10-19 15:13:04'),
(79, 'Delete', 'tbl_organization', 'org_id=>4', NULL, 1, '::1', '2016-10-19 15:17:42'),
(80, 'Insert', 'tbl_card_status', 'card_status_name=>test', NULL, 1, '::1', '2016-10-19 16:30:02'),
(81, 'Update', 'tbl_card_status', 'card_status_id=>4,card_status_name=>test1111', 'card_status_id=>4,card_status_name=>test', 1, '::1', '2016-10-19 16:30:14'),
(82, 'Delete', 'tbl_card_status', 'card_status_id=>4', NULL, 1, '::1', '2016-10-19 16:30:21'),
(83, 'Insert', 'tbl_category_type', 'category_type_name=>test', NULL, 1, '::1', '2016-10-19 16:50:57'),
(84, 'Update', 'tbl_category_type', 'category_type_id=>5,category_type_name=>test111', 'category_type_id=>5,category_type_name=>test', 1, '::1', '2016-10-19 16:51:12'),
(85, 'Delete', 'tbl_category_type', 'category_type_id=>5', NULL, 1, '::1', '2016-10-19 16:51:17'),
(86, 'Insert', 'tbl_user_type', 'user_type_id=>5,user_type_name=>kjhuiiooo,user_type_description=>', NULL, 1, '::1', '2016-10-19 17:16:30'),
(87, 'Update', 'tbl_user_type', 'user_type_id=>5,user_type_name=>kjhuiiooo,user_type_description=>154788522', 'user_type_id=>5,user_type_name=>kjhuiiooo,user_type_desc=>', 1, '::1', '2016-10-19 17:16:39'),
(88, 'Delete', 'tbl_user_type', 'user_type_id=>5', NULL, 1, '::1', '2016-10-19 17:16:45'),
(89, 'Update', 'tbl_user', 'user_id=>5,is_active=>1', 'user_id=>5,is_active=>0', 1, '::1', '2016-10-19 17:17:18'),
(90, 'Update', 'tbl_user', 'user_id=>5,is_active=>0', 'user_id=>5,is_active=>1', 1, '::1', '2016-10-19 17:17:23'),
(91, 'Insert', 'tbl_organization', 'org_name=>jihgff,org_address=>lpouuu,org_description=>1456', NULL, 1, '::1', '2016-10-19 17:18:07'),
(92, 'Update', 'tbl_organization', 'org_id=>6,org_name=>jihgff,org_address=>lpouuu12233333,org_description=>1456', 'org_name=>jihgff,org_address=>lpouuu,org_description=>1456', 1, '::1', '2016-10-19 17:18:17'),
(93, 'Delete', 'tbl_organization', 'org_id=>6', NULL, 1, '::1', '2016-10-19 17:18:24'),
(94, 'Insert', 'tbl_meal_status', 'meal_status_name=>finish breakfase', NULL, 1, '::1', '2016-10-19 18:19:41'),
(95, 'Insert', 'tbl_meal_status', 'meal_status_name=>finish lunch', NULL, 1, '::1', '2016-10-19 18:20:28'),
(96, 'Insert', 'tbl_meal_status', 'meal_status_name=>not finish breakfast', NULL, 1, '::1', '2016-10-19 18:21:24'),
(97, 'Insert', 'tbl_meal_status', 'meal_status_name=>not finish lunch', NULL, 1, '::1', '2016-10-19 18:21:35'),
(98, 'Delete', 'tbl_meal_status', 'meal_status_id=>4', NULL, 1, '::1', '2016-10-19 18:45:11'),
(99, 'Insert', 'tbl_meal_status', 'meal_status_name=>not finish lunch', NULL, 1, '::1', '2016-10-19 18:45:19'),
(100, 'Update', 'tbl_meal_status', 'meal_status_id=>1,meal_status_name=>finish breakfase1', 'meal_status_id=>1,meal_status_name=>finish breakfase', 1, '::1', '2016-10-20 11:44:01'),
(101, 'Insert', 'tbl_items', 'item_name=>Breakfast A,item_description=>Fried egg, Fried rice, Apple Juice,item_price=>10', NULL, 1, '::1', '2016-10-20 12:31:34'),
(102, 'Insert', 'tbl_items', 'item_name=>Breakfast B,item_description=>Fried noodle, Apple Juice , Meat,item_price=>15', NULL, 1, '::1', '2016-10-20 12:32:16'),
(103, 'Insert', 'tbl_items', 'item_name=>Lunch A,item_description=>Chicken rice,item_price=>5.50', NULL, 1, '::1', '2016-10-20 12:33:16'),
(104, 'Update', 'tbl_items', 'item_id=>1,item_name=>Breakfast A,item_description=>Fried egg, Fried rice, Apple Juice,item_price=>10.12', 'item_id=>1,item_name=>Breakfast A,item_desc=>Fried egg, Fried rice, Apple Juice,item_price=>10', 1, '::1', '2016-10-20 14:16:31'),
(105, 'Update', 'tbl_items', 'item_id=>2,item_name=>Breakfast B1,item_description=>Fried noodle, Apple Juice , Meat,item_price=>15', 'item_id=>2,item_name=>Breakfast B,item_desc=>Fried noodle, Apple Juice , Meat,item_price=>15', 1, '::1', '2016-10-20 14:16:40'),
(106, 'Delete', 'tbl_items', 'item_id=>2', NULL, 1, '::1', '2016-10-20 14:39:34'),
(107, 'Insert', 'tbl_user', 'user_name=>staff1,user_email=>s1@email.com,md5_user_password=>202cb962ac59075b964b07152d234b70,org_user_password=>123,user_address=>no:1,user_phone=>123456,user_gender_id=>1,user_type_id=>3', NULL, 1, '::1', '2016-10-20 16:46:59'),
(108, 'Update', 'tbl_user', 'user_id=>7,is_active=>0', 'staff_id=>7,is_active=>1', 1, '::1', '2016-10-20 17:07:41'),
(109, 'Update', 'tbl_user', 'user_id=>7,is_active=>1', 'staff_id=>7,is_active=>0', 1, '::1', '2016-10-20 17:09:45'),
(110, 'Insert', 'tbl_user', 'user_name=>staff2,user_email=>s2@email.com,md5_user_password=>202cb962ac59075b964b07152d234b70,org_user_password=>123,user_address=>dddd,user_phone=>deee2222,user_gender_id=>2,user_type_id=>3', NULL, 1, '::1', '2016-10-20 17:10:07'),
(111, 'Update', 'tbl_user', 'user_id=>8,is_active=>0', 'staff_id=>8,is_active=>1', 1, '::1', '2016-10-20 17:10:14'),
(112, 'Update', 'tbl_user', 'user_id=>,user_email=>s11@email.com,user_name=>staff11,user_address=>no:1,user_phone=>123456,user_gender_id=>1', 'staff_id=>7,staff_name=>staff1,staff_email=>s1@email.com,staff_password=>,staff_address=>no:1,staff_phone=>123456,staff_gender_id=>1', 1, '::1', '2016-10-20 17:50:36'),
(113, 'Update', 'tbl_user', 'user_id=>,user_email=>s1@email.com,user_name=>staff1,user_address=>no:1,user_phone=>1234562222,user_gender_id=>1', 'staff_id=>7,staff_name=>staff1,staff_email=>s1@email.com,staff_password=>,staff_address=>no:1,staff_phone=>123456,staff_gender_id=>1', 1, '::1', '2016-10-20 17:55:55'),
(114, 'Update', 'tbl_user', 'user_id=>,user_email=>s111@email.com,user_name=>staff111,user_address=>no:1,user_phone=>11111,user_gender_id=>1', 'staff_id=>7,staff_name=>staff1,staff_email=>s1@email.com,staff_password=>,staff_address=>no:1,staff_phone=>123456,staff_gender_id=>1', 1, '::1', '2016-10-20 17:56:32'),
(115, 'Update', 'tbl_user', 'user_id=>,user_email=>s111@email.com,user_name=>staff111,user_address=>no:1,user_phone=>11111,user_gender_id=>1', 'staff_id=>7,staff_name=>staff1,staff_email=>s1@email.com,staff_password=>,staff_address=>no:1,staff_phone=>123456,staff_gender_id=>1', 1, '::1', '2016-10-20 17:56:46'),
(116, 'Update', 'tbl_user', 'user_id=>,user_email=>s111@email.com,user_name=>staff111,user_address=>no:1111,user_phone=>1234561111,user_gender_id=>1', 'staff_id=>7,staff_name=>staff1,staff_email=>s1@email.com,staff_password=>,staff_address=>no:1,staff_phone=>123456,staff_gender_id=>1', 1, '::1', '2016-10-20 17:57:25'),
(117, 'Update', 'tbl_user', 'user_id=>7,user_email=>s1@email.com,user_name=>staff111,user_address=>no:1,user_phone=>123456,user_gender_id=>1', 'staff_id=>7,staff_name=>staff1,staff_email=>s1@email.com,staff_password=>,staff_address=>no:1,staff_phone=>123456,staff_gender_id=>1', 1, '::1', '2016-10-20 18:01:05'),
(118, 'Update', 'tbl_user', 'user_id=>7,user_email=>s1@email.com,user_name=>staff111,user_address=>no:1,user_phone=>123456,user_gender_id=>1', 'staff_id=>7,staff_name=>staff1,staff_email=>s1@email.com,staff_password=>,staff_address=>no:1,staff_phone=>123456,staff_gender_id=>1', 1, '::1', '2016-10-20 18:01:33'),
(119, 'Update', 'tbl_user', 'user_id=>7,user_email=>s1111@email.com,user_name=>staff111,user_address=>no:11111,user_phone=>1234561111,user_gender_id=>1', 'staff_id=>7,staff_name=>staff111,staff_email=>s1@email.com,staff_password=>,staff_address=>no:1,staff_phone=>123456,staff_gender_id=>1', 1, '::1', '2016-10-20 18:01:45'),
(120, 'Update', 'tbl_user', 'user_id=>7,user_password=>456,md5_user_password=>250cf8b51c773f3f8dc8b4be867a9a02', 'user_id=>7,user_password=>202cb962ac59075b964b07152d234b70', 1, '::1', '2016-10-21 10:50:02'),
(121, 'Insert', 'tbl_user', 'user_id=>9,user_name=>staff3,user_email=>s3@gmail.com,md5_user_password=>202cb962ac59075b964b07152d234b70,org_user_password=>123,user_address=>no:3,user_phone=>123456789,user_gender_id=>2,user_type_id=>3', NULL, 1, '::1', '2016-10-21 10:58:45'),
(122, 'Update', 'tbl_user', 'user_id=>9,user_email=>s3@gmail.com,user_name=>staff31,user_address=>no:3,user_phone=>123456789,user_gender_id=>2', 'staff_id=>9,staff_name=>staff3,staff_email=>s3@gmail.com,staff_address=>no:3,staff_phone=>123456789,staff_gender_id=>2', 1, '::1', '2016-10-21 10:59:08'),
(123, 'Reset Password', 'tbl_user', 'user_id=>9,user_password=>456,md5_user_password=>250cf8b51c773f3f8dc8b4be867a9a02', 'staff_id=>9,staff_password=>202cb962ac59075b964b07152d234b70', 1, '::1', '2016-10-21 10:59:35'),
(124, 'Insert', 'tbl_organization', 'org_id=>4,org_name=>Bs,org_address=>dddd,org_description=>eeeewwww', NULL, 1, '::1', '2016-10-21 11:18:54'),
(125, 'Update', 'tbl_card_status', 'card_status_id=>3,card_status_name=>in-active', 'card_status_id=>3,card_status_name=>In-active', 1, '::1', '2016-10-21 11:51:31'),
(126, 'Update', 'tbl_category_type', 'category_type_id=>4,category_type_name=>Dinner1', 'category_type_id=>4,category_type_name=>Dinner', 1, '::1', '2016-10-21 11:54:43'),
(127, 'Update', 'tbl_food_allergy', 'food_allergy_id=>3,food_allergy_name=>mushroom1', 'food_allergy_id=>3,food_allergy_name=>mushroom', 1, '::1', '2016-10-21 11:56:37'),
(128, 'Update', 'tbl_items', 'item_id=>1,item_name=>Breakfast A1,item_description=>Fried egg, Fried rice, Apple Juice,item_price=>10.12', 'item_id=>1,item_name=>Breakfast A,item_desc=>Fried egg, Fried rice, Apple Juice,item_price=>10.12', 1, '::1', '2016-10-21 12:02:03'),
(129, 'Update', 'tbl_meal_status', 'meal_status_id=>3,meal_status_name=>not finish breakfast22', 'meal_status_id=>3,meal_status_name=>not finish breakfast', 1, '::1', '2016-10-21 12:05:52'),
(130, 'Update', 'tbl_organization', 'org_id=>4,org_name=>Bs111,org_address=>dddd,org_description=>eeeewwww', 'org_name=>Bs,org_address=>dddd,org_description=>eeeewwww', 1, '::1', '2016-10-21 12:08:21'),
(131, 'Update', 'tbl_user', 'user_id=>staff_id=>7,staff_name=>staff111,staff_email=>s1111@email.com,staff_address=>no:11111,staff_phone=>1234561111,staff_gender_id=>1,user_email=>s1111@email.com,user_name=>staff111222,user_address=>no:11111,user_phone=>1234561111,user_gender_id=>1', '7', 1, '::1', '2016-10-21 12:10:51'),
(132, 'Update', 'tbl_user', 'user_id=>staff_id=>7,staff_name=>staff111,staff_email=>s1111@email.com,staff_address=>no:11111,staff_phone=>1234561111,staff_gender_id=>1,user_email=>s11112222@email.com,user_name=>staff111222,user_address=>no:11111,user_phone=>1234561111,user_gender_id=>', '7', 1, '::1', '2016-10-21 12:15:31'),
(133, 'Update', 'tbl_user', 'user_id=>staff_id=>7,staff_name=>staff111,staff_email=>s1111@email.com,staff_address=>no:11111,staff_phone=>1234561111,staff_gender_id=>1,user_email=>s11112222@email.com,user_name=>staff111222,user_address=>no:11111,user_phone=>1234561111,user_gender_id=>', '7', 1, '::1', '2016-10-21 12:17:14'),
(134, 'Update', 'tbl_user', 'user_id=>staff_id=>8,staff_name=>staff2,staff_email=>s2@email.com,staff_address=>dddd,staff_phone=>deee2222,staff_gender_id=>2,user_email=>s2@email.com,user_name=>staff2,user_address=>dddd,user_phone=>deee2222,user_gender_id=>2', '8', 1, '::1', '2016-10-21 12:17:28'),
(135, 'Update', 'tbl_food_allergy', 'food_allergy_id=>3,food_allergy_name=>mushroom1', 'food_allergy_id=>3,food_allergy_name=>mushroom1', 1, '::1', '2016-10-21 12:17:42'),
(136, 'Update', 'tbl_user', 'user_id=>8,user_email=>s2@email.com,user_name=>staff212,user_address=>dddd121,user_phone=>deee2222,user_gender_id=>2', 'staff_id=>8,staff_name=>staff2,staff_email=>s2@email.com,staff_address=>dddd,staff_phone=>deee2222,staff_gender_id=>2', 1, '::1', '2016-10-21 12:30:19'),
(137, 'Update', 'tbl_user', 'user_id=>8,user_email=>s2@email.com,user_name=>staff221,user_address=>dddd,user_phone=>deee2222,user_gender_id=>1', 'staff_id=>8,staff_name=>staff2,staff_email=>s2@email.com,staff_address=>dddd,staff_phone=>deee2222,staff_gender_id=>2', 1, '::1', '2016-10-21 12:31:07'),
(138, 'Update', 'tbl_user', 'user_id=>9,user_email=>s333@gmail.com,user_name=>staff333,user_address=>no:333,user_phone=>123456789,user_gender_id=>1', 'staff_id=>9,staff_name=>staff31,staff_email=>s3@gmail.com,staff_address=>no:3,staff_phone=>123456789,staff_gender_id=>2', 1, '::1', '2016-10-21 12:31:20'),
(139, 'Insert', 'tbl_user_type', 'user_type_id=>4,user_type_name=>eeee,user_type_description=>edww', NULL, 1, '::1', '2016-10-21 12:34:55'),
(140, 'Update', 'tbl_user_type', 'user_type_id=>4,user_type_name=>eeee234,user_type_description=>edww', 'user_type_id=>4,user_type_name=>eeee,user_type_desc=>edww', 1, '::1', '2016-10-21 12:35:01'),
(141, 'Reset Password', 'tbl_user', 'user_id=>7,user_password=>111,md5_user_password=>698d51a19d8a121ce581499d7b701668', 'staff_id=>7,staff_password=>250cf8b51c773f3f8dc8b4be867a9a02', 1, '::1', '2016-10-21 12:35:12'),
(142, 'Reset Password', 'tbl_user', 'user_id=>8,user_password=>123,md5_user_password=>202cb962ac59075b964b07152d234b70', 'staff_id=>8,staff_password=>202cb962ac59075b964b07152d234b70', 1, '::1', '2016-10-21 12:36:36'),
(143, 'Insert', 'tbl_user', 'user_id=>10,user_name=>Parent1,user_email=>parent1@gmail.com,md5_user_password=>202cb962ac59075b964b07152d234b70,org_user_password=>123,user_address=>no:123,user_phone=>14589,user_gender_id=>1,user_type_id=>2', NULL, 1, '::1', '2016-10-21 16:54:35'),
(144, 'Insert', 'tbl_organizer', 'parent/organizer_id=>1,user_id=>10,organizer_description=>I am parent', NULL, 1, '::1', '2016-10-21 16:54:35'),
(145, 'Insert', 'tbl_user', 'user_id=>11,user_name=>Parent22,user_email=>parent22@gmail.com,md5_user_password=>9996535e07258a7bbfd8b132435c5962,org_user_password=>1235,user_address=>NO:456,user_phone=>789521,user_gender_id=>2,user_type_id=>2', NULL, 1, '::1', '2016-10-21 16:57:08'),
(146, 'Insert', 'tbl_organizer', 'parent/organizer_id=>2,user_id=>11,organizer_description=>I am parent', NULL, 1, '::1', '2016-10-21 16:57:08'),
(147, 'Insert', 'tbl_user', 'user_id=>12,user_name=>eedddd,user_email=>tddddee@gmail.com,md5_user_password=>81dc9bdb52d04dc20036dbd8313ed055,org_user_password=>1234,user_address=>ddd,user_phone=>111111,user_gender_id=>1,user_type_id=>2', NULL, 1, '::1', '2016-10-21 16:57:57'),
(148, 'Insert', 'tbl_organizer', 'parent/organizer_id=>3,user_id=>12,organizer_description=>dfdfeere', NULL, 1, '::1', '2016-10-21 16:57:57'),
(149, 'Insert', 'tbl_user', 'user_id=>13,user_name=>dddd,user_email=>dddd,md5_user_password=>b59c67bf196a4758191e42f76670ceba,org_user_password=>1111,user_address=>ssss,user_phone=>ssss,user_gender_id=>1,user_type_id=>2', NULL, 1, '::1', '2016-10-21 17:27:25'),
(150, 'Insert', 'tbl_organizer', 'parent/organizer_id=>4,user_id=>13,organizer_description=>fdfreee', NULL, 1, '::1', '2016-10-21 17:27:26'),
(151, 'Insert', 'tbl_meal_status', 'meal_status_id=>6,meal_status_name=>eeee', NULL, 1, '::1', '2016-10-21 19:12:04'),
(152, 'Update', 'tbl_meal_status', 'meal_status_id=>5,meal_status_name=>not finish luncheee', 'meal_status_id=>5,meal_status_name=>not finish lunch', 1, '::1', '2016-10-21 19:12:09'),
(153, 'Delete', 'tbl_meal_status', 'meal_status_id=>5', NULL, 1, '::1', '2016-10-21 19:12:13'),
(154, 'Update', 'tbl_user', 'user_id=>13,is_active=>0', 'user_id=>13,is_active=>1', 1, '::1', '2016-10-21 19:14:42');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_food_allergy`
--

CREATE TABLE IF NOT EXISTS `tbl_food_allergy` (
  `food_allergy_id` int(11) NOT NULL AUTO_INCREMENT,
  `food_allergy_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `food_allergy_created_datetime` datetime DEFAULT NULL,
  `food_allergy_modified_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`food_allergy_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_food_allergy`
--

INSERT INTO `tbl_food_allergy` (`food_allergy_id`, `food_allergy_name`, `food_allergy_created_datetime`, `food_allergy_modified_datetime`) VALUES
(2, 'red beans', '2016-10-17 10:42:57', '2016-10-17 11:28:37'),
(3, 'mushroom1', '2016-10-17 11:47:25', '2016-10-21 12:17:42');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gender`
--

CREATE TABLE IF NOT EXISTS `tbl_gender` (
  `gender_id` int(11) NOT NULL AUTO_INCREMENT,
  `gender_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gender_prefix` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`gender_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_gender`
--

INSERT INTO `tbl_gender` (`gender_id`, `gender_name`, `gender_prefix`) VALUES
(1, 'Male', 'MR.'),
(2, 'Female', 'MS.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_items`
--

CREATE TABLE IF NOT EXISTS `tbl_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_description` text COLLATE utf8_unicode_ci NOT NULL,
  `item_price` double NOT NULL,
  `item_created_datetime` datetime DEFAULT NULL,
  `item_modified_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_items`
--

INSERT INTO `tbl_items` (`item_id`, `item_name`, `item_description`, `item_price`, `item_created_datetime`, `item_modified_datetime`) VALUES
(1, 'Breakfast A1', 'Fried egg, Fried rice, Apple Juice', 10.12, '2016-10-20 12:31:34', '2016-10-21 12:02:03'),
(3, 'Lunch A', 'Chicken rice', 5.5, '2016-10-20 12:33:16', '2016-10-20 12:33:16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_language`
--

CREATE TABLE IF NOT EXISTS `tbl_language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_language`
--

INSERT INTO `tbl_language` (`language_id`, `language_name`) VALUES
(1, 'english'),
(2, 'chinese');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_localization`
--

CREATE TABLE IF NOT EXISTS `tbl_localization` (
  `localization_id` int(11) NOT NULL AUTO_INCREMENT,
  `localization_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `default_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`localization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=191 ;

--
-- Dumping data for table `tbl_localization`
--

INSERT INTO `tbl_localization` (`localization_id`, `localization_name`, `parent_id`, `default_text`) VALUES
(1, 'home', 0, 'Home'),
(2, 'user_type', 1, 'User Type'),
(3, 'user', 1, 'User'),
(4, 'organization', 1, 'School'),
(5, 'food_allergy', 1, 'Food Allergy'),
(6, 'card_status', 1, 'Card Status'),
(7, 'category_type', 1, 'Category Type'),
(8, 'meal_status', 1, 'Meal Status'),
(9, 'items', 1, 'Items'),
(10, 'participant', 1, 'Student'),
(11, 'pre_order', 1, 'PreOrder'),
(12, 'user_type_name', 2, 'User Type Name'),
(13, 'user_type_list', 2, 'User Type List'),
(14, 'user_type_desc', 2, 'User Type Description'),
(15, 'add_new_user_type_btn', 2, 'Add New User Type'),
(16, 'confirm_del_user_type_msg', 2, 'Are you sure you want to delete this user type name - '),
(17, 'action', 1, 'Action'),
(18, 'search_btn', 1, 'Search'),
(19, 'show_all_btn', 1, 'Show All'),
(20, 'success_save_user_type_msg', 2, 'Saved user type successfully!.'),
(21, 'duplicate_save_user_type_msg', 2, 'Duplicate user type name!.'),
(22, 'check_req_field_user_type_name', 2, 'Please enter user type name.'),
(23, 'user_list', 3, 'User List'),
(24, 'name', 3, 'Name'),
(25, 'email', 3, 'Email'),
(26, 'user_type', 3, 'User Type'),
(27, 'address', 3, 'Address'),
(28, 'phone', 3, 'Phone'),
(29, 'user_status', 3, 'User Status'),
(30, 'confirm_change_user_status_1', 3, 'Are you sure you want to'),
(31, 'confirm_change_user_status_2', 3, ' this user type name- '),
(32, 'org_list', 4, 'School List'),
(33, 'org_name', 4, 'School Name'),
(34, 'org_address', 4, 'School Address'),
(35, 'org_desc', 4, 'School Description'),
(36, 'add_new_org_btn', 4, 'Add New School'),
(37, 'confirm_del_org_msg', 4, 'Are you sure you want to delete this organization name- '),
(38, 'success_org_msg', 4, 'Saved school successfully!.'),
(39, 'duplicate_org_msg', 4, 'Duplicate school name!.'),
(40, 'check_req_field_org_name', 4, 'Please enter school name.'),
(41, 'check_req_field_org_address', 4, 'Please enter school address.'),
(42, 'food_allergy_list', 5, 'Food Allergy List'),
(43, 'food_allergy_name', 5, 'Food Allergy Name'),
(44, 'add_new_food_allergy_btn', 5, 'Add New Food Allergy'),
(45, 'confirm_del_food_allergy_msg', 5, 'Are you sure you want to delete this food allergy name-'),
(46, 'check_req_field_food_allergy_name', 5, 'please enter food allergy name.'),
(47, 'save_food_allergy_msg', 5, 'Saved food allergy successfully!.'),
(48, 'duplicate_food_allergy_msg', 5, 'Duplicate food allergy name!.'),
(49, 'update_user_type_msg', 2, 'Updated user type successfully!.'),
(50, 'update_food_allergy_msg', 5, 'Updated food allergy successfully!.'),
(51, 'update_org_msg', 4, 'Updated school successfully!'),
(52, 'card_status_list', 6, 'Card Status List'),
(53, 'card_status_name', 6, 'Card Status Name'),
(54, 'add_new_card_status_btn', 6, 'Add New Card Status'),
(55, 'confirm_del_card_status_msg', 6, 'Are you sure you want to delete this card status name-'),
(56, 'check_req_field_card_status_name', 6, 'please enter card status name.'),
(57, 'save_card_status_msg', 6, 'Saved card status successfully!.'),
(58, 'duplicate_card_status_msg', 6, 'Duplicate card status name!.'),
(59, 'update_card_status_msg', 6, 'Updated card status successfully!.'),
(60, 'category_type_list', 7, 'Category Type List'),
(61, 'category_type_name', 7, 'Category Type Name'),
(62, 'add_new_category_type_btn', 7, 'Add New Category Type'),
(63, 'confirm_del_category_type_msg', 7, 'Are you sure you want to delete this category type name-'),
(64, 'check_req_field_category_type_name', 7, 'please enter category type name.'),
(65, 'save_category_type_msg', 7, 'Saved category type successfully!.'),
(66, 'duplicate_category_type_msg', 7, 'Duplicate category type name!.'),
(67, 'update_category_type_msg', 7, 'Updated category type successfully!.'),
(68, 'del_user_type_msg', 2, 'Deleted user type successfully!'),
(69, 'stillinuse_user_type_msg', 2, 'This user type is still in use!.'),
(70, 'del_org_msg', 4, 'Deleted school successfully!'),
(71, 'stillinuse_org_msg', 4, 'This school is still in use!.'),
(72, 'del_food_allergy_msg', 5, 'Deleted food allergy successfully!'),
(73, 'stillinuse_food_allergy_msg', 5, 'This food allergy is still in use!.'),
(74, 'del_card_status_msg', 6, 'Deleted card status successfully!'),
(75, 'stillinuse_card_status_msg', 6, 'This card status is still in use!.'),
(76, 'del_category_type_msg', 7, 'Deleted category type successfully!'),
(77, 'stillinuse_category_type_msg', 7, 'This category type is still in use!.'),
(78, 'loading_msg_datatable', 1, 'Loading data from server'),
(79, 'active_success_msg', 3, 'User is changed successfully to active status!'),
(80, 'inactive_success_msg', 3, 'User is changed successfully to in-active status!'),
(81, 'add_new_user_type_title', 2, 'Add New User Type'),
(82, 'edt_new_user_type_title', 2, 'Edit User Type'),
(83, 'add_new_org_title', 4, 'Add New School'),
(84, 'edt_org_title', 4, 'Edit School'),
(85, 'add_new_food_allergy_title', 5, 'Add New Food Allergy'),
(86, 'edt_food_allergy_title', 5, 'Edit Food Allergy'),
(87, 'save_btn', 1, 'Save'),
(88, 'cancel_btn', 1, 'Cancel'),
(89, 'edit_btn', 1, 'Edit'),
(90, 'add_new_card_status_title', 6, 'Add New Card Status'),
(91, 'edt_card_status_title', 6, 'Edit Card Status'),
(92, 'add_new_category_type_title', 7, 'Add New Category Type'),
(93, 'edt_category_type_title', 7, 'Edit Category Type'),
(94, 'meal_status_list', 8, 'Meal Status List'),
(95, 'meal_status_name', 8, 'Meal Status Name'),
(96, 'add_new_meal_status_btn', 8, 'Add New Meal Status'),
(97, 'add_new_meal_status_title', 8, 'Add New Meal Status'),
(98, 'edt_meal_status_title', 8, 'Edit Meal Status'),
(99, 'confirm_del_meal_status_msg', 8, 'Are you sure you want to delete this meal status name-'),
(100, 'check_req_field_meal_status_name', 8, 'please enter meal status name.'),
(101, 'save_meal_status_msg', 8, 'Saved meal status successfully!.'),
(102, 'duplicate_meal_status_msg', 8, 'Duplicate meal status name!.'),
(103, 'update_meal_status_msg', 8, 'Updated meal status successfully!.'),
(104, 'del_meal_status_msg', 8, 'Deleted meal status successfully!'),
(105, 'stillinuse_meal_status_msg', 8, 'This meal status is still in use!.'),
(106, 'items_list', 9, 'Items List'),
(107, 'item_name', 9, 'Item Name'),
(108, 'item_description', 9, 'Item Description'),
(109, 'item_price', 9, 'Item Price'),
(110, 'add_new_item_btn', 9, 'Add New Item'),
(111, 'add_new_item_title', 9, 'Add New Item'),
(112, 'edt_item_title', 9, 'Edit Item'),
(113, 'confirm_del_item_msg', 9, 'Are you sure you want to delete this item name-'),
(114, 'check_req_field_item_name', 9, 'please enter item name.'),
(115, 'check_req_field_item_desc', 9, 'please enter item description.'),
(116, 'check_req_field_item_price', 9, 'please enter item price.'),
(117, 'save_item_msg', 9, 'Saved item successfully!.'),
(118, 'duplicate_item_msg', 9, 'Duplicate item name!.'),
(119, 'update_item_msg', 9, 'Updated item successfully!.'),
(120, 'del_item_msg', 9, 'Deleted item successfully!'),
(121, 'stillinuse_item_msg', 9, 'This item is still in use!.'),
(122, 'staff', 1, 'Staff'),
(123, 'staff_list', 122, 'Staff List'),
(124, 'staff_email', 122, 'Staff Email'),
(125, 'staff_password', 122, 'Staff Password'),
(126, 'staff_user_type', 122, 'Staff User Type'),
(127, 'staff_name', 122, 'Staff Name'),
(128, 'staff_address', 122, 'Staff Address'),
(129, 'staff_phone', 122, 'Staff Phone'),
(130, 'staff_gender', 122, 'Staff Gender'),
(131, 'staff_status', 122, 'Staff Status'),
(132, 'add_new_staff_btn', 122, 'Add New Staff'),
(133, 'add_new_staff_title', 122, 'Add New Staff'),
(134, 'edt_staff_title', 122, 'Edit Staff'),
(135, 'check_req_field_staff_email', 122, 'please enter staff email.'),
(136, 'check_req_field_staff_name', 122, 'please enter staff name.'),
(137, 'check_req_field_staff_password', 122, 'please enter staff password.'),
(138, 'save_staff_msg', 122, 'Saved staff successfully!.'),
(139, 'duplicate_staff_msg', 122, 'Duplicate staff name!.'),
(140, 'update_staff_msg', 122, 'Updated staff successfully!.'),
(141, 'del_staff_msg', 122, 'Deleted staff successfully!'),
(142, 'stillinuse_staff_msg', 122, 'This staff is still in use!.'),
(143, 'confirm_change_staff_status_1', 122, 'Are you sure you want to'),
(144, 'confirm_change_staff_status_2', 122, ' this staff email - '),
(145, 'staff_active_success_msg', 122, 'Staff is changed successfully to active status!'),
(146, 'staff_inactive_success_msg', 122, 'Staff is changed successfully to in-active status!'),
(147, 'confirm_change_staff_status_resetpw', 122, 'Are you sure to reset password for this email -'),
(148, 'reset_pw_title', 1, 'Reset Password'),
(149, 'new_pw', 1, 'New Password'),
(150, 'save_pw_btn', 1, 'Save Password'),
(151, 'save_pw_msg', 1, 'Saved Password Successfully'),
(152, 'change_pw_msg', 1, 'Changed Password Successfully'),
(153, 'save_fail_msg', 1, 'Failed Saving!'),
(154, 'update_fail_msg', 1, 'Failed Updating!'),
(155, 'delete_fail_msg', 1, 'Failed Deleting!'),
(156, 'check_req_field_new_pw', 1, 'Please enter new password.'),
(157, 'user_profile', 1, 'User Profile'),
(158, 'topup', 1, 'Top-Up'),
(159, 'redumption', 1, 'Redemption'),
(160, 'reports', 1, 'Reports'),
(161, 'card_report_title', 1, 'Card Report'),
(162, 'pre_order_report_title', 1, 'Pre-Order Report'),
(163, 'topup_report_title', 1, 'Top-Up Report'),
(164, 'redemption_report_title', 1, 'Redemption Report'),
(165, 'trans_balance_report_title', 1, 'Transaction Balance Report'),
(166, 'forgot_pw', 1, 'Forgot Password'),
(167, 'change_pw', 1, 'Change Password'),
(168, 'logout', 1, 'Logout'),
(169, 'login', 1, 'Login'),
(170, 'register', 1, 'Register'),
(171, 'login_email', 169, 'Login Email'),
(172, 'login_pw', 169, 'Login Password'),
(173, 'login_btn', 169, 'Login'),
(174, 'check_req_field_login_email', 169, 'Please enter login email.'),
(175, 'check_req_field_login_pw', 169, 'Please enter login password.'),
(176, 'register_email', 170, 'Register Email'),
(177, 'register_pw', 170, 'Password'),
(178, 'register_btn', 170, 'Register'),
(179, 'check_req_field_register_email', 170, 'Please enter register email.'),
(180, 'check_req_field_register_pw', 170, 'Please enter register password.'),
(181, 'register_name', 170, 'Register Name'),
(182, 'check_req_field_register_name', 170, 'Please enter register name.'),
(183, 'gender', 1, 'Gender'),
(184, 'description', 170, 'Description'),
(185, 'reg_success_msg', 170, 'Registered Successfully'),
(186, 'reg_fail_msg', 170, 'Registered Fail.'),
(187, 'duplicate_reg_msg', 170, 'Duplicate register email.Please try with another email.'),
(188, 'login_success_msg', 169, 'Login Successfully.'),
(189, 'login_fail_msg', 169, 'Login Fail.'),
(190, 'check_valid_email_adddress', 1, 'Please enter valid email.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_localization_detail`
--

CREATE TABLE IF NOT EXISTS `tbl_localization_detail` (
  `localizationdetail_id` int(11) NOT NULL AUTO_INCREMENT,
  `localization_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `detail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`localizationdetail_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_meal_status`
--

CREATE TABLE IF NOT EXISTS `tbl_meal_status` (
  `meal_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `meal_status_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meal_status_created_datetime` datetime DEFAULT NULL,
  `meal_status_modified_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`meal_status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tbl_meal_status`
--

INSERT INTO `tbl_meal_status` (`meal_status_id`, `meal_status_name`, `meal_status_created_datetime`, `meal_status_modified_datetime`) VALUES
(1, 'finish breakfase1', '2016-10-19 18:19:41', '2016-10-20 11:44:01'),
(2, 'finish lunch', '2016-10-19 18:20:28', '2016-10-19 18:20:28'),
(3, 'not finish breakfast22', '2016-10-19 18:21:24', '2016-10-21 12:05:52'),
(6, 'eeee', '2016-10-21 19:12:04', '2016-10-21 19:12:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_organization`
--

CREATE TABLE IF NOT EXISTS `tbl_organization` (
  `org_id` int(11) NOT NULL AUTO_INCREMENT,
  `org_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `org_address` text COLLATE utf8_unicode_ci NOT NULL,
  `org_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `org_created_datetime` datetime DEFAULT NULL,
  `org_modified_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`org_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_organization`
--

INSERT INTO `tbl_organization` (`org_id`, `org_name`, `org_address`, `org_description`, `org_created_datetime`, `org_modified_datetime`) VALUES
(1, 'school-one', 'no:1, street1', 'primary school', '2016-10-14 16:09:40', '2016-10-14 16:54:14'),
(3, 'S school', 'no:1223', 'Secondary school', '2016-10-14 17:20:47', '2016-10-14 17:20:47'),
(4, 'Bs111', 'dddd', 'eeeewwww', '2016-10-21 11:18:54', '2016-10-21 12:08:21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_organizer`
--

CREATE TABLE IF NOT EXISTS `tbl_organizer` (
  `organizer_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'same meaning of parent_id',
  `user_id` int(11) NOT NULL,
  `organizer_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'same meaning of parent_description',
  PRIMARY KEY (`organizer_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_organizer`
--

INSERT INTO `tbl_organizer` (`organizer_id`, `user_id`, `organizer_description`) VALUES
(1, 10, 'I am parent'),
(2, 11, 'I am parent'),
(3, 12, 'dfdfeere'),
(4, 13, 'fdfreee');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_participant`
--

CREATE TABLE IF NOT EXISTS `tbl_participant` (
  `participant_id` int(11) NOT NULL AUTO_INCREMENT,
  `participant_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `org_id` int(11) NOT NULL,
  `participant_allergy_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1= allergy , 0=not allergy',
  `others_allergy_food_description` text COLLATE utf8_unicode_ci NOT NULL,
  `participant_description` text COLLATE utf8_unicode_ci NOT NULL,
  `participant_gender_id` int(11) NOT NULL,
  `organizer_id` int(11) NOT NULL COMMENT 'same meaning of parent_id',
  `participant_created_datetime` datetime DEFAULT NULL,
  `participant_modified_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`participant_id`),
  KEY `school_id` (`org_id`),
  KEY `student_gender_id` (`participant_gender_id`),
  KEY `parent_id` (`organizer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_participant_canteen_orders`
--

CREATE TABLE IF NOT EXISTS `tbl_participant_canteen_orders` (
  `participant_canteen_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) DEFAULT NULL,
  `participant_id` int(11) NOT NULL,
  `participant_canteen_order_datetime` datetime DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `category_type_id` int(11) NOT NULL,
  PRIMARY KEY (`participant_canteen_order_id`),
  KEY `card_id` (`card_id`),
  KEY `item_id` (`item_id`),
  KEY `category_type_id` (`category_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_participant_food_allergy`
--

CREATE TABLE IF NOT EXISTS `tbl_participant_food_allergy` (
  `participant_food_allergy_id` int(11) NOT NULL AUTO_INCREMENT,
  `participant_id` int(11) NOT NULL,
  `food_allergy_id` int(11) NOT NULL,
  PRIMARY KEY (`participant_food_allergy_id`),
  KEY `student_id` (`participant_id`),
  KEY `food_allergy_id` (`food_allergy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment_detail`
--

CREATE TABLE IF NOT EXISTS `tbl_payment_detail` (
  `payment_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `topup_id` int(11) NOT NULL,
  `account_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`payment_detail_id`),
  KEY `topup_id` (`topup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pre_orders`
--

CREATE TABLE IF NOT EXISTS `tbl_pre_orders` (
  `pre_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) DEFAULT NULL,
  `participant_id` int(11) NOT NULL,
  `preorder_date` datetime DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `category_type_id` int(11) NOT NULL,
  `meal_status_id` int(11) NOT NULL,
  `created_datetime` datetime DEFAULT NULL,
  `modified_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`pre_order_id`),
  KEY `card_id` (`card_id`),
  KEY `item_id` (`item_id`),
  KEY `category_type_id` (`category_type_id`),
  KEY `meal_status_id` (`meal_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_redemption`
--

CREATE TABLE IF NOT EXISTS `tbl_redemption` (
  `redemption_id` int(11) NOT NULL AUTO_INCREMENT,
  `redemption_amt` double NOT NULL,
  `user_id` int(11) NOT NULL,
  `pre_order_id` int(11) NOT NULL,
  `participant_canteen_order_id` int(11) NOT NULL,
  PRIMARY KEY (`redemption_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`pre_order_id`),
  KEY `stu_canteen_order_id` (`participant_canteen_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_setting`
--

CREATE TABLE IF NOT EXISTS `tbl_setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `setting_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_setting`
--

INSERT INTO `tbl_setting` (`setting_id`, `setting_name`, `setting_value`) VALUES
(1, 'project_type', 'school'),
(2, 'project_name', 'School Toup Management System');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_topup`
--

CREATE TABLE IF NOT EXISTS `tbl_topup` (
  `topup_id` int(11) NOT NULL AUTO_INCREMENT,
  `topup_amt` double NOT NULL,
  `trans_id` int(11) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_detail_id` int(11) NOT NULL,
  PRIMARY KEY (`topup_id`),
  KEY `trans_id` (`trans_id`),
  KEY `payment_detail_id` (`payment_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transaction`
--

CREATE TABLE IF NOT EXISTS `tbl_transaction` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'topup , redemption',
  `card_id` int(11) DEFAULT NULL,
  `topup_id` int(11) NOT NULL,
  `redempation_id` int(11) NOT NULL,
  `transaction_amt` double NOT NULL,
  `participant_id` int(11) NOT NULL,
  `transaction_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`trans_id`),
  KEY `card_id` (`card_id`),
  KEY `topup_id` (`topup_id`),
  KEY `redempation_id` (`redempation_id`),
  KEY `student_id` (`participant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_type_id` int(11) DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_address` text COLLATE utf8_unicode_ci,
  `user_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_gender_id` int(11) NOT NULL DEFAULT '1' COMMENT '1=Male,2=Female',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1= active user , 0 = inactive user',
  `user_created_datetime` datetime DEFAULT NULL,
  `user_modified_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_type_id` (`user_type_id`),
  KEY `user_gender_id` (`user_gender_id`),
  KEY `user_gender_id_2` (`user_gender_id`),
  KEY `user_gender_id_3` (`user_gender_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `user_email`, `user_password`, `user_type_id`, `user_name`, `user_address`, `user_phone`, `user_gender_id`, `is_active`, `user_created_datetime`, `user_modified_datetime`) VALUES
(5, 'ddd@gmail.com', '123', 1, 'ddd', 'no:1', '123', 1, 0, NULL, NULL),
(6, 'rr@butterfly.sg', '123', 1, 'RR', 'rno2', '456', 2, 1, NULL, NULL),
(7, 's1111@email.com', '698d51a19d8a121ce581499d7b701668', 3, 'staff111', 'no:11111', '1234561111', 1, 1, '2016-10-20 16:46:59', '2016-10-21 12:35:12'),
(8, 's2@email.com', '202cb962ac59075b964b07152d234b70', 3, 'staff221', 'dddd', 'deee2222', 1, 0, '2016-10-20 17:10:07', '2016-10-21 12:36:36'),
(9, 's333@gmail.com', '250cf8b51c773f3f8dc8b4be867a9a02', 3, 'staff333', 'no:333', '123456789', 1, 1, '2016-10-21 10:58:45', '2016-10-21 12:31:20'),
(10, 'parent1@gmail.com', '202cb962ac59075b964b07152d234b70', 2, 'Parent1', 'no:123', '14589', 1, 1, '2016-10-21 16:54:35', '2016-10-21 16:54:35'),
(11, 'parent22@gmail.com', '9996535e07258a7bbfd8b132435c5962', 2, 'Parent22', 'NO:456', '789521', 2, 1, '2016-10-21 16:57:08', '2016-10-21 16:57:08'),
(12, 'tddddee@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 2, 'eedddd', 'ddd', '111111', 1, 1, '2016-10-21 16:57:57', '2016-10-21 16:57:57'),
(13, 'dddd', 'b59c67bf196a4758191e42f76670ceba', 2, 'dddd', 'ssss', 'ssss', 1, 0, '2016-10-21 17:27:25', '2016-10-21 17:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_type`
--

CREATE TABLE IF NOT EXISTS `tbl_user_type` (
  `user_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_type_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_type_created_datetime` datetime DEFAULT NULL,
  `user_type_modified_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`user_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_user_type`
--

INSERT INTO `tbl_user_type` (`user_type_id`, `user_type_name`, `user_type_description`, `user_type_created_datetime`, `user_type_modified_datetime`) VALUES
(1, 'Admin', 'Administratior', '2016-10-13 00:00:00', '2016-10-13 18:45:10'),
(2, 'Parent', 'For student parents', '2016-10-13 14:34:27', '2016-10-14 10:47:55'),
(3, 'Staff', 'For casher staff in canteen', '2016-10-13 14:40:59', '2016-10-13 18:44:05'),
(4, 'eeee234', 'edww', '2016-10-21 12:34:54', '2016-10-21 12:35:01');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_card`
--
ALTER TABLE `tbl_card`
  ADD CONSTRAINT `tbl_card_ibfk_1` FOREIGN KEY (`participant_id`) REFERENCES `tbl_participant` (`participant_id`),
  ADD CONSTRAINT `tbl_card_ibfk_2` FOREIGN KEY (`card_status_id`) REFERENCES `tbl_card_status` (`card_status_id`);

--
-- Constraints for table `tbl_localization_detail`
--
ALTER TABLE `tbl_localization_detail`
  ADD CONSTRAINT `tbl_localization_detail_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `tbl_language` (`language_id`);

--
-- Constraints for table `tbl_organizer`
--
ALTER TABLE `tbl_organizer`
  ADD CONSTRAINT `tbl_organizer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`);

--
-- Constraints for table `tbl_participant`
--
ALTER TABLE `tbl_participant`
  ADD CONSTRAINT `tbl_participant_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `tbl_organization` (`org_id`),
  ADD CONSTRAINT `tbl_participant_ibfk_2` FOREIGN KEY (`participant_gender_id`) REFERENCES `tbl_gender` (`gender_id`),
  ADD CONSTRAINT `tbl_participant_ibfk_3` FOREIGN KEY (`organizer_id`) REFERENCES `tbl_organizer` (`organizer_id`);

--
-- Constraints for table `tbl_participant_canteen_orders`
--
ALTER TABLE `tbl_participant_canteen_orders`
  ADD CONSTRAINT `tbl_participant_canteen_orders_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `tbl_card` (`card_id`),
  ADD CONSTRAINT `tbl_participant_canteen_orders_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `tbl_items` (`item_id`),
  ADD CONSTRAINT `tbl_participant_canteen_orders_ibfk_3` FOREIGN KEY (`category_type_id`) REFERENCES `tbl_category_type` (`category_type_id`);

--
-- Constraints for table `tbl_participant_food_allergy`
--
ALTER TABLE `tbl_participant_food_allergy`
  ADD CONSTRAINT `tbl_participant_food_allergy_ibfk_1` FOREIGN KEY (`participant_id`) REFERENCES `tbl_participant` (`participant_id`),
  ADD CONSTRAINT `tbl_participant_food_allergy_ibfk_2` FOREIGN KEY (`food_allergy_id`) REFERENCES `tbl_food_allergy` (`food_allergy_id`);

--
-- Constraints for table `tbl_payment_detail`
--
ALTER TABLE `tbl_payment_detail`
  ADD CONSTRAINT `tbl_payment_detail_ibfk_1` FOREIGN KEY (`topup_id`) REFERENCES `tbl_topup` (`topup_id`);

--
-- Constraints for table `tbl_pre_orders`
--
ALTER TABLE `tbl_pre_orders`
  ADD CONSTRAINT `tbl_pre_orders_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `tbl_card` (`card_id`),
  ADD CONSTRAINT `tbl_pre_orders_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `tbl_items` (`item_id`),
  ADD CONSTRAINT `tbl_pre_orders_ibfk_3` FOREIGN KEY (`category_type_id`) REFERENCES `tbl_category_type` (`category_type_id`),
  ADD CONSTRAINT `tbl_pre_orders_ibfk_4` FOREIGN KEY (`meal_status_id`) REFERENCES `tbl_meal_status` (`meal_status_id`);

--
-- Constraints for table `tbl_redemption`
--
ALTER TABLE `tbl_redemption`
  ADD CONSTRAINT `tbl_redemption_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`),
  ADD CONSTRAINT `tbl_redemption_ibfk_2` FOREIGN KEY (`pre_order_id`) REFERENCES `tbl_pre_orders` (`pre_order_id`),
  ADD CONSTRAINT `tbl_redemption_ibfk_3` FOREIGN KEY (`participant_canteen_order_id`) REFERENCES `tbl_participant_canteen_orders` (`participant_canteen_order_id`);

--
-- Constraints for table `tbl_topup`
--
ALTER TABLE `tbl_topup`
  ADD CONSTRAINT `tbl_topup_ibfk_1` FOREIGN KEY (`trans_id`) REFERENCES `tbl_transaction` (`trans_id`),
  ADD CONSTRAINT `tbl_topup_ibfk_2` FOREIGN KEY (`payment_detail_id`) REFERENCES `tbl_payment_detail` (`payment_detail_id`);

--
-- Constraints for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  ADD CONSTRAINT `tbl_transaction_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `tbl_card` (`card_id`),
  ADD CONSTRAINT `tbl_transaction_ibfk_2` FOREIGN KEY (`topup_id`) REFERENCES `tbl_topup` (`topup_id`),
  ADD CONSTRAINT `tbl_transaction_ibfk_3` FOREIGN KEY (`redempation_id`) REFERENCES `tbl_redemption` (`redemption_id`),
  ADD CONSTRAINT `tbl_transaction_ibfk_4` FOREIGN KEY (`participant_id`) REFERENCES `tbl_participant` (`participant_id`);

--
-- Constraints for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD CONSTRAINT `tbl_user_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `tbl_user_type` (`user_type_id`),
  ADD CONSTRAINT `tbl_user_ibfk_2` FOREIGN KEY (`user_gender_id`) REFERENCES `tbl_gender` (`gender_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
