# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.12)
# Database: intranet_plate
# Generation Time: 2014-07-23 14:48:55 +0000
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
  `auditable_id` int(11) DEFAULT NULL,
  `auditable_type` varchar(255) DEFAULT NULL,
  `ref` varchar(128) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `data` text,
  `type_mask` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
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
  `amount` decimal(10,2) DEFAULT '0.00',
  `accepted` tinyint(4) DEFAULT '0',
  `remarks` text,
  `receipt_date` date DEFAULT NULL,
  `receipt_number` varchar(128) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`claim_id`,`claim_type_id`),
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
  `unit_price` decimal(10,2) DEFAULT '0.00',
  `unit` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `general_claim_types` WRITE;
/*!40000 ALTER TABLE `general_claim_types` DISABLE KEYS */;

INSERT INTO `general_claim_types` (`id`, `name`, `unit_price`, `unit`, `created_at`, `updated_at`)
VALUES
	(1,'Travel',0.70,'KM','2014-07-23 14:43:00','2014-07-23 14:43:00'),
	(2,'Parking',3.00,'Day','2014-07-23 14:43:00','2014-07-23 14:43:00'),
	(3,'Office Supplies',0.00,NULL,'2014-07-23 14:43:00','2014-07-23 14:43:00'),
	(4,'Entertainment',0.00,NULL,'2014-07-23 14:43:00','2014-07-23 14:43:00');

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
  `value` decimal(10,2) DEFAULT NULL,
  `remarks` varchar(1000) DEFAULT '',
  `status_id` int(11) NOT NULL DEFAULT '1',
  `upload_hash` varchar(128) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`user_id`),
  KEY `fk_claims_users1_idx` (`user_id`),
  KEY `fk_general_claims_status1_idx` (`status_id`),
  KEY `remarks` (`remarks`(255))
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

LOCK TABLES `leave_blocked_dates` WRITE;
/*!40000 ALTER TABLE `leave_blocked_dates` DISABLE KEYS */;

INSERT INTO `leave_blocked_dates` (`id`, `name`, `date`, `created_at`, `updated_at`)
VALUES
	(1,'Hari Raya','2014-07-28','2014-07-17 16:31:38','2014-07-17 16:31:38'),
	(2,'Hari Raya II','2014-07-29','2014-07-17 16:32:12','2014-07-17 16:32:20');

/*!40000 ALTER TABLE `leave_blocked_dates` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table leave_dates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leave_dates`;

CREATE TABLE `leave_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`leave_id`),
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
	(1,'Annual Leave',10,1,0,'#9f661c,#f39c30',1,1,'2014-07-23 14:42:59','2014-07-23 14:42:59'),
	(2,'Medical Leave',10,0,1,'#5b4a85,#967adc',0,1,'2014-07-23 14:42:59','2014-07-23 14:42:59');

/*!40000 ALTER TABLE `leave_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table leave_user_entitlements
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leave_user_entitlements`;

CREATE TABLE `leave_user_entitlements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_type_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `entitlement` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`leave_type_id`),
  KEY `fk_leave_user_entitlements_leave_types1_idx` (`leave_type_id`),
  CONSTRAINT `fk_leave_user_entitlements_leave_types1` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table leaves
# ------------------------------------------------------------

DROP TABLE IF EXISTS `leaves`;

CREATE TABLE `leaves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` varchar(128) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `remarks` varchar(1000) DEFAULT '',
  `total` int(11) NOT NULL,
  `upload_hash` varchar(128) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`user_id`,`leave_type_id`),
  KEY `fk_leaves_leave_types1_idx` (`leave_type_id`),
  KEY `fk_leaves_users1_idx` (`user_id`),
  KEY `remarks` (`remarks`(255)),
  CONSTRAINT `fk_leaves_leave_types1` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
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
	(1,'Child','2014-07-23 14:43:00','2014-07-23 14:43:00'),
	(2,'Mother','2014-07-23 14:43:00','2014-07-23 14:43:00'),
	(3,'Father','2014-07-23 14:43:00','2014-07-23 14:43:00'),
	(4,'Husband','2014-07-23 14:43:00','2014-07-23 14:43:00'),
	(5,'Wife','2014-07-23 14:43:00','2014-07-23 14:43:00');

/*!40000 ALTER TABLE `lookup_family_relationships` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lookup_timing_slots
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lookup_timing_slots`;

CREATE TABLE `lookup_timing_slots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `lookup_timing_slots` WRITE;
/*!40000 ALTER TABLE `lookup_timing_slots` DISABLE KEYS */;

