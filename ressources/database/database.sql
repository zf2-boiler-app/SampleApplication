SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `zf2base` DEFAULT CHARACTER SET latin1 ;
USE `zf2base` ;

-- -----------------------------------------------------
-- Table `zf2base`.`logs`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zf2base`.`logs` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `log_creation` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `log_request_uri` VARCHAR(250) NOT NULL ,
  `log_request_method` ENUM('GET','POST') NOT NULL ,
  `log_user_agent` TEXT NOT NULL ,
  `log_is_ajax` TINYINT(1) NOT NULL ,
  `log_user_id` INT(11) NULL DEFAULT NULL ,
  `log_route_name` VARCHAR(50) NULL DEFAULT NULL ,
  `log_controller_name` VARCHAR(250) NULL DEFAULT NULL ,
  `log_action_name` VARCHAR(50) NULL DEFAULT NULL ,
  `log_ending` TIMESTAMP NULL DEFAULT NULL ,
  PRIMARY KEY (`log_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zf2base`.`errors`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zf2base`.`errors` (
  `error_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `error_name` VARCHAR(250) NOT NULL ,
  `error_exception` TEXT NOT NULL ,
  `error_log_id` INT(11) NOT NULL ,
  PRIMARY KEY (`error_id`) ,
  INDEX `error_log_id` (`error_log_id` ASC) ,
  CONSTRAINT `logs_error_log_id`
    FOREIGN KEY (`error_log_id` )
    REFERENCES `zf2base`.`logs` (`log_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zf2base`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zf2base`.`users` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_email` VARCHAR(250) NOT NULL ,
  `user_password` VARCHAR(32) NOT NULL ,
  `user_registration_key` VARCHAR(13) NOT NULL ,
  `user_state` ENUM('PENDING','ACTIVE','DELETE') NOT NULL DEFAULT 'PENDING' ,
  `user_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `user_update` TIMESTAMP NULL DEFAULT NULL ,
  PRIMARY KEY (`user_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zf2base`.`users_logs`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zf2base`.`users_logs` (
  `user_log_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `user_email` VARCHAR(250) NOT NULL ,
  `user_password` VARCHAR(32) NOT NULL ,
  `user_state` ENUM('PENDING','ACTIVE','DELETE') NOT NULL DEFAULT 'PENDING' ,
  `user_registration_key` VARCHAR(13) NOT NULL ,
  `user_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `user_update` TIMESTAMP NULL DEFAULT NULL ,
  `entity_deleted` TINYINT(1) NOT NULL DEFAULT 0 ,
  `entity_log_id` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`user_log_id`) ,
  INDEX `entity_log_id` (`entity_log_id` ASC) ,
  CONSTRAINT `logs_users_log_id`
    FOREIGN KEY (`entity_log_id` )
    REFERENCES `zf2base`.`logs` (`log_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zf2base`.`users_providers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zf2base`.`users_providers` (
  `user_id` INT(11) NOT NULL ,
  `provider_id` VARCHAR(50) NOT NULL ,
  `provider_name` VARCHAR(255) NOT NULL ,
  `provider_state` ENUM('ACTIVE','DELETE') NOT NULL DEFAULT 'ACTIVE' ,
  `provider_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `provider_update` TIMESTAMP NULL DEFAULT NULL ,
  PRIMARY KEY (`provider_id`, `user_id`) ,
  UNIQUE INDEX `provider_id` (`provider_id` ASC, `provider_name` ASC) ,
  INDEX `user_id` (`user_id` ASC) ,
  CONSTRAINT `user_user_id`
    FOREIGN KEY (`user_id` )
    REFERENCES `zf2base`.`users` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zf2base`.`users_providers_logs`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zf2base`.`users_providers_logs` (
  `user_provider_log_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `provider_id` VARCHAR(50) NOT NULL ,
  `provider_name` VARCHAR(255) NOT NULL ,
  `provider_state` ENUM('ACTIVE','DELETE') NOT NULL DEFAULT 'ACTIVE' ,
  `provider_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `provider_update` TIMESTAMP NULL DEFAULT NULL ,
  `entity_deleted` TINYINT(1) NULL DEFAULT 0 ,
  `entity_log_id` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`user_provider_log_id`) ,
  INDEX `user_provider_id` (`user_id` ASC, `provider_id` ASC) ,
  INDEX `provider_id_provider_name` (`provider_id` ASC, `provider_name` ASC) ,
  INDEX `entity_log_id` (`entity_log_id` ASC) ,
  CONSTRAINT `logs_users_providers_log_id`
    FOREIGN KEY (`entity_log_id` )
    REFERENCES `zf2base`.`logs` (`log_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

USE `zf2base` ;
USE `zf2base`;

DELIMITER $$
USE `zf2base`$$


CREATE
DEFINER=`root`@`localhost`
TRIGGER `zf2base`.`insert_user_trigger`
AFTER INSERT ON `zf2base`.`users`
FOR EACH ROW
BEGIN
INSERT INTO users_logs(user_id,user_email,user_password,user_registration_key,user_state,user_create,user_update)
VALUES (NEW.user_id, NEW.user_email, NEW.user_password,NEW.user_registration_key, NEW.user_state, NEW.user_create, NEW.user_update);
END$$

USE `zf2base`$$


CREATE TRIGGER `delete_user_trigger` AFTER DELETE ON users FOR EACH ROW
-- Edit trigger body code below this line. Do not edit lines above this one
BEGIN
INSERT INTO users_logs(user_id,user_email,user_password,user_registration_key,user_state,user_create,user_update,entity_deleted)
VALUES (OLD.user_id, OLD.user_email, OLD.user_password, OLD.user_registration_key, OLD.user_state, OLD.user_create, OLD.user_update,1);
END
$$


DELIMITER ;

DELIMITER $$
USE `zf2base`$$
CREATE
DEFINER=`root`@`localhost`
TRIGGER `zf2base`.`insert_user_provider_trigger`
AFTER INSERT ON `zf2base`.`users_providers`
FOR EACH ROW
BEGIN
INSERT INTO users_providers_logs(user_id,provider_id,provider_name,provider_state,provider_create,provider_update)
VALUES (NEW.user_id, NEW.user_id, NEW.provider_id, NEW.provider_name, NEW.provider_state, NEW.provider_create, NEW.provider_update);
END$$

USE `zf2base`$$


CREATE TRIGGER `users_providers_ADEL` AFTER DELETE ON users_providers FOR EACH ROW
-- Edit trigger body code below this line. Do not edit lines above this one
BEGIN
INSERT INTO users_providers_logs(user_id,provider_id,provider_name,provider_state,provider_create,provider_update,entity_deleted)
VALUES (OLD.user_id, OLD.user_id, OLD.provider_id, OLD.provider_name, OLD.provider_state, OLD.provider_create, OLD.provider_update,1);
END
$$


DELIMITER ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
