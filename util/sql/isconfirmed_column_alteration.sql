-- WARNING: WILL DESTROY isconfirmed data
ALTER TABLE `natalieandnic_com`.`guest` 
DROP COLUMN `isconfirmed`;
ALTER TABLE `natalieandnic_com`.`guest` 
ADD COLUMN `isconfirmed` BIT(1) NOT NULL AFTER `isheadofhousehold`;