INSERT INTO `lookup_timing_slots` (`id`, `name`, `start`, `end`, `created_at`, `updated_at`)
VALUES
	(1,'Morning','09:00:00','10:30:00','2014-07-23 14:43:00','2014-07-23 14:43:00'),
	(2,'Late Morning','11:00:00','12:30:00','2014-07-23 14:43:00','2014-07-23 14:43:00'),
	(3,'Afternoon','14:00:00','15:30:00','2014-07-23 14:43:00','2014-07-23 14:43:00'),
	(4,'Late Afternoon','16:00:00','17:30:00','2014-07-23 14:43:00','2014-07-23 14:43:00');

/*!40000 ALTER TABLE `lookup_timing_slots` ENABLE KEYS */;
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

LOCK TABLES `medical_claim_panel_clinics` WRITE;
/*!40000 ALTER TABLE `medical_claim_panel_clinics` DISABLE KEYS */;

INSERT INTO `medical_claim_panel_clinics` (`id`, `name`, `created_at`, `updated_at`)
VALUES
	(1,'Klinik Sejahtera','2014-07-23 14:42:59','2014-07-23 14:42:59'),
	(2,'Klinik Sihat','2014-07-23 14:42:59','2014-07-23 14:42:59');

/*!40000 ALTER TABLE `medical_claim_panel_clinics` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medical_claim_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medical_claim_types`;

CREATE TABLE `medical_claim_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `default_entitlement` decimal(20,2) NOT NULL,
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
	(1,'Outpatient',600.00,'#246e88,#3bafda',1,'2014-07-23 14:42:59','2014-07-23 14:42:59'),
	(2,'Dental',200.00,'#516e30,#8cc152',1,'2014-07-23 14:42:59','2014-07-23 14:42:59');

/*!40000 ALTER TABLE `medical_claim_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table medical_claim_user_entitlements
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medical_claim_user_entitlements`;

CREATE TABLE `medical_claim_user_entitlements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `medical_claim_type_id` int(11) NOT NULL,
  `entitlement` decimal(20,2) NOT NULL,
  `start_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`medical_claim_type_id`),
  KEY `fk_medical_claim_user_packages_medical_claim_types1_idx` (`medical_claim_type_id`),
  CONSTRAINT `fk_medical_claim_user_packages_medical_claim_types1` FOREIGN KEY (`medical_claim_type_id`) REFERENCES `medical_claim_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table medical_claims
# ------------------------------------------------------------

DROP TABLE IF EXISTS `medical_claims`;

CREATE TABLE `medical_claims` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` varchar(45) DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `medical_claim_type_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `treatment_date` date DEFAULT NULL,
  `total` decimal(20,2) DEFAULT NULL,
  `medical_claim_panel_clinic_id` int(11) NOT NULL,
  `remarks` varchar(1000) DEFAULT '',
  `upload_hash` varchar(128) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`medical_claim_type_id`,`medical_claim_panel_clinic_id`),
  KEY `fk_medical_claims_medical_claim_types1_idx` (`medical_claim_type_id`),
  KEY `fk_medical_claims_medical_claim_panel_clinics1_idx` (`medical_claim_panel_clinic_id`),
  KEY `remarks` (`remarks`(255)),
  CONSTRAINT `fk_medical_claims_medical_claim_panel_clinics1` FOREIGN KEY (`medical_claim_panel_clinic_id`) REFERENCES `medical_claim_panel_clinics` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_medical_claims_medical_claim_types1` FOREIGN KEY (`medical_claim_type_id`) REFERENCES `medical_claim_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `modules`;

CREATE TABLE `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `verifier` int(11) DEFAULT NULL,
  `approver` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;

INSERT INTO `modules` (`id`, `name`, `verifier`, `approver`, `created_at`, `updated_at`)
VALUES
	(1,'Leave',-2,-1,'2014-07-23 14:42:59','2014-07-23 14:42:59'),
	(2,'Medical Claims',-2,-1,'2014-07-23 14:42:59','2014-07-23 14:42:59'),
	(3,'General Claims',-2,-1,'2014-07-23 14:42:59','2014-07-23 14:42:59'),
	(4,'Room Bookings',-2,-1,'2014-07-23 14:42:59','2014-07-23 14:42:59');

/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table room_booking_rooms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `room_booking_rooms`;

CREATE TABLE `room_booking_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `room_booking_rooms` WRITE;
/*!40000 ALTER TABLE `room_booking_rooms` DISABLE KEYS */;

