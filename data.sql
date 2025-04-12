-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 06:00 PM
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
-- Database: `data`
--

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('text','swatch') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `name`, `type`) VALUES
(1, 'Size', 'text'),
(2, 'Color', 'swatch'),
(3, 'Capacity', 'text'),
(4, 'With USB 3 ports', 'text'),
(5, 'Touch ID in keyboard', 'text');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_values`
--

CREATE TABLE `attribute_values` (
  `id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  `display_value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attribute_values`
--

INSERT INTO `attribute_values` (`id`, `attribute_id`, `value`, `display_value`) VALUES
(1, 1, '40', '40'),
(2, 1, '41', '41'),
(3, 1, '42', '42'),
(4, 1, '43', '43'),
(5, 1, 'S', 'Small'),
(6, 1, 'M', 'Medium'),
(7, 1, 'L', 'Large'),
(8, 1, 'XL', 'Extra Large'),
(9, 2, '#44FF03', 'Green'),
(10, 2, '#03FFF7', 'Cyan'),
(11, 2, '#030BFF', 'Blue'),
(12, 2, '#000000', 'Black'),
(13, 2, '#FFFFFF', 'White'),
(14, 3, '512G', '512G'),
(15, 3, '1T', '1T'),
(16, 2, '#44FF03', 'Green'),
(17, 2, '#03FFF7', 'Cyan'),
(18, 2, '#030BFF', 'Blue'),
(19, 2, '#000000', 'Black'),
(20, 2, '#FFFFFF', 'White'),
(21, 3, '512G', '512G'),
(22, 3, '1T', '1T'),
(23, 3, '256GB', '256GB'),
(24, 3, '512GB', '512GB'),
(25, 4, 'Yes', 'Yes'),
(26, 4, 'No', 'No'),
(27, 5, 'Yes', 'Yes'),
(28, 5, 'No', 'No'),
(29, 3, '512G', '512G'),
(30, 3, '1T', '1T'),
(31, 2, '#44FF03', 'Green'),
(32, 2, '#03FFF7', 'Cyan'),
(33, 2, '#030BFF', 'Blue'),
(34, 2, '#000000', 'Black'),
(35, 2, '#FFFFFF', 'White');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'all'),
(2, 'clothes'),
(3, 'tech');

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` int(11) NOT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `image_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `product_id`, `image_url`) VALUES
(1, 'huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_2_720x.jpg?v=1612816087'),
(2, 'huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_1_720x.jpg?v=1612816087'),
(3, 'huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_3_720x.jpg?v=1612816087'),
(4, 'huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_5_720x.jpg?v=1612816087'),
(5, 'huarache-x-stussy-le', 'https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_4_720x.jpg?v=1612816087'),
(6, 'jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016105/product-image/2409L_61.jpg'),
(7, 'jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016107/product-image/2409L_61_a.jpg'),
(8, 'jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016108/product-image/2409L_61_b.jpg'),
(9, 'jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016109/product-image/2409L_61_c.jpg'),
(10, 'jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016110/product-image/2409L_61_d.jpg'),
(11, 'jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_1333,c_scale,f_auto,q_auto:best/v1634058169/product-image/2409L_61_o.png'),
(12, 'jacket-canada-goosee', 'https://images.canadagoose.com/image/upload/w_1333,c_scale,f_auto,q_auto:best/v1634058159/product-image/2409L_61_p.png'),
(13, 'ps-5', 'https://images-na.ssl-images-amazon.com/images/I/510VSJ9mWDL._SL1262_.jpg'),
(14, 'ps-5', 'https://images-na.ssl-images-amazon.com/images/I/610%2B69ZsKCL._SL1500_.jpg'),
(15, 'ps-5', 'https://images-na.ssl-images-amazon.com/images/I/51iPoFwQT3L._SL1230_.jpg'),
(16, 'ps-5', 'https://images-na.ssl-images-amazon.com/images/I/61qbqFcvoNL._SL1500_.jpg'),
(17, 'ps-5', 'https://images-na.ssl-images-amazon.com/images/I/51HCjA3rqYL._SL1230_.jpg'),
(18, 'xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/71vPCX0bS-L._SL1500_.jpg'),
(19, 'xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/71q7JTbRTpL._SL1500_.jpg'),
(20, 'xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/71iQ4HGHtsL._SL1500_.jpg'),
(21, 'xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/61IYrCrBzxL._SL1500_.jpg'),
(22, 'xbox-series-s', 'https://images-na.ssl-images-amazon.com/images/I/61RnXmpAmIL._SL1500_.jpg'),
(23, 'apple-imac-2021', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/imac-24-blue-selection-hero-202104?wid=904&hei=840&fmt=jpeg&qlt=80&.v=1617492405000'),
(24, 'apple-iphone-12-pro', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-12-pro-family-hero?wid=940&amp;hei=1112&amp;fmt=jpeg&amp;qlt=80&amp;.v=1604021663000'),
(25, 'apple-airpods-pro', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/MWP22?wid=572&hei=572&fmt=jpeg&qlt=95&.v=1591634795000'),
(26, 'apple-airtag', 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/airtag-double-select-202104?wid=445&hei=370&fmt=jpeg&qlt=95&.v=1617761672000');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `product_id`, `quantity`, `price`, `image`, `name`, `created_at`) VALUES
(2, 'ps-5', 1, 844.02, 'https://images-na.ssl-images-amazon.com/images/I/51iPoFwQT3L._SL1230_.jpg', 'PlayStation 5', NULL),
(3, 'apple-imac-2021', 1, 1688.03, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/imac-24-blue-selection-hero-202104?wid=904&hei=840&fmt=jpeg&qlt=80&.v=1617492405000', 'iMac 2021', NULL),
(4, 'apple-iphone-12-pro', 1, 1000.76, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-12-pro-family-hero?wid=940&amp;hei=1112&amp;fmt=jpeg&amp;qlt=80&amp;.v=1604021663000', 'iPhone 12 Pro', NULL),
(5, 'apple-iphone-12-pro', 1, 1000.76, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-12-pro-family-hero?wid=940&amp;hei=1112&amp;fmt=jpeg&amp;qlt=80&amp;.v=1604021663000', 'iPhone 12 Pro', NULL),
(6, 'apple-imac-2021', 1, 1688.03, 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/imac-24-blue-selection-hero-202104?wid=904&hei=840&fmt=jpeg&qlt=80&.v=1617492405000', 'iMac 2021', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_attribute_values`
--

CREATE TABLE `order_attribute_values` (
  `order_id` int(11) NOT NULL,
  `attribute_value_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_attribute_values`
--

INSERT INTO `order_attribute_values` (`order_id`, `attribute_value_id`) VALUES
(2, 9),
(2, 14),
(4, 9),
(4, 14),
(5, 9),
(5, 14),
(6, 23),
(6, 25),
(6, 27);

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE `prices` (
  `id` int(11) NOT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `currency_label` varchar(10) DEFAULT NULL,
  `currency_symbol` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`id`, `product_id`, `amount`, `currency_label`, `currency_symbol`) VALUES
(1, 'huarache-x-stussy-le', 144.69, 'USD', '$'),
(2, 'jacket-canada-goosee', 518.47, 'USD', '$'),
(3, 'ps-5', 844.02, 'USD', '$'),
(4, 'xbox-series-s', 333.99, 'USD', '$'),
(5, 'apple-imac-2021', 1688.03, 'USD', '$'),
(6, 'apple-iphone-12-pro', 1000.76, 'USD', '$'),
(7, 'apple-airpods-pro', 300.23, 'USD', '$'),
(8, 'apple-airtag', 120.57, 'USD', '$');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `in_stock` tinyint(1) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `in_stock`, `description`, `category`, `brand`) VALUES
('apple-airpods-pro', 'AirPods Pro', 0, '\r\n<h3>Magic like you’ve never heard</h3>\r\n<p>AirPods Pro have been designed to deliver Active Noise Cancellation for immersive sound, Transparency mode so you can hear your surroundings, and a customizable fit for all-day comfort. Just like AirPods, AirPods Pro connect magically to your iPhone or Apple Watch. And they’re ready to use right out of the case.\r\n\r\n<h3>Active Noise Cancellation</h3>\r\n<p>Incredibly light noise-cancelling headphones, AirPods Pro block out your environment so you can focus on what you’re listening to. AirPods Pro use two microphones, an outward-facing microphone and an inward-facing microphone, to create superior noise cancellation. By continuously adapting to the geometry of your ear and the fit of the ear tips, Active Noise Cancellation silences the world to keep you fully tuned in to your music, podcasts, and calls.\r\n\r\n<h3>Transparency mode</h3>\r\n<p>Switch to Transparency mode and AirPods Pro let the outside sound in, allowing you to hear and connect to your surroundings. Outward- and inward-facing microphones enable AirPods Pro to undo the sound-isolating effect of the silicone tips so things sound and feel natural, like when you’re talking to people around you.</p>\r\n\r\n<h3>All-new design</h3>\r\n<p>AirPods Pro offer a more customizable fit with three sizes of flexible silicone tips to choose from. With an internal taper, they conform to the shape of your ear, securing your AirPods Pro in place and creating an exceptional seal for superior noise cancellation.</p>\r\n\r\n<h3>Amazing audio quality</h3>\r\n<p>A custom-built high-excursion, low-distortion driver delivers powerful bass. A superefficient high dynamic range amplifier produces pure, incredibly clear sound while also extending battery life. And Adaptive EQ automatically tunes music to suit the shape of your ear for a rich, consistent listening experience.</p>\r\n\r\n<h3>Even more magical</h3>\r\n<p>The Apple-designed H1 chip delivers incredibly low audio latency. A force sensor on the stem makes it easy to control music and calls and switch between Active Noise Cancellation and Transparency mode. Announce Messages with Siri gives you the option to have Siri read your messages through your AirPods. And with Audio Sharing, you and a friend can share the same audio stream on two sets of AirPods — so you can play a game, watch a movie, or listen to a song together.</p>\r\n', 'tech', 'Apple'),
('apple-airtag', 'AirTag', 1, '\r\n<h1>Lose your knack for losing things.</h1>\r\n<p>AirTag is an easy way to keep track of your stuff. Attach one to your keys, slip another one in your backpack. And just like that, they’re on your radar in the Find My app. AirTag has your back.</p>\r\n', 'tech', 'Apple'),
('apple-imac-2021', 'iMac 2021', 1, 'The new iMac!', 'tech', 'Apple'),
('apple-iphone-12-pro', 'iPhone 12 Pro', 1, 'This is iPhone 12. Nothing else to say.', 'tech', 'Apple'),
('huarache-x-stussy-le', 'Nike Air Huarache Le', 1, '<p>Great sneakers for everyday use!</p>', 'clothes', 'Nike x Stussy'),
('jacket-canada-goosee', 'Jacket', 1, '<p>Awesome winter jacket</p>', 'clothes', 'Canada Goose'),
('ps-5', 'PlayStation 5', 1, '<p>A good gaming console. Plays games of PS4! Enjoy if you can buy it mwahahahaha</p>', 'tech', 'Sony'),
('xbox-series-s', 'Xbox Series S 512GB', 0, '\r\n<div>\r\n    <ul>\r\n        <li><span>Hardware-beschleunigtes Raytracing macht dein Spiel noch realistischer</span></li>\r\n        <li><span>Spiele Games mit bis zu 120 Bilder pro Sekunde</span></li>\r\n        <li><span>Minimiere Ladezeiten mit einer speziell entwickelten 512GB NVMe SSD und wechsle mit Quick Resume nahtlos zwischen mehreren Spielen.</span></li>\r\n        <li><span>Xbox Smart Delivery stellt sicher, dass du die beste Version deines Spiels spielst, egal, auf welcher Konsole du spielst</span></li>\r\n        <li><span>Spiele deine Xbox One-Spiele auf deiner Xbox Series S weiter. Deine Fortschritte, Erfolge und Freundesliste werden automatisch auf das neue System übertragen.</span></li>\r\n        <li><span>Erwecke deine Spiele und Filme mit innovativem 3D Raumklang zum Leben</span></li>\r\n        <li><span>Der brandneue Xbox Wireless Controller zeichnet sich durch höchste Präzision, eine neue Share-Taste und verbesserte Ergonomie aus</span></li>\r\n        <li><span>Ultra-niedrige Latenz verbessert die Reaktionszeit von Controller zum Fernseher</span></li>\r\n        <li><span>Verwende dein Xbox One-Gaming-Zubehör -einschließlich Controller, Headsets und mehr</span></li>\r\n        <li><span>Erweitere deinen Speicher mit der Seagate 1 TB-Erweiterungskarte für Xbox Series X (separat erhältlich) und streame 4K-Videos von Disney+, Netflix, Amazon, Microsoft Movies &amp; TV und mehr</span></li>\r\n    </ul>\r\n</div>', 'tech', 'Microsoft');

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_values`
--

CREATE TABLE `product_attribute_values` (
  `product_id` varchar(255) NOT NULL,
  `attribute_value_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_attribute_values`
--

INSERT INTO `product_attribute_values` (`product_id`, `attribute_value_id`) VALUES
('apple-imac-2021', 23),
('apple-imac-2021', 24),
('apple-imac-2021', 25),
('apple-imac-2021', 26),
('apple-imac-2021', 27),
('apple-imac-2021', 28),
('apple-iphone-12-pro', 29),
('apple-iphone-12-pro', 30),
('apple-iphone-12-pro', 31),
('apple-iphone-12-pro', 32),
('apple-iphone-12-pro', 33),
('apple-iphone-12-pro', 34),
('apple-iphone-12-pro', 35),
('huarache-x-stussy-le', 1),
('huarache-x-stussy-le', 2),
('huarache-x-stussy-le', 3),
('huarache-x-stussy-le', 4),
('jacket-canada-goosee', 5),
('jacket-canada-goosee', 6),
('jacket-canada-goosee', 7),
('jacket-canada-goosee', 8),
('ps-5', 9),
('ps-5', 10),
('ps-5', 11),
('ps-5', 12),
('ps-5', 13),
('ps-5', 14),
('ps-5', 15),
('xbox-series-s', 16),
('xbox-series-s', 17),
('xbox-series-s', 18),
('xbox-series-s', 19),
('xbox-series-s', 20),
('xbox-series-s', 21),
('xbox-series-s', 22);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_id` (`attribute_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_attribute_values`
--
ALTER TABLE `order_attribute_values`
  ADD PRIMARY KEY (`order_id`,`attribute_value_id`),
  ADD KEY `attribute_value_id` (`attribute_value_id`);

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD PRIMARY KEY (`product_id`,`attribute_value_id`),
  ADD KEY `attribute_value_id` (`attribute_value_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `attribute_values`
--
ALTER TABLE `attribute_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `prices`
--
ALTER TABLE `prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD CONSTRAINT `attribute_values_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `galleries`
--
ALTER TABLE `galleries`
  ADD CONSTRAINT `galleries_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `order_attribute_values`
--
ALTER TABLE `order_attribute_values`
  ADD CONSTRAINT `order_attribute_values_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_attribute_values_ibfk_2` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`);

--
-- Constraints for table `prices`
--
ALTER TABLE `prices`
  ADD CONSTRAINT `prices_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD CONSTRAINT `product_attribute_values_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_attribute_values_ibfk_2` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
