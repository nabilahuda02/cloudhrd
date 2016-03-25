
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE TABLE IF NOT EXISTS `payroll_user_deductions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `duration` INT(11) NOT NULL DEFAULT 1,
  `balance_duration` INT(11) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_payroll_user_deductions_users1_idx` (`user_id` ASC),
  CONSTRAINT `fk_payroll_user_deductions_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `payroll_user_deduction_payments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `payroll_user_deduction_id` INT(11) NOT NULL,
  `payroll_user_id` INT(11) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_payroll_user_deduction_payments_payroll_user_deductions1_idx` (`payroll_user_deduction_id` ASC),
  INDEX `fk_payroll_user_deduction_payments_payroll_user1_idx` (`payroll_user_id` ASC),
  CONSTRAINT `fk_payroll_user_deduction_payments_payroll_user_deductions1`
    FOREIGN KEY (`payroll_user_deduction_id`)
    REFERENCES `payroll_user_deductions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_payroll_user_deduction_payments_payroll_user1`
    FOREIGN KEY (`payroll_user_id`)
    REFERENCES `payroll_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `payroll_user_additions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `duration` INT(11) NOT NULL DEFAULT 1,
  `balance_duration` INT(11) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_payroll_user_deductions_users1_idx` (`user_id` ASC),
  CONSTRAINT `fk_payroll_user_deductions_users10`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `payroll_user_addition_payments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `payroll_user_id` INT(11) NOT NULL,
  `payroll_user_addition_id` INT(11) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_payroll_user_deduction_payments_payroll_user1_idx` (`payroll_user_id` ASC),
  INDEX `fk_payroll_user_deduction_payments_copy1_payroll_user_addit_idx` (`payroll_user_addition_id` ASC),
  CONSTRAINT `fk_payroll_user_deduction_payments_payroll_user10`
    FOREIGN KEY (`payroll_user_id`)
    REFERENCES `payroll_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_payroll_user_deduction_payments_copy1_payroll_user_additio1`
    FOREIGN KEY (`payroll_user_addition_id`)
    REFERENCES `payroll_user_additions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
