ALTER TABLE `natalieandnic_com`.`guest` 
ADD COLUMN `additionalcomments` VARCHAR(200) NULL AFTER `plusonemeal`,
ADD COLUMN `email` VARCHAR(64) NULL AFTER `additionalcomments`,
ADD COLUMN `address_street` VARCHAR(64) NULL AFTER `email`,
ADD COLUMN `address_city` VARCHAR(64) NULL AFTER `address_street`,
ADD COLUMN `address_state` VARCHAR(64) NULL AFTER `address_city`,
ADD COLUMN `address_zip` VARCHAR(64) NULL AFTER `address_state`;
