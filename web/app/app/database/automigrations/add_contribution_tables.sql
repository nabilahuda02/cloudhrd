-- MySQL Workbench Synchronization
-- Generated: 2016-05-10 01:08
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Zulfa Juniadi bin Zulkifli

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE TABLE IF NOT EXISTS `epf_contributions` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `payroll_user_id` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `employee_contribution` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  `employer_contribution` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_epf_contributions_payroll_user1_idx` (`payroll_user_id` ASC),
  CONSTRAINT `fk_epf_contributions_payroll_user1`
    FOREIGN KEY (`payroll_user_id`)
    REFERENCES `payroll_user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `pcb_contributions` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `payroll_user_id` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `employee_contribution` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_epf_contributions_payroll_user1_idx` (`payroll_user_id` ASC),
  CONSTRAINT `fk_epf_contributions_payroll_user10`
    FOREIGN KEY (`payroll_user_id`)
    REFERENCES `payroll_user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `socso_contributions` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `payroll_user_id` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `employee_contribution` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  `employer_contribution` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_epf_contributions_payroll_user1_idx` (`payroll_user_id` ASC),
  CONSTRAINT `fk_epf_contributions_payroll_user11`
    FOREIGN KEY (`payroll_user_id`)
    REFERENCES `payroll_user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
