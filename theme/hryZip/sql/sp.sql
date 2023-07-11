-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 192.168.1.254
-- Generation Time: May 12, 2023 at 09:08 AM
-- Server version: 5.6.19
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rsp`
--

-- --------------------------------------------------------

--
-- Table structure for table `case_daily_expenses`
--

CREATE TABLE `case_daily_expenses` (
  `case_daily_expense_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `expense_date` date NOT NULL,
  `client_code_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `photocopy` varchar(20) DEFAULT '0',
  `courier_domestic` varchar(20) DEFAULT '0',
  `courier_international` varchar(20) DEFAULT '0',
  `stay_place` int(11) NOT NULL,
  `stayWithAss` int(11) NOT NULL,
  `hotelNarration` text NOT NULL,
  `hotelCalculat_bas` text NOT NULL,
  `hotel_stay` varchar(20) DEFAULT '0',
  `conveyance` varchar(20) DEFAULT '0',
  `airStay` int(11) NOT NULL,
  `airAss` int(11) NOT NULL,
  `airNarration` text NOT NULL,
  `airCalculat_bas` text NOT NULL,
  `air_ticket` varchar(20) DEFAULT '0',
  `bill_path` varchar(100) DEFAULT '',
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `invoice_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `oth_expense` varchar(45) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `case_daily_expenses`
--

INSERT INTO `case_daily_expenses` (`case_daily_expense_id`, `file_id`, `expense_date`, `client_code_id`, `case_id`, `photocopy`, `courier_domestic`, `courier_international`, `stay_place`, `stayWithAss`, `hotelNarration`, `hotelCalculat_bas`, `hotel_stay`, `conveyance`, `airStay`, `airAss`, `airNarration`, `airCalculat_bas`, `air_ticket`, `bill_path`, `is_active`, `invoice_id`, `created_by`, `created_on`, `updated_by`, `updated_on`, `oth_expense`) VALUES
(1, 3, '2023-04-17', 3, 0, '800', '200', '300', 2, 1, '', '', '1', '500', 0, 0, '', '', '2000', 'ExpenseBill_1_20230421152502.pdf', 1, NULL, 2, '2023-04-18 18:48:44', 2, '2023-04-21 13:26:25', '700'),
(2, 1, '2023-04-20', 1, 0, '0', '0', '0', 2, 2, 'test123', 'text987', '45', '0', 2, 1, 'test543', 'test567', '100', 'ExpenseBill_2_20230421120533.pdf', 1, NULL, 2, '2023-04-20 17:33:35', 2, '2023-04-21 10:06:46', '0'),
(3, 2, '2023-04-20', 2, 1, '0', '0', '0', 2, 2, 'testing11', 'testing22', '10', '0', 2, 2, 'testing1233', 'testing4333', '500', 'ExpenseBill_3_20230424102132.pdf', 1, NULL, 2, '2023-04-21 12:11:40', 2, '2023-04-24 08:22:46', '0'),
(4, 3, '2023-04-20', 3, 0, '0', '0', '0', 2, 2, 'narration', 'test', '20000', '0', 1, 1, 'test123', 'test2778', '30000', '0', 1, NULL, 2, '2023-04-21 12:52:08', 2, '2023-04-21 10:21:14', '0'),
(5, 1, '2023-04-21', 1, 0, '10', '10', '20', 1, 1, 'Narration test', 'Calculation Basis test', '1000', '50', 1, 1, 'Narration test air', 'Calculation Basis test air', '1000', '0', 1, NULL, 2, '2023-04-21 13:06:42', 2, '2023-04-21 07:38:11', '0'),
(6, 1, '2023-04-20', 1, 0, '0', '0', '0', 0, 0, '', '', '100', '0', 0, 0, '', '', '700', '0', 1, NULL, 2, '2023-04-21 15:48:00', 2, '2023-04-21 13:11:08', '0');

-- --------------------------------------------------------

--
-- Table structure for table `case_lawyer`
--

