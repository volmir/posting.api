-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 06 2018 г., 17:17
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
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `parent_id`, `name`) VALUES
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
(18, 2, 'Post title name', 'Value of field type must be part of list: seven, three, eight', '2018-07-04 10:29:25', 1),
(27, 1, 'Post title name', 'Value of field type must be part of list: seven, three, eight', '2018-07-04 11:53:01', 1),
(28, 1, 'Post title name', 'Value of field type must be part of list: seven, three, eight', '2018-07-04 13:29:51', 1);

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
(1, 1, 1);

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
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `auth_key`, `access_token`, `password_reset_token`, `email_confirm_token`, `firstname`, `lastname`, `status`, `created_at`) VALUES
(1, 'admin', '$2y$13$NUQnYWDs.e/AmtLmH5mYqukSIm49KU3/bNG35MkWysMTbVixFZGuO', 'admin@mail.com', 'Q7cqvkF62XPvMVaR7hz-2SvVLvjpFvik', '2KfdjUr34K5k73HJIKkrdf92dkLk', '5Tfb10xbacBLZzpxZLo82d5gD7F4S9f5_1530881096', '', 'Alexandr', 'Petrov', 1, '2018-06-27 12:36:15'),
(2, 'tester', '$2y$13$CIgZx8YApWllOm7S73G3E.Xmw4RjQfCNx0x9uxBu9Zd/rDX4Fy5ES', 'tester@mail.com', 'qNfV-scJqYjpQlvCfXtztUZcS0xCf_ex', 'sjWk72kls39kdjk733KL3Llk2LJio', '', '', 'Nikolay', 'Ivanov', 1, '2018-07-03 14:14:29'),
(14, 'user', '$2y$13$8svCnhjsyhXdjf1qchdMUuKYZfjQyBju8OJCI7flS6JGt7UbiCUYa', 'user@gmail.com', 'OcCmB99oZIHWdakCClitar2fLN4JyarN', 'dH4Oica5nzKvFN-9kzr0jvu_FLNnC-27', '', '', 'Sergey', 'Ivanov', 1, '2018-07-05 15:45:10'),
(16, 'volmir', '$2y$13$i29vkcMpe/HXboPS5HdbZep5bdhtgo7uSz/6g3quJoCZNu2HQcCi.', 'volmir@ukr.net', 'LJ6M08PIkaIJJC3HwOJaCBz1DeJSPZDU', '-ruS-QVb0c8pTuoUETAoi1BCmngOM5me', '', '', 'Vladimir', 'Prokhnenko', 1, '2018-07-05 16:45:05'),
(18, 'admin3', '$2y$13$qG9DNS4LK2GjtWNWAp2xauqqqV9mnEYuqYgv5AsD3T42ZythTF/Eu', 'tester3@mail.com', 'E08yfBXa5SeNIrgH3EcSpC9wMjXFnJyf', 'DMZaVYEjmCW_fVzRC6G68uVhnlhF_W_g', '', '', '', '', 1, '2018-07-06 10:21:03'),
(19, 'admin4', '$2y$13$NUxI.caBZqL9KT5Mj2b09e/d63IkjNJ1oOMZ9FUOy7EVAJbOZoHMi', 'user4@gmail.com', 'ooOZm4SLw46tZ6L66KH63G5v7lrqV2Ew', '0HP_NRFN44tADAUAeUh06N07l2uQ0P_y', '', '', '', '', 1, '2018-07-06 10:30:59'),
(20, 'admin5', '$2y$13$plFCLurDsZUK8YezVWmrvOgbG1Td7ijkKsHa008s/.DsQaWM8.zJe', 'test5@gmail.com', '285FnP6vuTlczyjFOQ9-hWXOJt62W7lg', '3BaMSbRewA83LxBjORs9IwrG5JXHNTlC', '', 'hdzYKjPDMReGQWxLLqzW10z2MY-ZKnPy', '', '', 0, '2018-07-06 11:29:06');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Индексы таблицы `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `post`
--
ALTER TABLE `post`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT для таблицы `post_vs_category`
--
ALTER TABLE `post_vs_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
