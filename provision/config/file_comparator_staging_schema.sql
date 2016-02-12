CREATE SCHEMA IF NOT EXISTS `file_comparator_staging`;

USE `file_comparator_staging`;

DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`file_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`state_prefix`  varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`img_data`  longblob NULL DEFAULT NULL ,
`ready_for_update`  varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

DROP TABLE IF EXISTS `urls`;
CREATE TABLE `urls` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`state_prefix`  varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`url`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`dynamic`  varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;