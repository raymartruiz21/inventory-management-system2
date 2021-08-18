-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2021 at 01:55 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(250) NOT NULL,
  `category_status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_status`) VALUES
(17, 'kakanin', 'active'),
(18, 'bread', 'active'),
(19, 'pastry', 'active'),
(20, 'cake', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`invoice_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(3, 1, 10, 22, 2.00),
(4, 2, 16, 2, 150.00),
(5, 3, 23, 10, 7.00),
(6, 4, 23, 3, 7.00),
(7, 5, 23, 3, 7.00),
(8, 6, 23, 10, 7.00),
(9, 6, 15, 10, 150.00),
(14, 9, 23, 50, 7.00),
(15, 10, 23, 1, 7.00),
(16, 11, 17, 2, 120.00),
(17, 11, 19, 10, 7.00),
(18, 11, 14, 1, 120.00),
(19, 12, 24, 20, 2.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_tbl`
--

CREATE TABLE `order_tbl` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_total` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `order_name` varchar(100) NOT NULL,
  `order_address` varchar(200) NOT NULL,
  `payment_status` enum('paid','unpaid') NOT NULL,
  `order_status` varchar(100) NOT NULL,
  `order_created_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_tbl`
--

INSERT INTO `order_tbl` (`order_id`, `user_id`, `order_total`, `order_date`, `order_name`, `order_address`, `payment_status`, `order_status`, `order_created_date`) VALUES
(12, 1, 40, '2021-06-14', 'Jerico Dela Cruz', 'Anayan, Pili, Camarines Sur', 'paid', 'active', '2021-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_price` double(10,2) NOT NULL,
  `product_status` enum('active','inactive') NOT NULL,
  `product_date` date NOT NULL,
  `product_code` varchar(250) NOT NULL,
  `QRcode_img` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `category_id`, `product_name`, `product_quantity`, `product_price`, `product_status`, `product_date`, `product_code`, `QRcode_img`) VALUES
(24, 18, 'Pandesal', 100, 2.00, 'active', '2021-06-16', 'lCiN4dAt5j', 'img/QRcode/Pandesal.png'),
(25, 18, 'Pan Legaspi', 50, 6.00, 'active', '2021-06-16', 'B3tEgvhbC2', 'img/QRcode/Pan Legaspi.png'),
(26, 20, 'Black Forest', 1, 350.00, 'active', '2021-06-16', 'uJ9qEMzToK', 'img/QRcode/Black Forest.png');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `user_email` varchar(200) NOT NULL,
  `user_password` varchar(200) NOT NULL,
  `user_name` varchar(200) NOT NULL,
  `user_type` enum('master','user') NOT NULL,
  `user_status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`user_id`, `fname`, `mname`, `lname`, `address`, `gender`, `user_email`, `user_password`, `user_name`, `user_type`, `user_status`) VALUES
(1, '', '', '', '', '', 'admin@gmail.com', '$2y$10$NbvkFNwjyjeikB.RMHL9UOxmuvk5PieH7zhIpgyWq3QQUtnELuD22', 'admin', 'master', 'Active'),
(18, 'Marthin', 'Dezon', 'Lopez', 'Sagarada, Anayan, Pili, Camarines Sur', 'Male', 'marthin21@yahoo.com', '$2y$10$oTAot/T5T907zgq56XuWqu1ddMKOZsx.Nt.X.upJhvP15dnKXM61a', 'Marthin21', 'user', 'Active'),
(21, 'Jino', 'Delo santos', 'Lopez', 'anayan, pili, cam, sur', 'Female', 'jino22@gmail.com', '$2y$10$1I3lX0zph.oeovYvjto9MOeunFNPPDVE3jdQlJdnTHTR5bMeYgWxe', 'jino22', 'user', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `order_tbl`
--
ALTER TABLE `order_tbl`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `order_tbl`
--
ALTER TABLE `order_tbl`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