INSERT INTO `room_booking_rooms` (`id`, `name`, `created_at`, `updated_at`)
VALUES
	(1,'Meeting Room','2014-07-23 14:43:00','2014-07-23 14:43:00');

/*!40000 ALTER TABLE `room_booking_rooms` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table room_booking_timing_slots
# ------------------------------------------------------------

DROP TABLE IF EXISTS `room_booking_timing_slots`;

CREATE TABLE `room_booking_timing_slots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_booking_id` int(11) NOT NULL,
  `lookup_timing_slot_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`room_booking_id`,`lookup_timing_slot_id`),
  KEY `fk_room_booking_slots_room_bookings_idx` (`room_booking_id`),
  KEY `fk_room_booking_slots_lookup_timing_slots1_idx` (`lookup_timing_slot_id`),
  CONSTRAINT `fk_room_booking_slots_room_bookings` FOREIGN KEY (`room_booking_id`) REFERENCES `room_bookings` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_room_booking_slots_lookup_timing_slots1` FOREIGN KEY (`lookup_timing_slot_id`) REFERENCES `lookup_timing_slots` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `room_booking_timing_slots` WRITE;
/*!40000 ALTER TABLE `room_booking_timing_slots` DISABLE KEYS */;

INSERT INTO `room_booking_timing_slots` (`id`, `room_booking_id`, `lookup_timing_slot_id`, `created_at`, `updated_at`)
VALUES
	(4,3,3,NULL,NULL),
	(5,4,4,NULL,NULL),
	(6,5,1,NULL,NULL),
	(7,6,2,NULL,NULL),
	(8,7,3,NULL,NULL),
	(9,8,4,NULL,NULL),
	(10,9,1,NULL,NULL),
	(11,10,2,NULL,NULL),
	(12,11,3,NULL,NULL),
	(13,12,4,NULL,NULL),
	(16,1,1,NULL,NULL),
	(17,2,2,NULL,NULL);

/*!40000 ALTER TABLE `room_booking_timing_slots` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table room_bookings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `room_bookings`;

CREATE TABLE `room_bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` varchar(128) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `room_booking_room_id` int(11) NOT NULL,
  `booking_date` date DEFAULT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `remarks` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`room_booking_room_id`),
  KEY `fk_room_bookings_lookup_rooms1_idx` (`room_booking_room_id`),
  KEY `remarks` (`remarks`(255)),
  KEY `purpose` (`purpose`),
  CONSTRAINT `fk_room_bookings_lookup_rooms1` FOREIGN KEY (`room_booking_room_id`) REFERENCES `room_booking_rooms` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table share_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `share_comments`;

CREATE TABLE `share_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `share_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table shares
# ------------------------------------------------------------

DROP TABLE IF EXISTS `shares`;

CREATE TABLE `shares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(128) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(30) DEFAULT NULL,
  `content` varchar(1000) DEFAULT '',
  `root_path` varchar(128) DEFAULT NULL,
  `file_name` varchar(128) DEFAULT NULL,
  `extension` varchar(45) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `pinned` tinyint(4) DEFAULT '0',
  `shareable_type` varchar(128) DEFAULT NULL,
  `shareable_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`user_id`),
  KEY `fk_shares_users1_idx` (`user_id`),
  KEY `title` (`title`,`content`(255)),
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
	(1,'Pending',NULL,'2014-07-23 14:42:59','2014-07-23 14:42:59',NULL),
	(2,'Verified',NULL,'2014-07-23 14:42:59','2014-07-23 14:42:59',NULL),
	(3,'Approved',NULL,'2014-07-23 14:42:59','2014-07-23 14:42:59',NULL),
	(4,'Rejected',NULL,'2014-07-23 14:42:59','2014-07-23 14:42:59',NULL),
	(5,'Cancelled',NULL,'2014-07-23 14:42:59','2014-07-23 14:42:59',NULL);

/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;


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
  PRIMARY KEY (`id`,`user_id`,`module_id`),
  KEY `fk_users_has_modules_modules1_idx` (`module_id`),
  KEY `fk_users_has_modules_users1_idx` (`user_id`),
  CONSTRAINT `fk_users_has_modules_modules1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_has_modules_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_modules` WRITE;
/*!40000 ALTER TABLE `user_modules` DISABLE KEYS */;

