-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 26, 2018 at 05:32 PM
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
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT '0',
  `name` varchar(250) NOT NULL,
  `status` tinyint(4) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
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
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int(10) UNSIGNED NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `description`) VALUES
(3, 'Virtual client'),
(28, 'Client description');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `specialist_id` int(10) UNSIGNED DEFAULT NULL,
  `text` text NOT NULL,
  `rating` int(4) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `client_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `company_id`, `specialist_id`, `text`, `rating`, `created_at`, `client_id`) VALUES
(1, 4, 5, 'Comment text', 3, '2018-07-24 10:31:36', 28),
(2, 4, 37, 'Good work', 5, '2018-07-24 10:32:57', 28),
(3, 4, NULL, 'Various versions have evolved over the years, sometimes by accident', 4, '2018-07-24 11:45:22', 28),
(5, 4, 5, 'Comment full text', 5, '2018-07-24 14:27:10', 28),
(6, 4, 5, 'Comment text', NULL, '2018-07-24 15:14:14', 28);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL,
  `fullname` varchar(512) DEFAULT NULL,
  `address` text,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `country_id`, `fullname`, `address`, `description`) VALUES
(4, 248, 'ANT Company', 'Kiev, 01001, First st. 17, of. 307', 'Company description'),
(39, 248, 'Rent Agency', 'Kharkov, 61000, Second st. 24, of. 218', 'Company short description'),
(40, 248, 'Trade B2B', 'Kiev, 01001, Third st. 27, of. 709', 'Company detail description');

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int(10) UNSIGNED NOT NULL,
  `cc_iso` varchar(2) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `cc_iso`, `name`) VALUES
(1, 'AW', 'Aruba'),
(2, 'AG', 'Antigua and Barbuda'),
(3, 'AE', 'United Arab Emirates'),
(4, 'AF', 'Afghanistan'),
(5, 'DZ', 'Algeria'),
(6, 'AZ', 'Azerbaijan'),
(7, 'AL', 'Albania'),
(8, 'AM', 'Armenia'),
(9, 'AD', 'Andorra'),
(10, 'AO', 'Angola'),
(11, 'AS', 'American Samoa'),
(12, 'AR', 'Argentina'),
(13, 'AU', 'Australia'),
(14, '', 'Ashmore and Cartier Islands'),
(15, 'AT', 'Austria'),
(16, 'AI', 'Anguilla'),
(17, 'AX', 'Åland Islands'),
(18, 'AQ', 'Antarctica'),
(19, 'BH', 'Bahrain'),
(20, 'BB', 'Barbados'),
(21, 'BW', 'Botswana'),
(22, 'BM', 'Bermuda'),
(23, 'BE', 'Belgium'),
(24, 'BS', 'Bahamas, The'),
(25, 'BD', 'Bangladesh'),
(26, 'BZ', 'Belize'),
(27, 'BA', 'Bosnia and Herzegovina'),
(28, 'BO', 'Bolivia'),
(29, 'MM', 'Myanmar'),
(30, 'BJ', 'Benin'),
(31, 'BY', 'Belarus'),
(32, 'SB', 'Solomon Islands'),
(33, '', 'Navassa Island'),
(34, 'BR', 'Brazil'),
(35, '', 'Bassas da India'),
(36, 'BT', 'Bhutan'),
(37, 'BG', 'Bulgaria'),
(38, 'BV', 'Bouvet Island'),
(39, 'BN', 'Brunei'),
(40, 'BI', 'Burundi'),
(41, 'CA', 'Canada'),
(42, 'KH', 'Cambodia'),
(43, 'TD', 'Chad'),
(44, 'LK', 'Sri Lanka'),
(45, 'CG', 'Congo, Republic of the'),
(46, 'CD', 'Congo, Democratic Republic of the'),
(47, 'CN', 'China'),
(48, 'CL', 'Chile'),
(49, 'KY', 'Cayman Islands'),
(50, 'CC', 'Cocos (Keeling) Islands'),
(51, 'CM', 'Cameroon'),
(52, 'KM', 'Comoros'),
(53, 'CO', 'Colombia'),
(54, 'MP', 'Northern Mariana Islands'),
(55, '', 'Coral Sea Islands'),
(56, 'CR', 'Costa Rica'),
(57, 'CF', 'Central African Republic'),
(58, 'CU', 'Cuba'),
(59, 'CV', 'Cape Verde'),
(60, 'CK', 'Cook Islands'),
(61, 'CY', 'Cyprus'),
(62, 'DK', 'Denmark'),
(63, 'DJ', 'Djibouti'),
(64, 'DM', 'Dominica'),
(65, 'UM', 'Jarvis Island'),
(66, 'DO', 'Dominican Republic'),
(67, '', 'Dhekelia Sovereign Base Area'),
(68, 'EC', 'Ecuador'),
(69, 'EG', 'Egypt'),
(70, 'IE', 'Ireland'),
(71, 'GQ', 'Equatorial Guinea'),
(72, 'EE', 'Estonia'),
(73, 'ER', 'Eritrea'),
(74, 'SV', 'El Salvador'),
(75, 'ET', 'Ethiopia'),
(76, '', 'Europa Island'),
(77, 'CZ', 'Czech Republic'),
(78, 'GF', 'French Guiana'),
(79, 'FI', 'Finland'),
(80, 'FJ', 'Fiji'),
(81, 'FK', 'Falkland Islands (Islas Malvinas)'),
(82, 'FM', 'Micronesia, Federated States of'),
(83, 'FO', 'Faroe Islands'),
(84, 'PF', 'French Polynesia'),
(85, 'UM', 'Baker Island'),
(86, 'FR', 'France'),
(87, 'TF', 'French Southern and Antarctic Lands'),
(88, 'GM', 'Gambia, The'),
(89, 'GA', 'Gabon'),
(90, 'GE', 'Georgia'),
(91, 'GH', 'Ghana'),
(92, 'GI', 'Gibraltar'),
(93, 'GD', 'Grenada'),
(94, '', 'Guernsey'),
(95, 'GL', 'Greenland'),
(96, 'DE', 'Germany'),
(97, '', 'Glorioso Islands'),
(98, 'GP', 'Guadeloupe'),
(99, 'GU', 'Guam'),
(100, 'GR', 'Greece'),
(101, 'GT', 'Guatemala'),
(102, 'GN', 'Guinea'),
(103, 'GY', 'Guyana'),
(104, '', 'Gaza Strip'),
(105, 'HT', 'Haiti'),
(106, 'HK', 'Hong Kong'),
(107, 'HM', 'Heard Island and McDonald Islands'),
(108, 'HN', 'Honduras'),
(109, 'UM', 'Howland Island'),
(110, 'HR', 'Croatia'),
(111, 'HU', 'Hungary'),
(112, 'IS', 'Iceland'),
(113, 'ID', 'Indonesia'),
(114, 'IM', 'Isle of Man'),
(115, 'IN', 'India'),
(116, 'IO', 'British Indian Ocean Territory'),
(117, '', 'Clipperton Island'),
(118, 'IR', 'Iran'),
(119, 'IL', 'Israel'),
(120, 'IT', 'Italy'),
(121, 'CI', 'Cote d\'Ivoire'),
(122, 'IQ', 'Iraq'),
(123, 'JP', 'Japan'),
(124, 'JE', 'Jersey'),
(125, 'JM', 'Jamaica'),
(126, 'SJ', 'Jan Mayen'),
(127, 'JO', 'Jordan'),
(128, 'UM', 'Johnston Atoll'),
(129, '', 'Juan de Nova Island'),
(130, 'KE', 'Kenya'),
(131, 'KG', 'Kyrgyzstan'),
(132, 'KP', 'Korea, North'),
(133, 'UM', 'Kingman Reef'),
(134, 'KI', 'Kiribati'),
(135, 'KR', 'Korea, South'),
(136, 'CX', 'Christmas Island'),
(137, 'KW', 'Kuwait'),
(138, 'KV', 'Kosovo'),
(139, 'KZ', 'Kazakhstan'),
(140, 'LA', 'Laos'),
(141, 'LB', 'Lebanon'),
(142, 'LV', 'Latvia'),
(143, 'LT', 'Lithuania'),
(144, 'LR', 'Liberia'),
(145, 'SK', 'Slovakia'),
(146, 'UM', 'Palmyra Atoll'),
(147, 'LI', 'Liechtenstein'),
(148, 'LS', 'Lesotho'),
(149, 'LU', 'Luxembourg'),
(150, 'LY', 'Libyan Arab'),
(151, 'MG', 'Madagascar'),
(152, 'MQ', 'Martinique'),
(153, 'MO', 'Macau'),
(154, 'MD', 'Moldova, Republic of'),
(155, 'YT', 'Mayotte'),
(156, 'MN', 'Mongolia'),
(157, 'MS', 'Montserrat'),
(158, 'MW', 'Malawi'),
(159, 'ME', 'Montenegro'),
(160, 'MK', 'The Former Yugoslav Republic of Macedonia'),
(161, 'ML', 'Mali'),
(162, 'MC', 'Monaco'),
(163, 'MA', 'Morocco'),
(164, 'MU', 'Mauritius'),
(165, 'UM', 'Midway Islands'),
(166, 'MR', 'Mauritania'),
(167, 'MT', 'Malta'),
(168, 'OM', 'Oman'),
(169, 'MV', 'Maldives'),
(170, 'MX', 'Mexico'),
(171, 'MY', 'Malaysia'),
(172, 'MZ', 'Mozambique'),
(173, 'NC', 'New Caledonia'),
(174, 'NU', 'Niue'),
(175, 'NF', 'Norfolk Island'),
(176, 'NE', 'Niger'),
(177, 'VU', 'Vanuatu'),
(178, 'NG', 'Nigeria'),
(179, 'NL', 'Netherlands'),
(180, '', 'No Man\'s Land'),
(181, 'NO', 'Norway'),
(182, 'NP', 'Nepal'),
(183, 'NR', 'Nauru'),
(184, 'SR', 'Suriname'),
(185, 'AN', 'Netherlands Antilles'),
(186, 'NI', 'Nicaragua'),
(187, 'NZ', 'New Zealand'),
(188, 'PY', 'Paraguay'),
(189, 'PN', 'Pitcairn Islands'),
(190, 'PE', 'Peru'),
(191, '', 'Paracel Islands'),
(192, '', 'Spratly Islands'),
(193, 'PK', 'Pakistan'),
(194, 'PL', 'Poland'),
(195, 'PA', 'Panama'),
(196, 'PT', 'Portugal'),
(197, 'PG', 'Papua New Guinea'),
(198, 'PW', 'Palau'),
(199, 'GW', 'Guinea-Bissau'),
(200, 'QA', 'Qatar'),
(201, 'RE', 'Reunion'),
(202, 'RS', 'Serbia'),
(203, 'MH', 'Marshall Islands'),
(204, 'MF', 'Saint Martin'),
(205, 'RO', 'Romania'),
(206, 'PH', 'Philippines'),
(207, 'PR', 'Puerto Rico'),
(208, 'RU', 'Russia'),
(209, 'RW', 'Rwanda'),
(210, 'SA', 'Saudi Arabia'),
(211, 'PM', 'Saint Pierre and Miquelon'),
(212, 'KN', 'Saint Kitts and Nevis'),
(213, 'SC', 'Seychelles'),
(214, 'ZA', 'South Africa'),
(215, 'SN', 'Senegal'),
(216, 'SH', 'Saint Helena'),
(217, 'SI', 'Slovenia'),
(218, 'SL', 'Sierra Leone'),
(219, 'SM', 'San Marino'),
(220, 'SG', 'Singapore'),
(221, 'SO', 'Somalia'),
(222, 'ES', 'Spain'),
(223, 'LC', 'Saint Lucia'),
(224, 'SD', 'Sudan'),
(225, 'SJ', 'Svalbard'),
(226, 'SE', 'Sweden'),
(227, 'GS', 'South Georgia and the Islands'),
(228, 'SY', 'Syrian Arab Republic'),
(229, 'CH', 'Switzerland'),
(230, 'TT', 'Trinidad and Tobago'),
(231, '', 'Tromelin Island'),
(232, 'TH', 'Thailand'),
(233, 'TJ', 'Tajikistan'),
(234, 'TC', 'Turks and Caicos Islands'),
(235, 'TK', 'Tokelau'),
(236, 'TO', 'Tonga'),
(237, 'TG', 'Togo'),
(238, 'ST', 'Sao Tome and Principe'),
(239, 'TN', 'Tunisia'),
(240, 'TL', 'East Timor'),
(241, 'TR', 'Turkey'),
(242, 'TV', 'Tuvalu'),
(243, 'TW', 'Taiwan'),
(244, 'TM', 'Turkmenistan'),
(245, 'TZ', 'Tanzania, United Republic of'),
(246, 'UG', 'Uganda'),
(247, 'GB', 'United Kingdom'),
(248, 'UA', 'Ukraine'),
(249, 'US', 'United States'),
(250, 'BF', 'Burkina Faso'),
(251, 'UY', 'Uruguay'),
(252, 'UZ', 'Uzbekistan'),
(253, 'VC', 'Saint Vincent and the Grenadines'),
(254, 'VE', 'Venezuela'),
(255, 'VG', 'British Virgin Islands'),
(256, 'VN', 'Vietnam'),
(257, 'VI', 'Virgin Islands (US)'),
(258, 'VA', 'Holy See (Vatican City)'),
(259, 'NA', 'Namibia'),
(260, '', 'West Bank'),
(261, 'WF', 'Wallis and Futuna'),
(262, 'EH', 'Western Sahara'),
(263, 'UM', 'Wake Island'),
(264, 'WS', 'Samoa'),
(265, 'SZ', 'Swaziland'),
(266, 'CS', 'Serbia and Montenegro'),
(267, 'YE', 'Yemen'),
(268, 'ZM', 'Zambia'),
(269, 'ZW', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `code` varchar(50) NOT NULL DEFAULT '',
  `name_short` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `name`, `code`, `name_short`) VALUES
(1, 'Украинская гривна', 'UAH', 'грн.'),
(2, 'Доллар США', 'USD', 'долл.'),
(3, 'Евро', 'EUR', 'евро'),
(4, 'Российский рубль', 'RUB', 'руб.');

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
-- Table structure for table `order`
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
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `client_id`, `schedule_id`, `status_client`, `status_specialist`, `created_at`) VALUES
(1, 3, 1, 2, 1, '2018-07-16 15:47:11'),
(2, 3, 2, 2, 1, '2018-07-16 15:47:38'),
(4, 3, 3, 2, 1, '2018-07-16 17:10:06'),
(5, 3, 4, 1, 1, '2018-07-17 13:07:17'),
(9, 3, 7, 2, 2, '2018-07-17 15:16:15'),
(11, 28, 21, 1, 1, '2018-07-23 12:17:50'),
(12, 3, 23, 1, 1, '2018-07-26 10:50:54'),
(15, 3, 22, 1, 1, '2018-07-26 11:26:22');

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `id` int(4) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`id`, `name`) VALUES
(1, 'Создан'),
(2, 'Принят'),
(3, 'Выполнен'),
(4, 'Отменен');

-- --------------------------------------------------------

--
-- Table structure for table `order_vs_service`
--

CREATE TABLE `order_vs_service` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_vs_service`
--

