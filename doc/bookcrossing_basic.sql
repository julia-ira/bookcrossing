SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `bookcrossing` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `bookcrossing` ;

-- -----------------------------------------------------
-- Table `bookcrossing`.`User`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bookcrossing`.`User` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `city` VARCHAR(45) NULL,
  `date_of_birth` DATE NULL,
  `description` LONGTEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bookcrossing`.`Book`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bookcrossing`.`Book` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `author` VARCHAR(45) NOT NULL,
  `year` YEAR NULL,
  `photo` VARCHAR(45) NULL,
  `condition` SET('new','old') NULL,
  `status` SET('free','busy') NOT NULL,
  `current_owner_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `current_owner_idx` (`current_owner_id` ASC),
  CONSTRAINT `current_owner`
    FOREIGN KEY (`current_owner_id`)
    REFERENCES `bookcrossing`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bookcrossing`.`Ownership`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bookcrossing`.`Ownership` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `book_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `start_date` DATE NULL,
  `end_date` DATE NULL,
  PRIMARY KEY (`id`),
  INDEX `owner_id_idx` (`user_id` ASC),
  INDEX `book_id_idx` (`book_id` ASC),
  CONSTRAINT `owner_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `bookcrossing`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `book_id`
    FOREIGN KEY (`book_id`)
    REFERENCES `bookcrossing`.`Book` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bookcrossing`.`Tags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bookcrossing`.`Tags` (
  `id` INT NOT NULL,
  `tag` VARCHAR(45) NOT NULL,
  `book_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `book_id_idx` (`book_id` ASC),
  CONSTRAINT `book_id`
    FOREIGN KEY (`book_id`)
    REFERENCES `bookcrossing`.`Book` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