INSERT INTO `user_modules` (`id`, `user_id`, `module_id`, `created_at`, `updated_at`)
VALUES
	(1,3,1,'2014-07-23 14:42:59','2014-07-23 14:42:59'),
	(2,3,2,'2014-07-23 14:42:59','2014-07-23 14:42:59'),
	(3,3,3,'2014-07-23 14:42:59','2014-07-23 14:42:59'),
	(4,3,4,'2014-07-23 14:42:59','2014-07-23 14:42:59');

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
  PRIMARY KEY (`id`,`user_profile_id`),
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
  PRIMARY KEY (`id`,`user_profile_id`),
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
  PRIMARY KEY (`id`,`user_profile_id`),
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
  PRIMARY KEY (`id`,`user_profile_id`),
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
  PRIMARY KEY (`id`,`user_profile_id`,`lookup_family_relationship_id`),
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
  PRIMARY KEY (`id`,`user_id`),
  KEY `fk_user_profiles_users1_idx` (`user_id`),
  CONSTRAINT `fk_user_profiles_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_profiles` WRITE;
/*!40000 ALTER TABLE `user_profiles` DISABLE KEYS */;

INSERT INTO `user_profiles` (`id`, `user_id`, `first_name`, `last_name`, `user_image`, `address`, `created_at`, `updated_at`)
VALUES
	(1,1,'admin',NULL,'http://api.randomuser.me/portraits/men/1.jpg',NULL,'2014-07-23 14:42:59','2014-07-23 14:42:59'),
	(2,2,'user',NULL,'/profile/af21c514b79412dd33fbfd6ae37e453e/original.png',NULL,'2014-07-23 14:42:59','2014-07-23 14:45:51'),
	(3,3,'module owner',NULL,'http://api.randomuser.me/portraits/men/3.jpg',NULL,'2014-07-23 14:42:59','2014-07-23 14:42:59'),
	(4,4,'unit head',NULL,'http://api.randomuser.me/portraits/men/4.jpg',NULL,'2014-07-23 14:42:59','2014-07-23 14:42:59');

/*!40000 ALTER TABLE `user_profiles` ENABLE KEYS */;
UNLOCK TABLES;


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
	(1,'main',4,NULL,1,2,0,'2014-07-23 14:42:59','2014-07-23 14:42:59');

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
  `email_password` varchar(255) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `is_admin` tinyint(4) NOT NULL DEFAULT '0',
  `verified` tinyint(4) DEFAULT '0',
  `verify_token` varchar(128) DEFAULT NULL,
  `remember_token` varchar(128) DEFAULT NULL,
  `leave_package_id` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`unit_id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_users_units1_idx` (`unit_id`),
  CONSTRAINT `fk_users_units1` FOREIGN KEY (`unit_id`) REFERENCES `user_units` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `unit_id`, `email`, `password`, `email_password`, `gender`, `is_admin`, `verified`, `verify_token`, `remember_token`, `leave_package_id`, `created_at`, `updated_at`)
VALUES
	(1,1,'admin','$2y$10$9E2SHlFZbHrQeveJLttGvu34z5nvnhMedzzvhskVeLaXQh3yMARRO','KKNA1h23',NULL,1,1,NULL,NULL,1,'2014-07-23 14:42:59','2014-07-23 14:42:59'),
	(2,1,'user','$2y$10$vGiVJqMs7U4jvtuBBhGmWubeLJUh7TcbAe/7o3KD3usHcfnm8Ecb2','d1OaddZE',NULL,0,1,NULL,NULL,1,'2014-07-23 14:42:59','2014-07-23 14:42:59'),
	(3,1,'mo','$2y$10$ZRbWgelTPscyJaTg85j9oO2iBOpqXGCAlqrPpaxb7IfJydZ4uI5aW','VneS8NNV',NULL,0,1,NULL,NULL,1,'2014-07-23 14:42:59','2014-07-23 14:42:59'),
	(4,1,'uh','$2y$10$VfN6BTGx3.s5jjIxZnWx9OXXMGVUlvzlOibYvBtX.XgUS0DuWJB.W','usse9Yye',NULL,0,1,NULL,NULL,1,'2014-07-23 14:42:59','2014-07-23 14:42:59');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
