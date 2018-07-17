-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 17 2018 г., 17:55
-- Версия сервера: 5.7.22-0ubuntu0.16.04.1
-- Версия PHP: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `posting`
--

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT '0',
  `name` varchar(250) NOT NULL,
  `status` tinyint(4) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `parent_id`, `name`, `status`) VALUES
(0, 0, '', 0),
(1, 0, 'Раздел 1', 1),
(2, 0, 'Раздел 2', 1),
(3, 0, 'Раздел 3', 1),
(4, 1, 'Раздел 1.1', 1),
(5, 1, 'Раздел 1.2', 1),
(6, 4, 'Раздел 1.1.1', 1),
(7, 2, 'Раздел 2.1', 1),
(8, 2, 'Раздел 2.2', 1),
(9, 3, 'Раздел 3.1', 1),
(10, 9, '3.1.1', 1),
(11, 9, '3.1.2', 1),
(12, 1, 'Section 4.7', 0),
(13, 2, 'Section 4.7', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `client`
--

CREATE TABLE `client` (
  `id` int(10) UNSIGNED NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `client`
--

INSERT INTO `client` (`id`, `description`) VALUES
(3, 'Virtual client');

-- --------------------------------------------------------

--
-- Структура таблицы `company`
--

CREATE TABLE `company` (
  `id` int(10) UNSIGNED NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `company`
--

INSERT INTO `company` (`id`, `description`) VALUES
(4, 'Company description'),
(39, 'Company short description');

-- --------------------------------------------------------

--
-- Структура таблицы `currency`
--

CREATE TABLE `currency` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `code` varchar(50) NOT NULL DEFAULT '',
  `name_short` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `currency`
--

INSERT INTO `currency` (`id`, `name`, `code`, `name_short`) VALUES
(1, 'Украинская гривна', 'UAH', 'грн.'),
(2, 'Доллар США', 'USD', 'долл.'),
(3, 'Евро', 'EUR', 'евро'),
(4, 'Российский рубль', 'RUB', 'руб.');

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1530085447);

-- --------------------------------------------------------

--
-- Структура таблицы `order`
--

CREATE TABLE `order` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `status_client` int(4) UNSIGNED NOT NULL DEFAULT '1',
  `status_specialist` int(4) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `order`
--

INSERT INTO `order` (`id`, `client_id`, `schedule_id`, `status_client`, `status_specialist`, `created_at`) VALUES
(1, 3, 1, 2, 1, '2018-07-16 15:47:11'),
(2, 3, 2, 2, 1, '2018-07-16 15:47:38'),
(4, 3, 3, 2, 1, '2018-07-16 17:10:06'),
(5, 3, 4, 1, 1, '2018-07-17 13:07:17'),
(9, 3, 7, 2, 2, '2018-07-17 15:16:15');

-- --------------------------------------------------------

--
-- Структура таблицы `order_status`
--

CREATE TABLE `order_status` (
  `id` int(4) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `order_status`
--

INSERT INTO `order_status` (`id`, `name`) VALUES
(1, 'Создан'),
(2, 'Принят'),
(3, 'Выполнен'),
(4, 'Отменен');

-- --------------------------------------------------------

--
-- Структура таблицы `order_vs_service`
--

CREATE TABLE `order_vs_service` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `order_vs_service`
--

INSERT INTO `order_vs_service` (`id`, `order_id`, `service_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1),
(4, 4, 2),
(5, 4, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `post`
--

CREATE TABLE `post` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(250) NOT NULL DEFAULT '',
  `content` text,
  `date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `post`
--

INSERT INTO `post` (`id`, `user_id`, `title`, `content`, `date_create`, `status`) VALUES
(1, 1, 'Повседневная практика показывает', 'Таким образом реализация намеченных плановых заданий позволяет оценить значение новых предложений. Равным образом постоянный количественный рост и сфера нашей активности играет важную роль в формировании системы обучения кадров, соответствует насущным потребностям. Товарищи! постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет выполнять важные задания по разработке модели развития.', '2018-06-27 12:38:04', 1),
(2, 1, 'Данные передаем в JSON', 'Для изменения данных RESTful предполагает использования двух методов PUT и PATCH, различия в них лишь в том, что PUT предполагает замену записи полностью, а PATCH должен обновлять лишь данные, которые пришли в запросе.', '2018-06-27 15:11:57', 1),
(3, 1, 'С PUT и PATCH методами не всё так гладко', 'Многие сервисы используют метод PUT как псевдоним к методу PATCH, да и наш «любимый» браузер не так давно научился работать с методом PATCH, так что можете пойти проверенным путём, никто на вас обиды держать не будет', '2018-06-27 15:15:32', 1),
(6, 1, 'Post title name', 'Value of field type must be part of list: seven, three, eight', '2018-06-27 16:53:22', 1),
(7, 1, 'Another post name', 'Another value of field type must be part of list: blue, red, green', '2018-06-27 16:55:12', 1),
(8, 1, 'Post title', 'Content text', '2018-06-27 16:56:11', 1),
(10, 1, 'Some post name', 'Another value of field type must be part of list: blue, red, green', '2018-07-02 15:23:58', 1),
(12, 1, 'Post title name', 'Value of field type must be part of list: seven, three, eight', '2018-07-02 16:37:11', 1),
(18, 6, 'Post title name', 'Value of field type must be part of list: seven, three, eight', '2018-07-04 10:29:25', 0),
(27, 1, 'Post title name', 'Value of field type must be part of list: seven, three, eight', '2018-07-04 11:53:01', 2),
(28, 1, 'Post title name', 'Value of field type must be part of list: seven, three, eight', '2018-07-04 13:29:51', 2),
(31, 1, 'Post title name', 'Value of field type must be part of list: seven, three, eight', '2018-07-09 11:22:45', 1),
(32, 1, 'Post title name', 'Value of field type must be part of list: seven, three, eight', '2018-07-12 16:03:09', 1),
(33, 1, 'Post title name', 'Value of field type must be part of list: seven, three, eight', '2018-07-17 16:12:59', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `post_vs_category`
--

CREATE TABLE `post_vs_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `post_vs_category`
--

INSERT INTO `post_vs_category` (`id`, `post_id`, `category_id`) VALUES
(4, 1, 2),
(1, 1, 9),
(7, 2, 4),
(5, 2, 5),
(6, 3, 11);

-- --------------------------------------------------------

--
-- Структура таблицы `schedule`
--

CREATE TABLE `schedule` (
  `id` int(10) UNSIGNED NOT NULL,
  `specialist_id` int(10) UNSIGNED NOT NULL,
  `date_from` datetime NOT NULL,
  `date_to` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `schedule`
--

INSERT INTO `schedule` (`id`, `specialist_id`, `date_from`, `date_to`) VALUES
(1, 5, '2018-07-16 13:48:28', '2018-07-16 13:48:31'),
(2, 5, '2018-07-16 12:50:26', '2018-07-16 12:50:34'),
(3, 37, '2018-07-16 17:07:53', '2018-07-16 17:07:55'),
(4, 5, '2018-07-15 16:00:00', '2018-07-15 17:00:00'),
(7, 37, '2018-07-17 14:53:46', '2018-07-17 14:53:48'),
(8, 5, '2018-07-15 16:00:00', '2018-07-15 17:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `service`
--

CREATE TABLE `service` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency_id` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `service`
--

INSERT INTO `service` (`id`, `category_id`, `company_id`, `price`, `currency_id`) VALUES
(1, 1, 4, '10.00', 1),
(2, 2, 4, '15.00', 1),
(3, 4, 4, '30.00', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `specialist`
--

CREATE TABLE `specialist` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `specialist`
--

INSERT INTO `specialist` (`id`, `company_id`, `description`) VALUES
(5, 4, NULL),
(37, 4, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `auth_key` varchar(60) NOT NULL DEFAULT '',
  `access_token` varchar(60) NOT NULL DEFAULT '',
  `password_reset_token` varchar(60) NOT NULL DEFAULT '',
  `email_confirm_token` varchar(60) NOT NULL DEFAULT '',
  `firstname` varchar(100) NOT NULL DEFAULT '',
  `lastname` varchar(100) NOT NULL DEFAULT '',
  `phone` varchar(100) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `auth_key`, `access_token`, `password_reset_token`, `email_confirm_token`, `firstname`, `lastname`, `phone`, `status`, `type`, `created_at`) VALUES
(1, 'admin', '$2y$13$NUQnYWDs.e/AmtLmH5mYqukSIm49KU3/bNG35MkWysMTbVixFZGuO', 'admin@mail.com', 'Q7cqvkF62XPvMVaR7hz-2SvVLvjpFvik', '2KfdjUr34K5k73HJIKkrdf92dkLk', '5Tfb10xbacBLZzpxZLo82d5gD7F4S9f5_1530881096', '', 'Alexandr', 'Petrov', '', 1, 1, '2018-06-27 12:36:15'),
(2, 'user', '$2y$13$.tF0DEF2KF4Pjl5niST.XO2r5KmXCFrnYTzuLXOyYfpLYy0nhbEEy', 'user@gmail.com', 'OcCmB99oZIHWdakCClitar2fLN4JyarN', 'dH4Oica5nzKvFN-9kzr0jvu_FLNnC-27', '', '', 'Sergey', 'Ivanov', '', 1, 0, '2018-07-05 15:45:10'),
(3, 'client', '$2y$13$F58K0HWi8Bvm/9oYIsaRlOnS7ewh8qjXIySzD9FSoAN9XXiTVx6ty', 'client@example.com', 'dLBSPihHn3HMqG4KKvYin38ooRIkqLjG', 'xcGiPQH-s_ejo-e1xNG_kAkK3WPELTfO', '', '_B2SWWQekzMMBliOf-oGty9pJC6SWV4o', 'John', 'Smith', '', 1, 4, '2018-07-13 17:15:46'),
(4, 'company', '$2y$13$xxPCurEkeBdqLEdGSJkR1ejUWxeytIRZjEYovyRUxPdlANR.ao2Be', 'company@mail.com', 'E08yfBXa5SeNIrgH3EcSpC9wMjXFnJyf', 'DMZaVYEjmCW_fVzRC6G68uVhnlhF_W_g', '', '', 'Victor', 'Chernov', '(050) 384-70-16', 1, 2, '2018-07-06 10:21:03'),
(5, 'specialist', '$2y$13$LS15CIMbxtROhrh4rfZoAuAs0F1/vKq3NiBP1ulgwaJT0aa4F37Y6', 'specialist@example.com', 'Q8VrAf6BADt_HBrcP8BFLYFKR8WBZL6D', '_xpE7SjDaxnl3MerLtld8xh3S3XG1U4T', '', 'zzXu0wnqQ2v4wRNcvmTmi7Sc8MGWppBN', 'Vasiliy', 'Kononenko', '', 1, 3, '2018-07-13 16:45:42'),
(6, 'tester', '$2y$13$CIgZx8YApWllOm7S73G3E.Xmw4RjQfCNx0x9uxBu9Zd/rDX4Fy5ES', 'tester@mail.com', 'qNfV-scJqYjpQlvCfXtztUZcS0xCf_ex', 'sjWk72kls39kdjk733KL3Llk2LJio', '', '', 'Nikolay', 'Ivanov', '', 1, 0, '2018-07-03 14:14:29'),
(10, 'volmir', '$2y$13$i29vkcMpe/HXboPS5HdbZep5bdhtgo7uSz/6g3quJoCZNu2HQcCi.', 'volmir@ukr.net', 'LJ6M08PIkaIJJC3HwOJaCBz1DeJSPZDU', '-ruS-QVb0c8pTuoUETAoi1BCmngOM5me', '', '', 'Vladimir', 'Prokhnenko', '', 1, 0, '2018-07-05 16:45:05'),
(19, 'admin4', '$2y$13$hu4kUfThVIVtaoVzLI2rAuUm.GhGk.0k0RiNtjcG5NpPqz0cuxkpq', 'user4@gmail.com', 'ooOZm4SLw46tZ6L66KH63G5v7lrqV2Ew', '0HP_NRFN44tADAUAeUh06N07l2uQ0P_y', '', '', '', '', '', 2, 0, '2018-07-06 10:30:59'),
(21, 'user5', '$2y$13$uAR/PP6Yd3CjnngmIw9qceROIDQERz4OuE4vWujl6KVZ1etGw.8zW', 'user5@posting.local', 'tzX8AWLziYjQkxOQIQPpUXetXxoPVrbh', '_CEN8LZty__HqjfCXgVoANhEXDdghnc6', '', 'uNRoWVUQ_xa5BRiugtdj21tanDqexwxZ', '', '', '', 0, 0, '2018-07-10 10:01:59'),
(22, 'user7', '$2y$13$zNBG8kcKDZA15HdmH4QrYe7HyJzqo0NFrCDzuBiwwtFMQBPmE65IC', 'user7@posting.local', 'ScMrrOFZxnRcVY2acDmbxh6QTrEi3b1F', 'QWc-cuVus5-8hHHGaSgNX0LYs2qUri5G', '', '', 'Dasdfasfa', 'Kdfdsdfsdfsd', '', 1, 0, '2018-07-10 13:20:03'),
(23, 'company2', '$2y$13$xxPCurEkeBdqLEdGSJkR1ejUWxeytIRZjEYovyRUxPdlANR.ao2Be', 'company2@mail.com', '0fdTFy-ZD86WhLLU3UVWiAM1H2-p-Rhv', 'kZvbjdipzmASLSbmHh3M9z7EB_ONZY8b', '', 'LHN7QnPFyGtA-3vaDzzYNKLW3UyIULY8', '', '', '', 0, 0, '2018-07-13 15:44:05'),
(28, 'client869', '$2y$13$dxK3sxvLwfDGb/5bYhtB2OblMVy3No5OJealB24Ph5Vx6kQaZAZli', 'client869@example.com', 'EN705PYVXeDIVaCy4IMGwHiPWA7waFAV', 'WIIbZ3XLCX4IifN2orhLygvoYYL1xdWR', '', 'I-uwYW54GEMfvcH8Pgz-5WrjY_i-vWwt', 'John', 'Smith', '', 1, 4, '2018-07-13 17:15:28'),
(37, 'specialist269', '$2y$13$8Q6fMNb2GOfELAeDyhzIG.cWx2UvNAyvvjOGntPtZ5p2ENYl2bECW', 'specialist269@example.com', 'AqWrPXqBAQQ8y19gH1SBygC95jd2w_jZ', 'fgeGW3EMEvxahBEd_O3uyqXanxE_atAN', '', 'MaoOtxr-MX33FN8hporZhnZRV2rBV1JX', 'John', 'Smith', '(099) 376-43-29', 1, 3, '2018-07-16 16:05:54'),
(39, 'company837', '$2y$13$s5GEq91sYeyyiqwo/SGtYu802vWwWdcfBt2VFd6FBeIf9iMKl3YNG', 'company@example.com', 'PHP30MTu7wv12Zwh81tQ-SIcS_u_oz0C', 'gXM9CueL-0D2MAPOZhLFPhDbD75jt3xT', '', 't4dyscJnRg3URLztfda5Nn0eKQo-BULM', '', '', '(099) 376-43-29', 1, 2, '2018-07-16 16:12:08');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Индексы таблицы `client`
--
ALTER TABLE `client`
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `company`
--
ALTER TABLE `company`
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `schedule_id` (`schedule_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Индексы таблицы `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `order_vs_service`
--
ALTER TABLE `order_vs_service`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id_service_id` (`order_id`,`service_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Индексы таблицы `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `post_vs_category`
--
ALTER TABLE `post_vs_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_id_category_id` (`post_id`,`category_id`),
  ADD KEY `FK_posts_vs_categories_categories` (`category_id`);

--
-- Индексы таблицы `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specialist_id` (`specialist_id`);

--
-- Индексы таблицы `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `company_id_catalog_id` (`company_id`,`category_id`),
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `specialist`
--
ALTER TABLE `specialist`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `password_reset_token` (`password_reset_token`),
  ADD KEY `email_confirm_token` (`email_confirm_token`),
  ADD KEY `status` (`status`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT для таблицы `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `order`
--
ALTER TABLE `order`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `order_status`
--
ALTER TABLE `order_status`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `order_vs_service`
--
ALTER TABLE `order_vs_service`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `post`
--
ALTER TABLE `post`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT для таблицы `post_vs_category`
--
ALTER TABLE `post_vs_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `service`
--
ALTER TABLE `service`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_fk1` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `client_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `company_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `order_ibfk_3` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_vs_service`
--
ALTER TABLE `order_vs_service`
  ADD CONSTRAINT `order_vs_service_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `order_vs_service_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_posts_users` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `post_vs_category`
--
ALTER TABLE `post_vs_category`
  ADD CONSTRAINT `FK_posts_vs_categories_categories` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_posts_vs_categories_posts` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`specialist_id`) REFERENCES `specialist` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `service_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `service_ibfk_3` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `specialist`
--
ALTER TABLE `specialist`
  ADD CONSTRAINT `specialist_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `specialist_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
