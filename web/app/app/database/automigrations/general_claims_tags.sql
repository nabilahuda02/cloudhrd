SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ALLOW_INVALID_DATES';

ALTER TABLE `shares`
DROP FOREIGN KEY `fk_shares_user_units1`;

ALTER TABLE `general_claim_entries`
ADD COLUMN `general_claim_tag_id` INT(11) NULL DEFAULT NULL COMMENT '' AFTER `receipt_number`,
ADD INDEX `fk_general_claim_entries_general_claim_tags1_idx` (`general_claim_tag_id` ASC)  COMMENT '';

CREATE TABLE IF NOT EXISTS `general_claim_tags` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(128) NULL DEFAULT NULL COMMENT '',
  `enabled` TINYINT(1) NULL DEFAULT 1 COMMENT '',
  `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;

ALTER TABLE `general_claim_entries`
ADD CONSTRAINT `fk_general_claim_entries_general_claim_tags1`
  FOREIGN KEY (`general_claim_tag_id`)
  REFERENCES `general_claim_tags` (`id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;

ALTER TABLE `shares`
ADD CONSTRAINT `fk_shares_user_units`
  FOREIGN KEY (`user_unit_id`)
  REFERENCES `user_units` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
