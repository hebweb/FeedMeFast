SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `matkon_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;
USE `matkon_db` ;

-- -----------------------------------------------------
-- Table `matkon_db`.`ingridiants`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `matkon_db`.`ingridiants` ;

CREATE  TABLE IF NOT EXISTS `matkon_db`.`ingridiants` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `matkon_db`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `matkon_db`.`users` ;

CREATE  TABLE IF NOT EXISTS `matkon_db`.`users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `admin` INT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`, `name`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `matkon_db`.`recipe`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `matkon_db`.`recipe` ;

CREATE  TABLE IF NOT EXISTS `matkon_db`.`recipe` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(256) NOT NULL ,
  `users_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`, `users_id`) ,
  CONSTRAINT `fk_recipe_users1`
    FOREIGN KEY (`users_id` )
    REFERENCES `matkon_db`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `fk_recipe_users1` ON `matkon_db`.`recipe` (`users_id` ASC) ;


-- -----------------------------------------------------
-- Table `matkon_db`.`steps`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `matkon_db`.`steps` ;

CREATE  TABLE IF NOT EXISTS `matkon_db`.`steps` (
  `id` INT UNSIGNED NOT NULL ,
  `text` TEXT NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_steps_recipe1`
    FOREIGN KEY (`id` )
    REFERENCES `matkon_db`.`recipe` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_steps_recipe1` ON `matkon_db`.`steps` (`id` ASC) ;


-- -----------------------------------------------------
-- Table `matkon_db`.`ingridiants_has_recipe`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `matkon_db`.`ingridiants_has_recipe` ;

CREATE  TABLE IF NOT EXISTS `matkon_db`.`ingridiants_has_recipe` (
  `ingridiants_id` INT UNSIGNED NOT NULL ,
  `recipe_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`ingridiants_id`, `recipe_id`) ,
  CONSTRAINT `fk_ingridiants_has_recipe_ingridiants`
    FOREIGN KEY (`ingridiants_id` )
    REFERENCES `matkon_db`.`ingridiants` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ingridiants_has_recipe_recipe1`
    FOREIGN KEY (`recipe_id` )
    REFERENCES `matkon_db`.`recipe` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_ingridiants_has_recipe_ingridiants` ON `matkon_db`.`ingridiants_has_recipe` (`ingridiants_id` ASC) ;

CREATE INDEX `fk_ingridiants_has_recipe_recipe1` ON `matkon_db`.`ingridiants_has_recipe` (`recipe_id` ASC) ;


-- -----------------------------------------------------
-- Table `matkon_db`.`ingridiant_details`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `matkon_db`.`ingridiant_details` ;

CREATE  TABLE IF NOT EXISTS `matkon_db`.`ingridiant_details` (
  `id` INT UNSIGNED NOT NULL ,
  `hot` TINYINT(2) NOT NULL DEFAULT 1 ,
  `cold` TINYINT(2) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_ingridiant_details_ingridiants1`
    FOREIGN KEY (`id` )
    REFERENCES `matkon_db`.`ingridiants` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_ingridiant_details_ingridiants1` ON `matkon_db`.`ingridiant_details` (`id` ASC) ;


-- -----------------------------------------------------
-- Table `matkon_db`.`ingridiant_manufactors`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `matkon_db`.`ingridiant_manufactors` ;

CREATE  TABLE IF NOT EXISTS `matkon_db`.`ingridiant_manufactors` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `ing_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_ingridiant_manufactors_ingridiants1`
    FOREIGN KEY (`ing_id` )
    REFERENCES `matkon_db`.`ingridiants` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_ingridiant_manufactors_ingridiants1` ON `matkon_db`.`ingridiant_manufactors` (`ing_id` ASC) ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
