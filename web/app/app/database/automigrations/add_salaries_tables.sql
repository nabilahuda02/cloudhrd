SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ALLOW_INVALID_DATES';

CREATE TABLE IF NOT EXISTS `deductions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `name` VARCHAR(128) NULL DEFAULT NULL COMMENT '',
  `total` DECIMAL(10,2) NULL DEFAULT 0.00 COMMENT '',
  `effective_date` DATE NULL DEFAULT NULL COMMENT '',
  `duration` INT(11) NULL DEFAULT NULL COMMENT '',
  `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_deductions_users1_idx` (`user_id` ASC)  COMMENT '',
  CONSTRAINT `fk_deductions_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `benefits` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `name` VARCHAR(128) NULL DEFAULT NULL COMMENT '',
  `effective_date` DATE NULL DEFAULT NULL COMMENT '',
  `duration` INT(11) NULL DEFAULT 0 COMMENT '',
  `total` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '',
  `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_benefits_users1_idx` (`user_id` ASC)  COMMENT '',
  CONSTRAINT `fk_benefits_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `user_salaries` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `effective_date` DATE NULL DEFAULT NULL COMMENT '',
  `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `total` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_salaries_users1_idx` (`user_id` ASC)  COMMENT '',
  CONSTRAINT `fk_salaries_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `salaries` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `start_date` DATE NULL DEFAULT NULL COMMENT '',
  `end_date` DATE NULL DEFAULT NULL COMMENT '',
  `is_published` TINYINT(1) NULL DEFAULT 0 COMMENT '',
  `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `total` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_salaries_users2_idx` (`user_id` ASC)  COMMENT '',
  CONSTRAINT `fk_salaries_users2`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `salary_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `salary_id` INT(11) NOT NULL COMMENT '',
  `name` VARCHAR(128) NULL DEFAULT NULL COMMENT '',
  `reference_id` INT(11) NULL DEFAULT NULL COMMENT '',
  `subtotal` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '',
  `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_salary_items_salaries1_idx` (`salary_id` ASC)  COMMENT '',
  CONSTRAINT `fk_salary_items_salaries1`
    FOREIGN KEY (`salary_id`)
    REFERENCES `salaries` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;