-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2022 年 06 月 23 日 19:36
-- 伺服器版本： 10.4.21-MariaDB
-- PHP 版本： 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- 資料庫: `DB_HW`
--

-- --------------------------------------------------------

--
-- 資料表結構 `contains`
--

CREATE TABLE `contains` (
  `OID` int(10) UNSIGNED NOT NULL,
  `PHID` int(10) UNSIGNED NOT NULL,
  `number` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `order`
--

CREATE TABLE `order` (
  `ID` int(11) UNSIGNED NOT NULL,
  `status` varchar(256) NOT NULL,
  `start_time` varchar(256) NOT NULL,
  `finish_time` varchar(256) DEFAULT NULL,
  `distance` int(10) UNSIGNED NOT NULL,
  `payment` int(10) UNSIGNED NOT NULL,
  `type` varchar(256) NOT NULL,
  `SID` int(11) UNSIGNED NOT NULL,
  `UID` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `product`
--

CREATE TABLE `product` (
  `ID` int(11) UNSIGNED NOT NULL,
  `name` varchar(256) NOT NULL,
  `image` mediumblob NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `SID` int(11) UNSIGNED NOT NULL,
  `picture_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `product_history`
--

CREATE TABLE `product_history` (
  `ID` int(11) UNSIGNED NOT NULL,
  `name` varchar(256) NOT NULL,
  `image` mediumblob NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `SID` int(11) UNSIGNED NOT NULL,
  `PID` int(11) UNSIGNED NOT NULL,
  `picture_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `store`
--

CREATE TABLE `store` (
  `ID` int(11) UNSIGNED NOT NULL,
  `name` varchar(256) NOT NULL,
  `position_longitude` double NOT NULL,
  `position_latitude` double NOT NULL,
  `phone_number` char(10) NOT NULL,
  `category` varchar(256) NOT NULL,
  `UID` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `transaction`
--

CREATE TABLE `transaction` (
  `ID` int(10) UNSIGNED NOT NULL,
  `type` varchar(256) NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `time` varchar(256) NOT NULL,
  `UID` int(10) UNSIGNED NOT NULL,
  `target_UID` int(10) UNSIGNED NOT NULL,
  `is_refund` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `ID` int(10) UNSIGNED NOT NULL,
  `account` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  `status` varchar(256) NOT NULL,
  `phone_number` char(10) NOT NULL,
  `wallet` int(10) UNSIGNED NOT NULL,
  `position_longitude` double NOT NULL,
  `position_latitude` double NOT NULL,
  `salt` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `contains`
--
ALTER TABLE `contains`
  ADD PRIMARY KEY (`OID`,`PHID`),
  ADD KEY `contains_product` (`PHID`);

--
-- 資料表索引 `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `order_store` (`SID`),
  ADD KEY `order_user` (`UID`);

--
-- 資料表索引 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `product_store` (`SID`);

--
-- 資料表索引 `product_history`
--
ALTER TABLE `product_history`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `product_history_store` (`SID`),
  ADD KEY `product_original` (`PID`);

--
-- 資料表索引 `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `owner` (`UID`);

--
-- 資料表索引 `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `transaction_user` (`UID`),
  ADD KEY `target_user` (`target_UID`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `order`
--
ALTER TABLE `order`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `product`
--
ALTER TABLE `product`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `product_history`
--
ALTER TABLE `product_history`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `store`
--
ALTER TABLE `store`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `transaction`
--
ALTER TABLE `transaction`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `contains`
--
ALTER TABLE `contains`
  ADD CONSTRAINT `contains_order` FOREIGN KEY (`OID`) REFERENCES `order` (`ID`),
  ADD CONSTRAINT `contains_product_history` FOREIGN KEY (`PHID`) REFERENCES `product_history` (`ID`);

--
-- 資料表的限制式 `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_store` FOREIGN KEY (`SID`) REFERENCES `store` (`ID`),
  ADD CONSTRAINT `order_user` FOREIGN KEY (`UID`) REFERENCES `user` (`ID`);

--
-- 資料表的限制式 `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_store` FOREIGN KEY (`SID`) REFERENCES `store` (`ID`);

--
-- 資料表的限制式 `product_history`
--
ALTER TABLE `product_history`
  ADD CONSTRAINT `product_history_store` FOREIGN KEY (`SID`) REFERENCES `store` (`ID`),
  ADD CONSTRAINT `product_original` FOREIGN KEY (`PID`) REFERENCES `product` (`ID`);

--
-- 資料表的限制式 `store`
--
ALTER TABLE `store`
  ADD CONSTRAINT `owner` FOREIGN KEY (`UID`) REFERENCES `user` (`ID`);

--
-- 資料表的限制式 `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `target_user` FOREIGN KEY (`target_UID`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `transaction_user` FOREIGN KEY (`UID`) REFERENCES `user` (`ID`);
COMMIT;
