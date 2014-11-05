# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.12)
# Database: cloudhrd_app
# Generation Time: 2014-11-05 03:06:01 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table audits
# ------------------------------------------------------------

DROP TABLE IF EXISTS `audits`;

CREATE TABLE `audits` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `auditable_id` int(11) DEFAULT NULL,
  `auditable_type` varchar(255) DEFAULT NULL,
  `ref` varchar(128) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `data` text,
  `type_mask` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_audits_users1_idx` (`user_id`),
  CONSTRAINT `fk_audits_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table general_claim_entries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `general_claim_entries`;

CREATE TABLE `general_claim_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claim_id` int(11) NOT NULL,
  `claim_type_id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT '0.00',
  `amount` decimal(20,4) DEFAULT '0.0000',
  `accepted` tinyint(4) DEFAULT '0',
  `remarks` text,
  `receipt_date` date DEFAULT NULL,
  `receipt_number` varchar(128) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_claim_entries_claims1_idx` (`claim_id`),
  KEY `fk_claim_entries_claim_types1_idx` (`claim_type_id`),
  CONSTRAINT `fk_claim_entries_claims1` FOREIGN KEY (`claim_id`) REFERENCES `general_claims` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_claim_entries_claim_types1` FOREIGN KEY (`claim_type_id`) REFERENCES `general_claim_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table general_claim_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `general_claim_types`;

CREATE TABLE `general_claim_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `unit_price` decimal(20,4) DEFAULT '0.0000',
  `unit` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `general_claim_types` WRITE;
/*!40000 ALTER TABLE `general_claim_types` DISABLE KEYS */;

INSERT INTO `general_claim_types` (`id`, `name`, `unit_price`, `unit`, `created_at`, `updated_at`)
VALUES
	(1,'Travel',0.4500,'Mi','2014-07-23 15:25:43','2014-07-23 15:25:43'),
	(2,'Office Supplies',0.0000,NULL,'2014-07-23 15:25:43','2014-07-23 15:25:43');

/*!40000 ALTER TABLE `general_claim_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table general_claims
# ------------------------------------------------------------

DROP TABLE IF EXISTS `general_claims`;

CREATE TABLE `general_claims` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ref` varchar(45) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `value` decimal(20,4) DEFAULT '0.0000',
  `remarks` varchar(1000) DEFAULT '',
  `status_id` int(11) NOT NULL DEFAULT '1',
  `upload_hash` varchar(128) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_general_claims_status1_idx` (`status_id`),
  KEY `remarks` (`remarks`(255)),
  KEY `fk_general_claims_users1_idx` (`user_id`),
  CONSTRAINT `fk_general_claims_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table leave_blocked_dates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leave_blocked_dates`;

CREATE TABLE `leave_blocked_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table leave_dates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leave_dates`;

CREATE TABLE `leave_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_leave_dates_leaves1_idx1` (`leave_id`),
  KEY `date` (`date`),
  CONSTRAINT `fk_leave_dates_leaves1` FOREIGN KEY (`leave_id`) REFERENCES `leaves` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table leave_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leave_types`;

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `default_entitlement` int(11) DEFAULT NULL,
  `future` tinyint(4) DEFAULT '1',
  `past` tinyint(4) DEFAULT '0',
  `colors` varchar(128) DEFAULT '#696969,#c4c4c4',
  `display_calendar` tinyint(4) DEFAULT NULL,
  `display_wall` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `leave_types` WRITE;
/*!40000 ALTER TABLE `leave_types` DISABLE KEYS */;

INSERT INTO `leave_types` (`id`, `name`, `default_entitlement`, `future`, `past`, `colors`, `display_calendar`, `display_wall`, `created_at`, `updated_at`)
VALUES
	(1,'Annual Leave',15,1,0,'#9f661c,#f39c30',1,1,'2014-07-23 15:25:43','2014-07-23 15:25:43'),
	(2,'Medical Leave',30,0,1,'#5b4a85,#967adc',0,1,'2014-07-23 15:25:43','2014-07-23 15:25:43');

/*!40000 ALTER TABLE `leave_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table leave_user_entitlements
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leave_user_entitlements`;

CREATE TABLE `leave_user_entitlements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `entitlement` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_leave_user_entitlements_leave_types1_idx` (`leave_type_id`),
  KEY `fk_leave_user_entitlements_users1_idx` (`user_id`),
  CONSTRAINT `fk_leave_user_entitlements_leave_types1` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_leave_user_entitlements_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table leaves
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leaves`;