INSERT INTO `order_vs_service` (`id`, `order_id`, `service_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1),
(4, 4, 2),
(5, 4, 3),
(6, 15, 2),
(7, 15, 5);

-- --------------------------------------------------------

--
-- Table structure for table `post`
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
-- Dumping data for table `post`
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
-- Table structure for table `post_vs_category`
--

CREATE TABLE `post_vs_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_vs_category`
--

INSERT INTO `post_vs_category` (`id`, `post_id`, `category_id`) VALUES
(4, 1, 2),
(1, 1, 9),
(7, 2, 4),
(5, 2, 5),
(6, 3, 11);

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(10) UNSIGNED NOT NULL,
  `specialist_id` int(10) UNSIGNED NOT NULL,
  `date_from` datetime NOT NULL,
  `date_to` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `specialist_id`, `date_from`, `date_to`) VALUES
(1, 5, '2018-07-16 13:48:28', '2018-07-16 13:48:31'),
(2, 5, '2018-08-16 12:50:26', '2018-07-16 12:50:34'),
(3, 37, '2018-08-16 17:07:53', '2018-07-16 17:07:55'),
(4, 5, '2018-08-15 16:00:00', '2018-07-15 17:00:00'),
(7, 37, '2018-07-17 14:53:46', '2018-07-17 14:53:48'),
(9, 5, '2018-08-15 14:00:00', '2018-07-15 15:00:00'),
(10, 37, '2018-08-15 14:00:00', '2018-07-15 15:00:00'),
(11, 37, '2018-07-15 16:00:00', '2018-07-15 17:00:00'),
(13, 5, '2018-07-15 17:00:00', '2018-07-15 18:00:00'),
(15, 5, '2018-08-15 18:00:00', '2018-07-15 19:00:00'),
(20, 5, '2018-07-15 19:30:00', '2018-07-15 19:31:00'),
(21, 37, '2018-07-15 16:00:00', '2018-07-15 17:00:00'),
(22, 5, '2018-08-14 18:30:00', '2018-07-14 19:30:00'),
(23, 37, '2018-07-14 18:30:00', '2018-07-14 19:30:00'),
(24, 37, '2018-08-14 18:30:00', '2018-08-14 19:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency_id` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `category_id`, `company_id`, `price`, `currency_id`) VALUES
(1, 1, 4, '10.00', 1),
(2, 2, 4, '15.00', 1),
(3, 4, 4, '32.56', 3),
(5, 6, 4, '40.95', 1);

