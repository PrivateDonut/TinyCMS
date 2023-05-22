  -- --------------------------------------------------------
  -- Host:                         127.0.0.1
  -- Server version:               8.0.30 - MySQL Community Server - GPL
  -- Server OS:                    Win64
  -- HeidiSQL Version:             12.0.0.6468
  -- --------------------------------------------------------

  /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
  /*!40101 SET NAMES utf8 */;
  /*!50503 SET NAMES utf8mb4 */;
  /*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
  /*!40103 SET TIME_ZONE='+00:00' */;
  /*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
  /*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
  /*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;



  -- Dumping structure for table website.access
  CREATE TABLE IF NOT EXISTS `access` (
    `id` int NOT NULL AUTO_INCREMENT,
    `account_id` int DEFAULT '0',
    `access_level` bit(1) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- Dumping data for table website.access: ~0 rows (approximately)

  -- Dumping structure for table website.cart
  CREATE TABLE IF NOT EXISTS `cart` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL,
    `product_id` bigint unsigned NOT NULL,
    `quantity` bigint DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `FK_users_user_id_2` (`user_id`),
    KEY `FK_products_product_id_3` (`product_id`),
    CONSTRAINT `FK_products_product_id_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
    CONSTRAINT `FK_users_user_id_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- Dumping data for table website.cart: ~0 rows (approximately)

  -- Dumping structure for table website.categories
  CREATE TABLE IF NOT EXISTS `categories` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
    `active` tinyint(1) NOT NULL DEFAULT '0',
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- Dumping data for table website.categories: ~1 rows (approximately)
  INSERT INTO `categories` (`id`, `title`, `active`, `deleted_at`) VALUES
    (1, 'Items', 1, NULL);

  -- Dumping structure for table website.category_products
  CREATE TABLE IF NOT EXISTS `category_products` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `category_id` bigint unsigned NOT NULL,
    `product_id` bigint unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `category_id` (`category_id`),
    KEY `product_id` (`product_id`),
    CONSTRAINT `FK_categories_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
    CONSTRAINT `FK_products_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- Dumping data for table website.category_products: ~0 rows (approximately)

  -- Dumping structure for table website.news
  CREATE TABLE IF NOT EXISTS `news` (
    `id` int NOT NULL AUTO_INCREMENT,
    `title` text COLLATE utf8mb4_general_ci,
    `content` text COLLATE utf8mb4_general_ci,
    `author` text COLLATE utf8mb4_general_ci,
    `edit_by` int DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- Dumping data for table website.news: ~1 rows (approximately)
  INSERT INTO `news` (`id`, `title`, `content`, `author`, `edit_by`, `created_at`) VALUES
    (1, 'Welcome To TinyCMS!', 'Thank you for using TinyCMS! You may remove this new post in the admin panel and create your own! ', 'PrivateDonut', NULL, '2023-05-13 00:19:32');

  -- Dumping structure for table website.orders
  CREATE TABLE IF NOT EXISTS `orders` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `FK_users_user_id` (`user_id`),
    CONSTRAINT `FK_users_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- Dumping data for table website.orders: ~0 rows (approximately)

  -- Dumping structure for table website.order_history
  CREATE TABLE IF NOT EXISTS `order_history` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `order_id` bigint unsigned DEFAULT NULL,
    `message` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    `vote_points` int unsigned DEFAULT '0',
    `donor_points` int unsigned DEFAULT '0',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `order_id` (`order_id`),
    CONSTRAINT `FK_order_id_orders_history` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- Dumping data for table website.order_history: ~0 rows (approximately)

  -- Dumping structure for table website.order_products
  CREATE TABLE IF NOT EXISTS `order_products` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `product_id` bigint unsigned NOT NULL,
    `order_id` bigint unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `product_id` (`product_id`),
    KEY `order_id` (`order_id`),
    CONSTRAINT `FK_orders_order_id_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
    CONSTRAINT `FK_products_product_id_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- Dumping data for table website.order_products: ~0 rows (approximately)

  -- Dumping structure for table website.products
  CREATE TABLE IF NOT EXISTS `products` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `item_id` int unsigned NOT NULL,
    `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
    `vote_points` int DEFAULT '0',
    `donor_points` int DEFAULT '0',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- Dumping data for table website.products: ~0 rows (approximately)

  -- Dumping structure for table website.users
  CREATE TABLE IF NOT EXISTS `users` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `account_id` int unsigned NOT NULL,
    `vote_points` int DEFAULT NULL,
    `donor_points` int DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  -- Dumping data for table website.users: ~0 rows (approximately)

  /*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
  /*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
  /*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
  /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
  /*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
