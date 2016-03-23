SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ALLOW_INVALID_DATES';

CREATE TABLE IF NOT EXISTS `payroll_deductions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(128) NULL DEFAULT NULL COMMENT '',
  `amount` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '',
  `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `payroll_deductions_overrides` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `payroll_deduction_id` INT(11) NOT NULL COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `amount` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '',
  `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_payroll_deductions_overrides_payroll_deductions1_idx` (`payroll_deduction_id` ASC)  COMMENT '',
  INDEX `fk_payroll_deductions_overrides_users1_idx` (`user_id` ASC)  COMMENT '',
  CONSTRAINT `fk_payroll_deductions_overrides_payroll_deductions1`
    FOREIGN KEY (`payroll_deduction_id`)
    REFERENCES `payroll_deductions` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_payroll_deductions_overrides_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `payroll_additions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(128) NULL DEFAULT NULL COMMENT '',
  `amount` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '',
  `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `payroll_addition_overrides` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `payroll_addition_id` INT(11) NOT NULL COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `amount` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '',
  `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_payroll_deductions_overrides_users1_idx` (`user_id` ASC)  COMMENT '',
  INDEX `fk_payroll_addition_overrides_payroll_additions1_idx` (`payroll_addition_id` ASC)  COMMENT '',
  CONSTRAINT `fk_payroll_deductions_overrides_users10`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_payroll_addition_overrides_payroll_additions1`
    FOREIGN KEY (`payroll_addition_id`)
    REFERENCES `payroll_additions` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
