-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2024 at 09:34 AM
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
-- Database: `musictimes`
--

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
  `updated_category` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `enterprise_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `created_category`, `updated_category`, `enterprise_id`) VALUES
(10, 'Music', '2024-05-24 07:36:01', '2024-05-24 07:36:01', 2),
(11, 'Test', '2024-05-24 15:34:13', '2024-05-24 15:34:13', 7);

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
(6, 'Arif', 'Arif', 'hehe', '$2y$10$or5bHuRlbZbv/ClXTzaCNOpkf3xx0yOZreAUThAZAHgIYqSmO9IuW', NULL, NULL, NULL, NULL, NULL),
(7, 'customers', 'customer', 'customer@gmail.com', '$2y$10$oTH9PBVRwc.5ICjXCpHzB.iy8DFqULaWYwaWnuvrVseYZoZmDnxZi', NULL, '', '', NULL, NULL),
(8, 'Arif ', 'Hehe', 'hehe@gmail.com', '$2y$10$7YYXWYSHYgm6eQqRhxtkROOy4prvyzJmlbxskWvlnHtQgpmq1kJke', '', '', '', NULL, NULL),
(10, 'reza', 'reza', 'reza@gmail.com', '$2y$10$0fdI85l847lDqt5CYysSuumhH8Elkm23mpaOagXquoeZA4hGALHOG', NULL, NULL, NULL, NULL, NULL),
(12, 'ARIF AZINUDDIN', 'customer001', 'customer001@gmail.com', '$2y$10$kM2/XZ7dFgYGQYHBM3nIauqSS9wP7IrFvK4LwUnxjFUWYUEVKCKau', 'f5b0543854b36a3486adbacf8d14c272.png', '01110794886', '1700 JALAN ANGGERIK 1/10,TAMAN ANGGERIK TENGGARA, BANDAR TENGGARA,', NULL, NULL),
(13, 'Irfan Don', 'irfan', 'irfan@gmail.com', '$2y$10$Lpu9OhfazEz2cfvkj.nfHeDXs0rUDXjRDeVmwzegCgOxrBePMIYnS', 'f4290941f769b5aa3381ca734d4966c7.png', '', '', NULL, NULL);

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
  `enterprise_address` text DEFAULT NULL,
  `ssm_certificate` varchar(255) DEFAULT NULL,
  `confirm_password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enterprise`
--

INSERT INTO `enterprise` (`enterprise_id`, `enterprise_name`, `enterprise_username`, `enterprise_email`, `enterprise_password`, `enterprise_image`, `enterprise_phone`, `enterprise_address`, `ssm_certificate`, `confirm_password`) VALUES
(2, 'Perusahaan Kompang Arif', 'enterprise', 'enterprise@gmail.com', '$2y$10$dgC0x66UFsG8a59dOfI5cOU/1n8vM9aIMufrtt2PtXBWS6OVNtNAC', '7ec2cead1713fdce0aab08c7a08dc33e.png', '', '', NULL, NULL),
(7, 'Bengkel Muzik', 'bengkel', 'bengkelmuzik@gmail.com', '$2y$10$l8aWRlloILmU/H.v7NRdgez15ZyX7YA4xm8NrYaYzjRVSiX/KtjP.', '48c36427f583019d612c7860ab655b59.png', '', '', 'image/enterprise/Activities SULAM_compressed.pdf', NULL);

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
  `rating` int(11) NOT NULL DEFAULT 0,
  `enterprise_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `product_id`, `customer_id`, `feedback_desc`, `feedback_media`, `is_deleted`, `rating`, `enterprise_id`, `order_id`) VALUES
