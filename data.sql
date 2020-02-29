-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2019 at 10:57 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unidayschallenge`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbldiscount`
--

CREATE TABLE `tbldiscount` (
  `discountid` varchar(30) NOT NULL,
  `originalprice` double(10,2) NOT NULL,
  `qtyneeded` int(11) NOT NULL,
  `discountprice` double(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbldiscount`
--

INSERT INTO `tbldiscount` (`discountid`, `originalprice`, `qtyneeded`, `discountprice`) VALUES
('2for20', 12.00, 2, 20.00),
('3for10', 4.00, 3, 10.00),
('3forpriceof2', 5.00, 3, 10.00),
('bogof', 7.00, 2, 7.00),
('nodiscount', 8.00, 1, 8.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct`
--

CREATE TABLE `tblproduct` (
  `id` varchar(8) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `price` double(10,2) NOT NULL,
  `discountid` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblproduct`
--

INSERT INTO `tblproduct` (`id`, `name`, `image`, `price`, `discountid`) VALUES
('A', 'Razer Gaming Laptop', 'product-images/gaming-laptop.jpg', 8.00, 'nodiscount'),
('B', 'Dior Sauvage Fragrance', 'product-images/dior-fragrance.jpg', 12.00, '2for20'),
('C', 'Mr Kipling Battenberg', 'product-images/battenberg.jpg', 4.00, '3for10'),
('D', 'Xbox One Console', 'product-images/xbox-console.jpg', 7.00, 'bogof'),
('E', 'Nike Air Force One', 'product-images/af1.jpg', 5.00, '3forpriceof2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbldiscount`
--
ALTER TABLE `tbldiscount`
  ADD PRIMARY KEY (`discountid`);

--
-- Indexes for table `tblproduct`
--
ALTER TABLE `tblproduct`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
