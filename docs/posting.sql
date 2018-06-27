-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 27, 2018 at 05:21 PM
-- Server version: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `posting`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`) VALUES
(1, 0, 'Раздел 1'),
(2, 0, 'Раздел 2'),
(3, 0, 'Раздел 3'),
(4, 1, 'Раздел 1.1'),
(5, 1, 'Раздел 1.2'),
(6, 4, 'Раздел 1.1.1'),
(7, 2, 'Раздел 2.1'),
(8, 2, 'Раздел 2.2'),
(9, 3, 'Раздел 3.1');

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1530085447);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(250) NOT NULL DEFAULT '',
  `content` text,
  `date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `content`, `date_create`, `status`) VALUES
(1, 1, 'Повседневная практика показывает', 'Таким образом реализация намеченных плановых заданий позволяет оценить значение новых предложений. Равным образом постоянный количественный рост и сфера нашей активности играет важную роль в формировании системы обучения кадров, соответствует насущным потребностям. Товарищи! постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет выполнять важные задания по разработке модели развития.', '2018-06-27 12:38:04', 1),
(2, 1, 'Данные передаем в JSON', 'Для изменения данных RESTful предполагает использования двух методов PUT и PATCH, различия в них лишь в том, что PUT предполагает замену записи полностью, а PATCH должен обновлять лишь данные, которые пришли в запросе.', '2018-06-27 15:11:57', 1),
(3, 1, 'С PUT и PATCH методами не всё так гладко', 'Многие сервисы используют метод PUT как псевдоним к методу PATCH, да и наш «любимый» браузер не так давно научился работать с методом PATCH, так что можете пойти проверенным путём, никто на вас обиды держать не будет', '2018-06-27 15:15:32', 1),
(6, 1, 'Post title name', 'Value of field type must be part of list: seven, three, eight', '2018-06-27 16:53:22', 1),
(7, 1, 'Another post name', 'Another value of field type must be part of list: blue, red, green', '2018-06-27 16:55:12', 1),
(8, 1, 'Post title', 'Content text', '2018-06-27 16:56:11', 1),
(9, 1, '2342342 Post title name', '234234Value of field type must be part of list: seven, three, eight', '2018-06-27 17:08:37', 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts_vs_categories`
--

CREATE TABLE `posts_vs_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts_vs_categories`
--

INSERT INTO `posts_vs_categories` (`id`, `post_id`, `category_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `login` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '1',
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `password`, `firstname`, `lastname`, `is_active`, `is_deleted`, `date_created`) VALUES
(1, 'tester', 'test@gmail.com', '111111', 'Alexandr', 'Petrenko', 1, 1, '2018-06-27 12:36:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts_vs_categories`
--
ALTER TABLE `posts_vs_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_id_category_id` (`post_id`,`category_id`),
  ADD KEY `FK_posts_vs_categories_categories` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login` (`login`),
  ADD KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `posts_vs_categories`
--
ALTER TABLE `posts_vs_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `FK_posts_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `posts_vs_categories`
--
ALTER TABLE `posts_vs_categories`
  ADD CONSTRAINT `FK_posts_vs_categories_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_posts_vs_categories_posts` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