(14, 3, 13, 'Memang best guna', '../image/feedback/3.png', 0, 5, 0, 9),
(15, 3, 13, 'Memang best guna', '../image/feedback/3.png', 0, 5, 0, 9),
(16, 7, 13, 'Saya suka', '../image/feedback/junesh.png', 0, 5, 0, 18),
(17, 2, 13, 'Good', '', 0, 5, 0, 9),
(18, 1, 12, 'Best main kompang', '../image/feedback/itc_run.png', 0, 5, 0, 25),
(19, 3, 13, 'Best tepuk, sorry gambar takde kena mengena', '../image/feedback/irfan.png', 0, 5, 0, 29);

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
  `order_status` enum('Preparing','Cancel','Shipping','Complete') DEFAULT NULL,
  `created_order` datetime DEFAULT current_timestamp(),
  `updated_order` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_quantity` int(11) DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `cancel_order_reason` varchar(255) DEFAULT NULL,
  `tracking_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `customer_id`, `enterprise_id`, `product_id`, `product_name`, `product_quantity`, `transaction_id`, `invoice_id`, `order_date`, `total_amount`, `order_notes`, `delivery_address`, `postcode`, `payment_method`, `order_status`, `created_order`, `updated_order`, `total_quantity`, `is_deleted`, `cancel_order_reason`, `tracking_number`) VALUES
(24, 12, 7, NULL, NULL, NULL, NULL, NULL, '2024-05-27 22:07:33', 10.00, NULL, NULL, NULL, NULL, 'Preparing', '2024-05-28 04:07:33', '2024-05-28 04:07:33', 0, 0, NULL, NULL),
(25, 12, 2, NULL, NULL, NULL, NULL, NULL, '2024-05-27 22:07:35', 60.00, NULL, NULL, NULL, NULL, 'Complete', '2024-05-28 04:07:35', '2024-05-28 14:05:52', 0, 0, NULL, 'wqewq213456'),
(26, 12, 2, NULL, NULL, NULL, NULL, NULL, '2024-05-28 08:29:58', 1.00, NULL, NULL, NULL, NULL, 'Preparing', '2024-05-28 14:29:58', '2024-05-28 14:29:58', 0, 0, NULL, NULL),
(27, 12, 2, NULL, NULL, NULL, NULL, NULL, '2024-05-28 11:01:33', 30.00, NULL, NULL, NULL, NULL, 'Complete', '2024-05-28 17:01:33', '2024-05-28 20:39:53', 0, 0, NULL, 'MY123456789'),
(28, 12, 7, NULL, NULL, NULL, NULL, NULL, '2024-05-28 11:01:34', 10.00, NULL, NULL, NULL, NULL, 'Preparing', '2024-05-28 17:01:34', '2024-05-28 17:01:34', 0, 0, NULL, NULL),
(29, 13, 2, NULL, NULL, NULL, NULL, NULL, '2024-05-28 16:11:21', 1.00, NULL, NULL, NULL, NULL, 'Complete', '2024-05-28 22:11:21', '2024-05-28 22:12:29', 0, 0, NULL, 'MY123456789465');

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
  `updated_order` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `enterprise_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`orderitem_id`, `order_id`, `product_id`, `order_quantity`, `created_order`, `updated_order`, `enterprise_id`) VALUES
(56, 24, 10, 1, '2024-05-27 20:07:34', '2024-05-27 20:07:34', 7),
(57, 25, 1, 2, '2024-05-27 20:07:35', '2024-05-27 20:07:35', 2),
(58, 26, 8, 1, '2024-05-28 06:29:58', '2024-05-28 06:29:58', 2),
(59, 27, 1, 1, '2024-05-28 09:01:33', '2024-05-28 09:01:33', 2),
(60, 28, 10, 1, '2024-05-28 09:01:34', '2024-05-28 09:01:34', 7),
(61, 29, 3, 1, '2024-05-28 14:11:21', '2024-05-28 14:11:21', 2);

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
  `payment_method` enum('Online Banking','Credit/Debit card') DEFAULT NULL,
  `enterprise_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `order_id`, `customer_id`, `transaction_id`, `total_amount`, `payment_method`, `enterprise_id`) VALUES
