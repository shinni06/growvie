-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 10, 2026 at 05:48 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `growvie`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

DROP TABLE IF EXISTS `announcement`;
CREATE TABLE IF NOT EXISTS `announcement` (
  `announcement_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `announce_title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `announce_body` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `announce_created_at` date NOT NULL,
  `announce_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `announce_schedule_date` date NOT NULL,
  PRIMARY KEY (`announcement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`announcement_id`, `announce_title`, `announce_body`, `announce_created_at`, `announce_status`, `announce_schedule_date`) VALUES
('A001', 'New Plant Quest Launch', 'We are excited to launch the new Blooming Tulip quest!', '2025-12-14', 'Published', '2025-12-14'),
('A002', 'Maintenance Notice', 'The Growvie system will undergo maintenance this weekend.', '2025-12-14', 'Published', '2025-12-30'),
('A003', 'Eco-Challenge Winners', 'Congratulations to our top players for completing quests!', '2025-12-14', 'Scheduled', '2026-01-15');

-- --------------------------------------------------------

--
-- Table structure for table `friend`
--

DROP TABLE IF EXISTS `friend`;
CREATE TABLE IF NOT EXISTS `friend` (
  `friendship_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `friend_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `friendship_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `date_added` date NOT NULL,
  PRIMARY KEY (`friendship_id`),
  KEY `user_id` (`user_id`),
  KEY `friend_id` (`friend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friend`
--

INSERT INTO `friend` (`friendship_id`, `user_id`, `friend_id`, `friendship_status`, `date_added`) VALUES
('FS001', 'USR002', 'USR004', 'Accepted', '2025-12-04'),
('FS002', 'USR005', 'USR002', 'Pending', '2026-01-01'),
('FS003', 'USR002', 'USR006', 'Accepted', '2024-12-10'),
('FS004', 'USR002', 'USR007', 'Accepted', '2024-12-12'),
('FS005', 'USR002', 'USR008', 'Pending', '2025-01-03'),
('FS006', 'USR006', 'USR009', 'Accepted', '2024-12-15'),
('FS007', 'USR007', 'USR010', 'Accepted', '2024-12-18'),
('FS008', 'USR008', 'USR011', 'Pending', '2025-01-05'),
('FS009', 'USR012', 'USR001', 'Pending', '2025-01-06'),
('FS010', 'USR009', 'USR013', 'Accepted', '2024-12-20'),
('FS011', 'USR010', 'USR014', 'Accepted', '2024-12-22'),
('FS012', 'USR015', 'USR007', 'Pending', '2025-01-07'),
('FS014', 'USR002', 'USR012', 'Pending', '2026-01-10');

-- --------------------------------------------------------

--
-- Table structure for table `partner`
--

DROP TABLE IF EXISTS `partner`;
CREATE TABLE IF NOT EXISTS `partner` (
  `partner_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `organization_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `contact_email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `partner_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`partner_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partner`
--

INSERT INTO `partner` (`partner_id`, `user_id`, `organization_name`, `contact_email`, `description`, `partner_status`) VALUES
('PO001', 'USR016', 'Green Earth Org', 'contact@greenearth.org', 'NGO focused on environmental awareness', 'Active'),
('PO002', 'USR017', 'EcoTech Solutions', 'info@ecotech.com', 'Company providing eco-friendly tech', 'Active'),
('PO003', 'USR018', 'Sustainable Future', 'hello@sustainablefut.com', 'Promotes sustainable living initiatives', 'Active'),
('PO004', 'USR019', 'Clean Planet Partners', 'support@cleanplanet.org', 'Partners with schools for green projects', 'Active'),
('PO005', 'USR020', 'TreeMates Foundation', 'contact@treemates.org', 'Focused on tree planting and reforestation', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `plant`
--

DROP TABLE IF EXISTS `plant`;
CREATE TABLE IF NOT EXISTS `plant` (
  `plant_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `plant_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `plant_desc` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `drops_required` int NOT NULL,
  PRIMARY KEY (`plant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plant`
--

INSERT INTO `plant` (`plant_id`, `plant_name`, `plant_desc`, `drops_required`) VALUES
('P001', 'Sprouting Fern', 'A small fern that grows quickly', 2000),
('P002', 'Sunny Cactus', 'A hardy cactus that loves water drops', 2500),
('P003', 'Blooming Tulip', 'Tulip that blooms after enough care', 3000),
('P004', 'Mighty Oak', 'A tree that takes effort to grow', 5000),
('P005', 'Cherry Blossom', 'Delicate flowering plant', 4000);

-- --------------------------------------------------------

--
-- Table structure for table `plant_request`
--

DROP TABLE IF EXISTS `plant_request`;
CREATE TABLE IF NOT EXISTS `plant_request` (
  `plant_request_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `request_date` date NOT NULL,
  `request_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`plant_request_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plant_request`
--

INSERT INTO `plant_request` (`plant_request_id`, `user_id`, `request_date`, `request_status`) VALUES
('PR001', 'USR001', '2025-11-15', 'Approved'),
('PR002', 'USR009', '2025-11-25', 'Pending'),
('PR003', 'USR012', '2025-11-22', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `quest`
--

DROP TABLE IF EXISTS `quest`;
CREATE TABLE IF NOT EXISTS `quest` (
  `quest_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `quest_title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `quest_description` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `quest_emoji` varchar(2) COLLATE utf8mb4_general_ci NOT NULL,
  `drop_reward` int NOT NULL,
  `eco_coin_reward` int NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `date_created` date NOT NULL,
  PRIMARY KEY (`quest_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quest`
--

INSERT INTO `quest` (`quest_id`, `quest_title`, `quest_description`, `quest_emoji`, `drop_reward`, `eco_coin_reward`, `category`, `created_by`, `status`, `date_created`) VALUES
('Q001', 'Bottle Buddy', 'Take a photo of your reusable bottle during your day.', '🥤', 1, 10, 'Waste Reduction', 'USR001', 'Active', '2025-12-14'),
('Q002', 'Bring Your Own Bag', 'Use a reusable shopping bag instead of plastic.', '🛍️', 1, 15, 'Waste Reduction', 'USR001', 'Active', '2025-12-14'),
('Q003', 'Zero-Waste Lunch', 'Prepare a lunch using reusable containers, cutlery, and cloth wraps.', '🍱', 2, 25, 'Waste Reduction', 'USR001', 'Active', '2025-12-14'),
('Q004', 'Green Commute', 'Walk, cycle, or take public transport instead of driving.', '🚲', 2, 30, 'Energy & Transport', 'USR001', 'Active', '2025-12-14'),
('Q005', 'Repair & Reuse', 'Fix a small item (stitch clothes, glue a broken piece, etc.)', '♻️', 3, 40, 'Sustainable Living', 'USR001', 'Active', '2025-12-14'),
('Q006', 'Meatless Monday', 'Eat a vegetarian meal. Upload a pic of your greens!', '🥗', 3, 50, 'Sustainable Living', 'USR001', 'Inactive', '2026-01-31'),
('Q007', 'Trash Tag', 'Pick up litter in your neighborhood. Show us the bag.', '🚮', 5, 100, 'Community & Nature', 'USR001', 'Inactive', '2026-01-31'),
('Q008', 'Thrift Haul', 'Buy something second-hand instead of new.', '👕', 4, 80, 'Sustainable Living', 'USR001', 'Inactive', '2026-01-31'),
('Q009', 'Step It Up', 'Take the stairs instead of the elevator. Snap a selfie of you walking up!', '🚶', 2, 20, 'Energy & Transport', 'USR001', 'Inactive', '2026-01-31'),
('Q010', 'Second Life', 'Donate clothes or books to a charity/community box.', '❤️', 4, 70, 'Community & Nature', 'USR001', 'Inactive', '2026-01-31');

-- --------------------------------------------------------

--
-- Table structure for table `quest_submission`
--

DROP TABLE IF EXISTS `quest_submission`;
CREATE TABLE IF NOT EXISTS `quest_submission` (
  `submission_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `quest_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `quest_submission_description` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `proof_code` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `approval_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `submitted_at` date NOT NULL,
  PRIMARY KEY (`submission_id`),
  KEY `quest_id` (`quest_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quest_submission`
--

INSERT INTO `quest_submission` (`submission_id`, `quest_id`, `user_id`, `quest_submission_description`, `proof_code`, `approval_status`, `submitted_at`) VALUES
('QS001', 'Q001', 'USR002', 'Drank water out of this bad boy', 'QS001.png', 'Pending', '2025-11-14'),
('QS002', 'Q001', 'USR002', 'i think my bottle is my emotional support', 'QS002.png', 'Pending', '2025-11-20'),
('QS003', 'Q001', 'USR002', 'Switch to reusable bottles today!', 'QS003.png', 'Approved', '2025-11-29'),
('QS004', 'Q002', 'USR004', '#notaperformativemale', 'QS004.png', 'Pending', '2025-11-15'),
('QS005', 'Q002', 'USR004', 'tote bags for life ', 'QS005.png', 'Approved', '2025-11-28'),
('QS006', 'Q001', 'USR002', 'Used my reusable bottle throughout the day on campus.', 'QS006.png', 'Approved', '2025-12-10'),
('QS007', 'Q001', 'USR006', 'Brought my own bottle to the gym instead of buying drinks.', 'QS007.png', 'Approved', '2025-12-11'),
('QS008', 'Q002', 'USR007', 'Used a reusable bag while grocery shopping.', 'QS008.png', 'Approved', '2025-12-12'),
('QS009', 'Q002', 'USR008', 'Carried my own tote bag to the convenience store.', 'QS009.png', 'Approved', '2025-12-13'),
('QS010', 'Q003', 'USR009', 'Prepared lunch using reusable containers and cutlery.', 'QS010.png', 'Approved', '2025-12-11'),
('QS011', 'Q003', 'USR010', 'Packed homemade lunch in reusable boxes.', 'QS011.png', 'Approved', '2025-12-12'),
('QS012', 'Q004', 'USR011', 'Took public transport instead of driving.', 'QS012.png', 'Approved', '2025-12-13'),
('QS013', 'Q004', 'USR006', 'Cycled to class instead of using a car.', 'QS013.png', 'Pending', '2025-12-14'),
('QS014', 'Q005', 'USR012', 'Repaired a torn backpack instead of buying a new one.', 'QS014.png', 'Pending', '2025-12-14'),
('QS015', 'Q005', 'USR007', 'Fixed a broken phone cable using tape and heat shrink.', 'QS015.png', 'Pending', '2025-12-14'),
('QS016', 'Q001', 'USR021', 'Always carrying my bottle!', 'QS016.png', 'Approved', '2025-10-18'),
('QS017', 'Q002', 'USR022', 'Grocery run with tote bag', 'QS017.png', 'Approved', '2025-11-12'),
('QS018', 'Q001', 'USR023', 'Hydrated and sustainable.', 'QS018.png', 'Approved', '2025-12-08'),
('QS019', 'Q004', 'USR022', 'Took the bus to work today.', 'QS019.png', 'Approved', '2025-11-18'),
('QS020', 'Q003', 'USR024', 'Packed lunch, no plastic!', 'QS020.png', 'Pending', '2026-01-05'),
('QS021', 'Q005', 'USR025', 'Fixed my torn shirt.', 'QS021.png', 'Pending', '2026-01-09'),
('QS022', 'Q001', 'USR026', 'refilled my bottle at the gym', 'QS022.png', 'Pending', '2026-01-02'),
('QS023', 'Q002', 'USR026', 'Grocery shopping with reusable tote!', 'QS023.png', 'Pending', '2026-01-03'),
('QS024', 'Q001', 'USR027', 'no plastic used today', 'QS024.png', 'Pending', '2026-01-04'),
('QS025', 'Q004', 'USR028', 'Cycled to the office instead of taking a cab.', 'QS025.png', 'Pending', '2026-01-05'),
('QS026', 'Q005', 'USR028', 'Repaired the strap on my briefcase.', 'QS026.png', 'Pending', '2026-01-06'),
('QS027', 'Q001', 'USR030', 'Hydrated. You’re welcome.', 'QS027.png', 'Pending', '2026-01-09'),
('QS028', 'Q003', 'USR030', 'Salad in a glass container', 'QS028.png', 'Pending', '2026-01-09'),
('QS029', 'Q002', 'USR022', 'brought my own bag', 'QS029.png', 'Pending', '2026-01-08'),
('QS030', 'Q004', 'USR023', 'Took the bus.', 'QS030.png', 'Pending', '2026-01-07'),
('QS031', 'Q001', 'USR025', 'refilled', 'QS031.png', 'Pending', '2026-01-08');

-- --------------------------------------------------------

--
-- Table structure for table `quest_submission_vote`
--

DROP TABLE IF EXISTS `quest_submission_vote`;
CREATE TABLE IF NOT EXISTS `quest_submission_vote` (
  `vote_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `submission_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `upvote_status` tinyint(1) NOT NULL,
  `downvote_status` tinyint(1) NOT NULL,
  `vote_timestamp` date NOT NULL,
  PRIMARY KEY (`vote_id`),
  KEY `user_id` (`user_id`),
  KEY `submission_id` (`submission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quest_submission_vote`
--

INSERT INTO `quest_submission_vote` (`vote_id`, `user_id`, `submission_id`, `upvote_status`, `downvote_status`, `vote_timestamp`) VALUES
('QSV001', 'USR004', 'QS001', 1, 0, '2025-12-14'),
('QSV002', 'USR005', 'QS001', 1, 0, '2025-12-14'),
('QSV003', 'USR005', 'QS002', 1, 0, '2025-12-14'),
('QSV004', 'USR002', 'QS004', 1, 0, '2025-12-14'),
('QSV005', 'USR006', 'QS006', 1, 0, '2025-12-10'),
('QSV006', 'USR007', 'QS006', 1, 0, '2025-12-10'),
('QSV007', 'USR002', 'QS007', 1, 0, '2025-12-11'),
('QSV008', 'USR008', 'QS007', 0, 1, '2025-12-11'),
('QSV009', 'USR009', 'QS008', 1, 0, '2025-12-12'),
('QSV010', 'USR006', 'QS009', 1, 0, '2025-12-13'),
('QSV011', 'USR010', 'QS009', 1, 0, '2025-12-13'),
('QSV012', 'USR002', 'QS010', 1, 0, '2025-12-11'),
('QSV013', 'USR011', 'QS010', 1, 0, '2025-12-11'),
('QSV014', 'USR006', 'QS011', 0, 1, '2025-12-12'),
('QSV015', 'USR007', 'QS012', 1, 0, '2025-12-13'),
('QSV016', 'USR008', 'QS012', 1, 0, '2025-12-13'),
('QSV017', 'USR002', 'QS013', 1, 0, '2025-12-14'),
('QSV018', 'USR009', 'QS014', 1, 0, '2025-12-14'),
('QSV019', 'USR010', 'QS014', 1, 0, '2025-12-14'),
('QSV020', 'USR011', 'QS015', 0, 1, '2025-12-14'),
('QSV021', 'USR002', 'QS016', 1, 0, '2026-01-10'),
('QSV022', 'USR002', 'QS019', 0, 1, '2026-01-10');

-- --------------------------------------------------------

--
-- Table structure for table `real_tree_record`
--

DROP TABLE IF EXISTS `real_tree_record`;
CREATE TABLE IF NOT EXISTS `real_tree_record` (
  `real_tree_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `virtual_plant_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `partner_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `location` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `coordinates` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `planting_site` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `date_reported` date NOT NULL,
  `request_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`real_tree_id`),
  KEY `virtual_plant_id` (`virtual_plant_id`),
  KEY `partner_id` (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `real_tree_record`
--

INSERT INTO `real_tree_record` (`real_tree_id`, `virtual_plant_id`, `partner_id`, `location`, `coordinates`, `planting_site`, `date_reported`, `request_status`) VALUES
('RT001', 'VP011', 'PO001', 'Sabah, Malaysia', '5.978°N, 116.075°E', 'Hill A', '2025-11-25', 'Approved'),
('RT002', 'VP008', 'PO002', 'Penang, Malaysia', '5.4164°N, 100.3327°E', 'Forest B', '2025-12-14', 'Approved'),
('RT003', 'VP013', 'PO003', 'Perak, Malaysia', '4.592°N, 101.090°E', 'Forest Reserve C', '2025-12-14', 'Approved'),
('RT004', 'VP015', 'PO001', 'Sarawak, Malaysia', '1.553°N, 110.359°E', 'National Park D', '2025-12-28', 'Approved'),
('RT005', 'VP003', 'PO002', 'Selangor, Malaysia', '3.0738°N, 101.5183°E', 'Shah Alam Community Park', '2025-12-30', 'Approved'),
('RT006', 'VP005', 'PO001', 'Pahang, Malaysia', '4.3820°N, 102.2400°E', 'Taman Negara Buffer Zone', '2026-01-03', 'Pending'),
('RT007', 'VP007', 'PO005', 'Sarawak, Malaysia', '1.6110°N, 110.1500°E', 'Kuching Wetlands', '2026-01-06', 'Pending'),
('RT008', 'VP010', 'PO003', 'Johor, Malaysia', '1.4854°N, 103.7618°E', 'Endau-Rompin Park', '2026-01-06', 'Pending'),
('RT009', 'VP019', 'PO004', 'Kedah, Malaysia', '6.1184°N, 100.3685°E', 'School Greening Project A', '2026-01-09', 'Pending'),
('RT010', 'VP020', 'PO002', 'Kuala Lumpur, Malaysia', '3.1390°N, 101.6869°E', 'Urban Rooftop Garden', '2026-01-11', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `shop_item`
--

DROP TABLE IF EXISTS `shop_item`;
CREATE TABLE IF NOT EXISTS `shop_item` (
  `item_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `item_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `item_desc` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `item_image_code` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `item_price` int NOT NULL,
  `item_category` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `item_availability` tinyint(1) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop_item`
--

INSERT INTO `shop_item` (`item_id`, `item_name`, `item_desc`, `item_image_code`, `item_price`, `item_category`, `item_availability`) VALUES
('ITM001', 'Fern Seed Pack', 'Pack of fern seeds to grow a sprout', 'plant_seeds.png', 1000, 'Plant Seeds', 1),
('ITM002', 'Tulip Seed Pack', 'Pack of tulip seeds for blooming plants', 'plant_seeds.png', 1500, 'Plant Seeds', 1),
('ITM003', 'Water Booster', 'Instantly adds 5 water drops to a plant', 'power_ups.png', 2000, 'Power Ups', 1),
('ITM004', 'Double Coins', 'Earn double eco-coins for 1 hour', 'power_ups.png', 2500, 'Power Ups', 1),
('ITM005', 'Extra Drops Pack', 'Buy 10 extra water drops', 'in_app_purchases.png', 30, 'In App Purchases', 1),
('ITM006', 'VIP Pass', 'Unlock special quests and rewards', 'in_app_purchases.png', 500, 'In App Purchases', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `profile_picture` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_joined` date NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `name`, `email`, `password`, `role`, `profile_picture`, `date_joined`) VALUES
('USR001', 'jamalchong_123', 'Jamal Chong', 'jamalchong@gmail.com', 'password123', 'Admin', 'default_profile_pic.jpeg', '2025-12-11'),
('USR002', 'leexiaoming88', 'Lee Xiao Ming', 'leexiaoming@gmail.com', 'password123', 'Player', 'default_profile_pic.jpeg', '2025-12-11'),
('USR003', 'muhammad_ali', 'Mhd Ali', 'muhammadali@gmail.com', 'password123', 'Player', 'default_profile_pic.jpeg', '2025-12-11'),
('USR004', 'huangxiaoli5', 'Xiao Li', 'huangxiaoli@gmail.com', 'password123', 'Player', 'default_profile_pic.jpeg', '2025-12-11'),
('USR005', 'suibian123', 'Submarine', 'suibian@gmail.com', 'password123', 'Player', 'default_profile_pic.jpeg', '2025-12-11'),
('USR006', 'eco_ella', 'Ella Green', 'ella.green@testmail.com', 'password123', 'player', 'default_profile_pic.jpeg', '2024-09-01'),
('USR007', 'planet_jay', 'Jay Walker', 'jay.walker@testmail.com', 'password123', 'player', 'default_profile_pic.jpeg', '2024-09-03'),
('USR008', 'leafy_mia', 'Mia Tan', 'mia.tan@testmail.com', 'password123', 'player', 'default_profile_pic.jpeg', '2024-09-05'),
('USR009', 'green_noah', 'Noah Lim', 'noah.lim@testmail.com', 'password123', 'player', 'default_profile_pic.jpeg', '2024-09-08'),
('USR010', 'eco_sara', 'Sara Lee', 'sara.lee@testmail.com', 'password123', 'player', 'default_profile_pic.jpeg', '2024-09-10'),
('USR011', 'sprout_kai', 'Kai Wong', 'kai.wong@testmail.com', 'password123', 'player', 'default_profile_pic.jpeg', '2024-09-12'),
('USR012', 'tree_aisyah', 'Aisyah Rahman', 'aisyah.rahman@testmail.com', 'password123', 'player', 'default_profile_pic.jpeg', '2024-09-15'),
('USR013', 'eco_adam', 'Adam Zainal', 'adam.zainal@testmail.com', 'password123', 'player', 'default_profile_pic.jpeg', '2024-09-18'),
('USR014', 'green_luna', 'Luna Cheong', 'luna.cheong@testmail.com', 'password123', 'player', 'default_profile_pic.jpeg', '2024-09-20'),
('USR015', 'earth_dan', 'Daniel Ho', 'daniel.ho@testmail.com', 'password123', 'player', 'default_profile_pic.jpeg', '2024-09-22'),
('USR016', 'GreenEarthOrg', 'Green Earth Org', 'contact@greenearth.org', 'password123', 'Partner', 'default_profile_pic.jpeg', '2025-12-03'),
('USR017', 'EcoTechSolutions', 'Eco Tech Solutions', 'info@ecotech.com', 'password123', 'Partner', 'default_profile_pic.jpeg', '2025-12-11'),
('USR018', 'cleanPlanetPartners', 'Clean Planet Partners', 'support@cleanplanet.org', 'password123', 'Partner', 'default_profile_pic.jpeg', '2025-12-03'),
('USR019', 'SustainableFuture', 'Sustainable Future', 'hello@sustainablefut.com', 'password123', 'Partner', 'default_profile_pic.jpeg', '2025-12-11'),
('USR020', 'TreeMates', 'Tree Mates Foundation', 'contact@treemates.org', 'password123', 'Partner', 'default_profile_pic.jpeg', '2025-12-03'),
('USR021', 'jungle_jim', 'Jim Morris', 'jim.morris@testmail.com', 'password123', 'Player', 'default_profile_pic.jpeg', '2025-10-12'),
('USR022', 'river_song', 'River Song', 'river.song@testmail.com', 'password123', 'Player', 'default_profile_pic.jpeg', '2025-11-05'),
('USR023', 'clara_oswald', 'Clara Oswald', 'clara.o@testmail.com', 'password123', 'Player', 'default_profile_pic.jpeg', '2025-12-01'),
('USR024', 'rwilliams', 'Rory Williams', 'rory.w@testmail.com', 'password123', 'Player', 'default_profile_pic.jpeg', '2026-01-04'),
('USR025', 'amy_pond', 'Amy Pond', 'amy.p@testmail.com', 'password123', 'Player', 'default_profile_pic.jpeg', '2026-01-08'),
('USR026', 'sarah_j', 'Sarah Jenkins', 'sarah.j@testmail.com', 'password123', 'Player', 'default_profile_pic.jpeg', '2026-01-02'),
('USR027', 'mike_ross', 'Mike Ross', 'mike.ross@testmail.com', 'password123', 'Player', 'default_profile_pic.jpeg', '2026-01-03'),
('USR028', 'harvey_spec', 'Harvey Specter', 'harvey.s@testmail.com', 'password123', 'Player', 'default_profile_pic.jpeg', '2026-01-05'),
('USR029', 'louis_litt', 'Louis Litt', 'louis.l@testmail.com', 'password123', 'Player', 'default_profile_pic.jpeg', '2026-01-07'),
('USR030', 'donna_paul', 'Donna Paulsen', 'donna.p@testmail.com', 'password123', 'Player', 'default_profile_pic.jpeg', '2026-01-09');

-- --------------------------------------------------------

--
-- Table structure for table `user_player`
--

DROP TABLE IF EXISTS `user_player`;
CREATE TABLE IF NOT EXISTS `user_player` (
  `user_player_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `player_tier` int NOT NULL,
  `eco_coins` int NOT NULL,
  `drops_progress` int NOT NULL,
  `total_quests_completed` int NOT NULL,
  `tree_planted_irl` int NOT NULL,
  `growvie_plants_planted` int NOT NULL,
  `player_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`user_player_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_player`
--

INSERT INTO `user_player` (`user_player_id`, `user_id`, `player_tier`, `eco_coins`, `drops_progress`, `total_quests_completed`, `tree_planted_irl`, `growvie_plants_planted`, `player_status`) VALUES
('UP001', 'USR002', 1, 9465, 1177, 59, 0, 5, 'Active'),
('UP002', 'USR003', 2, 678, 200, 100, 0, 10, 'Active'),
('UP003', 'USR004', 3, 90, 600, 150, 0, 20, 'Active'),
('UP004', 'USR005', 1, 120, 8, 5, 0, 6, 'Active'),
('UP005', 'USR006', 2, 355, 16, 19, 0, 14, 'Active'),
('UP006', 'USR007', 1, 90, 4, 3, 0, 4, 'Active'),
('UP007', 'USR008', 3, 680, 25, 32, 1, 20, 'Active'),
('UP008', 'USR009', 2, 410, 18, 21, 2, 14, 'Active'),
('UP009', 'USR010', 1, 150, 6, 7, 1, 9, 'Active'),
('UP010', 'USR011', 3, 720, 30, 40, 0, 23, 'Active'),
('UP011', 'USR012', 2, 390, 16, 19, 4, 12, 'Active'),
('UP012', 'USR013', 1, 110, 5, 6, 1, 2, 'Active'),
('UP013', 'USR014', 2, 460, 20, 24, 0, 11, 'Active'),
('UP014', 'USR015', 1, 0, 0, 0, 0, 0, 'Active'),
('UP015', 'USR021', 1, 150, 20, 3, 0, 1, 'Active'),
('UP016', 'USR022', 2, 450, 120, 12, 1, 5, 'Active'),
('UP017', 'USR023', 1, 200, 45, 5, 0, 2, 'Active'),
('UP018', 'USR024', 1, 50, 10, 1, 0, 0, 'Active'),
('UP019', 'USR025', 2, 600, 200, 15, 3, 6, 'Active'),
('UP020', 'USR026', 1, 110, 11, 3, 0, 1, 'Active'),
('UP021', 'USR027', 1, 50, 5, 1, 0, 0, 'Active'),
('UP022', 'USR028', 2, 300, 50, 5, 0, 3, 'Active'),
('UP023', 'USR029', 1, 20, 0, 0, 0, 0, 'Active'),
('UP024', 'USR030', 2, 500, 100, 10, 0, 4, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `user_purchase`
--

DROP TABLE IF EXISTS `user_purchase`;
CREATE TABLE IF NOT EXISTS `user_purchase` (
  `purchase_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `item_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `purchase_at` date NOT NULL,
  PRIMARY KEY (`purchase_id`),
  KEY `user_id` (`user_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_purchase`
--

INSERT INTO `user_purchase` (`purchase_id`, `user_id`, `item_id`, `purchase_at`) VALUES
('PUR001', 'USR002', 'ITM001', '2025-12-14'),
('PUR002', 'USR004', 'ITM003', '2025-12-14'),
('PUR003', 'USR002', 'ITM005', '2025-12-14'),
('PUR004', 'USR005', 'ITM003', '2025-12-14'),
('PUR005', 'USR021', 'ITM006', '2025-10-20'),
('PUR006', 'USR022', 'ITM001', '2025-11-15'),
('PUR007', 'USR023', 'ITM005', '2025-12-10'),
('PUR008', 'USR024', 'ITM005', '2026-01-05'),
('PUR009', 'USR022', 'ITM006', '2025-11-20'),
('PUR010', 'USR025', 'ITM004', '2026-01-08'),
('PUR011', 'USR002', 'ITM001', '2026-01-10'),
('PUR012', 'USR002', 'ITM001', '2026-01-10'),
('PUR013', 'USR002', 'ITM001', '2026-01-10');

-- --------------------------------------------------------

--
-- Table structure for table `virtual_plant`
--

DROP TABLE IF EXISTS `virtual_plant`;
CREATE TABLE IF NOT EXISTS `virtual_plant` (
  `virtual_plant_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `plant_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `current_stage` int NOT NULL,
  `drops_used` int NOT NULL,
  `date_planted` date NOT NULL,
  `is_completed` tinyint(1) NOT NULL,
  PRIMARY KEY (`virtual_plant_id`),
  KEY `user_id` (`user_id`),
  KEY `plant_id` (`plant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `virtual_plant`
--

INSERT INTO `virtual_plant` (`virtual_plant_id`, `user_id`, `plant_id`, `current_stage`, `drops_used`, `date_planted`, `is_completed`) VALUES
('VP001', 'USR002', 'P001', 5, 2012, '2025-12-14', 1),
('VP002', 'USR002', 'P001', 0, 0, '2025-12-25', 0),
('VP003', 'USR004', 'P002', 5, 2500, '2025-12-14', 1),
('VP004', 'USR005', 'P005', 1, 800, '2025-12-14', 0),
('VP005', 'USR006', 'P001', 5, 2000, '2025-12-13', 1),
('VP006', 'USR007', 'P003', 3, 1850, '2025-12-12', 0),
('VP007', 'USR008', 'P004', 5, 5000, '2025-12-10', 1),
('VP008', 'USR009', 'P002', 5, 2500, '2025-11-20', 1),
('VP009', 'USR010', 'P001', 1, 600, '2025-12-14', 0),
('VP010', 'USR011', 'P005', 5, 4000, '2025-12-11', 1),
('VP011', 'USR012', 'P004', 5, 4100, '2025-11-18', 1),
('VP012', 'USR021', 'P001', 3, 1500, '2025-10-15', 0),
('VP013', 'USR022', 'P002', 5, 2500, '2025-11-10', 1),
('VP014', 'USR023', 'P003', 2, 1000, '2025-12-05', 0),
('VP015', 'USR025', 'P004', 5, 5000, '2026-01-05', 1),
('VP016', 'USR004', 'P005', 2, 1200, '2026-01-02', 0),
('VP017', 'USR008', 'P003', 1, 500, '2026-01-05', 0),
('VP018', 'USR011', 'P004', 3, 2500, '2026-01-01', 0),
('VP019', 'USR003', 'P001', 5, 2000, '2025-12-20', 1),
('VP020', 'USR030', 'P002', 5, 2500, '2026-01-08', 1),
('VP021', 'USR028', 'P004', 2, 1000, '2026-01-06', 0),
('VP022', 'USR026', 'P001', 1, 100, '2026-01-09', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `friend`
--
ALTER TABLE `friend`
  ADD CONSTRAINT `friend_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `friend_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `partner`
--
ALTER TABLE `partner`
  ADD CONSTRAINT `partner_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `plant_request`
--
ALTER TABLE `plant_request`
  ADD CONSTRAINT `plant_request_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `quest`
--
ALTER TABLE `quest`
  ADD CONSTRAINT `quest_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `quest_submission`
--
ALTER TABLE `quest_submission`
  ADD CONSTRAINT `quest_submission_ibfk_1` FOREIGN KEY (`quest_id`) REFERENCES `quest` (`quest_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `quest_submission_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `quest_submission_vote`
--
ALTER TABLE `quest_submission_vote`
  ADD CONSTRAINT `quest_submission_vote_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `quest_submission_vote_ibfk_2` FOREIGN KEY (`submission_id`) REFERENCES `quest_submission` (`submission_id`);

--
-- Constraints for table `real_tree_record`
--
ALTER TABLE `real_tree_record`
  ADD CONSTRAINT `real_tree_record_ibfk_1` FOREIGN KEY (`virtual_plant_id`) REFERENCES `virtual_plant` (`virtual_plant_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `real_tree_record_ibfk_2` FOREIGN KEY (`partner_id`) REFERENCES `partner` (`partner_id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_player`
--
ALTER TABLE `user_player`
  ADD CONSTRAINT `user_player_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_purchase`
--
ALTER TABLE `user_purchase`
  ADD CONSTRAINT `user_purchase_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_purchase_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `shop_item` (`item_id`) ON UPDATE CASCADE;

--
-- Constraints for table `virtual_plant`
--
ALTER TABLE `virtual_plant`
  ADD CONSTRAINT `virtual_plant_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `virtual_plant_ibfk_2` FOREIGN KEY (`plant_id`) REFERENCES `plant` (`plant_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
