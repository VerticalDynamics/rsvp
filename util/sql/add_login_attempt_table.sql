CREATE TABLE `natalieandnic_com`.`login_attempt` (
  `login_attempt_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_ip_address` VARCHAR(45) NULL,
  `guestpassword` INT(11) NULL,
  `login_attempt_date` DATETIME NULL,
  PRIMARY KEY (`login_attempt_id`));