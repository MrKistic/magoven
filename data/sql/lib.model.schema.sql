
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- apns_devices
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `apns_devices`;


CREATE TABLE `apns_devices`
(
	`pid` INTEGER(10)  NOT NULL AUTO_INCREMENT,
	`clientid` VARCHAR(64)  NOT NULL,
	`appname` VARCHAR(255)  NOT NULL,
	`appversion` VARCHAR(25),
	`deviceuid` CHAR(40)  NOT NULL,
	`devicetoken` CHAR(64)  NOT NULL,
	`devicename` VARCHAR(255)  NOT NULL,
	`devicemodel` VARCHAR(100)  NOT NULL,
	`deviceversion` VARCHAR(25)  NOT NULL,
	`pushbadge` VARCHAR(12) default '',
	`pushalert` VARCHAR(12) default '',
	`pushsound` VARCHAR(12) default '',
	`development` VARCHAR(12) default '',
	`status` VARCHAR(12) default '',
	`created` DATETIME,
	`modified` DATETIME,
	PRIMARY KEY (`pid`),
	UNIQUE KEY `appname` (`appname`, `deviceuid`),
	KEY `clientid`(`clientid`),
	KEY `devicetoken`(`devicetoken`),
	KEY `devicename`(`devicename`),
	KEY `devicemodel`(`devicemodel`),
	KEY `deviceversion`(`deviceversion`),
	KEY `pushbadge`(`pushbadge`),
	KEY `pushalert`(`pushalert`),
	KEY `pushsound`(`pushsound`),
	KEY `development`(`development`),
	KEY `status`(`status`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- apns_device_history
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `apns_device_history`;


CREATE TABLE `apns_device_history`
(
	`pid` INTEGER(10)  NOT NULL AUTO_INCREMENT,
	`clientid` VARCHAR(64)  NOT NULL,
	`appname` VARCHAR(255)  NOT NULL,
	`appversion` VARCHAR(25),
	`deviceuid` CHAR(40)  NOT NULL,
	`devicetoken` CHAR(64)  NOT NULL,
	`devicename` VARCHAR(255)  NOT NULL,
	`devicemodel` VARCHAR(100)  NOT NULL,
	`deviceversion` VARCHAR(25)  NOT NULL,
	`pushbadge` VARCHAR(12) default '',
	`pushalert` VARCHAR(12) default '',
	`pushsound` VARCHAR(12) default '',
	`development` VARCHAR(12) default '' NOT NULL,
	`status` VARCHAR(12) default '' NOT NULL,
	`archived` DATETIME  NOT NULL,
	`created` DATETIME,
	`modified` DATETIME,
	PRIMARY KEY (`pid`),
	KEY `clientid`(`clientid`),
	KEY `devicetoken`(`devicetoken`),
	KEY `devicename`(`devicename`),
	KEY `devicemodel`(`devicemodel`),
	KEY `deviceversion`(`deviceversion`),
	KEY `pushbadge`(`pushbadge`),
	KEY `pushalert`(`pushalert`),
	KEY `pushsound`(`pushsound`),
	KEY `development`(`development`),
	KEY `status`(`status`),
	KEY `appname`(`appname`),
	KEY `appversion`(`appversion`),
	KEY `deviceuid`(`deviceuid`),
	KEY `archived`(`archived`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- apns_messages
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `apns_messages`;


CREATE TABLE `apns_messages`
(
	`pid` INTEGER(10)  NOT NULL AUTO_INCREMENT,
	`clientid` VARCHAR(64)  NOT NULL,
	`fk_device` INTEGER(9)  NOT NULL,
	`message` VARCHAR(255)  NOT NULL,
	`delivery` DATETIME  NOT NULL,
	`status` VARCHAR(12) default '' NOT NULL,
	`created` DATETIME,
	`modified` DATETIME,
	PRIMARY KEY (`pid`),
	KEY `clientid`(`clientid`),
	KEY `fk_device`(`fk_device`),
	KEY `status`(`status`),
	KEY `message`(`message`),
	KEY `delivery`(`delivery`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- issue
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `issue`;


CREATE TABLE `issue`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	`publication_id` INTEGER(10)  NOT NULL,
	`name` VARCHAR(100)  NOT NULL,
	`free` TINYINT default 1,
	`product_id` VARCHAR(255),
	`title` VARCHAR(100)  NOT NULL,
	`info` VARCHAR(500)  NOT NULL,
	`date` DATE  NOT NULL,
	`published` TINYINT default 1,
	`cover` VARCHAR(1024),
	`url` VARCHAR(1024),
	`upload_type` VARCHAR(1) default '' NOT NULL,
	`uploaded_cover` VARCHAR(256),
	`uploaded_hpub` VARCHAR(256),
	`uploaded_zip` VARCHAR(256),
	`allow_notification` TINYINT default 0,
	`notified` TINYINT default 0,
	`itunes_summary` VARCHAR(1024),
	`itunes_coverart_url` VARCHAR(1024),
	`itunes_published` DATETIME(26),
	`itunes_updated` DATETIME(26),
	PRIMARY KEY (`id`),
	INDEX `issue_FI_1` (`publication_id`),
	CONSTRAINT `issue_FK_1`
		FOREIGN KEY (`publication_id`)
		REFERENCES `publication` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- publication
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `publication`;


CREATE TABLE `publication`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	`application_id` VARCHAR(255)  NOT NULL,
	`name` VARCHAR(100)  NOT NULL,
	`development_mode` TINYINT default 1,
	`subscription_behavior` VARCHAR(255),
	`issue_download_security` TINYINT default 0,
	`itunes_revalidation_duration` INTEGER default 12,
	`itunes_production_level` VARCHAR(255),
	`itunes_shared_secret` VARCHAR(255),
	`itunes_updated` DATETIME,
	`sandbox_pem` VARCHAR(256),
	`sandbox_password` VARCHAR(32),
	`production_pem` VARCHAR(256),
	`production_password` VARCHAR(32),
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- purchase
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `purchase`;


CREATE TABLE `purchase`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	`application_id` VARCHAR(255)  NOT NULL,
	`user_id` VARCHAR(255) default '' NOT NULL,
	`product_id` VARCHAR(255) default '' NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `purchase_app_user_product` (`application_id`, `user_id`, `product_id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- receipt
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `receipt`;


CREATE TABLE `receipt`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	`application_id` VARCHAR(255)  NOT NULL,
	`quantity` VARCHAR(10),
	`product_id` VARCHAR(255),
	`receipt_type` VARCHAR(30),
	`transaction_id` VARCHAR(250) default '' NOT NULL,
	`user_id` VARCHAR(255) default '' NOT NULL,
	`purchase_date` VARCHAR(50),
	`original_transaction_id` VARCHAR(100),
	`original_purchase_date` VARCHAR(50),
	`app_item_id` VARCHAR(150),
	`version_external_identifier` VARCHAR(50),
	`bid` VARCHAR(50),
	`bvrs` VARCHAR(50),
	`base64_receipt` TEXT,
	PRIMARY KEY (`id`),
	UNIQUE KEY `receipt_app_user_transaction` (`application_id`, `user_id`, `transaction_id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- subscription
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `subscription`;


CREATE TABLE `subscription`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	`application_id` VARCHAR(255)  NOT NULL,
	`user_id` VARCHAR(255)  NOT NULL,
	`effective_date` DATETIME  NOT NULL,
	`expiration_date` DATETIME  NOT NULL,
	`last_validated` DATETIME  NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `subscription_app_user` (`application_id`, `user_id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- system_log
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `system_log`;


CREATE TABLE `system_log`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	`log_type` VARCHAR(25),
	`message` TEXT,
	`application_id` VARCHAR(255),
	`user_id` VARCHAR(255),
	PRIMARY KEY (`id`)
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
