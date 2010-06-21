/*
MySQL Data Transfer
Source Host: localhost
Source Database: matkon_db
Target Host: localhost
Target Database: matkon_db
Date: 16/06/2010 14:31:36
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for ingridiant_details
-- ----------------------------
DROP TABLE IF EXISTS `ingridiant_details`;
CREATE TABLE `ingridiant_details` (
  `id` int(10) unsigned NOT NULL,
  `hot` tinyint(2) NOT NULL DEFAULT '1',
  `cold` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_ingridiant_details_ingridiants1` (`id`),
  CONSTRAINT `fk_ingridiant_details_ingridiants1` FOREIGN KEY (`id`) REFERENCES `ingridiants` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Table structure for ingridiant_manufactors
-- ----------------------------
DROP TABLE IF EXISTS `ingridiant_manufactors`;
CREATE TABLE `ingridiant_manufactors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `ing_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ingridiant_manufactors_ingridiants1` (`ing_id`),
  CONSTRAINT `fk_ingridiant_manufactors_ingridiants1` FOREIGN KEY (`ing_id`) REFERENCES `ingridiants` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Table structure for ingridiants
-- ----------------------------
DROP TABLE IF EXISTS `ingridiants`;
CREATE TABLE `ingridiants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Table structure for ingridiants_has_recipe
-- ----------------------------
DROP TABLE IF EXISTS `ingridiants_has_recipe`;
CREATE TABLE `ingridiants_has_recipe` (
  `ingridiants_id` int(10) unsigned NOT NULL,
  `recipe_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ingridiants_id`,`recipe_id`),
  KEY `fk_ingridiants_has_recipe_ingridiants` (`ingridiants_id`),
  KEY `fk_ingridiants_has_recipe_recipe1` (`recipe_id`),
  CONSTRAINT `fk_ingridiants_has_recipe_ingridiants` FOREIGN KEY (`ingridiants_id`) REFERENCES `ingridiants` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ingridiants_has_recipe_recipe1` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Table structure for recipe
-- ----------------------------
DROP TABLE IF EXISTS `recipes`;
CREATE TABLE `recipes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) COLLATE utf8_bin NOT NULL,
  `users_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`,`users_id`),
  KEY `fk_recipe_users1` (`users_id`),
  CONSTRAINT `fk_recipe_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `admin` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `ingridiant_details` VALUES ('1', '1', '1');
INSERT INTO `ingridiant_details` VALUES ('2', '1', '1');
INSERT INTO `ingridiant_details` VALUES ('3', '1', '1');
INSERT INTO `ingridiant_manufactors` VALUES ('1', 'tnuva', '1');
INSERT INTO `ingridiant_manufactors` VALUES ('2', 'tara', '1');
INSERT INTO `ingridiants` VALUES ('1', 'milk');
INSERT INTO `ingridiants` VALUES ('2', 'sugar');
INSERT INTO `ingridiants` VALUES ('3', 'salt');
INSERT INTO `ingridiants_has_recipe` VALUES ('1', '1');
INSERT INTO `ingridiants_has_recipe` VALUES ('3', '1');
INSERT INTO `recipes` VALUES ('1', 'random', '1');
INSERT INTO `users` VALUES ('1', 'arieh', '1');
