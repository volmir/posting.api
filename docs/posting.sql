-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 04 2018 г., 12:03
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
(26, 1, 'Post title name', 'Value of field type must be part of list: seven, three, eight', '2018-07-04 11:31:46', 1),
(27, 1, 'Post title name', 'Value of field type must be part of list: seven, three, eight', '2018-07-04 11:53:01', 1);

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
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `auth_key` varchar(50) NOT NULL,
  `access_token` varchar(50) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `auth_key`, `access_token`, `firstname`, `lastname`, `is_active`, `is_deleted`, `date_created`) VALUES
(1, 'admin', 'admin', 'admin@mail.com', 'auth-admin', '2KfdjUr34K5k73HJIKkrdf92dkLk', 'Alexandr', 'Petrov', 1, 0, '2018-06-27 12:36:15'),
(2, 'tester', 'tester', 'tester@mail.com', 'auth-tester', 'sjWk72kls39kdjk733KL3Llk2LJio', 'Nikolay', 'Ivanov', 1, 0, '2018-07-03 14:14:29');

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
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT для таблицы `post_vs_category`
--
ALTER TABLE `post_vs_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
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
