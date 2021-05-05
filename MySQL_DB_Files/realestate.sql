-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2021 at 09:29 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `realestate`
--

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `company_name` text NOT NULL,
  `manager` text NOT NULL,
  `staff` text NOT NULL,
  `properties` text NOT NULL,
  `invite_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `manager`, `staff`, `properties`, `invite_code`) VALUES
(1, 'Geek Inc', '1', '', '', '1b7b4f383078efc89784');

-- --------------------------------------------------------

--
-- Table structure for table `island`
--

CREATE TABLE `island` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `island`
--

INSERT INTO `island` (`id`, `name`) VALUES
(1, 'South Island'),
(2, 'North Island'),
(3, 'Stewart Island'),
(4, 'Adams Island'),
(5, 'Anchor Island'),
(6, 'Antipodes Island'),
(7, 'Arapaoa Island'),
(8, 'Auckland Island'),
(9, 'Big South Cape Island'),
(10, 'Campbell Island'),
(11, 'Chatham Island'),
(12, 'Coal Island'),
(13, 'Codfish Island'),
(14, 'Cooper Island'),
(15, 'D\'Urville Island'),
(16, 'Great Barrier Island'),
(17, 'Great Mercury Island'),
(18, 'Kapiti Island'),
(19, 'Kawau Island'),
(20, 'Little Barrier Island'),
(21, 'Long Island, Southland'),
(22, 'Matakana Island'),
(23, 'Mayor Island'),
(24, 'Motiti Island'),
(25, 'Motutapu Island'),
(26, 'Pitt Island'),
(27, 'Ponui Island'),
(28, 'Rangitoto Island'),
(29, 'Raoul Island'),
(30, 'Resolution Island'),
(31, 'Ruapuke Island'),
(32, 'Waiheke Island');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `st_name` text NOT NULL,
  `st_number` int(11) NOT NULL,
  `neighbourhood` varchar(25) NOT NULL,
  `city` varchar(25) NOT NULL,
  `island_id` int(11) NOT NULL,
  `listing_type` text NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL,
  `facilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`facilities`)),
  `agent_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `listing_created` datetime NOT NULL,
  `listing_close_date` datetime NOT NULL,
  `views` int(11) NOT NULL,
  `unique_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `st_name`, `st_number`, `neighbourhood`, `city`, `island_id`, `listing_type`, `description`, `price`, `facilities`, `agent_id`, `company_id`, `listing_created`, `listing_close_date`, `views`, `unique_id`) VALUES
(5, 'Korora st', 15, 'Bromley', 'Christchurch', 1, 'BuyNow', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sed sagittis justo, nec sagittis lorem. In feugiat consequat est sit amet dapibus. Ut at ullamcorper magna, in imperdiet purus. Duis vulputate vulputate nisl eget congue. Donec pretium velit nibh, nec ullamcorper lectus elementum in. Aliquam sollicitudin elit id leo ultricies ultricies. Etiam justo ligula, pulvinar sit amet libero aliquam, eleifend malesuada massa. Cras mollis tellus nisi, et consequat nulla blandit et.', 365000, '{\"garage\":\"1\",\"bedrooms\":\"3\",\"bathrooms\":\"1\",\"parking_type\":\"Off Street\"}', 1, 1, '2021-04-20 10:11:29', '2021-04-20 10:11:00', 0, '6d77b2111b6fc9c63d12e0ff8ba631c52062878f');

-- --------------------------------------------------------

--
-- Table structure for table `queries`
--

CREATE TABLE `queries` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `message` text NOT NULL,
  `property_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `role_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `wishlist` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `island`
--
ALTER TABLE `island`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `queries`
--
ALTER TABLE `queries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `island`
--
ALTER TABLE `island`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `queries`
--
ALTER TABLE `queries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
