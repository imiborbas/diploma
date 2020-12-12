
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- page
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `page`;


CREATE TABLE `page`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(128)  NOT NULL,
	`content` TEXT,
	`created_at` DATETIME,
	PRIMARY KEY (`id`)
)ENGINE=InnoDB;

#-----------------------------------------------------------------------------
#-- user
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;


CREATE TABLE `user`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(128)  NOT NULL,
	`password` VARCHAR(128)  NOT NULL,
	`salt` VARCHAR(128)  NOT NULL,
	`is_active` TINYINT,
	`last_login` DATETIME,
	`created_at` DATETIME,
	PRIMARY KEY (`id`)
)ENGINE=InnoDB;

#-----------------------------------------------------------------------------
#-- credential
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `credential`;


CREATE TABLE `credential`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(32)  NOT NULL,
	PRIMARY KEY (`id`)
)ENGINE=InnoDB;

#-----------------------------------------------------------------------------
#-- user_credential
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `user_credential`;


CREATE TABLE `user_credential`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`user_id` INTEGER,
	`credential_id` INTEGER,
	PRIMARY KEY (`id`),
	INDEX `user_credential_FI_1` (`user_id`),
	CONSTRAINT `user_credential_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `user` (`id`)
		ON DELETE CASCADE,
	INDEX `user_credential_FI_2` (`credential_id`),
	CONSTRAINT `user_credential_FK_2`
		FOREIGN KEY (`credential_id`)
		REFERENCES `credential` (`id`)
		ON DELETE CASCADE
)ENGINE=InnoDB;

#-----------------------------------------------------------------------------
#-- resource_credential
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `resource_credential`;


CREATE TABLE `resource_credential`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`resource_class` VARCHAR(128)  NOT NULL,
	`resource_id` INTEGER  NOT NULL,
	`credential_id` INTEGER,
	PRIMARY KEY (`id`),
	INDEX `resource_credential_FI_1` (`credential_id`),
	CONSTRAINT `resource_credential_FK_1`
		FOREIGN KEY (`credential_id`)
		REFERENCES `credential` (`id`)
		ON DELETE CASCADE
)ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
