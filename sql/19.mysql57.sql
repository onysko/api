ALTER TABLE `material` CHANGE `Draft` `Draft` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `material` CHANGE `structure_id` `structure_id` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `material` CHANGE `Created` `Created` DATETIME NULL DEFAULT NULL;
ALTER TABLE `field` CHANGE `filtered` `filtered` INT(10) NOT NULL DEFAULT '0';
ALTER TABLE `field` CHANGE `Active` `Active` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `field` CHANGE `Type` `Type` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `field` CHANGE `local` `local` INT(10) NOT NULL DEFAULT '0';
ALTER TABLE `field` CHANGE `Created` `Created` DATETIME NULL;