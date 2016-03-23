SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ALLOW_INVALID_DATES';

CREATE TABLE IF NOT EXISTS `payrolls` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `status_id` INT(11) NOT NULL COMMENT '',
  `name` VARCHAR(128) NULL DEFAULT NULL COMMENT '',
  `total` DECIMAL(20,2) NULL DEFAULT 0.00 COMMENT '',
  `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_payrolls_status1_idx` (`status_id` ASC)  COMMENT '',
  CONSTRAINT `fk_payrolls_status1`
    FOREIGN KEY (`status_id`)
    REFERENCES `status` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `payroll_user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `payroll_id` INT(11) NOT NULL COMMENT '',
  `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `total` DECIMAL(15,2) NULL DEFAULT 0.00 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_payroll_user_payrolls1_idx` (`payroll_id` ASC)  COMMENT '',
  INDEX `fk_payroll_user_users1_idx` (`user_id` ASC)  COMMENT '',
  CONSTRAINT `fk_payroll_user_payrolls1`
    FOREIGN KEY (`payroll_id`)
    REFERENCES `payrolls` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_payroll_user_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `payroll_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `payroll_user_id` INT(11) NOT NULL COMMENT '',
  `name` VARCHAR(128) NULL DEFAULT NULL COMMENT '',
  `payrollable_type` VARCHAR(128) NULL DEFAULT NULL COMMENT '',
  `payrollable_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT '',
  `amount` VARCHAR(128) NULL DEFAULT '0.00' COMMENT '',
  `description` TEXT NULL DEFAULT NULL COMMENT '',
  `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_payroll_items_payroll_user1_idx` (`payroll_user_id` ASC)  COMMENT '',
  CONSTRAINT `fk_payroll_items_payroll_user1`
    FOREIGN KEY (`payroll_user_id`)
    REFERENCES `payroll_user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