CREATE TABLE `leaves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ref` varchar(128) DEFAULT NULL,
  `leave_type_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `remarks` varchar(1000) DEFAULT '',
  `total` int(11) NOT NULL,
  `upload_hash` varchar(128) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_leaves_leave_types1_idx` (`leave_type_id`),
  KEY `remarks` (`remarks`(255)),
  KEY `fk_leaves_users1_idx` (`user_id`),
  CONSTRAINT `fk_leaves_leave_types1` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_leaves_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table lookup_family_relationships
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lookup_family_relationships`;

CREATE TABLE `lookup_family_relationships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `lookup_family_relationships` WRITE;
/*!40000 ALTER TABLE `lookup_family_relationships` DISABLE KEYS */;

INSERT INTO `lookup_family_relationships` (`id`, `name`, `created_at`, `updated_at`)
VALUES
	(1,'Child','2014-07-23 15:25:44','2014-07-23 15:25:44'),
	(2,'Mother','2014-07-23 15:25:44','2014-07-23 15:25:44'),
	(3,'Father','2014-07-23 15:25:44','2014-07-23 15:25:44'),
	(4,'Husband','2014-07-23 15:25:44','2014-07-23 15:25:44'),
	(5,'Wife','2014-07-23 15:25:44','2014-07-23 15:25:44');

/*!40000 ALTER TABLE `lookup_family_relationships` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medical_claim_panel_clinics
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medical_claim_panel_clinics`;

CREATE TABLE `medical_claim_panel_clinics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table medical_claim_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medical_claim_types`;

CREATE TABLE `medical_claim_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `default_entitlement` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `colors` varchar(128) DEFAULT '#696969,#c4c4c4',
  `display_wall` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `medical_claim_types` WRITE;
/*!40000 ALTER TABLE `medical_claim_types` DISABLE KEYS */;

INSERT INTO `medical_claim_types` (`id`, `name`, `default_entitlement`, `colors`, `display_wall`, `created_at`, `updated_at`)
VALUES
	(1,'Outpatient',900.0000,'#246e88,#3bafda',1,'2014-07-23 15:25:43','2014-07-23 15:25:43'),
	(2,'Dental',200.0000,'#516e30,#8cc152',1,'2014-07-23 15:25:43','2014-07-23 15:25:43');

