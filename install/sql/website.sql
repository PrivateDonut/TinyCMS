/*
 Navicat MySQL Dump SQL

 Source Server         : MySQL Server
 Source Server Type    : MySQL
 Source Server Version : 80038 (8.0.38)
 Source Host           : 127.0.0.1
 Source Schema         : website

 Target Server Type    : MySQL
 Target Server Version : 80038 (8.0.38)
 File Encoding         : 65001

 Date: 16/07/2024 02:51:45
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for access
-- ----------------------------
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_id` int NULL DEFAULT 0,
  `access_level` bit(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of access
-- ----------------------------

-- ----------------------------
-- Table structure for login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` int NOT NULL DEFAULT 1,
  `last_attempt` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `ip`(`ip` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of login_attempts
-- ----------------------------

-- ----------------------------
-- Table structure for navbar_items
-- ----------------------------
DROP TABLE IF EXISTS `navbar_items`;
CREATE TABLE `navbar_items`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `order_index` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of navbar_items
-- ----------------------------
INSERT INTO `navbar_items` VALUES (1, 'Home', '/', 'Home', 1, 0);
INSERT INTO `navbar_items` VALUES (3, 'Features', '#', 'Features', 3, 0);
INSERT INTO `navbar_items` VALUES (4, 'Rules', '#', 'Rules', 4, 0);
INSERT INTO `navbar_items` VALUES (5, 'Support', '#', 'Support', 5, 0);
INSERT INTO `navbar_items` VALUES (6, 'Store', '#', 'Store', 6, 0);

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `author` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `edit_by` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `thumbnail` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of news
-- ----------------------------
INSERT INTO `news` VALUES (1, 'Thank you for using DonutCMS', 'Thank you for using DonutCMS if you encounter any bugs please report them to the repo at https://github.com/PrivateDonut/DonutCMS . You can remove this post in the admin panel!', 'DonutCMS', NULL, '2024-06-27 16:59:45', NULL);
INSERT INTO `news` VALUES (2, 'Thank you for using DonutCMS', 'Thank you for using DonutCMS if you encounter any bugs please report them to the repo at https://github.com/PrivateDonut/DonutCMS . You can remove this post in the admin panel!', 'DonutCMS', NULL, '2024-06-27 16:59:45', NULL);

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `sess_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sess_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sess_time` int UNSIGNED NOT NULL,
  PRIMARY KEY (`sess_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('4mf01fnmafpobpkha5ahioh532', 'A0b/sbHpFY02UHYECn29zUU5b1U3Tzh4RU91NHpiU1I1cHUyblZrbEQ0cmVMNDBnUjBueXVMN3RHWlNYTmxsVkFVbDhVdW1xNkx2OERXcWFYRUo1cllKSFZtWm1ML1hYL2VNWHQzeHEyRmF1dW96MzNCc0pzMVVxNnIyM0c4cEtkQWlSbjE1WWNrdlFicnFqLzJRVGl5bXUwNGZRR3hmR21qbnJXS21HVDFlVFlqM3NHZVdYV2xGOUZ6TGxxR1JVRXVxM0RJM1pFRHhDUnh4SE5hVnVoMVJnR21CVnIyU0k2eDNiYkhiV1JnVDV4bzJMQ2UxTjBKR2Urcyt0MStKRytnZTB0cDdSMnZTbEFYdVoyUVl1L1JqZ0pEa1ZNblVOak5UdkppZExVbUFSQm5DWlZJU2JBTWNMUHdXNXVReUNzaERiVWtHekVOMVFvMUNKUzdUTndsaFVaY3hrNm1zWGdNZyttOG90YkVJUGRYSDE4TWZsNEFNc0xJNFkyN3FHbzNoMVlvQzVTYlpOMDRjSkZRQU41NGMwSkVqamtjYjV2R2xlL1YrTGtQSG5xU3dXQXRyUnJSQmpVSG5UYWZtVUxYUnNmb1VPMklDRGJUV1I=', 1721116154);

-- ----------------------------
-- Table structure for slider_items
-- ----------------------------
DROP TABLE IF EXISTS `slider_items`;
CREATE TABLE `slider_items`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `button_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `button_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `order_index` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of slider_items
-- ----------------------------
INSERT INTO `slider_items` VALUES (1, 'Lorem ipsum 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis.', 'Read more', '#', 1);
INSERT INTO `slider_items` VALUES (2, 'Lorem ipsum 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis.', 'Read more', '#', 2);
INSERT INTO `slider_items` VALUES (3, 'Lorem ipsum 3', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis.', 'Read more', '#', 3);
INSERT INTO `slider_items` VALUES (4, 'Test', 'Test', 'Read more', '#', 0);

-- ----------------------------
-- Table structure for social_media_links
-- ----------------------------
DROP TABLE IF EXISTS `social_media_links`;
CREATE TABLE `social_media_links`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `order_index` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of social_media_links
-- ----------------------------
INSERT INTO `social_media_links` VALUES (1, 'Discord', 'https://discord.gg/your-discord-link', 1);
INSERT INTO `social_media_links` VALUES (2, 'Facebook', 'https://www.facebook.com/your-facebook-page', 2);
INSERT INTO `social_media_links` VALUES (3, 'Youtube', 'https://www.youtube.com/your-youtube-channel', 3);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_id` int UNSIGNED NOT NULL,
  `vote_points` int NULL DEFAULT NULL,
  `donor_points` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