-- --------------------------------------------------------

--
-- Table structure for table `specialist`
--

CREATE TABLE `specialist` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `specialist`
--

INSERT INTO `specialist` (`id`, `company_id`, `description`) VALUES
(5, 4, 'Active specialist'),
(37, 4, 'Great work experience');

-- --------------------------------------------------------

--
-- Table structure for table `upload`
--

CREATE TABLE `upload` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `type` int(4) UNSIGNED NOT NULL,
  `ext` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `upload`
--

INSERT INTO `upload` (`id`, `user_id`, `type`, `ext`) VALUES
(1, 4, 1, 'jpg'),
(2, 4, 1, 'jpg'),
(3, 4, 1, 'jpg'),
(5, 4, 1, 'jpg'),
(6, 5, 1, 'jpg'),
(7, 5, 1, 'jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
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
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `auth_key`, `access_token`, `password_reset_token`, `email_confirm_token`, `firstname`, `lastname`, `phone`, `status`, `type`, `created_at`) VALUES
(1, 'admin', '$2y$13$NUQnYWDs.e/AmtLmH5mYqukSIm49KU3/bNG35MkWysMTbVixFZGuO', 'admin@mail.com', 'Q7cqvkF62XPvMVaR7hz-2SvVLvjpFvik', '2KfdjUr34K5k73HJIKkrdf92dkLk', '5Tfb10xbacBLZzpxZLo82d5gD7F4S9f5_1530881096', '', 'Alexandr', 'Petrov', '', 1, 1, '2018-06-27 12:36:15'),
(2, 'user', '$2y$13$.tF0DEF2KF4Pjl5niST.XO2r5KmXCFrnYTzuLXOyYfpLYy0nhbEEy', 'user@gmail.com', 'OcCmB99oZIHWdakCClitar2fLN4JyarN', 'dH4Oica5nzKvFN-9kzr0jvu_FLNnC-27', '', '', 'Sergey', 'Ivanov', '', 1, 0, '2018-07-05 15:45:10'),
(3, 'client', '$2y$13$F58K0HWi8Bvm/9oYIsaRlOnS7ewh8qjXIySzD9FSoAN9XXiTVx6ty', 'client@example.com', 'dLBSPihHn3HMqG4KKvYin38ooRIkqLjG', 'xcGiPQH-s_ejo-e1xNG_kAkK3WPELTfO', '', '_B2SWWQekzMMBliOf-oGty9pJC6SWV4o', 'John', 'Smith', '+380952762548', 1, 4, '2018-07-13 17:15:46'),
(4, 'company', '$2y$13$xxPCurEkeBdqLEdGSJkR1ejUWxeytIRZjEYovyRUxPdlANR.ao2Be', 'company@mail.com', 'E08yfBXa5SeNIrgH3EcSpC9wMjXFnJyf', 'DMZaVYEjmCW_fVzRC6G68uVhnlhF_W_g', '', '', 'Victor', 'Chernov', '+380503847016', 1, 2, '2018-07-06 10:21:03'),
(5, 'specialist', '$2y$13$LS15CIMbxtROhrh4rfZoAuAs0F1/vKq3NiBP1ulgwaJT0aa4F37Y6', 'specialist@example.com', 'Q8VrAf6BADt_HBrcP8BFLYFKR8WBZL6D', '_xpE7SjDaxnl3MerLtld8xh3S3XG1U4T', '', 'zzXu0wnqQ2v4wRNcvmTmi7Sc8MGWppBN', 'Vasiliy', 'Kononenko', '+380955764328', 1, 3, '2018-07-13 16:45:42'),
(6, 'tester', '$2y$13$CIgZx8YApWllOm7S73G3E.Xmw4RjQfCNx0x9uxBu9Zd/rDX4Fy5ES', 'tester@mail.com', 'qNfV-scJqYjpQlvCfXtztUZcS0xCf_ex', 'sjWk72kls39kdjk733KL3Llk2LJio', '', '', 'Nikolay', 'Ivanov', '', 1, 0, '2018-07-03 14:14:29'),
(10, 'volmir', '$2y$13$i29vkcMpe/HXboPS5HdbZep5bdhtgo7uSz/6g3quJoCZNu2HQcCi.', 'volmir@ukr.net', 'LJ6M08PIkaIJJC3HwOJaCBz1DeJSPZDU', '-ruS-QVb0c8pTuoUETAoi1BCmngOM5me', '', '', 'Vladimir', 'Prokhnenko', '', 1, 0, '2018-07-05 16:45:05'),
(19, 'admin4', '$2y$13$hu4kUfThVIVtaoVzLI2rAuUm.GhGk.0k0RiNtjcG5NpPqz0cuxkpq', 'user4@gmail.com', 'ooOZm4SLw46tZ6L66KH63G5v7lrqV2Ew', '0HP_NRFN44tADAUAeUh06N07l2uQ0P_y', '', '', '', '', '', 2, 0, '2018-07-06 10:30:59'),
(21, 'user5', '$2y$13$uAR/PP6Yd3CjnngmIw9qceROIDQERz4OuE4vWujl6KVZ1etGw.8zW', 'user5@posting.local', 'tzX8AWLziYjQkxOQIQPpUXetXxoPVrbh', '_CEN8LZty__HqjfCXgVoANhEXDdghnc6', '', 'uNRoWVUQ_xa5BRiugtdj21tanDqexwxZ', '', '', '', 0, 0, '2018-07-10 10:01:59'),
(22, 'user7', '$2y$13$zNBG8kcKDZA15HdmH4QrYe7HyJzqo0NFrCDzuBiwwtFMQBPmE65IC', 'user7@posting.local', 'ScMrrOFZxnRcVY2acDmbxh6QTrEi3b1F', 'QWc-cuVus5-8hHHGaSgNX0LYs2qUri5G', '', '', 'Dasdfasfa', 'Kdfdsdfsdfsd', '', 1, 0, '2018-07-10 13:20:03'),
(28, 'client869', '$2y$13$dxK3sxvLwfDGb/5bYhtB2OblMVy3No5OJealB24Ph5Vx6kQaZAZli', 'client869@example.com', 'EN705PYVXeDIVaCy4IMGwHiPWA7waFAV', 'WIIbZ3XLCX4IifN2orhLygvoYYL1xdWR', '', 'I-uwYW54GEMfvcH8Pgz-5WrjY_i-vWwt', 'John', 'Smith', '', 1, 4, '2018-07-13 17:15:28'),
(37, 'specialist269', '$2y$13$8Q6fMNb2GOfELAeDyhzIG.cWx2UvNAyvvjOGntPtZ5p2ENYl2bECW', 'specialist269@example.com', 'AqWrPXqBAQQ8y19gH1SBygC95jd2w_jZ', 'fgeGW3EMEvxahBEd_O3uyqXanxE_atAN', '', 'MaoOtxr-MX33FN8hporZhnZRV2rBV1JX', 'John', 'Smith', '+380993764329', 1, 3, '2018-07-16 16:05:54'),
(39, 'company837', '$2y$13$s5GEq91sYeyyiqwo/SGtYu802vWwWdcfBt2VFd6FBeIf9iMKl3YNG', 'company@example.com', 'PHP30MTu7wv12Zwh81tQ-SIcS_u_oz0C', 'gXM9CueL-0D2MAPOZhLFPhDbD75jt3xT', '', 't4dyscJnRg3URLztfda5Nn0eKQo-BULM', '', '', '+380993762379', 1, 2, '2018-07-16 16:12:08'),
(40, 'company839', '$2y$13$LVr1x5pjVd7aBK.EfJ03xuWQ0lkp1lx0sBUG7sTmyXbY/S6X22faG', 'company839@example.com', '822Ot7tkNKNeyPU8-RV0kRs8ZxKOi-4N', 'L1y_Htj9BEXafaVt7X9R94GvjOIxOGss', '', 'Tecg5V_Fjz5y_A2dS10HrbgQB3Etedmy', '', '', '+380993762379', 1, 2, '2018-07-20 10:20:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `specialist_id` (`specialist_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `country_id` (`country_id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cc_iso` (`cc_iso`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `schedule_id` (`schedule_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_vs_service`
--
ALTER TABLE `order_vs_service`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id_service_id` (`order_id`,`service_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_vs_category`
--
ALTER TABLE `post_vs_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_id_category_id` (`post_id`,`category_id`),
  ADD KEY `FK_posts_vs_categories_categories` (`category_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specialist_id` (`specialist_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `company_id_catalog_id` (`company_id`,`category_id`),
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `specialist`
--
ALTER TABLE `specialist`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `upload`
--
ALTER TABLE `upload`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `password_reset_token` (`password_reset_token`),
  ADD KEY `email_confirm_token` (`email_confirm_token`),
  ADD KEY `status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=270;
--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `order_vs_service`
--
ALTER TABLE `order_vs_service`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `post_vs_category`
--
ALTER TABLE `post_vs_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `upload`
--
ALTER TABLE `upload`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_fk1` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `client_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`specialist_id`) REFERENCES `specialist` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`);

--
-- Constraints for table `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `company_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `company_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `order_ibfk_3` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_vs_service`
--
ALTER TABLE `order_vs_service`
  ADD CONSTRAINT `order_vs_service_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `order_vs_service_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_posts_users` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `post_vs_category`
--
ALTER TABLE `post_vs_category`
  ADD CONSTRAINT `FK_posts_vs_categories_categories` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_posts_vs_categories_posts` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`specialist_id`) REFERENCES `specialist` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `service_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `service_ibfk_3` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `specialist`
--
ALTER TABLE `specialist`
  ADD CONSTRAINT `specialist_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `specialist_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `upload`
--
ALTER TABLE `upload`
  ADD CONSTRAINT `upload_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