CREATE TABLE `case_lawyer` (
  `case_lawyer_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `per_hour_fee` decimal(8,2) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `case_lawyer`
--

INSERT INTO `case_lawyer` (`case_lawyer_id`, `case_id`, `user_id`, `per_hour_fee`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 1, 1, '1000.00', 2, 0, '2022-07-25 12:26:06', 0, '2022-08-04 04:14:37'),
(2, 1, 2, '2000.00', 1, 0, '2022-07-25 12:26:18', 0, '2022-07-25 06:56:18'),
(3, 2, 1, '10000.00', 2, 0, '2022-07-25 12:47:52', 0, '2022-07-26 12:22:51'),
(4, 2, 2, '20000.00', 1, 0, '2022-07-25 12:48:08', 0, '2022-07-25 07:18:08'),
(5, 2, 2, '300.00', 2, 0, '2022-07-25 12:59:01', 0, '2022-07-26 12:22:54'),
(6, 2, 1, '13000.00', 1, 0, '2022-07-25 13:02:38', 0, '2022-07-25 07:32:38'),
(7, 5, 2, '500.00', 1, 0, '2022-08-01 16:53:37', 0, '2022-08-01 11:23:37'),
(8, 6, 3, '15000.00', 1, 0, '2022-08-02 12:54:39', 0, '2022-08-02 07:24:39'),
(9, 7, 3, '10000.00', 1, 0, '2022-08-02 13:13:02', 0, '2022-08-02 07:43:02');

-- --------------------------------------------------------

--
-- Table structure for table `case_master`
--

CREATE TABLE `case_master` (
  `case_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `client_code_id` int(11) DEFAULT '0',
  `case_no` varchar(200) NOT NULL,
  `case_detail` varchar(200) DEFAULT NULL,
  `case_vs_from` varchar(200) NOT NULL,
  `case_vs_to` varchar(200) NOT NULL,
  `case_start_date` date NOT NULL,
  `court_case_title` varchar(500) DEFAULT '',
  `case_description` varchar(500) DEFAULT '',
  `case_close_date` date DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `clerkage` decimal(12,2) DEFAULT '0.00',
  `clerkage_type` varchar(100) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `case_master`
--

INSERT INTO `case_master` (`case_id`, `file_id`, `client_code_id`, `case_no`, `case_detail`, `case_vs_from`, `case_vs_to`, `case_start_date`, `court_case_title`, `case_description`, `case_close_date`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`, `clerkage`, `clerkage_type`) VALUES
(1, 2, 2, 'RIL23OCT21', 'Request for constitution of Special Bench -Appeal before the ITAT in the case of M/s . Reliance Industries Ltd. - A.Y. 1986-87 - reg', 'Reliance inds. LTD', 'Mumbai Dy. Cit, Special Range 18', '2023-02-01', 'Request for constitution of Special Bench -Appeal before the ITAT in the case of M/s . Reliance Industries Ltd. - A.Y. 1986-87 - reg', 'The Hon\'ble President, accepting the request, was pleased to consititute a Special Bench to decide the question which has been reproduced above.', NULL, 1, 0, '2023-04-11 18:07:55', 0, '2023-04-11 12:37:55', '0.00', '');

-- --------------------------------------------------------

--
-- Table structure for table `cause_list`
--

CREATE TABLE `cause_list` (
  `cause_list_id` int(11) NOT NULL,
  `court_no` int(11) NOT NULL,
  `court_id` int(11) NOT NULL,
  `item_no` varchar(100) NOT NULL,
  `justice` varchar(400) NOT NULL,
  `file_id` int(11) NOT NULL,
  `case_id` int(11) DEFAULT '0',
  `client_code_id` int(11) NOT NULL,
  `short_title` varchar(200) DEFAULT '',
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cause_date` datetime NOT NULL,
  `activity_type` varchar(45) NOT NULL,
  `remarks` varchar(500) DEFAULT NULL,
  `cause_desc` varchar(300) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cause_list`
--

INSERT INTO `cause_list` (`cause_list_id`, `court_no`, `court_id`, `item_no`, `justice`, `file_id`, `case_id`, `client_code_id`, `short_title`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`, `cause_date`, `activity_type`, `remarks`, `cause_desc`) VALUES
(1, 203, 2, 'RIL/TY5465/678', 'Hon\'ble Justice Krishna Yadav,Hon\'ble Justice Dinesh Maheshwari ', 2, 1, 2, 'Reliance inds. LTD VS Mumbai Dy. Cit, Special Range 18', 1, 2, '2023-04-11 18:23:32', 2, '2023-04-11 12:56:20', '2023-04-09 00:00:00', '9', NULL, 'The revenue\'s appeal in the case of M/s . Reliance Industries Ltd. for A.Y. 86-87 is pending before the Hon\'ble ITAT \"J\" Bench, Mumbai. The main issue relates to the claim of notional sales-tax as capital receipt by the assessee company.'),
(4, 236757, 4, 'ONGC/9635', 'Hon\'ble Justice Prateek Jalan', 1, 0, 1, 'ONGC  Ltd. VS ABAN Offshore', 1, 2, '2023-04-11 18:27:45', 2, '2023-04-11 13:06:23', '2023-04-08 00:00:00', '1', NULL, 'ONGC  Ltd. VS ABAN Offshore XYZ');

-- --------------------------------------------------------

--
-- Table structure for table `cause_list_detail`
--

CREATE TABLE `cause_list_detail` (
  `cause_list_detail_id` int(11) NOT NULL,
  `cause_list_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `to_time` time DEFAULT '00:00:00',
  `from_time` time DEFAULT '00:00:00',
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cause_list_detail`
--

INSERT INTO `cause_list_detail` (`cause_list_detail_id`, `cause_list_id`, `user_id`, `to_time`, `from_time`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 1, 1, '00:00:00', '00:00:00', 1, 2, '2023-04-11 18:23:32', 2, '2023-04-11 13:09:35'),
(2, 1, 2, '00:00:00', '00:00:00', 2, 2, '2023-04-11 18:23:32', 0, '2023-04-11 13:09:35'),
(3, 4, 1, '00:00:00', '00:00:00', 1, 2, '2023-04-11 18:27:45', 2, '2023-04-11 13:07:43'),
(4, 4, 16, '00:00:00', '00:00:00', 2, 2, '2023-04-11 18:27:45', 0, '2023-04-11 13:06:23'),
(5, 1, 16, '00:00:00', '00:00:00', 2, 2, '2023-04-11 18:30:06', 0, '2023-04-11 13:00:25'),
(6, 1, 5, '00:00:00', '00:00:00', 1, 2, '2023-04-11 18:31:13', 2, '2023-04-11 13:09:35'),
(7, 4, 5, '00:00:00', '00:00:00', 2, 2, '2023-04-11 18:36:23', 0, '2023-04-11 13:07:43'),
(8, 4, 2, '00:00:00', '00:00:00', 1, 2, '2023-04-11 18:37:43', 0, '2023-04-11 13:07:43'),
(9, 1, 14, '00:00:00', '00:00:00', 1, 2, '2023-04-11 18:39:35', 0, '2023-04-11 13:09:35');

-- --------------------------------------------------------

--
-- Table structure for table `client_code`
--

CREATE TABLE `client_code` (
  `client_code_id` int(11) NOT NULL,
  `client_code` varchar(255) NOT NULL,
  `client_name` varchar(255) NOT NULL DEFAULT '',
  `client_address` varchar(500) NOT NULL DEFAULT '',
  `contact_person_name` varchar(255) NOT NULL DEFAULT '',
  `contact_person_mobile_no` bigint(20) NOT NULL DEFAULT '0',
  `contact_person_email_id` varchar(150) NOT NULL DEFAULT '',
  `state_id` int(11) DEFAULT '0',
  `gst_no` varchar(15) DEFAULT NULL,
  `gst_on_bill` varchar(45) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `clerkage` decimal(5,2) NOT NULL DEFAULT '10.00',
  `clerkage_type` varchar(20) NOT NULL DEFAULT 'percentage'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client_code`
--

INSERT INTO `client_code` (`client_code_id`, `client_code`, `client_name`, `client_address`, `contact_person_name`, `contact_person_mobile_no`, `contact_person_email_id`, `state_id`, `gst_no`, `gst_on_bill`, `sort_order`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`, `clerkage`, `clerkage_type`) VALUES
(1, 'ONGC', 'ONGC Petero Additions Ltd', 'Plot No. Z/1 & Z/38 Dahej SEZ, Taluka Vagra District Bharuch, Gujarat 392130', 'XYZ', 9874563210, 'ongc@gmail.com', 24, '24AAACO9200B3Z2', '1', 0, 1, 0, '2023-04-11 17:14:57', 0, '2023-04-11 11:56:19', '15.00', 'percentage'),
(2, 'RELINDLTD', 'Reliance Industries', 'Maker Chambers 4th floor Gujrat India', 'Aswin M Dave', 9874563215, 'reliance@gmail.com', 24, '24ABACO6200B3X6', '1', 0, 1, 0, '2023-04-11 18:01:02', 0, '2023-04-11 12:31:02', '10.00', 'percentage'),
(3, 'CBA', 'C Batra Associates', '36/4, East Patel Nagar, New Delhi - 110008', 'C J Batra', 9810135096, 'cbatra.bsa@bsa.co.in', 7, '', '0', 0, 1, 0, '2023-04-18 17:37:12', 0, '2023-04-18 12:07:12', '10.00', 'percentage');

-- --------------------------------------------------------

--
-- Table structure for table `court`
--

CREATE TABLE `court` (
  `court_id` int(11) NOT NULL,
  `court_name` varchar(80) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `court`
--

INSERT INTO `court` (`court_id`, `court_name`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 'SUPREME COURT OF INDIA', 1, 2, '2023-03-16 17:32:11', 0, '2023-03-16 12:02:11'),
(2, 'HIGH COURT OF DELHI', 1, 2, '2023-03-16 17:32:23', 0, '2023-03-16 12:02:23'),
(3, 'TIZ HAZARI COURT', 1, 2, '2023-03-16 17:32:51', 2, '2023-03-16 12:02:57'),
(4, 'PATIALA HOUSE COURT', 1, 2, '2023-03-16 17:33:06', 0, '2023-03-16 12:03:06'),
(5, 'SAKET COURT', 1, 2, '2023-03-16 17:33:11', 0, '2023-03-16 12:03:11'),
(6, 'DWARKA COURT', 1, 2, '2023-03-16 17:33:20', 0, '2023-03-16 12:03:20'),
(7, 'ROHINI COURT', 1, 2, '2023-03-16 17:33:31', 0, '2023-03-16 12:03:31'),
(8, 'GHAZIABAD COURT', 1, 2, '2023-03-16 17:33:42', 0, '2023-03-16 12:03:42'),
(9, 'ROUSE AVENUE COURT', 1, 2, '2023-03-16 17:33:46', 0, '2023-03-16 12:03:46'),
(10, 'NCLT', 1, 2, '2023-03-16 17:34:03', 0, '2023-03-16 12:04:03'),
(11, 'NCLAT', 2, 2, '2023-03-16 17:34:09', 2, '2023-04-04 12:07:34'),
(12, 'TDSAT', 1, 2, '2023-03-16 17:34:18', 0, '2023-03-16 12:04:18'),
(13, 'NGT', 1, 2, '2023-03-16 17:34:28', 0, '2023-03-16 12:04:28'),
(14, 'NCDRC', 1, 2, '2023-03-16 17:34:34', 0, '2023-03-16 12:04:34'),
(15, 'APTEL', 1, 2, '2023-03-16 17:34:45', 0, '2023-03-16 12:04:45'),
(16, 'CERC', 1, 2, '2023-03-16 17:35:00', 0, '2023-03-16 12:05:00'),
(17, 'PNGRB', 2, 2, '2023-03-16 17:35:08', 2, '2023-04-04 12:07:43'),
(18, 'SUPREME COURT OF INDIA', 2, 2, '2023-03-21 16:26:43', 2, '2023-04-04 12:08:00'),
(19, 'ABCFD', 2, 2, '2023-03-21 16:27:06', 2, '2023-04-04 12:07:09');

-- --------------------------------------------------------

--
-- Table structure for table `fee_master`
--

CREATE TABLE `fee_master` (
  `fee_master_id` int(11) NOT NULL,
  `client_code_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `unit` tinyint(4) NOT NULL DEFAULT '0',
  `fee` bigint(20) NOT NULL DEFAULT '0',
  `particulars` varchar(500) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fee_master`
--

INSERT INTO `fee_master` (`fee_master_id`, `client_code_id`, `task_id`, `unit`, `fee`, `particulars`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 1, 1, 2, 5000, 'Daily Fee for legal work carried out abroad per day including and not limiting to fee for conference meeting hearing before Courts Arbitral Tribunal any other judicial or quasi judicial authority etc', 1, 2, '2023-04-11 17:37:15', 1, '2023-04-11 13:23:15'),
(2, 1, 10, 1, 7800, 'Fee for holding conferences meeting with officers of Dgh,MOP&NG senior and any other conference of similer nature in india', 1, 2, '2023-04-11 17:40:03', 1, '2023-04-11 13:19:24'),
(3, 1, 1, 2, 34000, 'Fee for appearing in Lower Courts,High Court, Tribunal Other and Consumer Forums any judical in DELHI/NCR  ', 1, 2, '2023-04-11 18:12:43', 0, '2023-04-11 12:42:43'),
(4, 2, 9, 1, 78000, 'High Cour, National Green, APTEL for Crimal Cases Senior officel company  ', 1, 2, '2023-04-11 18:15:35', 0, '2023-04-11 12:45:35'),
(5, 2, 13, 2, 45500, 'Research for matter in High court,APTEL and NCDRC With Senir guidance', 1, 2, '2023-04-11 18:17:59', 0, '2023-04-11 12:47:59'),
(6, 3, 1, 1, 50000, 'Fees for Apperance befor tribunal / court abroad for partner Mr. R.S. Prabhu', 1, 2, '2023-04-18 17:48:10', 2, '2023-04-21 07:03:31'),
(7, 3, 1, 2, 20000, 'Fees for Apperance befor tribunal / court abroad for an associate', 1, 2, '2023-04-18 17:50:30', 2, '2023-04-21 07:03:36'),
(8, 3, 1, 3, 20000, 'Non Hearing days abroad including team discussion Mr. R S Prabhu', 1, 2, '2023-04-18 17:56:55', 2, '2023-04-21 07:03:41'),
(9, 3, 4, 1, 650, 'Air Ticket in USD $', 1, 2, '2023-04-18 18:01:41', 0, '2023-04-18 12:31:41'),
(10, 3, 4, 1, 1, 'Air Travel Businees Classs', 1, 2, '2023-04-18 18:11:05', 0, '2023-04-18 12:41:05'),
(11, 3, 4, 1, 650, 'Daily Stay Expenses Fixed', 1, 2, '2023-04-18 18:11:53', 0, '2023-04-18 12:41:53'),
(12, 3, 4, 1, 350, 'Hotel Stay 5 Star + Fixed Pay out', 1, 2, '2023-04-18 18:12:38', 0, '2023-04-18 12:42:38'),
(13, 3, 10, 4, 7000, 'text', 1, 2, '2023-04-19 11:27:19', 2, '2023-04-21 07:03:46'),
(14, 3, 1, 5, 15000, 'text', 1, 2, '2023-04-19 13:30:51', 2, '2023-04-20 12:24:38'),
(15, 3, 1, 1, 32154, 'text', 1, 2, '2023-04-19 13:34:10', 2, '2023-04-21 07:03:51');

-- --------------------------------------------------------

--
-- Table structure for table `file_master`
--

CREATE TABLE `file_master` (
  `file_id` int(11) NOT NULL,
  `file_no` varchar(50) NOT NULL DEFAULT '',
  `client_code_id` int(11) NOT NULL,
  `file_title` varchar(100) NOT NULL,
  `file_description` varchar(400) DEFAULT '',
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `file_master`
--

INSERT INTO `file_master` (`file_id`, `file_no`, `client_code_id`, `file_title`, `file_description`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 'File 1', 1, 'ONGC Ltd', 'Review Of The parties\' pleadings and documents and drafting of outline of witness ecidence', 1, 2, '2023-04-11 17:31:05', 0, '2023-04-11 12:01:05'),
(2, 'File 2', 2, 'RIL 07', 'Tribunal - Mumbai Dy. Cit, Special Range 18 vs Reliance Industries Ltd. on 23 October, 2003 Equivalent citations ... ITAT in the case of M/s . Reliance Industries Ltd', 1, 2, '2023-04-11 18:04:34', 0, '2023-04-11 12:34:34'),
(3, 'AB231', 3, 'Janta Cooperative Bank VS CBA', 'Filing of Court Case', 1, 2, '2023-04-18 17:38:24', 0, '2023-04-18 12:08:24');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_master`
--

CREATE TABLE `invoice_master` (
  `invoice_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `bill_no` varchar(200) NOT NULL,
  `bill_date` date NOT NULL,
  `clerkage` decimal(12,2) DEFAULT '0.00',
  `photocopy_charges` decimal(12,2) DEFAULT '0.00',
  `int_courier_charges` decimal(12,2) DEFAULT '0.00',
  `other_charge` decimal(12,2) DEFAULT '0.00',
  `grand_total` decimal(12,2) NOT NULL,
  `is_final` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `invoice_master`
--

INSERT INTO `invoice_master` (`invoice_id`, `client_id`, `file_id`, `start_date`, `end_date`, `total`, `bill_no`, `bill_date`, `clerkage`, `photocopy_charges`, `int_courier_charges`, `other_charge`, `grand_total`, `is_final`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 1, 1, '2023-04-01', '2023-04-15', '175800.00', 'RSPL/001/01052023', '2023-05-01', '26370.00', '0.00', '0.00', '0.00', '202170.00', 0, 1, 1, '2023-04-11 18:53:45', 0, NULL),
(2, 3, 3, '2023-04-17', '2023-04-18', '73500.00', 'RSPL/002/19042023', '2023-04-19', '7350.00', '0.00', '0.00', '0.00', '77000.00', 0, 1, 2, '2023-04-18 18:59:19', 2, '2023-04-18 13:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `justice`
--

CREATE TABLE `justice` (
  `justice_id` int(11) NOT NULL,
  `justice_name` varchar(80) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `justice`
--

INSERT INTO `justice` (`justice_id`, `justice_name`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 'Chief Justice Dhananjaya Y  Chandrachud', 1, 2, '2023-03-06 14:52:00', 2, '2023-03-09 07:13:41'),
(2, 'Justice K. M. Joseph', 1, 2, '2023-03-09 12:44:09', 2, '2023-03-09 07:14:28'),
(3, 'Justice Ajay Rastogi', 1, 2, '2023-03-09 12:44:55', 2, '2023-03-09 07:15:36');

-- --------------------------------------------------------

--
-- Table structure for table `lawyer_advisory`
--

CREATE TABLE `lawyer_advisory` (
  `lawyer_advisory_id` int(11) NOT NULL,
  `client_code_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `file_id` int(11) NOT NULL,
  `case_id` int(11) DEFAULT '0',
  `lawyer_name` varchar(100) NOT NULL,
  `fee` bigint(20) NOT NULL,
  `task_id` int(11) NOT NULL,
  `sacn_bill_copy_path` varchar(200) DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `invoice_id` int(11) DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lawyer_advisory`
--

INSERT INTO `lawyer_advisory` (`lawyer_advisory_id`, `client_code_id`, `date`, `file_id`, `case_id`, `lawyer_name`, `fee`, `task_id`, `sacn_bill_copy_path`, `is_active`, `invoice_id`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 1, '2023-04-06', 10, 1, 'Hariom Pal', 8000, 7, '1_20230407135218.pdf', 1, 1, 1, '2023-04-10 17:22:53', 2, '2023-04-11 11:02:12'),
(2, 1, '2023-04-01', 10, 1, 'Gajendra', 85000, 14, '2_20230411104711.pdf', 1, 1, 1, '2023-04-11 10:47:13', 2, '2023-04-11 11:02:12'),
(3, 2, '2023-04-01', 12, 2, 'Gajendra', 8000, 14, '3_20230411111540.pdf', 1, 3, 1, '2023-04-11 11:15:43', 1, '2023-04-11 05:48:10'),
(4, 1, '2023-04-01', 1, 0, 'Gajendra', 80000, 14, '4_20230411184703.pdf', 1, 0, 1, '2023-04-11 18:47:03', 1, '2023-04-11 13:17:03');

-- --------------------------------------------------------

--
-- Table structure for table `lawyer_fee`
--

CREATE TABLE `lawyer_fee` (
  `lawyer_fee_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `fee` decimal(12,2) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lawyer_fee`
--

INSERT INTO `lawyer_fee` (`lawyer_fee_id`, `case_id`, `user_id`, `task_id`, `type`, `fee`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(4, 7, 2, 3, 'PerVisit', '90000.00', 1, 0, '2022-08-02 17:19:57', 0, '2023-03-06 05:08:43'),
(5, 7, 1, 2, 'perhour', '0.00', 1, 0, '2022-08-02 17:19:57', 0, '2022-08-02 11:49:57'),
(6, 7, 1, 1, 'PerHour', '0.00', 1, 0, '2022-08-04 10:37:51', 0, '2022-08-04 05:07:51'),
(8, 7, 1, 4, 'PerHour', '0.00', 1, 0, '2022-08-04 10:40:39', 0, '2022-08-04 05:10:39'),
(9, 7, 1, 3, 'PerHour', '9000.00', 1, 0, '2022-08-04 10:44:36', 0, '2022-08-16 10:52:02'),
(10, 7, 2, 3, 'PerHour', '0.00', 2, 0, '2022-08-04 15:15:13', 0, '2022-08-04 09:51:14'),
(11, 7, 2, 1, 'PerHour', '120.00', 2, 0, '2022-08-04 15:20:16', 0, '2022-08-04 09:53:15'),
(12, 7, 2, 4, 'PerHour', '1400.00', 1, 0, '2022-08-04 15:20:16', 0, '2023-03-16 09:19:30'),
(13, 7, 2, 2, 'PerHour', '300.00', 2, 0, '2022-08-04 15:20:16', 0, '2022-08-04 09:52:58'),
(14, 7, 3, 1, 'PerHour', '3000.00', 1, 0, '2022-08-04 15:24:04', 0, '2023-03-06 05:33:13'),
(15, 7, 3, 4, 'PerVisit', '4000.00', 1, 0, '2022-08-04 15:24:04', 0, '2022-08-13 11:38:28'),
(16, 7, 3, 3, 'PerVisit', '9400.00', 1, 0, '2022-08-04 15:24:04', 0, '2022-08-13 11:37:38'),
(17, 7, 3, 2, 'PerHour', '5500.00', 1, 0, '2022-08-04 15:24:04', 0, '2022-08-13 11:32:52'),
(18, 6, 3, 1, 'PerHour', '6000.00', 1, 0, '2022-08-04 15:25:42', 0, '2022-08-04 09:55:42'),
(19, 6, 3, 4, 'PerVisit', '5000.00', 1, 0, '2022-08-04 15:25:42', 0, '2022-08-04 09:55:42'),
(20, 6, 3, 3, 'PerHour', '9000.00', 1, 0, '2022-08-04 15:25:42', 0, '2022-08-04 09:55:42'),
(21, 6, 3, 2, 'PerHour', '30000.00', 1, 0, '2022-08-04 15:25:42', 0, '2022-08-04 09:55:42'),
(22, 1, 3, 1, 'PerVisit', '9000.00', 2, 0, '2022-08-04 15:27:22', 0, '2023-03-16 06:13:34'),
(23, 1, 3, 4, 'PerVisit', '780.00', 1, 0, '2022-08-04 15:27:22', 0, '2022-08-04 09:57:22'),
(24, 1, 3, 3, 'PerHour', '640.00', 1, 0, '2022-08-04 15:27:22', 0, '2022-08-04 09:57:22'),
(25, 1, 3, 2, 'PerHour', '5000.00', 1, 0, '2022-08-04 15:27:22', 0, '2022-08-04 09:57:22'),
(26, 5, 2, 1, 'PerVisit', '7000.00', 1, 0, '2022-08-04 17:09:35', 0, '2022-08-13 11:00:41'),
(27, 5, 2, 4, 'PerHour', '7000.00', 1, 0, '2022-08-04 17:09:35', 0, '2022-08-04 11:39:35'),
(28, 5, 2, 3, 'PerVisit', '9000.00', 1, 0, '2022-08-04 17:09:35', 0, '2022-08-04 11:39:35'),
(29, 5, 2, 2, 'PerHour', '4000.00', 1, 0, '2022-08-04 17:09:35', 0, '2022-08-13 11:01:05'),
(30, 6, 2, 1, 'PerHour', '120.00', 1, 0, '2022-08-06 13:42:44', 0, '2022-08-16 07:41:35'),
(31, 6, 2, 4, 'PerHour', '780.00', 1, 0, '2022-08-06 13:42:44', 0, '2022-08-16 07:49:40'),
(32, 6, 2, 3, 'PerHour', '900.00', 1, 0, '2022-08-06 13:42:44', 0, '2022-08-16 10:50:40'),
(33, 6, 2, 2, 'PerHour', '250.00', 1, 0, '2022-08-06 13:42:44', 0, '2022-08-16 07:42:03'),
(34, 5, 1, 1, 'PerHour', '9000.00', 1, 0, '2022-08-06 14:14:29', 0, '2022-08-13 11:30:55'),
(35, 5, 1, 4, 'PerHour', '9600.00', 1, 0, '2022-08-06 14:14:29', 0, '2022-08-13 11:31:07'),
(36, 5, 1, 3, 'PerHour', '8000.00', 1, 0, '2022-08-06 14:14:29', 0, '2022-08-06 08:44:29'),
(37, 5, 1, 2, 'PerHour', '6900.00', 1, 0, '2022-08-06 14:14:29', 0, '2022-08-13 11:16:13'),
(38, 2, 3, 1, 'PerVisit', '120.00', 1, 0, '2022-08-06 14:19:01', 0, '2022-08-06 08:49:01'),
(39, 2, 3, 2, 'PerVisit', '6000.00', 1, 0, '2022-08-06 14:19:01', 0, '2022-08-06 08:49:01'),
(40, 2, 2, 1, 'PerVisit', '120.00', 1, 0, '2022-08-06 14:20:11', 0, '2022-08-06 08:50:11'),
(41, 2, 2, 4, 'PerHour', '6000.00', 1, 0, '2022-08-06 14:20:11', 0, '2022-08-06 08:50:11'),
(42, 2, 2, 2, 'PerVisit', '40000.00', 1, 0, '2022-08-06 14:20:11', 0, '2022-08-06 08:50:11'),
(43, 7, 2, 1, 'PerHour', '120.00', 1, 0, '2022-08-06 14:22:48', 0, '2022-08-06 08:52:48'),
(44, 7, 2, 2, 'PerHour', '3000.00', 1, 0, '2022-08-06 14:22:48', 0, '2022-08-06 08:52:48'),
(45, 2, 3, 4, 'PerVisit', '8000.00', 1, 0, '2022-08-06 14:28:49', 0, '2022-08-06 08:58:49'),
(46, 2, 3, 3, 'PerHour', '8000.00', 1, 0, '2022-08-06 14:29:34', 0, '2022-08-06 08:59:34'),
(47, 6, 1, 1, 'PerVisit', '1000.00', 1, 0, '2022-08-10 10:54:09', 0, '2022-08-10 05:24:09'),
(48, 6, 1, 4, 'PerHour', '200.00', 1, 0, '2022-08-10 10:54:09', 0, '2022-08-10 05:24:09'),
(49, 6, 1, 3, 'PerHour', '590.00', 1, 0, '2022-08-10 10:54:09', 0, '2022-08-17 09:04:03'),
(50, 6, 1, 2, 'PerVisit', '1900.00', 1, 0, '2022-08-10 10:54:09', 0, '2022-08-13 11:23:06'),
(51, 6, 3, 5, 'PerHour', '100.00', 1, 0, '2022-08-18 13:40:49', 0, '2022-08-18 08:10:49'),
(52, 6, 3, 6, 'PerVisit', '225.00', 1, 0, '2022-08-18 14:45:54', 0, '2022-08-18 09:15:54'),
(53, 2, 1, 1, 'PerHour', '10.00', 1, 0, '2022-09-21 11:42:45', 0, '2022-09-21 06:12:45'),
(54, 2, 1, 4, 'PerHour', '10.00', 1, 0, '2022-09-21 11:42:45', 0, '2022-09-21 06:12:45'),
(55, 10, 2, 1, 'PerHour', '50.00', 2, 0, '2023-03-15 16:05:28', 0, '2023-03-15 10:36:00'),
(56, 10, 2, 7, 'PerHour', '60.00', 2, 0, '2023-03-15 16:05:28', 0, '2023-03-15 10:36:03'),
(57, 10, 2, 4, 'PerHour', '90.00', 2, 0, '2023-03-15 16:05:28', 0, '2023-03-15 10:36:07'),
(58, 10, 2, 3, 'PerHour', '40.00', 2, 0, '2023-03-15 16:05:28', 0, '2023-03-15 10:36:10'),
(59, 10, 2, 8, 'PerHour', '80.00', 2, 0, '2023-03-15 16:05:28', 0, '2023-03-15 10:36:12'),
(60, 10, 2, 2, 'PerHour', '60.00', 2, 0, '2023-03-15 16:05:28', 0, '2023-03-15 10:36:21'),
(61, 10, 2, 6, 'PerHour', '70.00', 2, 0, '2023-03-15 16:05:28', 0, '2023-03-15 10:36:15'),
(62, 10, 2, 5, 'PerHour', '80.00', 2, 0, '2023-03-15 16:05:28', 0, '2023-03-15 10:36:18'),
(63, 10, 2, 9, 'PerHour', '90.00', 2, 0, '2023-03-15 16:05:28', 0, '2023-03-15 10:36:24'),
(64, 10, 2, 1, 'PerHour', '10099.00', 1, 0, '2023-03-15 16:06:49', 0, '2023-03-15 10:43:11'),
(65, 10, 2, 7, 'PerHour', '68956.00', 1, 0, '2023-03-15 16:19:40', 0, '2023-03-15 10:49:40'),
(66, 10, 2, 4, 'PerHour', '68960.00', 1, 0, '2023-03-15 16:19:40', 0, '2023-03-15 10:49:40'),
(67, 10, 2, 3, 'PerHour', '68970.00', 1, 0, '2023-03-15 16:19:40', 0, '2023-03-15 10:49:40'),
(68, 10, 2, 8, 'PerHour', '68980.00', 1, 0, '2023-03-15 16:19:40', 0, '2023-03-15 10:49:40'),
(69, 1, 3, 1, 'PerVisit', '90000.00', 1, 0, '2023-03-16 12:04:37', 0, '2023-03-16 06:34:37'),
(70, 7, 2, 9, 'PerVisit', '5800.00', 1, 0, '2023-03-16 15:29:48', 0, '2023-03-16 09:59:48'),
(71, 12, 5, 1, 'PerVisit', '10000.00', 1, 0, '2023-03-17 11:37:57', 0, '2023-03-17 06:07:57'),
(72, 12, 5, 3, 'PerHour', '7000.00', 1, 0, '2023-03-17 11:37:57', 0, '2023-03-17 06:07:57'),
(73, 12, 5, 5, 'PerVisit', '2000.00', 1, 0, '2023-03-17 11:37:57', 0, '2023-03-17 06:07:57'),
(74, 12, 5, 4, 'PerVisit', '1000.00', 1, 0, '2023-03-17 11:37:58', 0, '2023-03-17 06:07:58'),
(75, 12, 2, 1, 'PerVisit', '10000.00', 1, 0, '2023-03-17 11:41:48', 0, '2023-03-17 06:11:48'),
(76, 12, 2, 3, 'PerVisit', '7000.00', 1, 0, '2023-03-17 11:41:48', 0, '2023-03-17 06:11:48'),
(77, 12, 2, 5, 'PerVisit', '5000.00', 1, 0, '2023-03-17 11:41:48', 0, '2023-03-17 06:11:48');

-- --------------------------------------------------------

--
-- Table structure for table `task_type`
--

CREATE TABLE `task_type` (
  `task_id` int(11) NOT NULL,
  `task_type` varchar(100) NOT NULL,
  `sort_code` varchar(50) DEFAULT '',
  `task_description` varchar(200) DEFAULT '',
  `cause_task` varchar(5) NOT NULL DEFAULT 'Y',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `task_type`
--

INSERT INTO `task_type` (`task_id`, `task_type`, `sort_code`, `task_description`, `cause_task`, `sort_order`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 'APPEARANCE', 'APPEARANCE', 'APPEARANCE BY PANKAJ', 'Y', 0, 1, 0, '2022-07-25 12:25:16', 0, '2023-03-22 05:59:23'),
(2, 'Hearing', '', '', 'N', 0, 2, 0, '2022-07-25 12:25:23', 0, '2023-03-29 05:48:33'),
(3, 'CONFERENCE', 'CONF', '', 'N', 0, 1, 0, '2022-07-25 12:25:34', 0, '2023-03-29 05:48:33'),
(4, 'TRAVELLING', 'TRAVEL', '', 'N', 0, 2, 0, '2022-07-25 12:25:49', 0, '2023-04-18 13:38:59'),
(5, 'MISC. EXPENSES', 'MISC', '', 'N', 0, 2, 0, '2022-08-18 13:40:00', 0, '2023-04-18 13:38:56'),
(6, 'BRIEFING', 'BRIEF', '', 'N', 0, 1, 0, '2022-08-18 14:45:19', 0, '2023-03-29 05:48:33'),
(7, 'ARBITRATION', 'ARB', 'ARBITRATION IN COURT', 'N', 0, 1, 0, '2023-03-06 14:55:12', 0, '2023-03-29 05:48:33'),
(8, 'DRAFTING', 'DRAFT', '', 'N', 0, 1, 0, '2023-03-06 14:56:24', 0, '2023-03-29 05:48:33'),
(9, 'WATCHING', 'WATCH', '', 'Y', 0, 1, 0, '2023-03-06 14:57:03', 0, '2023-03-16 12:23:59'),
(10, 'FILING', 'FILING', '', 'N', 0, 1, 0, '2023-03-16 17:51:51', 0, '2023-03-29 05:48:33'),
(11, 'TEST', 'TEST', '', 'N', 0, 2, 0, '2023-03-17 11:25:49', 0, '2023-03-29 05:48:33'),
(12, 'task', '54534', 'test', 'N', 0, 2, 0, '2023-03-18 15:13:46', 0, '2023-03-29 05:48:33'),
(13, 'RESEARCH', 'RESEARCH', '', 'N', 0, 1, 0, '2023-03-21 16:06:42', 0, '2023-03-29 05:48:33'),
(14, 'OPINION', 'OPN', '', 'N', 0, 1, 0, '2023-03-22 11:29:00', 0, '2023-03-29 05:48:33'),
(15, 'pankaj', '98765', '', 'N', 0, 2, 0, '2023-03-25 12:51:25', 0, '2023-03-29 05:48:33');

-- --------------------------------------------------------

--
-- Table structure for table `time_sheet`
--

CREATE TABLE `time_sheet` (
  `time_sheet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `file_id` int(11) NOT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `total_time` int(11) NOT NULL,
  `case_id` int(11) DEFAULT '0',
  `cause_list_id` int(11) DEFAULT '0',
  `amount` decimal(12,2) NOT NULL,
  `task_id` int(11) NOT NULL,
  `fee_id` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `billable` varchar(1) NOT NULL DEFAULT 'Y',
  `invoice_id` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `time_sheet`
--

INSERT INTO `time_sheet` (`time_sheet_id`, `user_id`, `file_id`, `start_time`, `end_time`, `total_time`, `case_id`, `cause_list_id`, `amount`, `task_id`, `fee_id`, `description`, `billable`, `invoice_id`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 2, 1, '2023-04-09 13:10:00', '2023-04-09 15:10:00', 120, 0, 4, '0.00', 1, 3, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled', 'Y', 0, 2, 2, '2023-04-11 18:42:28', 2, '2023-04-18 13:26:00'),
(2, 1, 1, '2023-04-01 09:15:00', '2023-04-01 13:15:00', 240, 0, 4, '0.00', 1, 1, 'It is a long established fact that a reader will be distracted by the readable content of a page when ', 'Y', 0, 1, 1, '2023-04-11 18:45:22', 0, '2023-04-12 06:26:21'),
(3, 1, 1, '2023-04-07 11:15:00', '2023-04-07 15:15:00', 240, 0, 0, '0.00', 10, 2, 'It is a long established fact that a reader will be distracted by the readable content of a of letters,', 'Y', 0, 1, 1, '2023-04-11 18:46:21', 0, '2023-04-11 13:16:21'),
(4, 2, 3, '2023-04-17 13:15:00', '2023-04-17 14:15:00', 60, 0, 0, '0.00', 4, 11, 'Test', 'Y', 0, 2, 2, '2023-04-18 18:46:32', 2, '2023-04-18 13:25:53'),
(5, 2, 3, '2023-04-17 17:15:00', '2023-04-17 18:15:00', 60, 0, 0, '0.00', 4, 9, '', 'Y', 0, 2, 2, '2023-04-18 18:48:09', 2, '2023-04-18 13:25:56'),
(6, 2, 3, '2023-04-17 13:25:00', '2023-04-17 13:30:00', 5, 0, 0, '0.00', 1, 6, 'Test', 'Y', 0, 1, 2, '2023-04-18 18:57:21', 0, '2023-04-18 13:27:21'),
(7, 2, 3, '2023-04-18 04:25:00', '2023-04-18 13:35:00', 550, 0, 0, '0.00', 1, 7, '', 'Y', 0, 1, 2, '2023-04-18 18:57:47', 0, '2023-04-18 13:27:47');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email_id` varchar(100) NOT NULL,
  `mobile_no` bigint(20) NOT NULL,
  `user_type_id` int(11) NOT NULL DEFAULT '0',
  `user_designation_id` int(11) DEFAULT NULL,
  `seniority_by_years` int(11) NOT NULL DEFAULT '0',
  `login_access` tinyint(1) NOT NULL DEFAULT '0',
  `father_name` varchar(45) DEFAULT '',
  `email_personal` varchar(45) DEFAULT '',
  `mobile_2` varchar(45) DEFAULT '',
  `address` varchar(200) DEFAULT '',
  `pincode` int(6) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `email_id`, `mobile_no`, `user_type_id`, `user_designation_id`, `seniority_by_years`, `login_access`, `father_name`, `email_personal`, `mobile_2`, `address`, `pincode`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 'Sunil Dutt Shukla', 'sunil.shukla@bsa.co.in', 9582945034, 2, 1, 0, 1, '', '', '', '', 0, 1, 1, '2022-07-25 12:19:59', 0, '2023-04-10 09:34:22'),
(2, 'Dinesh Solanki', 'dinesh.solanki@bsa.co.in', 9582945034, 2, 1, 0, 1, '', '', '', '', 0, 1, 1, '2022-07-25 12:20:53', 0, '2023-04-10 07:41:13'),
(3, 'Test', 'test@bsa.co.in', 9999999999, 3, 3, 0, 1, '', '', '', '', 0, 1, 1, '2022-07-30 10:21:35', 0, '2023-04-10 07:45:49'),
(4, 'Admin', 'admin@bsa.co.in', 9876543210, 1, 2, 0, 1, '', '', '', '', 0, 1, 2, '2023-03-06 10:43:12', 0, '2023-04-10 07:40:40'),
(5, 'VISHNU', 'ABC@C.COM', 9876543212, 2, 1, 4, 1, 'unkown', 'ABC123@C.COM', '3216547890', 'a', 0, 1, 2, '2023-03-06 14:52:00', 0, '2023-04-10 07:34:21'),
(6, 'Hari', 'admin@rsprabhu.com', 9999578237, 3, 3, 0, 1, '', '', '', '', 0, 1, 2, '2023-03-17 12:59:49', 0, '2023-04-10 07:40:59'),
(14, 'Hariom Pal', 'hariom@bsa.co.in', 7417122828, 2, 2, 0, 1, 'dfgdsfgd', '', '', '', 0, 1, 2, '2023-03-22 18:48:18', 0, '2023-04-10 07:41:22'),
(15, 'pankajDk', 'pankajdk@gmail.com', 9876543210, 1, 2, 0, 1, '', '', '', '', 0, 2, 2, '2023-03-25 12:41:23', 0, '2023-03-25 07:19:05'),
(16, 'pankaj', 'pankaj@gmail.com', 9876543210, 2, 1, 4, 0, 'pankaj', 'pankaj@gmail.com', '3216547890', 'delhi', 110020, 1, 2, '2023-04-10 12:23:06', 0, '2023-04-10 07:45:19'),
(17, 'Pankaj Mandal', 'pankaj@bsa.co.in', 9990463861, 2, 1, 0, 0, 'Manoranjan Mandal', '', '', 'ABC', 0, 1, 2, '2023-04-18 17:44:16', 0, '2023-04-18 12:14:16');

-- --------------------------------------------------------

--
-- Table structure for table `user_designation`
--

CREATE TABLE `user_designation` (
  `user_designation_id` int(11) NOT NULL,
  `user_designation` varchar(50) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `sort_code` varchar(25) NOT NULL DEFAULT '',
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_designation`
--

INSERT INTO `user_designation` (`user_designation_id`, `user_designation`, `user_type_id`, `sort_code`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 'Senior associate', 2, 'a', 1, 2, '2023-03-21 12:02:44', 0, '2023-04-10 07:33:53'),
(2, 'Junior associate', 2, 'd', 1, 2, '2023-03-21 12:09:33', 0, '2023-04-10 07:33:53'),
(3, 'Senior Clerk', 3, '13654', 1, 2, '2023-04-10 12:55:36', 0, '2023-04-10 07:33:53'),
(4, 'Middle associate', 2, 'a', 1, 2, '2023-04-19 10:40:35', 0, '2023-04-19 05:10:35'),
(5, 'Junior Clerk', 3, 'b', 1, 2, '2023-04-19 10:42:22', 0, '2023-04-19 05:12:22'),
(6, 'Middle Clerk', 3, '123', 1, 2, '2023-04-19 10:42:59', 0, '2023-04-19 05:12:59');

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE `user_login` (
  `user_login_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `last_login_ip` varchar(10) DEFAULT NULL,
  `default_password_change` tinyint(1) NOT NULL DEFAULT '0',
  `password_change_time` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_login`
--

INSERT INTO `user_login` (`user_login_id`, `user_id`, `username`, `password`, `last_login_time`, `last_login_ip`, `default_password_change`, `password_change_time`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 1, 'sunil.shukla@bsa.co.in', '$2y$12$fJdF9L/yB0fzqWdNHrM8lu7XWKbASM9L8mI1VQ4kCZodHwHIIID.S', '2023-04-09 08:24:44', '', 1, NULL, 1, 1, '2022-07-25 12:19:59', 2, '2023-04-12 06:25:20'),
(2, 2, 'dinesh.solanki@bsa.co.in', '$2y$12$qPofsr2tY89m8FW7iJIW.e22GQaITtYzsctQ3J.1vnJqBhG38nZ5W', '2023-05-12 09:02:00', '', 1, NULL, 1, 1, '2022-07-25 12:20:53', 14, '2023-05-12 07:02:04'),
(3, 3, 'test', '$2y$12$Wa3t7MiVKdkqDEEG9qDoQe/zzG.KZWrtJXqZceQz1pQfCZpepMFpW', '2023-04-11 13:52:09', '', 1, NULL, 1, 1, '2022-07-30 10:21:36', 1, '2023-04-11 08:22:11'),
(4, 4, 'admin@bsa.co.in', '$2y$12$t5vtFfiRxA5XRDt1pRsuMeuCI.LY8iyf8UfGmKo.dTT/tBDcU1RhG', '2023-03-15 08:25:14', '', 1, NULL, 1, 2, '2023-03-06 10:43:13', 2, '2023-04-10 08:26:23'),
(5, 5, 'ABC@C.COM', '$2y$12$L68CkMI9aRpJAPr0xF1U5OtccfUZeM8mZTNlMSO0EOiSnvEQDol5i', NULL, '', 0, NULL, 1, 2, '2023-03-06 14:52:00', 2, '2023-04-10 07:34:21'),
(6, 6, 'admin@rsprabhu.com', '$2y$12$HGYJzOJwyZ9y/HAtOPJpB.CHTJZtcNyzs0zxZBXVERK4NgsBD/962', '2023-03-17 13:35:32', '', 1, NULL, 1, 2, '2023-03-17 12:59:49', 2, '2023-04-10 07:40:59'),
(9, 14, 'hariom@bsa.co.in', '$2y$12$0/J4qkFhfIKSantY0MqI7OxRAOg2YF23K5jZ018hs8muPjWOGsyJG', '2023-04-13 07:03:29', '', 1, NULL, 1, 2, '2023-03-22 18:48:19', 1, '2023-04-13 05:04:30'),
(10, 15, 'pankajdk', '$2y$12$Re.mT4V3iIVH7MNDQ30LEeqYyOSymvCPxC.5AUfSKjt1ph3ew0aTS', '2023-03-25 08:13:48', '', 1, NULL, 1, 2, '2023-03-25 12:41:23', 2, '2023-03-25 07:17:31');

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `user_type_id` int(11) NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `sort_code` varchar(50) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`user_type_id`, `user_type`, `sort_code`, `sort_order`, `is_active`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 'Admin', '', 0, 1, 0, '2022-07-25 12:14:11', 0, '2023-04-10 07:32:10'),
(2, 'Lawyer ', '', 0, 1, 0, '2022-07-25 12:14:18', 0, '2023-04-10 07:32:10'),
(3, 'Clerk', '', 0, 1, 0, '2022-08-19 10:33:21', 0, '2023-04-10 07:32:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `case_daily_expenses`
--
ALTER TABLE `case_daily_expenses`
  ADD PRIMARY KEY (`case_daily_expense_id`);

--
-- Indexes for table `case_lawyer`
--
ALTER TABLE `case_lawyer`
  ADD PRIMARY KEY (`case_lawyer_id`);

--
-- Indexes for table `case_master`
--
ALTER TABLE `case_master`
  ADD PRIMARY KEY (`case_id`);

--
-- Indexes for table `cause_list`
--
ALTER TABLE `cause_list`
  ADD PRIMARY KEY (`cause_list_id`);

--
-- Indexes for table `cause_list_detail`
--
ALTER TABLE `cause_list_detail`
  ADD PRIMARY KEY (`cause_list_detail_id`);

--
-- Indexes for table `client_code`
--
ALTER TABLE `client_code`
  ADD PRIMARY KEY (`client_code_id`);

--
-- Indexes for table `court`
--
ALTER TABLE `court`
  ADD PRIMARY KEY (`court_id`);

--
-- Indexes for table `fee_master`
--
ALTER TABLE `fee_master`
  ADD PRIMARY KEY (`fee_master_id`),
  ADD KEY `client_code_id` (`client_code_id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `file_master`
--
ALTER TABLE `file_master`
  ADD PRIMARY KEY (`file_id`);

--
-- Indexes for table `invoice_master`
--
ALTER TABLE `invoice_master`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `justice`
--
ALTER TABLE `justice`
  ADD PRIMARY KEY (`justice_id`);

--
-- Indexes for table `lawyer_advisory`
--
ALTER TABLE `lawyer_advisory`
  ADD PRIMARY KEY (`lawyer_advisory_id`);

--
-- Indexes for table `lawyer_fee`
--
ALTER TABLE `lawyer_fee`
  ADD PRIMARY KEY (`lawyer_fee_id`);

--
-- Indexes for table `task_type`
--
ALTER TABLE `task_type`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `time_sheet`
--
ALTER TABLE `time_sheet`
  ADD PRIMARY KEY (`time_sheet_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_designation`
--
ALTER TABLE `user_designation`
  ADD PRIMARY KEY (`user_designation_id`);

--
-- Indexes for table `user_login`
--
ALTER TABLE `user_login`
  ADD PRIMARY KEY (`user_login_id`),
  ADD KEY `username_password_active` (`username`,`password`,`is_active`),
  ADD KEY `username_active` (`username`,`is_active`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`user_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `case_daily_expenses`
--
ALTER TABLE `case_daily_expenses`
  MODIFY `case_daily_expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `case_lawyer`
--
ALTER TABLE `case_lawyer`
  MODIFY `case_lawyer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `case_master`
--
ALTER TABLE `case_master`
  MODIFY `case_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cause_list`
--
ALTER TABLE `cause_list`
  MODIFY `cause_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cause_list_detail`
--
ALTER TABLE `cause_list_detail`
  MODIFY `cause_list_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `client_code`
--
ALTER TABLE `client_code`
  MODIFY `client_code_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `court`
--
ALTER TABLE `court`
  MODIFY `court_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `fee_master`
--
ALTER TABLE `fee_master`
  MODIFY `fee_master_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `file_master`
--
ALTER TABLE `file_master`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoice_master`
--
ALTER TABLE `invoice_master`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `justice`
--
ALTER TABLE `justice`
  MODIFY `justice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lawyer_advisory`
--
ALTER TABLE `lawyer_advisory`
  MODIFY `lawyer_advisory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lawyer_fee`
--
ALTER TABLE `lawyer_fee`
  MODIFY `lawyer_fee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `task_type`
--
ALTER TABLE `task_type`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `time_sheet`
--
ALTER TABLE `time_sheet`
  MODIFY `time_sheet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_designation`
--
ALTER TABLE `user_designation`
  MODIFY `user_designation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
  MODIFY `user_login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
