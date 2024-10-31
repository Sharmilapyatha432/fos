-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2024 at 03:19 PM
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
-- Database: `bite_bliss`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `aid` int(11) NOT NULL,
  `adminname` varchar(255) NOT NULL,
  `adminpassword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aid`, `adminname`, `adminpassword`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_otp_hash` varchar(64) DEFAULT NULL,
  `reset_otp_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cid`, `name`, `address`, `mobile`, `email`, `password`, `reset_otp_hash`, `reset_otp_expires_at`) VALUES
(1, 'Sugyan Shrestha', 'Thimi', 9800000000, 'sugyan@gmail.com', '$2y$10$Y1ltSacLt37Iw5PnOP.B.OqIAmkfRS.SG261hNzHMsPbiBrF.AuO2', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deliveryassignment`
--

CREATE TABLE `deliveryassignment` (
  `assignment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `dpid` int(11) DEFAULT NULL,
  `assigned_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `delivery_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_person`
--

CREATE TABLE `delivery_person` (
  `dpid` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `license` bigint(20) NOT NULL,
  `vehicle` varchar(50) NOT NULL,
  `vehicle_number` varchar(50) NOT NULL,
  `status` enum('Pending','Approved','Not Approved') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_person`
--

INSERT INTO `delivery_person` (`dpid`, `fullname`, `address`, `mobile`, `email`, `password`, `license`, `vehicle`, `vehicle_number`, `status`) VALUES
(1, 'Ram Shrestha', 'Thimi', 9800000000, 'ram@gmail.com', '$2y$10$KTttqg5FIKx.orNJPEVQP.wNKulVb9uWgBlbMYLaOk5rj9ZPEPEjS', 0, 'Motorcycle', '4884', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `fcm_tokens`
--

CREATE TABLE `fcm_tokens` (
  `id` int(11) NOT NULL,
  `delivery_person_id` int(11) NOT NULL,
  `fcm_token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foodcategory`
--

CREATE TABLE `foodcategory` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `foodcategory`
--

INSERT INTO `foodcategory` (`category_id`, `category_name`) VALUES
(3, 'Burger'),
(9, 'Momo'),
(1, 'Pizza');

-- --------------------------------------------------------

--
-- Table structure for table `fooditem`
--

CREATE TABLE `fooditem` (
  `food_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fooditem`
--

INSERT INTO `fooditem` (`food_id`, `name`, `description`, `price`, `image`, `category_id`) VALUES
(1, 'Ham Burger', 'A tasty ham burger for you my love!!!!', 190.00, '../img/1729270430_Homemade-Hamburgers-TIMG.jpg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `order_details_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `food_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`order_details_id`, `order_id`, `food_id`, `quantity`, `price`) VALUES
(1, 3, 1, 2, 190.00),
(2, 4, 1, 2, 190.00),
(3, 5, 1, 2, 190.00),
(4, 6, 1, 2, 190.00),
(5, 7, 1, 1, 190.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `cid` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `delivery_status` enum('Pending','Shipped','Completed','Canceled') DEFAULT 'Pending',
  `shipping_address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `payment_method` enum('Cash on Delivery','Credit Card','Debit Card','E-sewa','Other') DEFAULT 'Cash on Delivery'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `cid`, `order_date`, `total_amount`, `delivery_status`, `shipping_address`, `city`, `payment_method`) VALUES
(3, NULL, '2024-10-24 14:50:53', 380.00, 'Pending', NULL, NULL, 'Cash on Delivery'),
(4, NULL, '2024-10-24 14:54:18', 380.00, 'Pending', NULL, NULL, 'Cash on Delivery'),
(5, NULL, '2024-10-24 15:04:39', 380.00, 'Pending', NULL, NULL, 'Cash on Delivery'),
(6, NULL, '2024-10-24 15:19:48', 380.00, 'Pending', NULL, NULL, 'Cash on Delivery'),
(7, NULL, '2024-10-24 15:59:56', 190.00, 'Pending', 'thimi', 'bkt', 'Cash on Delivery');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `reset_otp_hash` (`reset_otp_hash`);

--
-- Indexes for table `deliveryassignment`
--
ALTER TABLE `deliveryassignment`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `dpid` (`dpid`);

--
-- Indexes for table `delivery_person`
--
ALTER TABLE `delivery_person`
  ADD PRIMARY KEY (`dpid`);

--
-- Indexes for table `fcm_tokens`
--
ALTER TABLE `fcm_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_person_id` (`delivery_person_id`);

--
-- Indexes for table `foodcategory`
--
ALTER TABLE `foodcategory`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `fooditem`
--
ALTER TABLE `fooditem`
  ADD PRIMARY KEY (`food_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`order_details_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`cid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `deliveryassignment`
--
ALTER TABLE `deliveryassignment`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_person`
--
ALTER TABLE `delivery_person`
  MODIFY `dpid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fcm_tokens`
--
ALTER TABLE `fcm_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foodcategory`
--
ALTER TABLE `foodcategory`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `fooditem`
--
ALTER TABLE `fooditem`
  MODIFY `food_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `order_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deliveryassignment`
--
ALTER TABLE `deliveryassignment`
  ADD CONSTRAINT `deliveryassignment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `deliveryassignment_ibfk_2` FOREIGN KEY (`dpid`) REFERENCES `delivery_person` (`dpid`);

--
-- Constraints for table `fcm_tokens`
--
ALTER TABLE `fcm_tokens`
  ADD CONSTRAINT `fcm_tokens_ibfk_1` FOREIGN KEY (`delivery_person_id`) REFERENCES `delivery_person` (`dpid`) ON DELETE CASCADE;

--
-- Constraints for table `fooditem`
--
ALTER TABLE `fooditem`
  ADD CONSTRAINT `fooditem_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `foodcategory` (`category_id`);

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `fooditem` (`food_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `customer` (`cid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