(1, 1, 12, '979892', 66.00, 'Credit/Debit card', 0),
(2, 2, 12, '114931', 8.00, 'Credit/Debit card', 0),
(3, 3, 12, '653056', 50.00, 'Credit/Debit card', 0),
(4, 4, 12, '716334', 105.00, 'Credit/Debit card', 0),
(5, 5, 12, '738498', 75.00, 'Credit/Debit card', 0),
(6, 6, 12, '140386', 50.00, 'Credit/Debit card', 0),
(7, 7, 12, '377203', 21.00, 'Credit/Debit card', 0),
(8, 8, 13, '372116', 21.00, 'Credit/Debit card', 0),
(9, 9, 13, '343748', 5.00, 'Credit/Debit card', 0),
(10, 10, 12, '514886', 3.00, 'Credit/Debit card', 0),
(11, 11, 12, '341630', 30.00, 'Credit/Debit card', 0),
(12, 12, 12, '123116', 31.00, 'Credit/Debit card', 0),
(13, 13, 12, '798572', 1.00, 'Credit/Debit card', 0),
(14, 14, 12, '480254', 1.00, 'Credit/Debit card', 0),
(15, 15, 12, '244573', 2.00, 'Credit/Debit card', 0),
(16, 16, 12, '406888', 10.00, 'Credit/Debit card', 0),
(17, 17, 13, '638546', 178.00, 'Credit/Debit card', 0),
(18, 18, 13, '311710', 1.00, 'Credit/Debit card', 0),
(19, 19, 13, '293683', 1.00, 'Credit/Debit card', 0),
(20, 20, 13, '811006', 30.00, 'Credit/Debit card', 0),
(21, 21, 13, '749015', 11.00, 'Credit/Debit card', 0),
(22, 22, 12, '726869', 1.00, 'Credit/Debit card', 0),
(23, 23, 12, '734241', 10.00, 'Credit/Debit card', 0),
(24, 24, 12, '926609', 10.00, 'Credit/Debit card', 0),
(25, 25, 12, '116053', 60.00, 'Credit/Debit card', 0),
(26, 26, 12, '580341', 1.00, 'Credit/Debit card', 0),
(27, 27, 12, '968266', 30.00, 'Credit/Debit card', 0),
(28, 28, 12, '151184', 10.00, 'Credit/Debit card', 0),
(29, 29, 13, '616697', 1.00, 'Credit/Debit card', 0);

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
(1, 10, 2, 'Kompang', '50 cm diameter', 90, 'Best', 30.00, 'c593b0cc012a3c3da8e527b649b34bc7.jpeg', '2024-01-08 07:39:23', NULL, 'Available'),
(2, 6, 2, 'Kompang Laser Cut', '50 cm diameter with laser cut', 968, 'Style', 1.00, 'a14c9333ee01eea5afab09bf6ccbaf94.jpeg', '2024-01-08 13:08:43', NULL, 'Available'),
(3, 6, 2, 'Jidor', '90 cm diameter', 925, 'Best', 1.00, 'ecde6a74c0a78c0ab5693e614916681e.png', '2024-01-08 13:09:09', NULL, 'Available'),
(4, 10, 2, 'Rebana', '50 cm diameter', 1, 'Style', 1.00, '8fa232a4e884f8e2cc36ef443882cac1.jpeg', '2024-01-09 17:00:23', NULL, 'Not Available'),
(5, 6, 2, 'Angklung', 'Music', 997, 'Best', 1.00, 'feeb75692cbe6e19665bc3088b457c9b.jpeg', '2024-01-09 17:01:07', NULL, 'Available'),
(7, 6, 2, 'Gambus', '6 strings', 995, 'Style', 1.00, 'b6a27cf034b4459db6796234316c7b0f.jpeg', '2024-01-09 17:18:50', NULL, 'Available'),
(8, 10, 2, 'Marwas', 'Music', 997, 'Best', 1.00, 'b44252cc834ddeddb54c74f17c74e84b.png', '2024-01-10 08:03:51', NULL, 'Available'),
(10, 11, 7, 'Test', 'Test', 5, 'Test', 10.00, '3d06c3bf16cefa568311cb7c1ee0dad8.png', '2024-05-24 15:34:33', NULL, 'Available');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `enterprise`
--
ALTER TABLE `enterprise`
  MODIFY `enterprise_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `orderitem_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
