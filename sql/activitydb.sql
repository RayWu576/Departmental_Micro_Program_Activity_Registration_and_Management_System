-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-01-02 17:04:51
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `activitydb`
--

-- --------------------------------------------------------

--
-- 資料表結構 `activity`
--

CREATE TABLE `activity` (
  `activity_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `start_date_time` datetime NOT NULL,
  `end_date_time` datetime NOT NULL,
  `location` varchar(100) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `organizer` varchar(255) NOT NULL,
  `capacity` int(10) UNSIGNED DEFAULT NULL,
  `register_deadline` datetime NOT NULL,
  `cost` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `category` tinyint(1) NOT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT 0,
  `participants` int(11) DEFAULT NULL,
  `year` varchar(3) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `additional_info` varchar(300) DEFAULT NULL,
  `hours` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 傾印資料表的資料 `activity`
--

INSERT INTO `activity` (`activity_id`, `name`, `start_date_time`, `end_date_time`, `location`, `description`, `organizer`, `capacity`, `register_deadline`, `cost`, `status`, `category`, `processed`, `participants`, `year`, `semester`, `additional_info`, `hours`) VALUES
(14, '測試活動01', '2023-12-31 00:51:00', '2024-05-28 00:53:00', '', '', '', 0, '2024-03-29 00:52:00', '', '可報名', 0, 1, 1, '', 0, '', 0),
(15, '測試活動02', '2023-12-31 00:53:00', '2023-12-31 00:55:00', '', '', '', 0, '2023-12-31 00:54:00', '', '已結束', 0, 1, 0, '', 0, '', 0),
(16, '測試活動03', '2023-12-31 01:04:00', '2023-12-31 01:07:00', '', '', '', 0, '2023-12-31 01:06:00', '', '已結束', 0, 1, 0, '', 0, '', 0),
(17, '測試活動04', '2023-12-31 01:04:00', '2023-12-31 01:09:00', '地點1', '87', '測試人員01-123', 100, '2023-12-31 01:06:00', '1000元', '已結束', 0, 1, 0, '', 0, '', 0),
(18, '測試活動04', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', 0, '0000-00-00 00:00:00', '', '已結束', 0, 1, 0, '', 0, '', 0),
(19, '測試活動05', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', 0, '0000-00-00 00:00:00', '', '已結束', 0, 1, 0, '', 0, '', 0),
(20, '測試活動06', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', 0, '0000-00-00 00:00:00', '', '已結束', 0, 1, 0, '', 0, '', 0),
(21, '測試活動07', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', 0, '0000-00-00 00:00:00', '', '已結束', 0, 1, 0, '', 0, '', 0),
(22, '網際網路ff', '2024-01-01 23:41:00', '2024-01-11 23:41:00', '吳奇瑞家', '', '吳奇瑞', 100, '2024-04-24 23:41:00', '5555', '可報名', 0, 0, 1, '', 0, '', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `blacklist`
--

CREATE TABLE `blacklist` (
  `id` varchar(30) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `notification`
--

CREATE TABLE `notification` (
  `activity_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(50) NOT NULL,
  `datetime` datetime NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `registration`
--

CREATE TABLE `registration` (
  `activity_id` int(10) UNSIGNED NOT NULL,
  `student_id` varchar(30) NOT NULL,
  `additional_info` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 傾印資料表的資料 `registration`
--

INSERT INTO `registration` (`activity_id`, `student_id`, `additional_info`) VALUES
(14, '1102963', ''),
(22, '1102921', ''),
(22, '1102963', '');

-- --------------------------------------------------------

--
-- 資料表結構 `sign_in`
--

CREATE TABLE `sign_in` (
  `activity_id` int(10) UNSIGNED NOT NULL,
  `student_id` varchar(30) NOT NULL,
  `sign_in_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 傾印資料表的資料 `sign_in`
--

INSERT INTO `sign_in` (`activity_id`, `student_id`, `sign_in_datetime`) VALUES
(14, '1102963', '2024-01-01 23:37:43'),
(22, '1102921', '2024-01-01 23:42:23'),
(22, '1102963', '2024-01-02 22:10:46');

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `user_id` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `department` varchar(30) NOT NULL,
  `phone_number` varchar(30) NOT NULL,
  `user_type` tinyint(1) DEFAULT NULL,
  `password` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`user_id`, `email`, `name`, `department`, `phone_number`, `user_type`, `password`) VALUES
('1102921', 's1102921@mail.ncyu.edu.tw', 'lin xin yi', 'csie', '0964576567', 0, '123456'),
('1102963', 's1102963@mail.ncyu.edu.tw', '洪軍皓', 'cs', '0909', 0, '123456'),
('admin', 'admin@g.ncyu.edu.tw', 'admin_01', 'csie', '09xxxxxxxx', 1, '123456');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`activity_id`);

--
-- 資料表索引 `blacklist`
--
ALTER TABLE `blacklist`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `notification`
--
ALTER TABLE `notification`
  ADD KEY `activity_id` (`activity_id`);

--
-- 資料表索引 `registration`
--
ALTER TABLE `registration`
  ADD KEY `registration_ibfk_1` (`activity_id`);

--
-- 資料表索引 `sign_in`
--
ALTER TABLE `sign_in`
  ADD KEY `activity_id` (`activity_id`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `activity`
--
ALTER TABLE `activity`
  MODIFY `activity_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`activity_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的限制式 `registration`
--
ALTER TABLE `registration`
  ADD CONSTRAINT `registration_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`activity_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的限制式 `sign_in`
--
ALTER TABLE `sign_in`
  ADD CONSTRAINT `sign_in_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`activity_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
