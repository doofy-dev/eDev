/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50626
Source Host           : localhost:3306
Source Database       : edev

Target Server Type    : MYSQL
Target Server Version : 50626
File Encoding         : 65001

Date: 2015-10-26 00:10:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for calendar
-- ----------------------------
DROP TABLE IF EXISTS `calendar`;
CREATE TABLE `calendar` (
  `calendar_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `entry_type_id` int(11) NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `calendar_day` date NOT NULL,
  PRIMARY KEY (`calendar_id`),
  KEY `fk_calendar_entry_type_id` (`entry_type_id`),
  KEY `fk_calendar_user_id` (`user_id`),
  CONSTRAINT `fk_calendar_entry_type_id` FOREIGN KEY (`entry_type_id`) REFERENCES `calendar_entry_type` (`entry_type_id`),
  CONSTRAINT `fk_calendar_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Table structure for calendar_entry_type
-- ----------------------------
DROP TABLE IF EXISTS `calendar_entry_type`;
CREATE TABLE `calendar_entry_type` (
  `entry_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `entry_formula` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`entry_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `enabled` tinyint(2) NOT NULL DEFAULT '0',
  `role` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
