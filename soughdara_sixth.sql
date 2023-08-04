/*
 Navicat Premium Data Transfer

 Source Server         : Local
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : soughdara

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 04/08/2023 16:54:34
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for analysis_process
-- ----------------------------
DROP TABLE IF EXISTS `analysis_process`;
CREATE TABLE `analysis_process`  (
  `id` int NOT NULL,
  `month` int NULL DEFAULT NULL,
  `year` int NULL DEFAULT NULL,
  `min_support` int NULL DEFAULT NULL,
  `min_confidence` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of analysis_process
-- ----------------------------

-- ----------------------------
-- Table structure for category_prod
-- ----------------------------
DROP TABLE IF EXISTS `category_prod`;
CREATE TABLE `category_prod`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `note` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of category_prod
-- ----------------------------
INSERT INTO `category_prod` VALUES (1, 'The OG', NULL, '2023-07-23 07:25:17', '2023-07-23 16:21:10', NULL);
INSERT INTO `category_prod` VALUES (2, 'Signature', NULL, '2023-07-23 07:41:57', NULL, NULL);
INSERT INTO `category_prod` VALUES (3, 'Package', NULL, '2023-07-23 07:42:12', NULL, NULL);
INSERT INTO `category_prod` VALUES (4, 'Seasonal Menu', NULL, '2023-07-23 07:42:25', NULL, NULL);

-- ----------------------------
-- Table structure for details_analysis
-- ----------------------------
DROP TABLE IF EXISTS `details_analysis`;
CREATE TABLE `details_analysis`  (
  `id` int NOT NULL,
  `id_analysis` int NULL DEFAULT NULL,
  `id_product` int NULL DEFAULT NULL,
  `support_value` int NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of details_analysis
-- ----------------------------

-- ----------------------------
-- Table structure for details_order
-- ----------------------------
DROP TABLE IF EXISTS `details_order`;
CREATE TABLE `details_order`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_order` int NULL DEFAULT NULL,
  `id_product` int NULL DEFAULT NULL,
  `qty` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of details_order
-- ----------------------------
INSERT INTO `details_order` VALUES (9, 1, 2, 1, '2023-07-31 06:38:50', NULL, NULL);
INSERT INTO `details_order` VALUES (10, 1, 4, 2, '2023-07-31 06:38:50', NULL, NULL);
INSERT INTO `details_order` VALUES (11, 2, 5, 1, '2023-07-31 06:39:24', NULL, NULL);
INSERT INTO `details_order` VALUES (12, 2, 3, 1, '2023-07-31 06:39:24', NULL, NULL);
INSERT INTO `details_order` VALUES (13, 2, 4, 1, '2023-07-31 06:39:24', NULL, NULL);
INSERT INTO `details_order` VALUES (14, 3, 5, 1, '2023-07-31 19:42:25', NULL, NULL);
INSERT INTO `details_order` VALUES (15, 3, 3, 2, '2023-07-31 19:42:25', NULL, NULL);
INSERT INTO `details_order` VALUES (16, 3, 4, 2, '2023-07-31 19:42:25', NULL, NULL);
INSERT INTO `details_order` VALUES (17, 4, 4, 1, '2023-08-03 06:04:08', NULL, NULL);

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` date NULL DEFAULT NULL,
  `product_id` int NULL DEFAULT NULL,
  `payment_method` int NULL DEFAULT NULL,
  `qty` int NULL DEFAULT NULL,
  `entry_price` int NULL DEFAULT NULL,
  `note` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `tax` int NULL DEFAULT NULL,
  `profit` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `updated_by` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of orders
-- ----------------------------

-- ----------------------------
-- Table structure for orders_new
-- ----------------------------
DROP TABLE IF EXISTS `orders_new`;
CREATE TABLE `orders_new`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `receipt_number` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `date` date NULL DEFAULT NULL,
  `time` time NOT NULL,
  `refund` int NULL DEFAULT NULL,
  `discount` int NULL DEFAULT NULL,
  `total_amount` int NULL DEFAULT NULL,
  `event_type` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `payment_method` int NULL DEFAULT NULL,
  `note` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of orders_new
-- ----------------------------
INSERT INTO `orders_new` VALUES (1, 'SG325', '2023-07-31', '06:30:00', NULL, NULL, 33000, 'Payment', 3, NULL, '2023-07-31 06:29:18', '2023-07-31 06:38:50', NULL, 1, 1);
INSERT INTO `orders_new` VALUES (2, 'SG4363', '2023-07-31', '06:29:00', NULL, NULL, 45000, 'Payment', 3, NULL, '2023-07-31 06:29:55', '2023-07-31 06:39:24', NULL, 1, 1);
INSERT INTO `orders_new` VALUES (3, 'ZAFFF213', '2023-07-31', '19:41:00', NULL, NULL, 72000, 'Payment', 6, NULL, '2023-07-31 19:42:25', NULL, NULL, 1, NULL);
INSERT INTO `orders_new` VALUES (4, '23423', '2023-08-03', '06:03:00', NULL, 0, 11000, 'Payment', 3, NULL, '2023-08-03 06:04:08', NULL, NULL, 1, NULL);

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for payment_method
-- ----------------------------
DROP TABLE IF EXISTS `payment_method`;
CREATE TABLE `payment_method`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `payment_method` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `note` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of payment_method
-- ----------------------------
INSERT INTO `payment_method` VALUES (1, 'Bank Transfer', NULL, '2023-07-22 22:20:52', '2023-07-22 22:21:10', NULL);
INSERT INTO `payment_method` VALUES (2, 'werwwr', NULL, '2023-07-22 22:21:15', '2023-07-22 15:21:17', '2023-07-22 15:21:17');
INSERT INTO `payment_method` VALUES (3, 'Ovo', NULL, '2023-07-22 22:21:49', NULL, NULL);
INSERT INTO `payment_method` VALUES (4, 'Gopay', NULL, '2023-07-22 22:21:55', NULL, NULL);
INSERT INTO `payment_method` VALUES (5, 'Shopeepay', NULL, '2023-07-22 22:22:05', NULL, NULL);
INSERT INTO `payment_method` VALUES (6, 'Bank Mandiri', NULL, '2023-07-22 22:22:23', NULL, NULL);
INSERT INTO `payment_method` VALUES (7, 'Cash', NULL, '2023-07-22 22:23:55', NULL, NULL);
INSERT INTO `payment_method` VALUES (8, 'Qris', NULL, '2023-07-22 22:24:22', NULL, NULL);

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for product
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `category_id` int NULL DEFAULT NULL,
  `price` int NULL DEFAULT NULL,
  `upload` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `desc` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of product
-- ----------------------------
INSERT INTO `product` VALUES (1, 'Real OG', 1, 11000, '1690104983_og.png', 'Real OG Doughnut', '2023-07-23 16:36:23', '2023-07-23 16:52:09', '2023-07-23 16:52:09', 1, 1);
INSERT INTO `product` VALUES (2, 'Real OG', 1, 11000, NULL, NULL, '2023-07-28 00:45:43', NULL, NULL, 1, NULL);
INSERT INTO `product` VALUES (3, 'Lemon Glaze', 2, 16000, NULL, NULL, '2023-07-31 05:03:04', NULL, NULL, 1, NULL);
INSERT INTO `product` VALUES (4, 'OG Glaze', 1, 11000, NULL, NULL, '2023-07-31 05:03:57', NULL, NULL, 1, NULL);
INSERT INTO `product` VALUES (5, 'Chicken Mayo', 2, 18000, NULL, NULL, '2023-07-31 05:04:36', NULL, NULL, 1, NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `role_id` int NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Administrator', 'soughdara@gmail.com', NULL, '$2y$10$TJOW7ohU1CYYN.ooPbqvVOhExeX5To0J77db6cMligv8zY/hOIYaq', NULL, 'admin', '081364243280', 1, 'Cibubur', '2022-12-02 00:49:19', '2023-07-23 07:20:01', NULL);
INSERT INTO `users` VALUES (6, 'Staff Utama', 'soughdara_first_staff@gmail.com', NULL, '$2y$10$FicxdVSPOFk/eQcWfn0U7.1Kf3qmm6eSBakRWL5x3eBnq4XASSaIC', NULL, 'soughdara_utama', '0823213239', 2, 'Cibubur', '2023-07-31 19:45:12', NULL, NULL);

-- ----------------------------
-- Table structure for users_role
-- ----------------------------
DROP TABLE IF EXISTS `users_role`;
CREATE TABLE `users_role`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `role` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `note` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users_role
-- ----------------------------
INSERT INTO `users_role` VALUES (1, 'Admin', 'user for admin role', '2022-11-20 13:09:55', NULL, NULL);
INSERT INTO `users_role` VALUES (2, 'Staff', NULL, '2022-12-11 02:46:46', NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
