-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 03, 2024 at 02:34 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `musictimes`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) DEFAULT NULL,
  `admin_username` varchar(20) DEFAULT NULL,
  `admin_email` varchar(100) DEFAULT NULL,
  `admin_password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `admin_username`, `admin_email`, `admin_password`) VALUES
(1, 'admin', 'admin', 'admin@gmail.com', '$2y$10$dgC0x66UFsG8a59dOfI5cOU/1n8vM9aIMufrtt2PtXBWS6OVNtNAC');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(12) NOT NULL,
  `customer_id` int(12) DEFAULT NULL,
  `product_id` int(12) DEFAULT NULL,
  `cart_quantity` int(100) DEFAULT NULL,
  `created_cart` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_cart` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(5) NOT NULL,
  `category_name` varchar(50) DEFAULT NULL,
  `created_category` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_category` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `created_category`, `updated_category`) VALUES
(6, 'Music', '2024-01-10 06:47:46', '2024-01-10 06:47:46'),
(8, 'Instrument', '2024-01-10 08:02:36', '2024-01-10 08:02:36');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_username` varchar(20) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `customer_password` varchar(255) DEFAULT NULL,
  `customer_image` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(15) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `customer_status` enum('active','inactive','pending') DEFAULT NULL,
  `confirm_password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer_name`, `customer_username`, `customer_email`, `customer_password`, `customer_image`, `customer_phone`, `customer_address`, `customer_status`, `confirm_password`) VALUES
