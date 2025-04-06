-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2025 at 06:49 AM
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
-- Database: `e-commerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

CREATE TABLE `card` (
  `title` varchar(121) NOT NULL,
  `subtitle` varchar(121) NOT NULL,
  `thumbnail` varchar(121) NOT NULL,
  `discription` varchar(121) NOT NULL,
  `cash` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `card`
--

INSERT INTO `card` (`title`, `subtitle`, `thumbnail`, `discription`, `cash`, `id`) VALUES
('hanako ', 'butterfly', 'https://i.pinimg.com/236x/f3/13/69/f313692cbc7e29907bdaf5474f16483a.jpg', 'this is gojo an alibino man he was suppose to be afrikan but due to desease of albinism his cells lost all of his natural', 1212, 3);

-- --------------------------------------------------------

--
-- Table structure for table `costumer`
--

CREATE TABLE `costumer` (
  `costumer_id` int(11) NOT NULL,
  `costumer_name` varchar(111) NOT NULL,
  `email` varchar(121) NOT NULL,
  `contact` int(11) NOT NULL,
  `password` varchar(212) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `costumer`
--

INSERT INTO `costumer` (`costumer_id`, `costumer_name`, `email`, `contact`, `password`) VALUES
(1, 'bipa', '123456789@gmail.com', 11212, '$2y$10$9v3tl6cuqYvDgqxm4I962eLRHZgxyxOSrce3O9W3e918wnMloQkfS'),
(2, '121asda', '1111@gmail.com', 0, '$2y$10$6GGOiFbYeW3tNHzqDduprOmYdp8JEKjECkLCQj4LfV5r981FIn36G'),
(3, 'nanse', 'nanase12@gmail.com', 0, '$2y$10$GsfEQHnDg4uZ2TYJX/dG0OB91pWsOOHVfStHJyjxXmEVDpPGyHtkm'),
(4, 'nama', 'nama12@gmail.com', 0, '$2y$10$cOgaHO2e7ndr1Jvzx0ah6emDneBDf9JWDO.A00kTJYeFy5UvwaqBi'),
(5, 'adhsjkd', '12412@asd.sjad', 2147483647, '$2y$10$756OgpQ7nHtQg2iMcq6s4.giP57ZCbXhSE5CviTxAW8De/cTyAQLW');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `costumer_id` int(11) NOT NULL,
  `saller_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `tracking_number` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `saller_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `saller_id`, `product_name`, `stock_quantity`, `description`, `price`, `stock`, `category`, `image_path`) VALUES
(1, 1, 'wahst', 0, 'sasd', 1211.00, 12, 'antique', '../assets/upload/dress.jpg'),
(2, 1, 'wahstAWEW', 0, 'sde', 1244.00, 12, 'antique', '../assets/upload/69fb557d82d18728e6c26a93396be8ae.jpg'),
(3, 1, 'asasd', 0, 'dasd', 2122.00, 21, 'sass', '../assets/upload/67bd536d472618339e489f9e1ef0ddd4.jpg'),
(4, 1, 'ssaa', 11, 'ssssssddddddddd', 11111.00, 211, 'beauty', '../assets/upload/69db9f71138844434ec280cdca8fb5df.jpg'),
(5, 8, 'asasasasasas', 0, 'sas', 111.00, 12, 'sssssss', '../assets/upload/354dbc47148549337d888f8d83d57db6.jpg'),
(6, 8, 'sadadasd', 0, 'dawaeadd', 111111.00, 121, 'wq', '../assets/upload/23.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `saller`
--

CREATE TABLE `saller` (
  `saller_id` int(111) NOT NULL,
  `contact` int(111) NOT NULL,
  `email` varchar(121) NOT NULL,
  `user_name` varchar(111) NOT NULL,
  `shop_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `password` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saller`
--

INSERT INTO `saller` (`saller_id`, `contact`, `email`, `user_name`, `shop_name`, `address`, `password`) VALUES
(1, 900000999, '', 'bipana', '', '', '$2y$10$Frt3tR8GJTnKQd92VnTpe.HkEA5d1yISToWAmT5bsHSDVML96bKCG'),
(8, 0, '212121@gmail.com', 'hanako', 'sda', 'sadd', '$2y$10$nfjS.RW4dSjPbWZnxLRBOOe4TTzm/Qzvz6gV2uYkwSp4Dcl8rEiS6'),
(10, 0, 'sdas@gmail.com', 'asas', 'asd', 'sda', '$2y$10$aJAwEctO4knkE0b.2mbJO.pmlS9UXxsvVVesfOzuKa5iCZMwjff/O'),
(11, 0, 'saasa@sdasd.cg', 'saa', 'sda', 'dsfsd', '$2y$10$JZJ3CV5x3WW/Un5x85l7O.E7TtAIbdR7KFjGl8VtFAO.N2yTQcnjC'),
(12, 2147483647, 'nana21@gmail.com', 'nana', 'ss', 'sadsd', '$2y$10$ljwMDOMUhi6u4pgXv4Mdiu.L1hmkKW6g3qjhSkYQhUQcdmppNxhvG'),
(14, 2147483647, '111@sda.sssss', 'sasa', 'asa', 'sas', '$2y$10$GyDxadzLd7UGw86T2fTDweid00JPhbc2bcMeaoMXh5gWdiedvbPvS'),
(15, 1225121, 'hanako@gmail.com', 'supreem', 'asjdgha', '122jhasdb', '$2y$10$C/Jr5cbpoOo8PfnxFXjgFeDfgn/6DRECt8ocvhnkgy6WOiNwwsPpi'),
(16, 2147483647, '123@gaksd.saidj', 'ashakd', 'sads', 'asssssssss', '$2y$10$x6Ctlljv6dxy0pp1Bdr8Ne2Y.uN5a6UW/MUzx4xRi3XAPAzpqaPFa'),
(17, 2147483647, 'wea@asd.das', 'asdjn', 'adada', 'fffffffffffffffffffffffffffffff', '$2y$10$qD2TNJ3aP8/Zz1WQ8oKqEOXuwH0gBb.iv5ctj00Lpq5lAuM5bgBnu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `costumer`
--
ALTER TABLE `costumer`
  ADD PRIMARY KEY (`costumer_id`),
  ADD UNIQUE KEY `password` (`password`),
  ADD UNIQUE KEY `costumer_name` (`costumer_name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `saller_id` (`saller_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `customer_id` (`costumer_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `saller_id` (`saller_id`);

--
-- Indexes for table `saller`
--
ALTER TABLE `saller`
  ADD PRIMARY KEY (`saller_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `password` (`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `card`
--
ALTER TABLE `card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `costumer`
--
ALTER TABLE `costumer`
  MODIFY `costumer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `saller`
--
ALTER TABLE `saller`
  MODIFY `saller_id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`saller_id`) REFERENCES `saller` (`saller_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`costumer_id`) REFERENCES `costumer` (`costumer_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`saller_id`) REFERENCES `saller` (`saller_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
