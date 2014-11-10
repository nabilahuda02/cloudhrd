SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE TABLE IF NOT EXISTS `knowledge_bases` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `user_unit_id` INT(11) NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `article` MEDIUMTEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_knowledge_bases_users1_idx` (`user_id` ASC),
  INDEX `fk_knowledge_bases_user_units1_idx` (`user_unit_id` ASC),
  CONSTRAINT `fk_knowledge_bases_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_knowledge_bases_user_units1`
    FOREIGN KEY (`user_unit_id`)
    REFERENCES `user_units` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `knowledge_base_tags` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `knowledge_bases_knowledge_base_tags` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `knowledge_base_id` INT(10) UNSIGNED NOT NULL,
  `knowledge_base_tag_id` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_knowledge_bases_knowledge_base_tags_knowledge_base_tags1_idx` (`knowledge_base_tag_id` ASC),
  INDEX `fk_knowledge_bases_knowledge_base_tags_knowledge_bases1_idx` (`knowledge_base_id` ASC),
  CONSTRAINT `fk_knowledge_bases_knowledge_base_tags_knowledge_bases1`
    FOREIGN KEY (`knowledge_base_id`)
    REFERENCES `knowledge_bases` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_knowledge_bases_knowledge_base_tags_knowledge_base_tags1`
    FOREIGN KEY (`knowledge_base_tag_id`)
    REFERENCES `knowledge_base_tags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