(1, 'Arif', 'ArifAzi', 'chojjaarif2002@gmail.com', '$2y$10$r9JpcZMvyuvn8k3PJl46NuV1OYbyKLXAXgzFTc3DEdKpZICZHhdVW', NULL, NULL, NULL, NULL, NULL),
(2, 'Atiqah', 'Atiqahehe', 'aatiqaah@gmail.com', '$2y$10$EXXgmdG64jOkdrGpFgSYLehO0i5MtSgClrkp7tjMcisTfujSgvKUy', NULL, NULL, NULL, NULL, NULL),
(6, 'Arif', 'Arif', 'hehe', '$2y$10$or5bHuRlbZbv/ClXTzaCNOpkf3xx0yOZreAUThAZAHgIYqSmO9IuW', NULL, NULL, NULL, NULL, NULL),
(7, 'customers', 'customer', 'customer@gmail.com', '$2y$10$oTH9PBVRwc.5ICjXCpHzB.iy8DFqULaWYwaWnuvrVseYZoZmDnxZi', NULL, '', '', NULL, NULL),
(8, 'Arif ', 'Hehe', 'hehe@gmail.com', '$2y$10$7YYXWYSHYgm6eQqRhxtkROOy4prvyzJmlbxskWvlnHtQgpmq1kJke', '', '', '', NULL, NULL),
(10, 'reza', 'reza', 'reza@gmail.com', '$2y$10$0fdI85l847lDqt5CYysSuumhH8Elkm23mpaOagXquoeZA4hGALHOG', NULL, NULL, NULL, NULL, NULL),
(12, 'customer001', 'customer001', 'customer001@gmail.com', '$2y$10$XteJf24kdwBifXzsi.yTYe9VXwxPN2YPmbuQECns6en3M9lDZ3kPe', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enterprise`
--

CREATE TABLE `enterprise` (
  `enterprise_id` int(11) NOT NULL,
  `enterprise_name` varchar(100) DEFAULT NULL,
  `enterprise_username` varchar(20) DEFAULT NULL,
  `enterprise_email` varchar(100) DEFAULT NULL,
  `enterprise_password` varchar(255) DEFAULT NULL,
  `enterprise_image` varchar(255) DEFAULT NULL,
  `enterprise_phone` varchar(15) DEFAULT NULL,
  `enterprise_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enterprise`
--

INSERT INTO `enterprise` (`enterprise_id`, `enterprise_name`, `enterprise_username`, `enterprise_email`, `enterprise_password`, `enterprise_image`, `enterprise_phone`, `enterprise_address`) VALUES
(2, 'Perusahaan Kompang Arif', 'enterprise', 'enterprise@gmail.com', '$2y$10$dgC0x66UFsG8a59dOfI5cOU/1n8vM9aIMufrtt2PtXBWS6OVNtNAC', '', '', ''),
(3, 'Arif', 'arif', 'arif@gmail.com', 'arif', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(12) NOT NULL,
  `product_id` int(12) DEFAULT NULL,
  `customer_id` int(12) DEFAULT NULL,
  `feedback_desc` text DEFAULT NULL,
  `feedback_media` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `rating` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `product_id`, `customer_id`, `feedback_desc`, `feedback_media`, `is_deleted`, `rating`) VALUES
(1, 12, 12, '', '', 0, 0),
(2, 12, 12, 'dqwdqwdqwdwqdq', '', 0, 0),
(3, 12, 12, 'dqwdqwdqwdwqdq', '', 0, 0),
(4, 12, 12, 'dqwdqwdqwdwqdq', '', 0, 0),
(5, 12, 12, 'joijiojij', '', 0, 0),
(6, 12, 12, 'oinihhio', '', 0, 0),
(7, 12, 12, 'nonion', '../image/uploads/1*V9-OPWpauGEi-JMp05RC_A.png', 0, 0),
(8, 12, 12, 'biubiubb', '../image/uploads/1*V9-OPWpauGEi-JMp05RC_A.png', 0, 0),
(9, 12, 12, 'good good', '../image/uploads/1*V9-OPWpauGEi-JMp05RC_A.png', 0, 0),
(10, 12, 12, 'niceeeee', '../image/uploads/1*V9-OPWpauGEi-JMp05RC_A.png', 0, 0),
(11, 12, 12, 'sxaasdda', '', 0, 0),
(12, 12, 12, 'asdqwdwq', '', 0, 3),
(13, 12, 12, 'good', '', 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int(12) NOT NULL,
  `customer_id` int(5) DEFAULT NULL,
  `enterprise_id` int(12) DEFAULT NULL,
  `product_id` int(100) DEFAULT NULL,
  `product_name` varchar(300) DEFAULT NULL,
  `product_quantity` int(200) DEFAULT NULL,
  `transaction_id` int(50) DEFAULT NULL,
  `invoice_id` int(14) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `order_notes` text DEFAULT NULL,
  `delivery_address` varchar(100) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `payment_method` text DEFAULT NULL,
  `order_status` enum('Paid','Cancel','Shipping','Pending','Complete') DEFAULT NULL,
  `created_order` datetime DEFAULT current_timestamp(),
  `updated_order` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_quantity` int(11) DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `cancel_order_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `customer_id`, `enterprise_id`, `product_id`, `product_name`, `product_quantity`, `transaction_id`, `invoice_id`, `order_date`, `total_amount`, `order_notes`, `delivery_address`, `postcode`, `payment_method`, `order_status`, `created_order`, `updated_order`, `total_quantity`, `is_deleted`, `cancel_order_reason`) VALUES
(1, 12, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-30 17:21:19', 66.00, NULL, NULL, NULL, NULL, 'Cancel', '2024-03-31 00:21:19', '2024-04-03 10:27:13', 0, 0, 'wrong order'),
(2, 12, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-30 17:25:32', 8.00, NULL, NULL, NULL, NULL, 'Cancel', '2024-03-31 00:25:32', '2024-04-03 10:27:18', 0, 0, 'wrong order'),
(3, 12, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-30 17:26:13', 50.00, NULL, NULL, NULL, NULL, 'Cancel', '2024-03-31 00:26:13', '2024-04-03 10:27:20', 0, 0, 'wrong order'),
(4, 12, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-30 18:21:55', 105.00, NULL, NULL, NULL, NULL, 'Cancel', '2024-03-31 01:21:55', '2024-04-03 10:27:22', 0, 0, 'wrong order'),
(5, 12, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-30 18:30:59', 75.00, NULL, NULL, NULL, NULL, NULL, '2024-03-31 01:30:59', '2024-04-03 10:27:24', 0, 0, 'wrong order'),
(6, 12, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-03 04:20:04', 50.00, NULL, NULL, NULL, NULL, 'Cancel', '2024-04-03 10:20:04', '2024-04-03 10:20:43', 0, 0, NULL),
(7, 12, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-03 04:22:17', 21.00, NULL, NULL, NULL, NULL, 'Cancel', '2024-04-03 10:22:17', '2024-04-03 10:26:55', 0, 0, 'wqewqeq');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `orderitem_id` int(12) NOT NULL,
  `order_id` int(12) DEFAULT NULL,
  `product_id` int(12) DEFAULT NULL,
  `order_quantity` int(100) DEFAULT NULL,
  `created_order` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_order` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`orderitem_id`, `order_id`, `product_id`, `order_quantity`, `created_order`, `updated_order`) VALUES
