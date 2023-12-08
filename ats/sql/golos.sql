SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `golos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `golos`;

CREATE TABLE `block` (
  `id` int NOT NULL,
  `num` int NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `block_building` (
  `block_id` int NOT NULL,
  `building_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `building` (
  `id` int NOT NULL,
  `street_id` int NOT NULL,
  `code` varchar(4) NOT NULL,
  `num` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `device` (
  `id` int NOT NULL,
  `sippeer_id` int DEFAULT NULL,
  `ps_endpoint_id` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `sipusername` varchar(20) DEFAULT NULL,
  `sippassword` varchar(20) DEFAULT NULL,
  `block_id` int DEFAULT NULL,
  `porch_id` int DEFAULT NULL,
  `room_id` int DEFAULT NULL,
  `type` int NOT NULL,
  `num` int NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `porch` (
  `id` int NOT NULL,
  `building_id` int NOT NULL,
  `num` int NOT NULL,
  `concierge_device_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `room` (
  `id` int NOT NULL,
  `porch_id` int NOT NULL,
  `num` int NOT NULL,
  `mobile_device_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `session` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `street` (
  `id` int NOT NULL,
  `code` varchar(3) NOT NULL,
  `title` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `user` (
  `id` int NOT NULL,
  `login` varchar(40) NOT NULL,
  `password` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


ALTER TABLE `block`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `block_building`
  ADD PRIMARY KEY (`block_id`,`building_id`) USING BTREE,
  ADD KEY `block_id` (`block_id`),
  ADD KEY `building_id` (`building_id`);

ALTER TABLE `building`
  ADD PRIMARY KEY (`id`),
  ADD KEY `street_id` (`street_id`);

ALTER TABLE `device`
  ADD PRIMARY KEY (`id`),
  ADD KEY `block_id` (`block_id`),
  ADD KEY `porch_id` (`porch_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `sippeer_id` (`sippeer_id`),
  ADD KEY `ps_endpoint_id` (`ps_endpoint_id`);

ALTER TABLE `porch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `building_id` (`building_id`),
  ADD KEY `concierge_device_id` (`concierge_device_id`);

ALTER TABLE `room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `porch_id` (`porch_id`),
  ADD KEY `mobile_device_id` (`mobile_device_id`);

ALTER TABLE `session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_timestamp` (`timestamp`);

ALTER TABLE `street`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `block`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `building`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `device`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `porch`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `room`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `street`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;


ALTER TABLE `block_building`
  ADD CONSTRAINT `block_building_ibfk_1` FOREIGN KEY (`block_id`) REFERENCES `block` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `block_building_ibfk_2` FOREIGN KEY (`building_id`) REFERENCES `building` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE `building`
  ADD CONSTRAINT `building_ibfk_1` FOREIGN KEY (`street_id`) REFERENCES `street` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `device`
  ADD CONSTRAINT `device_ibfk_1` FOREIGN KEY (`block_id`) REFERENCES `block` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  ADD CONSTRAINT `device_ibfk_2` FOREIGN KEY (`porch_id`) REFERENCES `porch` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  ADD CONSTRAINT `device_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  ADD CONSTRAINT `device_ibfk_4` FOREIGN KEY (`sippeer_id`) REFERENCES `asterisk`.`sippeers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  ADD CONSTRAINT `device_ibfk_6` FOREIGN KEY (`ps_endpoint_id`) REFERENCES `asterisk`.`ps_endpoints` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;

ALTER TABLE `porch`
  ADD CONSTRAINT `porch_ibfk_1` FOREIGN KEY (`building_id`) REFERENCES `building` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `porch_ibfk_2` FOREIGN KEY (`concierge_device_id`) REFERENCES `device` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`porch_id`) REFERENCES `porch` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `room_ibfk_3` FOREIGN KEY (`mobile_device_id`) REFERENCES `device` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