/*!40000 ALTER TABLE `medical_claim_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medical_claim_user_entitlements
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medical_claim_user_entitlements`;

CREATE TABLE `medical_claim_user_entitlements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `medical_claim_type_id` int(11) NOT NULL,
  `entitlement` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `start_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_medical_claim_user_packages_medical_claim_types1_idx` (`medical_claim_type_id`),
  CONSTRAINT `fk_medical_claim_user_packages_medical_claim_types1` FOREIGN KEY (`medical_claim_type_id`) REFERENCES `medical_claim_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table medical_claims
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medical_claims`;

CREATE TABLE `medical_claims` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ref` varchar(45) DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `medical_claim_type_id` int(11) NOT NULL,
  `treatment_date` date DEFAULT NULL,
  `total` decimal(20,4) DEFAULT '0.0000',
  `remarks` varchar(1000) DEFAULT '',
  `upload_hash` varchar(128) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_medical_claims_medical_claim_types1_idx` (`medical_claim_type_id`),
  KEY `remarks` (`remarks`(255)),
  KEY `fk_medical_claims_users1_idx` (`user_id`),
  CONSTRAINT `fk_medical_claims_medical_claim_types1` FOREIGN KEY (`medical_claim_type_id`) REFERENCES `medical_claim_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_medical_claims_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `modules`;

CREATE TABLE `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `verifier` int(11) DEFAULT NULL,
  `approver` int(11) DEFAULT NULL,
  `has_config` tinyint(4) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;

INSERT INTO `modules` (`id`, `name`, `verifier`, `approver`, `has_config`, `created_at`, `updated_at`)
VALUES
	(1,'Leave',-2,-1,1,'2014-07-23 15:25:43','2014-07-23 15:25:43'),
	(2,'Medical Claims',-2,-1,1,'2014-07-23 15:25:43','2014-07-23 15:25:43'),
	(3,'General Claims',-2,-1,1,'2014-07-23 15:25:43','2014-07-23 15:25:43'),
	(4,'Tasks',-2,-1,1,'2014-07-23 15:25:43','2014-07-23 15:25:43');

/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table share_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `share_comments`;

CREATE TABLE `share_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `share_id` int(11) NOT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_share_comments_users1_idx` (`user_id`),
  KEY `fk_share_comments_shares1_idx` (`share_id`),
  CONSTRAINT `fk_share_comments_shares1` FOREIGN KEY (`share_id`) REFERENCES `shares` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_share_comments_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table shares
# ------------------------------------------------------------

DROP TABLE IF EXISTS `shares`;

CREATE TABLE `shares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(128) NOT NULL,
  `content` text,
  `meta` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_shares_users1_idx` (`user_id`),
  CONSTRAINT `fk_shares_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `status`;

CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `overide_name` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;

INSERT INTO `status` (`id`, `name`, `overide_name`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(1,'Pending',NULL,'2014-07-23 15:25:43','2014-07-23 15:25:43',NULL),
	(2,'Verified',NULL,'2014-07-23 15:25:43','2014-07-23 15:25:43',NULL),
	(3,'Approved',NULL,'2014-07-23 15:25:43','2014-07-23 15:25:43',NULL),
	(4,'Rejected',NULL,'2014-07-23 15:25:43','2014-07-23 15:25:43',NULL),
	(5,'Cancelled',NULL,'2014-07-23 15:25:43','2014-07-23 15:25:43',NULL);

/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tag_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tag_categories`;

CREATE TABLE `tag_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `in_header` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `tag_categories` WRITE;
/*!40000 ALTER TABLE `tag_categories` DISABLE KEYS */;