(1, 1, 1, 6, '2024-03-30 16:21:19', '2024-03-30 16:21:19'),
(2, 1, 2, 6, '2024-03-30 16:21:19', '2024-03-30 16:21:19'),
(3, 2, 3, 3, '2024-03-30 16:25:32', '2024-03-30 16:25:32'),
(4, 2, 4, 5, '2024-03-30 16:25:32', '2024-03-30 16:25:32'),
(5, 3, 1, 5, '2024-03-30 16:26:13', '2024-03-30 16:26:13'),
(6, 4, 1, 10, '2024-03-30 17:21:55', '2024-03-30 17:21:55'),
(7, 4, 2, 5, '2024-03-30 17:21:55', '2024-03-30 17:21:55'),
(8, 5, 1, 7, '2024-03-30 17:30:59', '2024-03-30 17:30:59'),
(9, 5, 2, 5, '2024-03-30 17:30:59', '2024-03-30 17:30:59'),
(10, 6, 1, 5, '2024-04-03 02:20:04', '2024-04-03 02:20:04'),
(11, 7, 2, 4, '2024-04-03 02:22:17', '2024-04-03 02:22:17'),
(12, 7, 4, 5, '2024-04-03 02:22:17', '2024-04-03 02:22:17'),
(13, 7, 3, 12, '2024-04-03 02:22:17', '2024-04-03 02:22:17');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(12) NOT NULL,
  `order_id` int(12) DEFAULT NULL,
  `customer_id` int(12) DEFAULT NULL,
  `transaction_id` text DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` enum('Online Banking','Credit/Debit card') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `order_id`, `customer_id`, `transaction_id`, `total_amount`, `payment_method`) VALUES
(1, 1, 12, '979892', 66.00, 'Credit/Debit card'),
(2, 2, 12, '114931', 8.00, 'Credit/Debit card'),
(3, 3, 12, '653056', 50.00, 'Credit/Debit card'),
(4, 4, 12, '716334', 105.00, 'Credit/Debit card'),
(5, 5, 12, '738498', 75.00, 'Credit/Debit card'),
(6, 6, 12, '140386', 50.00, 'Credit/Debit card'),
(7, 7, 12, '377203', 21.00, 'Credit/Debit card');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(12) NOT NULL,
  `category_id` int(5) DEFAULT NULL,
  `enterprise_id` int(12) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `product_desc` varchar(300) DEFAULT NULL,
  `product_quantity` int(200) DEFAULT NULL,
  `product_tag` varchar(50) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `created_product` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_product` timestamp NULL DEFAULT NULL,
  `product_status` enum('Available','Not Available') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `category_id`, `enterprise_id`, `product_name`, `product_desc`, `product_quantity`, `product_tag`, `product_price`, `product_image`, `created_product`, `updated_product`, `product_status`) VALUES
(1, 6, 2, 'Kompang', '50 cm diameter', 966, 'Best', 10.00, 'c593b0cc012a3c3da8e527b649b34bc7.jpeg', '2024-01-08 07:39:23', NULL, 'Available'),
(2, 6, 2, 'Kompang Laser Cut', '50 cm diameter with laser cut', 979, 'Style', 1.00, 'a14c9333ee01eea5afab09bf6ccbaf94.jpeg', '2024-01-08 13:08:43', NULL, 'Available'),
(3, 6, 2, 'Jidor', '90 cm diameter', 984, 'Best', 1.00, 'ecde6a74c0a78c0ab5693e614916681e.png', '2024-01-08 13:09:09', NULL, 'Available'),
(4, 6, 2, 'Rebana', '50 cm diameter', 989, 'Style', 1.00, '8fa232a4e884f8e2cc36ef443882cac1.jpeg', '2024-01-09 17:00:23', NULL, 'Available'),
(5, 6, 2, 'Angklung', 'Music', 999, 'Best', 1.00, 'feeb75692cbe6e19665bc3088b457c9b.jpeg', '2024-01-09 17:01:07', NULL, 'Available'),
(7, 6, 2, 'Gambus', '6 strings', 999, 'Style', 1.00, 'b6a27cf034b4459db6796234316c7b0f.jpeg', '2024-01-09 17:18:50', NULL, 'Available'),
(8, 6, 2, 'Marwas', 'Music', 999, 'Best', 1.00, 'b44252cc834ddeddb54c74f17c74e84b.png', '2024-01-10 08:03:51', NULL, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `report_id` int(5) NOT NULL,
  `admin_id` int(50) DEFAULT NULL,
  `enterprise_id` int(11) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `report_type` varchar(50) DEFAULT NULL,
  `sales` decimal(10,2) DEFAULT NULL,
  `insight` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `enterprise`
--
ALTER TABLE `enterprise`
  ADD PRIMARY KEY (`enterprise_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `enterprise_id` (`enterprise_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`orderitem_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `transaction_id` (`transaction_id`(768));

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `enterprise_id` (`enterprise_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `enterprise_id` (`enterprise_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `enterprise`
--
ALTER TABLE `enterprise`
  MODIFY `enterprise_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `orderitem_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
