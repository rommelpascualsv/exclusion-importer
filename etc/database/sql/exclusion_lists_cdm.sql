SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for exclusion_lists
-- ----------------------------
DROP TABLE IF EXISTS `exclusion_lists`;
CREATE TABLE `exclusion_lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `accr` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `import_url` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_auto_import` int(10) unsigned DEFAULT NULL,
  `is_active` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for files
-- ----------------------------
DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `state_prefix` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `img_data` longblob,
  `ready_for_update` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;