INSERT INTO `tag_categories` (`id`, `name`, `in_header`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(1,'Status',1,'2014-07-23 15:25:43','2014-07-23 15:25:43',NULL),
	(2,'Priority',1,'2014-07-23 15:25:43','2014-07-23 15:25:43',NULL);

/*!40000 ALTER TABLE `tag_categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tag_user_orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tag_user_orders`;

CREATE TABLE `tag_user_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `order` int(2) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tag_user_orders_users1_idx` (`user_id`),
  KEY `fk_tag_user_orders_tags1_idx` (`tag_id`),
  CONSTRAINT `fk_tag_user_orders_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tag_user_orders_tags1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table tag_user_placements
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tag_user_placements`;

CREATE TABLE `tag_user_placements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL DEFAULT 'left',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tag_user_placements_users1_idx` (`user_id`),
  KEY `fk_tag_user_placements_tags1_idx` (`tag_id`),
  CONSTRAINT `fk_tag_user_placements_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tag_user_placements_tags1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_category_id` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `label` varchar(128) DEFAULT 'default',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tags_tag_categories1_idx` (`tag_category_id`),
  CONSTRAINT `fk_tags_tag_categories1` FOREIGN KEY (`tag_category_id`) REFERENCES `tag_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;

INSERT INTO `tags` (`id`, `tag_category_id`, `name`, `label`, `created_at`, `updated_at`)
VALUES
	(1,1,'New','warning','2014-07-23 15:25:43','2014-07-23 15:25:43'),
	(2,1,'Doing','info','2014-07-23 15:25:43','2014-07-23 15:25:43'),
	(3,1,'Done','success','2014-07-23 15:25:43','2014-07-23 15:25:43'),
	(4,2,'High','danger','2014-07-23 15:25:43','2014-07-23 15:25:43'),
	(5,2,'Low','info','2014-07-23 15:25:43','2014-07-23 15:25:43');

/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table todo_followers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `todo_followers`;

CREATE TABLE `todo_followers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `todo_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_todos_todos1_idx` (`todo_id`),
  KEY `fk_users_todos_users1_idx` (`user_id`),
  CONSTRAINT `fk_users_todos_todos1` FOREIGN KEY (`todo_id`) REFERENCES `todos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_users_todos_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table todo_histories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `todo_histories`;

CREATE TABLE `todo_histories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `todo_id` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_todo_histories_todos1_idx` (`todo_id`),
  KEY `fk_todo_histories_users1_idx` (`user_id`),
  CONSTRAINT `fk_todo_histories_todos1` FOREIGN KEY (`todo_id`) REFERENCES `todos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_todo_histories_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table todo_orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `todo_orders`;

CREATE TABLE `todo_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `todo_id` int(10) unsigned NOT NULL,
  `tag_category_id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `order` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_todo_orders_tag_categories1_idx` (`tag_category_id`),
  KEY `fk_todo_orders_users1_idx` (`user_id`),
  KEY `fk_todo_orders_todos1_idx` (`todo_id`),
  CONSTRAINT `fk_todo_orders_tag_categories1` FOREIGN KEY (`tag_category_id`) REFERENCES `tag_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_todo_orders_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_todo_orders_todos1` FOREIGN KEY (`todo_id`) REFERENCES `todos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table todo_tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `todo_tags`;

CREATE TABLE `todo_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `todo_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_todos_tags_tags1_idx` (`tag_id`),
  KEY `fk_todos_tags_todos1_idx` (`todo_id`),
  CONSTRAINT `fk_todos_tags_tags1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_todos_tags_todos1` FOREIGN KEY (`todo_id`) REFERENCES `todos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table todos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `todos`;

CREATE TABLE `todos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `description` text,
  `archived` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_todos_users1_idx` (`owner_id`),
  CONSTRAINT `fk_todos_users1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table uploads
# ------------------------------------------------------------

DROP TABLE IF EXISTS `uploads`;

CREATE TABLE `uploads` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `imageable_id` int(11) DEFAULT NULL,
  `imageable_type` varchar(255) NOT NULL DEFAULT '',
  `mask` varchar(128) DEFAULT NULL,
  `file_name` varchar(128) DEFAULT NULL,
  `size` int(20) DEFAULT NULL,
  `file_url` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `thumb_url` varchar(255) DEFAULT NULL,
  `thumb_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user_modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_modules`;

CREATE TABLE `user_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_has_modules_modules1_idx` (`module_id`),
  KEY `fk_users_has_modules_users1_idx` (`user_id`),
  CONSTRAINT `fk_users_has_modules_modules1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_has_modules_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_modules` WRITE;
/*!40000 ALTER TABLE `user_modules` DISABLE KEYS */;

INSERT INTO `user_modules` (`id`, `user_id`, `module_id`, `created_at`, `updated_at`)
VALUES
	(1,1,1,'2014-07-23 15:25:43','2014-07-23 15:25:43'),
	(2,1,2,'2014-07-23 15:25:43','2014-07-23 15:25:43'),
	(3,1,3,'2014-07-23 15:25:43','2014-07-23 15:25:43'),
	(4,1,4,'2014-07-23 15:25:43','2014-07-23 15:25:43');

/*!40000 ALTER TABLE `user_modules` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_profile_contacts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_profile_contacts`;

CREATE TABLE `user_profile_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_profile_id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_profile_contacts_user_profiles1_idx` (`user_profile_id`),
  CONSTRAINT `fk_user_profile_contacts_user_profiles1` FOREIGN KEY (`user_profile_id`) REFERENCES `user_profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user_profile_education_histories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_profile_education_histories`;

CREATE TABLE `user_profile_education_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_profile_id` int(11) NOT NULL,
  `institution` varchar(128) DEFAULT NULL,
  `course` varchar(128) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_profile_education_histories_user_profiles1_idx` (`user_profile_id`),
  CONSTRAINT `fk_user_profile_education_histories_user_profiles1` FOREIGN KEY (`user_profile_id`) REFERENCES `user_profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user_profile_emergency_contacts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_profile_emergency_contacts`;

CREATE TABLE `user_profile_emergency_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_profile_id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `phone` varchar(128) DEFAULT NULL,
  `address` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_profile_emergency_contacts_user_profiles1_idx` (`user_profile_id`),
  CONSTRAINT `fk_user_profile_emergency_contacts_user_profiles1` FOREIGN KEY (`user_profile_id`) REFERENCES `user_profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user_profile_employment_histories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_profile_employment_histories`;

CREATE TABLE `user_profile_employment_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_profile_id` int(11) NOT NULL,
  `company_name` varchar(128) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `position` varchar(128) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_profile_employement_histories_user_profiles1_idx` (`user_profile_id`),
  CONSTRAINT `fk_user_profile_employement_histories_user_profiles1` FOREIGN KEY (`user_profile_id`) REFERENCES `user_profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user_profile_family_members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_profile_family_members`;

CREATE TABLE `user_profile_family_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_profile_id` int(11) NOT NULL,
  `lookup_family_relationship_id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`lookup_family_relationship_id`),
  KEY `fk_user_profile_family_members_user_profiles1_idx` (`user_profile_id`),
  KEY `fk_user_profile_family_members_lookup_family_relationships1_idx` (`lookup_family_relationship_id`),
  CONSTRAINT `fk_user_profile_family_members_lookup_family_relationships1` FOREIGN KEY (`lookup_family_relationship_id`) REFERENCES `lookup_family_relationships` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_profile_family_members_user_profiles1` FOREIGN KEY (`user_profile_id`) REFERENCES `user_profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user_profiles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_profiles`;

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(128) DEFAULT NULL,
  `last_name` varchar(128) DEFAULT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `address` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_profiles_users1_idx` (`user_id`),
  CONSTRAINT `fk_user_profiles_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_profiles` WRITE;
/*!40000 ALTER TABLE `user_profiles` DISABLE KEYS */;

INSERT INTO `user_profiles` (`id`, `user_id`, `first_name`, `last_name`, `user_image`, `address`, `created_at`, `updated_at`)
VALUES
	(1,1,'admin',NULL,'http://api.randomuser.me/portraits/men/1.jpg',NULL,'2014-07-23 15:25:43','2014-07-23 15:25:43');

/*!40000 ALTER TABLE `user_profiles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_share_pins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_share_pins`;

CREATE TABLE `user_share_pins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `share_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_share_user_pins_users1_idx` (`user_id`),
  KEY `fk_share_user_pins_shares1_idx` (`share_id`),
  CONSTRAINT `fk_share_user_pins_shares1` FOREIGN KEY (`share_id`) REFERENCES `shares` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_share_user_pins_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table user_units
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_units`;

CREATE TABLE `user_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_units_users_idx` (`user_id`),
  CONSTRAINT `fk_units_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_units` WRITE;
/*!40000 ALTER TABLE `user_units` DISABLE KEYS */;

INSERT INTO `user_units` (`id`, `name`, `user_id`, `parent_id`, `lft`, `rgt`, `depth`, `created_at`, `updated_at`)
VALUES
	(1,'main',4,NULL,1,4,0,'2014-07-23 15:25:43','2014-08-15 08:27:49');

/*!40000 ALTER TABLE `user_units` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `gender` int(11) DEFAULT NULL,
  `is_admin` tinyint(4) NOT NULL DEFAULT '0',
  `verified` tinyint(4) DEFAULT '0',
  `verify_token` varchar(128) DEFAULT NULL,
  `remember_token` varchar(128) DEFAULT NULL,
  `leave_package_id` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_users_user_units1_idx` (`unit_id`),
  CONSTRAINT `fk_users_user_units1` FOREIGN KEY (`unit_id`) REFERENCES `user_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `unit_id`, `email`, `password`, `gender`, `is_admin`, `verified`, `verify_token`, `remember_token`, `leave_package_id`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(1,0,'admin@intranet.boxedge.com','$2y$10$f7Ot8H3LzMn2QvNLYOrOOe9eWLl64tT.c4SS/yDwLM0EgtLevMS8i',NULL,1,1,NULL,'kYjnzQTyfEwBlsF84h6Xq1fzGhTQ8DfIyi8j6hnsgjAnVrvPQpqAoztAokdX',1,'2014-07-23 15:25:43','2014-08-15 08:16:10',NULL);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
