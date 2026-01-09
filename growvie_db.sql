-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 09, 2026 at 07:21 AM
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
-- Database: `growvie_db`
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
('A002', 'Maintenance Notice', 'The Growvie system will undergo maintenance this weekend.', '2025-12-14', 'Scheduled', '2025-12-30'),
('A003', 'Eco-Challenge Winners', 'Congratulations to our top players for completing quests!', '2025-12-14', 'Scheduled', '2026-01-15');

-- --------------------------------------------------------

--
-- Table structure for table `announcement_read`
--

DROP TABLE IF EXISTS `announcement_read`;
CREATE TABLE IF NOT EXISTS `announcement_read` (
  `announcement_read_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `announcement_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`announcement_read_id`),
  KEY `user_id` (`user_id`),
  KEY `announcement_id` (`announcement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('FS001', 'USR001', 'USR004', 'Accepted', '2025-12-04'),
('FS002', 'USR005', 'USR002', 'Pending', '2026-01-01'),
('FS003', 'USR001', 'USR006', 'Accepted', '2024-12-10'),
('FS004', 'USR001', 'USR007', 'Accepted', '2024-12-12'),
('FS005', 'USR001', 'USR008', 'Pending', '2025-01-03'),
('FS006', 'USR006', 'USR009', 'Accepted', '2024-12-15'),
('FS007', 'USR007', 'USR010', 'Accepted', '2024-12-18'),
('FS008', 'USR008', 'USR011', 'Pending', '2025-01-05'),
('FS009', 'USR012', 'USR001', 'Pending', '2025-01-06'),
('FS010', 'USR009', 'USR013', 'Accepted', '2024-12-20'),
('FS011', 'USR010', 'USR014', 'Accepted', '2024-12-22'),
('FS012', 'USR015', 'USR007', 'Pending', '2025-01-07');

-- --------------------------------------------------------

--
-- Table structure for table `partner`
--

DROP TABLE IF EXISTS `partner`;
CREATE TABLE IF NOT EXISTS `partner` (
  `partner_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `organization_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `contact_email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `partner_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partner`
--

INSERT INTO `partner` (`partner_id`, `organization_name`, `contact_email`, `description`, `partner_status`) VALUES
('PO001', 'Green Earth Org', 'contact@greenearth.org', 'NGO focused on environmental awareness', 'Active'),
('PO002', 'EcoTech Solutions', 'info@ecotech.com', 'Company providing eco-friendly tech', 'Active'),
('PO003', 'Sustainable Future', 'hello@sustainablefut.com', 'Promotes sustainable living initiatives', 'Active'),
('PO004', 'Clean Planet Partners', 'support@cleanplanet.org', 'Partners with schools for green projects', 'Active'),
('PO005', 'TreeMates Foundation', 'contact@treemates.org', 'Focused on tree planting and reforestation', 'Active');

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
('Q002', 'Bring Your Own Bag', 'Use a reusable shopping bag instead of plastic.', '🛍️', 1, 15, 'Waste', 'USR001', 'Active', '2025-12-14'),
('Q003', 'Zero-Waste Lunch', 'Prepare a lunch using reusable containers, cutlery, and cloth wraps.', '🍱', 2, 25, 'Waste Reduction', 'USR001', 'Active', '2025-12-14'),
('Q004', 'Green Commute', 'Walk, cycle, or take public transport instead of driving.', '🚲', 2, 30, 'Sustainable Transport', 'USR001', 'Active', '2025-12-14'),
('Q005', 'Repair & Reuse', 'Fix a small item (stitch clothes, glue a broken piece, etc.)', '♻️', 3, 40, 'Waste Reduction', 'USR001', 'Active', '2025-12-14'),
('Q006', 'new quest test!!!', 'ajhakshdlsj', '🎯', 50, 50, 'Community', 'USR001', 'Inactive', '2026-01-24'),
('Q007', 'test', 'qweqwe', '🎯', 0, 0, 'Community', 'USR001', 'Inactive', '2026-01-06'),
('Q008', 'inactive quest test', 'zzHJAAAAAAAAAAA', '🎯', 69, 69, 'Community', 'USR001', 'Active', '2026-01-29');

-- --------------------------------------------------------

--
-- Table structure for table `quest_submission`
--

DROP TABLE IF EXISTS `quest_submission`;
CREATE TABLE IF NOT EXISTS `quest_submission` (
  `submission_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `quest_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `proof_code` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `quest_submission_description` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `approval_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `submitted_at` date NOT NULL,
  PRIMARY KEY (`submission_id`),
  KEY `quest_id` (`quest_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quest_submission`
--

INSERT INTO `quest_submission` (`submission_id`, `quest_id`, `user_id`, `proof_code`, `quest_submission_description`, `approval_status`, `submitted_at`) VALUES
('QS001', 'Q001', 'USR002', 'QS001.png', 'Drank water out of this bad boy', 'Pending', '2025-11-14'),
('QS002', 'Q001', 'USR002', 'QS002.png', 'i think my bottle is my emotional support', 'Pending', '2025-11-20'),
('QS003', 'Q001', 'USR002', 'QS003.png', 'Switch to reusable bottles today!', 'Pending', '2025-11-29'),
('QS004', 'Q002', 'USR004', 'QS004.png', '#notaperformativemale', 'Pending', '2025-11-15'),
('QS005', 'Q002', 'USR004', 'QS005.png', 'tote bags for life ', 'Pending', '2025-11-28'),
('QS006', 'Q001', 'USR002', 'QS006.png', 'Used my reusable bottle throughout the day on campus.', 'Pending', '2025-12-10'),
('QS007', 'Q001', 'USR006', 'QS007.png', 'Brought my own bottle to the gym instead of buying drinks.', 'Pending', '2025-12-11'),
('QS008', 'Q002', 'USR007', 'QS008.png', 'Used a reusable bag while grocery shopping.', 'Pending', '2025-12-12'),
('QS009', 'Q002', 'USR008', 'QS009.png', 'Carried my own tote bag to the convenience store.', 'Pending', '2025-12-13'),
('QS010', 'Q003', 'USR009', 'QS010.png', 'Prepared lunch using reusable containers and cutlery.', 'Pending', '2025-12-11'),
('QS011', 'Q003', 'USR010', 'QS011.png', 'Packed homemade lunch in reusable boxes.', 'Pending', '2025-12-12'),
('QS012', 'Q004', 'USR011', 'QS012.png', 'Took public transport instead of driving.', 'Pending', '2025-12-13'),
('QS013', 'Q004', 'USR006', 'QS013.png', 'Cycled to class instead of using a car.', 'Pending', '2025-12-14'),
('QS014', 'Q005', 'USR012', 'QS014.png', 'Repaired a torn backpack instead of buying a new one.', 'Pending', '2025-12-14'),
('QS015', 'Q005', 'USR007', 'QS015.png', 'Fixed a broken phone cable using tape and heat shrink.', 'Pending', '2025-12-14');

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
('QSV012', 'USR001', 'QS010', 1, 0, '2025-12-11'),
('QSV013', 'USR011', 'QS010', 1, 0, '2025-12-11'),
('QSV014', 'USR006', 'QS011', 0, 1, '2025-12-12'),
('QSV015', 'USR007', 'QS012', 1, 0, '2025-12-13'),
('QSV016', 'USR008', 'QS012', 1, 0, '2025-12-13'),
('QSV017', 'USR002', 'QS013', 1, 0, '2025-12-14'),
('QSV018', 'USR009', 'QS014', 1, 0, '2025-12-14'),
('QSV019', 'USR010', 'QS014', 1, 0, '2025-12-14'),
('QSV020', 'USR011', 'QS015', 0, 1, '2025-12-14');

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
  `photo_code` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_reported` date NOT NULL,
  `request_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`real_tree_id`),
  KEY `virtual_plant_id` (`virtual_plant_id`),
  KEY `partner_id` (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `real_tree_record`
--

INSERT INTO `real_tree_record` (`real_tree_id`, `virtual_plant_id`, `partner_id`, `location`, `coordinates`, `planting_site`, `photo_code`, `date_reported`, `request_status`) VALUES
('RT001', 'VP011', 'PO001', 'Sabah, Malaysia', '5.978°N, 116.075°E', 'Hill A', 'RT001.png', '2025-12-14', 'Pending'),
('RT002', 'VP008', 'PO002', 'Penang, Malaysia', '5.4164°N, 100.3327°E', 'Forest B', 'RT002.png', '2025-12-14', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `shop_item`
--

DROP TABLE IF EXISTS `shop_item`;
CREATE TABLE IF NOT EXISTS `shop_item` (
  `item_id` varchar(6) COLLATE utf8mb4_general_ci NOT NULL,
  `item_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `item_desc` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `item_price` int NOT NULL,
  `item_category` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `item_availability` tinyint(1) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop_item`
--

INSERT INTO `shop_item` (`item_id`, `item_name`, `item_desc`, `item_price`, `item_category`, `item_availability`) VALUES
('ITM001', 'Fern Seed Pack', 'Pack of fern seeds to grow a sprout', 1000, 'Plant Seeds', 1),
('ITM002', 'Tulip Seed Pack', 'Pack of tulip seeds for blooming plants', 1500, 'Plant Seeds', 1),
('ITM003', 'Water Booster', 'Instantly adds 5 water drops to a plant', 2000, 'Power Ups', 1),
('ITM004', 'Double Coins', 'Earn double eco-coins for 1 hour', 2500, 'Power Ups', 1),
('ITM005', 'Extra Drops Pack', 'Buy 10 extra water drops', 3000, 'In App Purchases', 1),
('ITM006', 'VIP Pass', 'Unlock special quests and rewards', 50000, 'In App Purchases', 0);

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
  `date_joined` date NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `name`, `email`, `password`, `role`, `date_joined`) VALUES
('USR001', 'jamalchong_123', 'Jamal Chong', 'jamalchong@gmail.com', 'password1\r\n', 'Admin', '2025-12-11'),
('USR002', 'leexiaoming88', 'Lee Xiao Ming', 'leexiaoming@gmail.com', 'password2', 'Player', '2025-12-11'),
('USR003', 'muhammad_ali', 'Mhd Ali', 'muhammadali@gmail.com', 'password3', 'Player', '2025-12-11'),
('USR004', 'huangxiaoli5', 'Xiao Li', 'huangxiaoli@gmail.com', 'password4', 'Player', '2025-12-11'),
('USR005', 'suibian123', 'Submarine', 'suibian@gmail.com', 'password5', 'Player', '2025-12-11'),
('USR006', 'eco_ella', 'Ella Green', 'ella.green@testmail.com', 'password123', 'player', '2024-09-01'),
('USR007', 'planet_jay', 'Jay Walker', 'jay.walker@testmail.com', 'password123', 'player', '2024-09-03'),
('USR008', 'leafy_mia', 'Mia Tan', 'mia.tan@testmail.com', 'password123', 'player', '2024-09-05'),
('USR009', 'green_noah', 'Noah Lim', 'noah.lim@testmail.com', 'password123', 'player', '2024-09-08'),
('USR010', 'eco_sara', 'Sara Lee', 'sara.lee@testmail.com', 'password123', 'player', '2024-09-10'),
('USR011', 'sprout_kai', 'Kai Wong', 'kai.wong@testmail.com', 'password123', 'player', '2024-09-12'),
('USR012', 'tree_aisyah', 'Aisyah Rahman', 'aisyah.rahman@testmail.com', 'password123', 'player', '2024-09-15'),
('USR013', 'eco_adam', 'Adam Zainal', 'adam.zainal@testmail.com', 'password123', 'player', '2024-09-18'),
('USR014', 'green_luna', 'Luna Cheong', 'luna.cheong@testmail.com', 'password123', 'player', '2024-09-20'),
('USR015', 'earth_dan', 'Daniel Ho', 'daniel.ho@testmail.com', 'password123', 'player', '2024-09-22'),
('USR016', 'greenearthorg', 'Green Earth Org', 'contact@greenearth.org', 'partner1', 'Partner', '2024-08-31'),
('USR017', 'ecotechsolutions', 'EcoTech Solutions', 'info@ecotech.com', 'partner2', 'Partner', '2024-08-31'),
('USR018', 'sustainablesolutions', 'Sustainable Future', 'hello@sustainablefut.com', 'partner3', 'Partner', '2024-08-31'),
('USR019', 'cleanplanetpartners', 'Clean Planet Partners', 'support@cleanplanet.org', 'partner4', 'Partner', '2024-08-31'),
('USR020', 'treematesfoundation', 'TreeMates Foundation', 'contact@treemates.org', 'partner5', 'Partner', '2024-08-31');

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
('UP001', 'USR002', 1, 12365, 1273, 52, 0, 5, 'Active'),
('UP002', 'USR003', 2, 678, 200, 100, 0, 10, 'Active'),
('UP003', 'USR004', 3, 90, 300, 150, 0, 20, 'Active'),
('UP004', 'USR005', 1, 120, 8, 5, 0, 6, 'Active'),
('UP005', 'USR006', 2, 355, 16, 19, 0, 14, 'Active'),
('UP006', 'USR007', 1, 90, 4, 3, 0, 4, 'Active'),
('UP007', 'USR008', 3, 680, 25, 32, 0, 20, 'Active'),
('UP008', 'USR009', 2, 410, 18, 21, 0, 14, 'Active'),
('UP009', 'USR010', 1, 150, 6, 7, 1, 9, 'Active'),
('UP010', 'USR011', 3, 720, 30, 40, 0, 23, 'Active'),
('UP011', 'USR012', 2, 390, 16, 19, 0, 12, 'Active'),
('UP012', 'USR013', 1, 110, 5, 6, 1, 2, 'Active'),
('UP013', 'USR014', 2, 460, 20, 24, 0, 11, 'Active'),
('UP014', 'USR015', 1, 0, 0, 0, 0, 0, 'Suspended');

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
('PUR004', 'USR005', 'ITM003', '2025-12-14');

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
('VP001', 'USR002', 'P001', 5, 2012, '2025-12-14', 0),
('VP002', 'USR002', 'P001', 4, 1780, '2025-11-15', 0),
('VP003', 'USR004', 'P002', 2, 1300, '2025-12-14', 0),
('VP004', 'USR005', 'P005', 1, 800, '2025-12-14', 0),
('VP005', 'USR006', 'P001', 2, 900, '2025-12-13', 0),
('VP006', 'USR007', 'P003', 3, 1850, '2025-12-12', 0),
('VP007', 'USR008', 'P004', 4, 4000, '2025-12-10', 0),
('VP008', 'USR009', 'P002', 5, 2500, '2025-11-20', 1),
('VP009', 'USR010', 'P001', 1, 600, '2025-12-14', 0),
('VP010', 'USR011', 'P005', 3, 2800, '2025-12-11', 0),
('VP011', 'USR012', 'P004', 5, 4100, '2025-11-18', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcement_read`
--
ALTER TABLE `announcement_read`
  ADD CONSTRAINT `announcement_read_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `announcement_read_ibfk_2` FOREIGN KEY (`announcement_id`) REFERENCES `announcement` (`announcement_id`) ON UPDATE CASCADE;

--
-- Constraints for table `friend`
--
ALTER TABLE `friend`
  ADD CONSTRAINT `friend_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `friend_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `quest_submission_vote_ibfk_2` FOREIGN KEY (`submission_id`) REFERENCES `quest_submission` (`submission_id`) ON UPDATE CASCADE;

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
