-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 07, 2026 at 03:44 AM
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
-- Database: `visithunnasgiriya`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', '$2y$10$uLPnkzs8xFns0jezVfD5i.r9LRdbQ.MnG/RfxSUu67WNZJ.QVQ6yW');

-- --------------------------------------------------------

--
-- Table structure for table `attractions`
--

DROP TABLE IF EXISTS `attractions`;
CREATE TABLE IF NOT EXISTS `attractions` (
  `attraction_id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  `location` varchar(255) DEFAULT NULL,
  `opening_hours` varchar(100) DEFAULT NULL,
  `best_time_to_visit` varchar(150) DEFAULT NULL,
  `estimated_visit_duration` varchar(100) DEFAULT NULL,
  `distance_km` decimal(5,2) DEFAULT NULL,
  `travel_time` varchar(100) DEFAULT NULL,
  `google_maps_link` text,
  `road_condition` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`attraction_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attractions`
--

INSERT INTO `attractions` (`attraction_id`, `category_id`, `name`, `description`, `location`, `opening_hours`, `best_time_to_visit`, `estimated_visit_duration`, `distance_km`, `travel_time`, `google_maps_link`, `road_condition`, `created_at`) VALUES
(1, 1, 'Deanston Mini Worlds End', 'A scenic cliff viewpoint located inside the Knuckles Mountain Range.', 'Deanston, Hunnasgiriya', '6:00 AM - 6:00 PM', 'Morning', '1-2 hours', 7.80, '20 minutes', 'https://maps.app.goo.gl/aB391uUve925CLpV8', 'Road conditions may vary depending on weather.', '2026-08-14 17:02:14'),
(2, 1, 'Katahera Waterfall', 'A scenic hidden waterfall located in Hunnasgiriya and surrounded by lush greenery.', 'Hunnasgiriya', 'Open throughout the day', 'Morning or afternoon', '1 hour', 6.50, '15 minutes', 'https://maps.app.goo.gl/AB9DeKgmf82vf4ti6', 'Some sections may be slippery during rainy weather.', '2026-08-14 17:02:14'),
(3, 2, 'Keulgama Sri Dalada Gaman Wiharaya', 'An important historical cave temple with religious significance.', 'Keulgama', '6:00 AM - 6:00 PM', 'Morning', '1-2 hours', 17.00, '40 minutes', 'https://maps.app.goo.gl/3PDNxoGPYbPpa4BP8', 'Accessible by the main road.', '2026-08-14 17:02:14'),
(4, 1, 'Pitigoda Village', 'A small rural village surrounded by mountains and natural scenery.', 'Pitigoda, Hunnasgiriya', 'Open throughout the day', 'Morning', '2-3 hours', 11.00, '30 minutes', 'https://maps.app.goo.gl/rbucxoA4AZczA7fu5', 'Some rural roads may be narrow.', '2026-08-14 17:02:14'),
(5, 1, 'Wewatenna Camping Site', 'A scenic and remote wilderness location near Medamahanuwara.', 'Wewatenna, Medamahanuwara', 'Open throughout the day', 'Morning or late afternoon', '2-4 hours', 19.00, '45 minutes', 'https://maps.app.goo.gl/NGDexyt9LWWXhoQKA', 'Road conditions may be difficult during heavy rain.', '2026-08-14 17:02:14'),
(6, 1, 'Corbetts Gap', 'A scenic mountain pass in the Knuckles Range offering panoramic views of valleys and mountains.', 'Hunnasgiriya-Meemure Road', 'Open throughout the day', 'Early morning', '1-2 hours', 18.00, '45 minutes', 'https://maps.app.goo.gl/BmYkQTk9XaTUKwfP9', 'Mountain roads can be narrow and windy.', '2026-08-14 17:02:14'),
(7, 1, 'Bambaragan Oya', 'A beautiful stream originating from the Knuckles Forest Reserve and forming several waterfalls.', 'Hunnasgiriya', 'Open throughout the day', 'Morning', '1-2 hours', 3.80, '10 minutes', 'https://maps.app.goo.gl/tK3nyfB37ongNYRZ9', 'Be careful of slippery rocks and strong water flow.', '2026-08-14 17:02:14'),
(8, 1, 'Kahatagolla Waterfall', 'A scenic waterfall flowing through the Knuckles Reserve with a unique natural rock formation.', 'Kahatagolla, Hunnasgiriya', 'Open throughout the day', 'Morning or afternoon', '1 hour', 4.20, '10 minutes', 'https://maps.app.goo.gl/njRbQQEGvBN4BfvN6', 'Roads and walking areas may be slippery during rainy weather.', '2026-08-14 17:02:14'),
(9, 3, 'Lord Buddha Statue', 'A roadside and mountain-view religious landmark located near Hunnasgiriya.', 'Hunnasgiriya', 'Open throughout the day', 'Morning or evening', '30 minutes - 1 hour', 0.70, '5 minutes', 'https://maps.app.goo.gl/PoV6N5EdjsEzMa6n8\r\n3', 'Easily accessible from the main road.', '2026-08-14 17:02:14'),
(10, 1, 'Alla Gala Waterfall', 'A scenic rural waterfall famous for its reverse waterfall phenomenon during strong winds.', 'Along Udadumbara-Kalugala Road', 'Open throughout the day', 'Morning or during suitable weather', '1-2 hours', 9.40, '25 minutes', 'https://maps.app.goo.gl/qEtc6KUBG2CA2nFi7', 'Check weather conditions before visiting and be careful on wet roads.', '2026-08-14 17:02:14');

-- --------------------------------------------------------

--
-- Table structure for table `attraction_images`
--

DROP TABLE IF EXISTS `attraction_images`;
CREATE TABLE IF NOT EXISTS `attraction_images` (
  `image_id` int NOT NULL AUTO_INCREMENT,
  `attraction_id` int NOT NULL,
  `image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `attraction_id` (`attraction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attraction_images`
--

INSERT INTO `attraction_images` (`image_id`, `attraction_id`, `image_path`) VALUES
(21, 1, 'assets/images/deanston.webp'),
(22, 1, 'assets/images/deestonminiworld.jpg'),
(23, 2, 'assets/images/katahara.jpg'),
(24, 2, 'assets/images/katahara_Alla.webp'),
(25, 2, 'assets/images/kataharawater.jpg'),
(26, 2, 'assets/images/kataharawaterfall.webp'),
(27, 3, 'assets/images/keulgama.JPG'),
(28, 4, 'assets/images/pitigoda village.webp'),
(29, 4, 'assets/images/pitigoda.jpg'),
(30, 4, 'assets/images/pitigodagama.jpg'),
(31, 5, 'assets/images/wawa.jpg'),
(32, 5, 'assets/images/wawathanna.jpg'),
(33, 5, 'assets/images/wawathanna_nature.jpg'),
(34, 5, 'assets/images/wawathannavisit.jpg'),
(35, 6, 'assets/images/cobert.jpg'),
(36, 6, 'assets/images/cobertsgap.jpg'),
(37, 7, 'assets/images/bambaran.jpg'),
(38, 7, 'assets/images/bambaranoya.jpg'),
(39, 8, 'assets/images/kahatagolla_alla.jpg'),
(40, 9, 'assets/images/Loardstatue.JPG'),
(41, 10, 'assets/images/alla.webp');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Nature'),
(2, 'Religious'),
(3, 'Historical'),
(4, 'Cultural');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_contacts`
--

DROP TABLE IF EXISTS `emergency_contacts`;
CREATE TABLE IF NOT EXISTS `emergency_contacts` (
  `contact_id` int NOT NULL AUTO_INCREMENT,
  `service_name` varchar(150) NOT NULL,
  `contact_number` varchar(30) NOT NULL,
  `description` text,
  PRIMARY KEY (`contact_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `emergency_contacts`
--

INSERT INTO `emergency_contacts` (`contact_id`, `service_name`, `contact_number`, `description`) VALUES
(1, 'Police Emergency', '119 / 118', 'Emergency police assistance in Sri Lanka.'),
(2, 'Free Ambulance (Suwa Seriya)', '1990', 'Free emergency ambulance service.'),
(3, 'Fire, Ambulance & Rescue', '110', 'Emergency fire, ambulance and rescue services.'),
(4, 'Disaster Management Centre', '117 / 011-2136222', 'Emergency assistance and disaster-related information.'),
(5, 'Government Information Centre', '1919', 'Government information and public service assistance.'),
(6, 'Tourist Support & Tourist Police', '1912', '24/7 islandwide tourist guidance, safety assistance and travel disruption information.'),
(7, 'Udadumbara Police Station', '081-2402222', 'Police station serving the Udadumbara and Hunnasgiriya area.'),
(8, 'Panwila Police Station', '081-2472222 / 081-2472223', 'Police station covering Panwila and western routes towards Knuckles.'),
(9, 'Rangala Police Station', '081-2400222', 'Police station located near high-altitude trail areas.'),
(10, 'Hasalaka Police Station', '055-2257122', 'Police station near the 18-hairpin bends.'),
(11, 'Teldeniya Police Station', '081-2374048 / 081-3803475', 'Regional police division serving the Teldeniya area.'),
(12, 'Kandy District Tourist Police Office', '081-3837392', 'Tourist police assistance for visitors in the Kandy District.'),
(13, 'Central Province Police Head Office', '081-2222222', 'Central Province police headquarters in Kandy.'),
(14, 'Divisional Hospital - Udadumbara', '081-2402461', 'Primary medical facility serving the Udadumbara and Hunnasgiriya area.'),
(15, 'Medamahanuwara Rural Hospital', '081-2402261', 'Rural hospital located west of the Hunnasgiriya pass.'),
(16, 'Teldeniya Base Hospital', '081-2374055', 'Base hospital providing medical and emergency services in the region.'),
(17, 'National Hospital - Kandy', '081-2222261', 'Major hospital in Kandy providing emergency and specialist medical services.'),
(18, 'Udadumbara Divisional Secretariat', '081-2402266 / 081-2402238', 'Local administrative office for the Udadumbara Division.'),
(19, 'Divisional Disaster Management Unit - Udadumbara', '081-2402238', 'Local disaster management and emergency coordination assistance.'),
(20, 'District Disaster Management Coordinating Unit - Kandy', '081-2202697 / 077-3957890', 'District-level disaster management coordination and emergency assistance.'),
(21, 'Assistant Divisional Secretary - Udadumbara', '081-2402678 / 071-6357507', 'Administrative assistance for the Udadumbara Division.'),
(22, 'Panwila Divisional Secretariat Office', '081-2472266 / 081-2374077', 'Divisional administrative office serving the Panwila area.'),
(23, 'Forest Department Headquarters', '011-2866631 / 011-2866632', 'Forest-related assistance and support for search and rescue coordination.'),
(24, 'Hunnasgiriya Sub Post Office', '081-2402252', 'Local postal service in Hunnasgiriya.'),
(25, 'Udadumbara Main Post Office', '081-2402251', 'Main postal service serving Udadumbara.'),
(26, 'Rambukwella Post Office', '081-2375604', 'Local postal service serving the Rambukwella area.'),
(27, 'Ceylon Electricity Board', '1987', 'Power failure and electricity emergency hotline.'),
(28, 'Child Help Line', '1929', 'National child protection and assistance helpline.'),
(29, 'Women\'s Help Line', '1938', 'National support helpline for women.'),
(30, 'Mental Health Crisis Line', '1926', 'National mental health support and crisis helpline.');

-- --------------------------------------------------------

--
-- Table structure for table `itineraries`
--

DROP TABLE IF EXISTS `itineraries`;
CREATE TABLE IF NOT EXISTS `itineraries` (
  `itinerary_id` int NOT NULL AUTO_INCREMENT,
  `itinerary_name` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`itinerary_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `itinerary_items`
--

DROP TABLE IF EXISTS `itinerary_items`;
CREATE TABLE IF NOT EXISTS `itinerary_items` (
  `item_id` int NOT NULL AUTO_INCREMENT,
  `itinerary_id` int NOT NULL,
  `attraction_id` int NOT NULL,
  `visit_order` int NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `itinerary_id` (`itinerary_id`),
  KEY `attraction_id` (`attraction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

DROP TABLE IF EXISTS `restaurants`;
CREATE TABLE IF NOT EXISTS `restaurants` (
  `restaurant_id` int NOT NULL AUTO_INCREMENT,
  `attraction_id` int DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `estimated_spending` decimal(10,2) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`restaurant_id`),
  KEY `attraction_id` (`attraction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`restaurant_id`, `attraction_id`, `name`, `location`, `estimated_spending`, `description`) VALUES
(1, 6, 'Shady Hills Hunnasgiriya', 'Wadawalakanda, Hunnasgiriya - Meemure Road, Hunnasgiriya', 2500.00, 'Mountain-area accommodation and dining option near Hunnasgiriya, suitable for visitors exploring the surrounding hills.'),
(2, 2, 'Rajagala Residence Hunnasgiriya', 'Rajagala, Galekele, A26, Hunnasgiriya', 2000.00, 'Residence and hospitality property located along the A26 near Hunnasgiriya.'),
(3, 1, 'Shanthi Uyana', 'Wadawalakanda, Meemure', 1500.00, 'Hotel and restaurant located in the Wadawalakanda and Meemure area.'),
(4, 1, 'Hillside Hotel', 'Galkale, Medamahanuwara, Hunnasgiriya', 1800.00, 'Hotel located in the Galkale, Medamahanuwara area near Hunnasgiriya.'),
(5, 1, 'Chathurika Hotel', 'A26, Hunnasgiriya', 1200.00, 'Local restaurant located along the A26 road in Hunnasgiriya.'),
(6, 10, 'Priyangi Hills ', ' No. 209, Kandy Road, Udadumbara, Sri Lanka', 2500.00, 'Contact Numbers: +94 72 748 5425 / +94 77 069 8858');

-- --------------------------------------------------------

--
-- Table structure for table `travel_tips`
--

DROP TABLE IF EXISTS `travel_tips`;
CREATE TABLE IF NOT EXISTS `travel_tips` (
  `tip_id` int NOT NULL AUTO_INCREMENT,
  `attraction_id` int NOT NULL,
  `tip_type` enum('Travel','Safety') NOT NULL,
  `tip_description` text NOT NULL,
  PRIMARY KEY (`tip_id`),
  KEY `attraction_id` (`attraction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `travel_tips`
--

INSERT INTO `travel_tips` (`tip_id`, `attraction_id`, `tip_type`, `tip_description`) VALUES
(1, 1, 'Travel', 'Start your journey early to enjoy the attraction with fewer visitors.'),
(2, 1, 'Travel', 'Wear comfortable shoes because some areas may require walking.'),
(3, 1, 'Safety', 'Be careful on slippery surfaces, especially after rain.'),
(4, 1, 'Safety', 'Carry drinking water and stay on marked paths.'),
(5, 2, 'Travel', 'Check the weather before travelling, especially during the rainy season.'),
(6, 2, 'Safety', 'Avoid entering restricted or unsafe areas.'),
(7, 1, 'Travel', 'Start your journey early to enjoy the attraction and surrounding scenery with enough time.'),
(8, 1, 'Travel', 'Check the weather forecast before travelling, especially during the rainy season.'),
(9, 1, 'Safety', 'Wear comfortable shoes and be careful on slippery or uneven surfaces.'),
(10, 1, 'Safety', 'Carry sufficient drinking water and follow marked paths and safety instructions.'),
(11, 2, 'Travel', 'Plan your visit during daylight hours to enjoy the surrounding natural scenery.'),
(12, 2, 'Travel', 'Carry essential items such as drinking water, snacks and a fully charged mobile phone.'),
(13, 2, 'Safety', 'Be careful when walking near steep slopes, rocks and wet surfaces.'),
(14, 2, 'Safety', 'Avoid entering restricted areas or travelling alone in unfamiliar locations.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `created_at`) VALUES
(1, 'thanushi rathnajeewa', 'thanushirathnajeewa@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llCz9QzQ6L0vJ6nXj6nO', '2026-09-26 13:19:16');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
