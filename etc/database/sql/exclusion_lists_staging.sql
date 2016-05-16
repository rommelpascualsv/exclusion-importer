SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ak1_records
-- ----------------------------
DROP TABLE IF EXISTS `ak1_records`;
CREATE TABLE `ak1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `exclusion_date` date DEFAULT NULL,
  `last_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `middle_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `exclusion_authority` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `exclusion_reason` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for al1_records
-- ----------------------------
DROP TABLE IF EXISTS `al1_records`;
CREATE TABLE `al1_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_of_provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `suspension_effective_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `suspension_initiated_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `aka_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `provider` (`name_of_provider`) USING BTREE,
  KEY `date_created` (`date_created`) USING BTREE,
  KEY `hash` (`hash`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for ar1_records
-- ----------------------------
DROP TABLE IF EXISTS `ar1_records`;
CREATE TABLE `ar1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Division` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `FacilityName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ProviderName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `City` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `State` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for az1_records
-- ----------------------------
DROP TABLE IF EXISTS `az1_records`;
CREATE TABLE `az1_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `middle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name_company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `term_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `specialty` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`) USING BTREE,
  KEY `first_name` (`first_name`) USING BTREE,
  KEY `last_name_company_name` (`last_name_company_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for ca1_records
-- ----------------------------
DROP TABLE IF EXISTS `ca1_records`;
CREATE TABLE `ca1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `last_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `middle_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `aka_dba` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `business` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `addresses` text COLLATE utf8_unicode_ci NOT NULL,
  `provider_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `license_numbers` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `provider_numbers` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_of_suspension` date DEFAULT NULL,
  `active_period` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) DEFAULT NULL,
  `new_hash` binary(16) DEFAULT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `first_middle` (`first_name`,`middle_name`),
  FULLTEXT KEY `last_name` (`last_name`),
  FULLTEXT KEY `business` (`business`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for csl_records
-- ----------------------------
DROP TABLE IF EXISTS `csl_records`;
CREATE TABLE `csl_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `programs` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `addresses` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `federal_register_notice` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `standard_order` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `license_requirement` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `license_policy` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `call_sign` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vessel_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gross_tonnage` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gross_registered_tonnage` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vessel_flag` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vessel_owner` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remarks` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_list_url` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alt_names` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `citizenships` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dates_of_birth` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nationalities` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `places_of_birth` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_information_url` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` longblob,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`(50)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for ct1_records
-- ----------------------------
DROP TABLE IF EXISTS `ct1_records`;
CREATE TABLE `ct1_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `business` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `specialty` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `effective_date` date NOT NULL,
  `period` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` longblob NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`(50)) USING BTREE,
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for dc1_records
-- ----------------------------
DROP TABLE IF EXISTS `dc1_records`;
CREATE TABLE `dc1_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `middle_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `principals` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termination_date` date DEFAULT NULL,
  `hash` binary(16) DEFAULT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for epls_records
-- ----------------------------
DROP TABLE IF EXISTS `epls_records`;
CREATE TABLE `epls_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Prefix` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `First` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Middle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Last` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Suffix` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Classification` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Exclusion_Type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Address_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `City` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `State` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Province` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `DUNS` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `CT_Code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Agency` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Action_Date` date NOT NULL,
  `Termination_Date` date NOT NULL,
  `Action_Status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `Name` (`Name`) USING BTREE,
  KEY `Middle` (`Middle`) USING BTREE,
  KEY `Last` (`Last`) USING BTREE,
  KEY `First` (`First`) USING BTREE,
  KEY `hash` (`hash`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

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
-- Table structure for fdac_records
-- ----------------------------
DROP TABLE IF EXISTS `fdac_records`;
CREATE TABLE `fdac_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `center` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_of_status` date DEFAULT NULL,
  `date_of_nidpoe_issued` date DEFAULT NULL,
  `date_of_nooh_issued` date DEFAULT NULL,
  `hash` binary(16) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for fdadl_records
-- ----------------------------
DROP TABLE IF EXISTS `fdadl_records`;
CREATE TABLE `fdadl_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aka` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `term_of_debarment` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `hash` longblob,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for fehbp_records
-- ----------------------------
DROP TABLE IF EXISTS `fehbp_records`;
CREATE TABLE `fehbp_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ssn` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ssn_last_four` char(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tin` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `npi` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `street_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `debar_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `suspend_date` date DEFAULT NULL,
  `debar_date` date DEFAULT NULL,
  `hash` longblob,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
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

-- ----------------------------
-- Table structure for fl2_records
-- ----------------------------
DROP TABLE IF EXISTS `fl2_records`;
CREATE TABLE `fl2_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `medicaid_provider_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `license_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_rendered` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sanction_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `violation_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fine_amount` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sanction_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ahca_case_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `formal_informal_case_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `document_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` longblob NOT NULL,
  `new_hash` longblob,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `date_created` (`date_created`),
  KEY `hash` (`hash`(50)) USING BTREE,
  KEY `provider` (`provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for ga1_records
-- ----------------------------
DROP TABLE IF EXISTS `ga1_records`;
CREATE TABLE `ga1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `last_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `general` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sanction_date` date DEFAULT NULL,
  `hash` binary(16) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for healthmil_records
-- ----------------------------
DROP TABLE IF EXISTS `healthmil_records`;
CREATE TABLE `healthmil_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_excluded` date DEFAULT NULL,
  `term` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `exclusion_end_date` date DEFAULT NULL,
  `companies` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `middle_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `addresses` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `summary` text COLLATE utf8_unicode_ci,
  `hash` binary(16) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for hi1_records
-- ----------------------------
DROP TABLE IF EXISTS `hi1_records`;
CREATE TABLE `hi1_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `last_name_or_business` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `middle_initial` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `medicaid_provide_id_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_known_program_or_provider_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `exclusion_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reinstatement_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `last_name_or_business` (`last_name_or_business`) USING BTREE,
  KEY `first_name` (`first_name`) USING BTREE,
  KEY `middle_initial` (`middle_initial`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for ia1_records
-- ----------------------------
DROP TABLE IF EXISTS `ia1_records`;
CREATE TABLE `ia1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sanction_start_date` date DEFAULT NULL,
  `npi` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `individual_last_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `individual_first_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sanction` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sanction_end_date` date DEFAULT NULL,
  `hash` binary(16) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for id1_records
-- ----------------------------
DROP TABLE IF EXISTS `id1_records`;
CREATE TABLE `id1_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `exclusion_start_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_eligible_for_reinstatement` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_reinstated` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `additional_information` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for il1_records
-- ----------------------------
DROP TABLE IF EXISTS `il1_records`;
CREATE TABLE `il1_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `il1_id` int(10) unsigned NOT NULL,
  `ProvName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `LIC` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `AFFILIATION` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ACTION_DT` date NOT NULL,
  `ACTION_TYPE` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ADDRESS` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ADDRESS2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `CITY` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `STATE` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `ZIP_CODE` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `NEW_ADDITION` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`) USING BTREE,
  KEY `ProvName` (`ProvName`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for ks1_records
-- ----------------------------
DROP TABLE IF EXISTS `ks1_records`;
CREATE TABLE `ks1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `termination_date` date DEFAULT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `d_b_a` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kmap_provider_number` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kmap_provider_number_2` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `npi` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` binary(16) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for ky1_records
-- ----------------------------
DROP TABLE IF EXISTS `ky1_records`;
CREATE TABLE `ky1_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name_or_practice` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `license` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `effective_date` date DEFAULT NULL,
  `reason_for_term` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timeframe_of_term` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` longblob NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for la1_records
-- ----------------------------
DROP TABLE IF EXISTS `la1_records`;
CREATE TABLE `la1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_or_entity_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthdate` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `affiliated_entity` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title_or_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `exclusion_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period_of_exclusion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `reinstate_date` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state_zip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` binary(16) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for ma1_records
-- ----------------------------
DROP TABLE IF EXISTS `ma1_records`;
CREATE TABLE `ma1_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `provider_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi` int(10) unsigned DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `effective_date` date DEFAULT NULL,
  `hash` binary(16) DEFAULT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `npi` (`npi`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for md1_records
-- ----------------------------
DROP TABLE IF EXISTS `md1_records`;
CREATE TABLE `md1_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `specialty` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sanction_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sanction_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city_state_zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for me1_records
-- ----------------------------
DROP TABLE IF EXISTS `me1_records`;
CREATE TABLE `me1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entity` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `middle_initial` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prov_type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `case_status` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sanction_start_date` date DEFAULT NULL,
  `aka_list` text COLLATE utf8_unicode_ci,
  `hash` binary(16) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for mi1_records
-- ----------------------------
DROP TABLE IF EXISTS `mi1_records`;
CREATE TABLE `mi1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entity_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `license_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sanction_date_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sanction_source_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sanction_date_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sanction_source_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for mn1_records
-- ----------------------------
DROP TABLE IF EXISTS `mn1_records`;
CREATE TABLE `mn1_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `provider_type_description` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `sort_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `middle_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `effective_date_of_exclusion` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `address_line1` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `address_line2` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sort_name` (`sort_name`) USING BTREE,
  KEY `last_name` (`last_name`) USING BTREE,
  KEY `middle_name` (`middle_name`) USING BTREE,
  KEY `hash` (`hash`) USING BTREE,
  KEY `date_created` (`date_created`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for mo1_records
-- ----------------------------
DROP TABLE IF EXISTS `mo1_records`;
CREATE TABLE `mo1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `termination_date` date DEFAULT NULL,
  `letter_date` date DEFAULT NULL,
  `provider_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `ssn` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_type` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `license_number` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `termination_reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for ms1_records
-- ----------------------------
DROP TABLE IF EXISTS `ms1_records`;
CREATE TABLE `ms1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `last_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `middle_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dba` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `specialty` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `exclusion_from_date` date DEFAULT NULL,
  `exclusion_to_date` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `npi` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termination_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sanction_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for mt1_records
-- ----------------------------
DROP TABLE IF EXISTS `mt1_records`;
CREATE TABLE `mt1_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `exclusion_termination_date` date DEFAULT NULL,
  `exclusion_termination_agency` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` binary(16) DEFAULT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for nc1_records
-- ----------------------------
DROP TABLE IF EXISTS `nc1_records`;
CREATE TABLE `nc1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `npi` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `health_plan` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_excluded` date DEFAULT NULL,
  `exclusion_reason` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` binary(16) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for nd1_records
-- ----------------------------
DROP TABLE IF EXISTS `nd1_records`;
CREATE TABLE `nd1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `provider_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_verification` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_name_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `medicaid_provider_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `medicaid_provider_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `medicaid_provider_number_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `npi` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `exclusion_date` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `exclusion_reason` text COLLATE utf8_unicode_ci,
  `exclusion_reason_2` text COLLATE utf8_unicode_ci,
  `hash` binary(16) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for ne1_records
-- ----------------------------
DROP TABLE IF EXISTS `ne1_records`;
CREATE TABLE `ne1_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `provider_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `termination_or_suspension` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `effective_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `end_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reason_for_action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` longblob NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `provider_name` (`provider_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for njcdr_records
-- ----------------------------
DROP TABLE IF EXISTS `njcdr_records`;
CREATE TABLE `njcdr_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `firm_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `firm_street` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `firm_city` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `firm_state` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `firm_zip` char(5) COLLATE utf8_unicode_ci NOT NULL,
  `firm_plus4` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `provider_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `state` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `zip` char(5) COLLATE utf8_unicode_ci NOT NULL,
  `plus4` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `reason` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `debarring_dept` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `debarring_agency` char(4) COLLATE utf8_unicode_ci NOT NULL,
  `effective_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `permanent_debarment` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) NOT NULL,
  `new_hash` binary(16) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`) USING BTREE,
  KEY `firm_name` (`firm_name`) USING BTREE,
  KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for nv1_records
-- ----------------------------
DROP TABLE IF EXISTS `nv1_records`;
CREATE TABLE `nv1_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `doing_business_as` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `legal_entity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ownership_of_at_least_5_percent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `medicaid_provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `termination_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sanction_tier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sanction_period` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sanction_period_end_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reinstatement_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `doing_business_as` (`doing_business_as`) USING BTREE,
  KEY `legal_entity` (`legal_entity`) USING BTREE,
  KEY `ownership_of_at_least_5_percent` (`ownership_of_at_least_5_percent`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for nyomig_records
-- ----------------------------
DROP TABLE IF EXISTS `nyomig_records`;
CREATE TABLE `nyomig_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provtype` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_number_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `county` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `zip` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `action_date` date DEFAULT NULL,
  `action_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `profession_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` longblob NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `business` (`business`),
  KEY `hash` (`hash`(50)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for oh1_records
-- ----------------------------
DROP TABLE IF EXISTS `oh1_records`;
CREATE TABLE `oh1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `last_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `organization_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `npi` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `provider_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `zip_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `provider_id` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `action_date` date DEFAULT NULL,
  `date_added` date DEFAULT NULL,
  `provider_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_revised` date DEFAULT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for oig_records
-- ----------------------------
DROP TABLE IF EXISTS `oig_records`;
CREATE TABLE `oig_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lastname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `midname` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `busname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `general` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `specialty` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `upin` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `npi` int(10) unsigned DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `state` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `zip` char(5) COLLATE utf8_unicode_ci NOT NULL,
  `excltype` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `excldate` date DEFAULT NULL,
  `reindate` date DEFAULT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lastname` (`lastname`) USING BTREE,
  KEY `upin` (`upin`) USING BTREE,
  KEY `hash` (`hash`) USING BTREE,
  KEY `firstname` (`firstname`) USING BTREE,
  KEY `midname` (`midname`) USING BTREE,
  KEY `busname` (`busname`) USING BTREE,
  KEY `dob` (`dob`) USING BTREE,
  KEY `date_created` (`date_created`) USING BTREE,
  KEY `excldate` (`excldate`) USING BTREE,
  KEY `npi` (`npi`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for pa1_records
-- ----------------------------
DROP TABLE IF EXISTS `pa1_records`;
CREATE TABLE `pa1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ProviderName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `LicenseNumber` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `BeginDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `CAO` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ListDate` date DEFAULT NULL,
  `IND_DELTD` int(11) NOT NULL,
  `TXT_REASON_DELTD` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `DTE_DELTD` date DEFAULT NULL,
  `IND_CHGD` int(11) NOT NULL,
  `DTE_CHANGE_LAST` date DEFAULT NULL,
  `NAM_LAST_PROVR` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `NAM_FIRST_PROVR` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `NAM_MIDDLE_PROVR` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `NAM_TITLE_PROVR` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `NAM_SUFFIX_PROVR` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `NAM_PROVR_ALT` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `NAM_BUSNS_MP` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `IDN_NPI` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `TXT_CMT` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`) USING BTREE,
  KEY `ProviderName` (`ProviderName`) USING BTREE,
  KEY `NAM_LAST_PROVR` (`NAM_LAST_PROVR`) USING BTREE,
  KEY `NAM_FIRST_PROVR` (`NAM_FIRST_PROVR`) USING BTREE,
  KEY `NAM_MIDDLE_PROVR` (`NAM_MIDDLE_PROVR`) USING BTREE,
  KEY `NAM_BUSNS_MP` (`NAM_BUSNS_MP`) USING BTREE,
  KEY `NAM_PROVR_ALT` (`NAM_PROVR_ALT`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for phs_records
-- ----------------------------
DROP TABLE IF EXISTS `phs_records`;
CREATE TABLE `phs_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `middle_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `debarment_until` date DEFAULT NULL,
  `no_phs_advisory_until` date DEFAULT NULL,
  `certification_of_work_until` date DEFAULT NULL,
  `supervision_until` date DEFAULT NULL,
  `memo` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` binary(16) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sam_records
-- ----------------------------
DROP TABLE IF EXISTS `sam_records`;
CREATE TABLE `sam_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Classification` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Prefix` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `First` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Middle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Last` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Suffix` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Address_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Address_3` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Address_4` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `City` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `State` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `DUNS` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Exclusion_Program` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Excluding_Agency` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `CT_Code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Exclusion_Type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Additional_Comments` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Active_Date` date DEFAULT NULL,
  `Termination_Date` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `Record_Status` bit(1) NOT NULL DEFAULT b'1',
  `Cross_Reference` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `SAM_Number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `matching_OIG_hash` binary(16) NOT NULL,
  `hash` binary(16) NOT NULL,
  `new_hash` binary(16) DEFAULT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `Name` (`Name`) USING BTREE,
  KEY `First` (`First`) USING BTREE,
  KEY `Middle` (`Middle`) USING BTREE,
  KEY `Last` (`Last`) USING BTREE,
  KEY `Active_Date` (`Active_Date`) USING BTREE,
  KEY `Excluding_Agency` (`Excluding_Agency`) USING BTREE,
  KEY `date_created` (`date_created`) USING BTREE,
  KEY `City` (`City`) USING BTREE,
  KEY `State` (`State`) USING BTREE,
  KEY `Country` (`Country`) USING BTREE,
  KEY `Zip` (`Zip`) USING BTREE,
  KEY `hash` (`hash`) USING BTREE,
  KEY `new_hash` (`new_hash`) USING BTREE,
  KEY `matching_oig_hash` (`matching_OIG_hash`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sc1_records
-- ----------------------------
DROP TABLE IF EXISTS `sc1_records`;
CREATE TABLE `sc1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `zip` char(5) COLLATE utf8_unicode_ci NOT NULL,
  `provider_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_excluded` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`) USING BTREE,
  KEY `entity` (`entity`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for sdn_address_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_address_list`;
CREATE TABLE `sdn_address_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sdn_entry_id` int(10) unsigned DEFAULT NULL,
  `uid` int(10) unsigned DEFAULT NULL,
  `address1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address3` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stateOrProvince` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postalCode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`) USING BTREE,
  KEY `sdn_entry_id` (`sdn_entry_id`,`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sdn_aka_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_aka_list`;
CREATE TABLE `sdn_aka_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sdn_entry_id` int(10) unsigned DEFAULT NULL,
  `uid` int(10) unsigned DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `firstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`) USING BTREE,
  KEY `sdn_entry_id` (`sdn_entry_id`) USING BTREE,
  KEY `firstName` (`firstName`) USING BTREE,
  KEY `lastName` (`lastName`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for sdn_citizenship_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_citizenship_list`;
CREATE TABLE `sdn_citizenship_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sdn_entry_id` int(10) unsigned DEFAULT NULL,
  `uid` int(10) unsigned DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mainEntry` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`) USING BTREE,
  KEY `sdn_entry_id` (`sdn_entry_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sdn_date_of_birth_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_date_of_birth_list`;
CREATE TABLE `sdn_date_of_birth_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sdn_entry_id` int(10) unsigned DEFAULT NULL,
  `uid` int(10) unsigned DEFAULT NULL,
  `dateOfBirth` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mainEntry` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`) USING BTREE,
  KEY `sdn_entry_id` (`sdn_entry_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sdn_entries
-- ----------------------------
DROP TABLE IF EXISTS `sdn_entries`;
CREATE TABLE `sdn_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned DEFAULT NULL,
  `firstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sdnType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`) USING BTREE,
  KEY `firstName` (`firstName`) USING BTREE,
  KEY `lastName` (`lastName`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for sdn_id_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_id_list`;
CREATE TABLE `sdn_id_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sdn_entry_id` int(10) unsigned DEFAULT NULL,
  `uid` int(10) unsigned DEFAULT NULL,
  `idType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `idNumber` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `idCountry` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `issueDate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expirationDate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`) USING BTREE,
  KEY `sdn_entry_id` (`sdn_entry_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sdn_import_log
-- ----------------------------
DROP TABLE IF EXISTS `sdn_import_log`;
CREATE TABLE `sdn_import_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `publish_date` date DEFAULT NULL,
  `record_count` int(10) unsigned NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sdn_nationality_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_nationality_list`;
CREATE TABLE `sdn_nationality_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sdn_entry_id` int(10) unsigned DEFAULT NULL,
  `uid` int(10) unsigned DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mainEntry` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`) USING BTREE,
  KEY `sdn_entry_id` (`sdn_entry_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sdn_place_of_birth_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_place_of_birth_list`;
CREATE TABLE `sdn_place_of_birth_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sdn_entry_id` int(10) unsigned DEFAULT NULL,
  `uid` int(10) unsigned DEFAULT NULL,
  `placeOfBirth` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mainEntry` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`) USING BTREE,
  KEY `sdn_entry_id` (`sdn_entry_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sdn_program_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_program_list`;
CREATE TABLE `sdn_program_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sdn_entry_id` int(10) unsigned DEFAULT NULL,
  `program` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `program` (`sdn_entry_id`,`program`) USING BTREE,
  KEY `sdn_entry_id` (`sdn_entry_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for sdn_vessel_info
-- ----------------------------
DROP TABLE IF EXISTS `sdn_vessel_info`;
CREATE TABLE `sdn_vessel_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sdn_entry_id` int(10) unsigned DEFAULT NULL,
  `callSign` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vesselType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vesselFlag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vesselOwner` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tonnage` int(11) DEFAULT NULL,
  `grossRegisteredTonnage` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vessel` (`sdn_entry_id`,`callSign`) USING BTREE,
  KEY `sdn_entry_id` (`sdn_entry_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for tn1_records
-- ----------------------------
DROP TABLE IF EXISTS `tn1_records`;
CREATE TABLE `tn1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `last_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `begin_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`) USING BTREE,
  KEY `last` (`last_name`) USING BTREE,
  KEY `first` (`first_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for tx1_records
-- ----------------------------
DROP TABLE IF EXISTS `tx1_records`;
CREATE TABLE `tx1_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mid_initial` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `occupation` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `license_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `add_date` date DEFAULT NULL,
  `reinstated_date` date DEFAULT NULL,
  `web_comments` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` longblob NOT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `CompanyName` (`company_name`),
  KEY `FirstName` (`first_name`),
  KEY `LastName` (`last_name`),
  KEY `MidInitial` (`mid_initial`),
  KEY `hash` (`hash`(50)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for unsancentities_records
-- ----------------------------
DROP TABLE IF EXISTS `unsancentities_records`;
CREATE TABLE `unsancentities_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataid` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `un_list_type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference_number` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `listed_on` date DEFAULT NULL,
  `submitted_on` date DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  `entity_alias` text COLLATE utf8_unicode_ci,
  `entity_address` text COLLATE utf8_unicode_ci,
  `last_updated` date DEFAULT NULL,
  `hash` binary(16) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for unsancindividuals_records
-- ----------------------------
DROP TABLE IF EXISTS `unsancindividuals_records`;
CREATE TABLE `unsancindividuals_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataid` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `second_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `third_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fourth_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `un_list_type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference_number` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `listed_on` date DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nationality2` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `designation` text COLLATE utf8_unicode_ci,
  `last_updated` date DEFAULT NULL,
  `alias` text COLLATE utf8_unicode_ci,
  `address` text COLLATE utf8_unicode_ci,
  `date_of_birth` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `place_of_birth` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  `hash` binary(16) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for usdocdp_records
-- ----------------------------
DROP TABLE IF EXISTS `usdocdp_records`;
CREATE TABLE `usdocdp_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street_address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postal_code` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `last_update` date DEFAULT NULL,
  `hash` longblob,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for usdosd_records
-- ----------------------------
DROP TABLE IF EXISTS `usdosd_records`;
CREATE TABLE `usdosd_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aka_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notice` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notice_date` date DEFAULT NULL,
  `hash` binary(16) DEFAULT NULL,
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for wa1_records
-- ----------------------------
DROP TABLE IF EXISTS `wa1_records`;
CREATE TABLE `wa1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `last_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `middle_etc` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_license` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termination_date` date DEFAULT NULL,
  `termination_reason` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for wv2_records
-- ----------------------------
DROP TABLE IF EXISTS `wv2_records`;
CREATE TABLE `wv2_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `npi_number` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `middle_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `generation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credentials` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `exclusion_date` date DEFAULT NULL,
  `reason_for_exclusion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reinstatement_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reinstatement_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` binary(16) DEFAULT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for wy1_records
-- ----------------------------
DROP TABLE IF EXISTS `wy1_records`;
CREATE TABLE `wy1_records` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `exclusion_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `npi` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` binary(16) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `first_name` (`first_name`) USING BTREE,
  KEY `last_name` (`last_name`) USING BTREE,
  KEY `business_name` (`business_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- ----------------------------
-- View structure for exclusion_search_table_view
-- ----------------------------
DROP VIEW IF EXISTS `exclusion_search_table_view`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `exclusion_search_table_view` AS (select 1 AS `exclusion_list_id`,`oig_records`.`id` AS `exclusion_list_record_id`,`oig_records`.`firstname` AS `first_name`,`oig_records`.`midname` AS `middle_name`,`oig_records`.`lastname` AS `last_name`,`oig_records`.`busname` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`oig_records`.`npi` AS `npi`,`oig_records`.`upin` AS `upin`,`oig_records`.`dob` AS `date_of_birth` from `oig_records`) union (select 2 AS `exclusion_list_id`,`nyomig_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`nyomig_records`.`business` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`nyomig_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `nyomig_records`) union (select 3 AS `exclusion_list_id`,`njcdr_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`njcdr_records`.`name` AS `entity1`,`njcdr_records`.`firm_name` AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`njcdr_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `njcdr_records`) union (select 4 AS `exclusion_list_id`,`pa1_records`.`id` AS `exclusion_list_record_id`,`pa1_records`.`NAM_FIRST_PROVR` AS `first_name`,`pa1_records`.`NAM_MIDDLE_PROVR` AS `middle_name`,`pa1_records`.`NAM_LAST_PROVR` AS `last_name`,`pa1_records`.`NAM_BUSNS_MP` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`pa1_records`.`IDN_NPI` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `pa1_records`) union (select 5 AS `exclusion_list_id`,`oh1_records`.`id` AS `exclusion_list_record_id`,`oh1_records`.`first_name` AS `first_name`,NULL AS `middle_name`,`oh1_records`.`last_name` AS `last_name`,`oh1_records`.`organization_name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`oh1_records`.`npi` AS `npi`,NULL AS `upin`,`oh1_records`.`date_of_birth` AS `date_of_birth` from `oh1_records`) union (select 6 AS `exclusion_list_id`,`il1_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`il1_records`.`ProvName` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `il1_records`) union (select 7 AS `exclusion_list_id`,`ky1_records`.`id` AS `exclusion_list_record_id`,`ky1_records`.`first_name` AS `first_name`,NULL AS `middle_name`,`ky1_records`.`last_name_or_practice` AS `last_name`,`ky1_records`.`last_name_or_practice` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`ky1_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `ky1_records`) union (select 8 AS `exclusion_list_id`,`ma1_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`ma1_records`.`provider_name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`ma1_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `ma1_records`) union (select 9 AS `exclusion_list_id`,`sc1_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`sc1_records`.`entity` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`sc1_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `sc1_records`) union (select 10 AS `exclusion_list_id`,`tx1_records`.`id` AS `exclusion_list_record_id`,`tx1_records`.`first_name` AS `first_name`,`tx1_records`.`mid_initial` AS `middle_name`,`tx1_records`.`last_name` AS `last_name`,`tx1_records`.`company_name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`tx1_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `tx1_records`) union (select 11 AS `exclusion_list_id`,`sam_records`.`id` AS `exclusion_list_record_id`,`sam_records`.`First` AS `first_name`,`sam_records`.`Middle` AS `middle_name`,`sam_records`.`Last` AS `last_name`,`sam_records`.`Name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `sam_records`) union (select 12 AS `exclusion_list_id`,`ct1_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`ct1_records`.`business` AS `entity1`,`ct1_records`.`name` AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `ct1_records`) union (select 13 AS `exclusion_list_id`,`ar1_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`ar1_records`.`FacilityName` AS `entity1`,`ar1_records`.`ProviderName` AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `ar1_records`) union (select 14 AS `exclusion_list_id`,`fl2_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`fl2_records`.`provider` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`fl2_records`.`npi_number` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `fl2_records`) union (select 15 AS `exclusion_list_id`,`mi1_records`.`id` AS `exclusion_list_record_id`,`mi1_records`.`first_name` AS `first_name`,`mi1_records`.`middle_name` AS `middle_name`,`mi1_records`.`last_name` AS `last_name`,`mi1_records`.`entity_name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`mi1_records`.`npi_number` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `mi1_records`) union (select 16 AS `exclusion_list_id`,`al1_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`al1_records`.`name_of_provider` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `al1_records`) union (select 17 AS `exclusion_list_id`,`wv2_records`.`id` AS `exclusion_list_record_id`,`wv2_records`.`first_name` AS `first_name`,`wv2_records`.`middle_name` AS `middle_name`,`wv2_records`.`last_name` AS `last_name`,`wv2_records`.`full_name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`wv2_records`.`npi_number` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `wv2_records`) union (select 18 AS `exclusion_list_id`,`md1_records`.`id` AS `exclusion_list_record_id`,`md1_records`.`first_name` AS `first_name`,NULL AS `middle_name`,`md1_records`.`last_name` AS `last_name`,NULL AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `md1_records`) union (select 19 AS `exclusion_list_id`,`id1_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`id1_records`.`name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `id1_records`) union (select 20 AS `exclusion_list_id`,`ne1_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`ne1_records`.`provider_name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `ne1_records`) union (select 21 AS `exclusion_list_id`,`wy1_records`.`id` AS `exclusion_list_record_id`,`wy1_records`.`first_name` AS `first_name`,NULL AS `middle_name`,`wy1_records`.`last_name` AS `last_name`,`wy1_records`.`business_name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`wy1_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `wy1_records`) union (select 22 AS `exclusion_list_id`,`nv1_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`nv1_records`.`doing_business_as` AS `entity1`,`nv1_records`.`legal_entity` AS `entity2`,`nv1_records`.`ownership_of_at_least_5_percent` AS `entity3`,NULL AS `ssn_last_four`,`nv1_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `nv1_records`) union (select 23 AS `exclusion_list_id`,`ca1_records`.`id` AS `exclusion_list_record_id`,`ca1_records`.`first_name` AS `first_name`,`ca1_records`.`middle_name` AS `middle_name`,`ca1_records`.`last_name` AS `last_name`,`ca1_records`.`business` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `ca1_records`) union (select 24 AS `exclusion_list_id`,`hi1_records`.`id` AS `exclusion_list_record_id`,`hi1_records`.`first_name` AS `first_name`,NULL AS `middle_name`,`hi1_records`.`last_name_or_business` AS `last_name`,NULL AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `hi1_records`) union (select 25 AS `exclusion_list_id`,`az1_records`.`id` AS `exclusion_list_record_id`,`az1_records`.`first_name` AS `first_name`,`az1_records`.`middle` AS `middle_name`,`az1_records`.`last_name_company_name` AS `last_name`,NULL AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`az1_records`.`npi_number` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `az1_records`) union (select 26 AS `exclusion_list_id`,`tn1_records`.`id` AS `exclusion_list_record_id`,`tn1_records`.`first_name` AS `first_name`,NULL AS `middle_name`,`tn1_records`.`last_name` AS `last_name`,NULL AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`tn1_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `tn1_records`) union (select 27 AS `exclusion_list_id`,`mn1_records`.`id` AS `exclusion_list_record_id`,`mn1_records`.`first_name` AS `first_name`,`mn1_records`.`middle_name` AS `middle_name`,`mn1_records`.`last_name` AS `last_name`,`mn1_records`.`sort_name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `mn1_records`) union (select 29 AS `exclusion_list_id`,`ak1_records`.`id` AS `exclusion_list_record_id`,`ak1_records`.`first_name` AS `first_name`,`ak1_records`.`middle_name` AS `middle_name`,`ak1_records`.`last_name` AS `last_name`,NULL AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `ak1_records`) union (select 30 AS `exclusion_list_id`,`ms1_records`.`id` AS `exclusion_list_record_id`,`ms1_records`.`first_name` AS `first_name`,`ms1_records`.`middle_name` AS `middle_name`,`ms1_records`.`last_name` AS `last_name`,`ms1_records`.`entity_name` AS `entity1`,`ms1_records`.`dba` AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`ms1_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `ms1_records`) union (select 31 AS `exclusion_list_id`,`nc1_records`.`id` AS `exclusion_list_record_id`,`nc1_records`.`first_name` AS `first_name`,NULL AS `middle_name`,`nc1_records`.`last_name` AS `last_name`,NULL AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`nc1_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `nc1_records`) union (select 32 AS `exclusion_list_id`,`mo1_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`mo1_records`.`provider_name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`mo1_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `mo1_records`) union (select 33 AS `exclusion_list_id`,`wa1_records`.`id` AS `exclusion_list_record_id`,`wa1_records`.`first_name` AS `first_name`,`wa1_records`.`middle_etc` AS `middle_name`,`wa1_records`.`last_name` AS `last_name`,`wa1_records`.`entity` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `wa1_records`) union (select 34 AS `exclusion_list_id`,`ks1_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`ks1_records`.`name` AS `entity1`,`ks1_records`.`d_b_a` AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`ks1_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `ks1_records`) union (select 35 AS `exclusion_list_id`,`la1_records`.`id` AS `exclusion_list_record_id`,`la1_records`.`first_name` AS `first_name`,NULL AS `middle_name`,`la1_records`.`last_or_entity_name` AS `last_name`,`la1_records`.`last_or_entity_name` AS `entity1`,`la1_records`.`affiliated_entity` AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`la1_records`.`npi` AS `npi`,NULL AS `upin`,`la1_records`.`birthdate` AS `date_of_birth` from `la1_records`) union (select 36 AS `exclusion_list_id`,`nd1_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`nd1_records`.`provider_name` AS `entity1`,`nd1_records`.`business` AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`nd1_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `nd1_records`) union (select 37 AS `exclusion_list_id`,`mt1_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`mt1_records`.`provider_name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `mt1_records`) union (select 38 AS `exclusion_list_id`,`dc1_records`.`id` AS `exclusion_list_record_id`,`dc1_records`.`first_name` AS `first_name`,`dc1_records`.`middle_name` AS `middle_name`,`dc1_records`.`last_name` AS `last_name`,`dc1_records`.`company_name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `dc1_records`) union (select 39 AS `exclusion_list_id`,`ia1_records`.`id` AS `exclusion_list_record_id`,`ia1_records`.`individual_first_name` AS `first_name`,NULL AS `middle_name`,`ia1_records`.`individual_last_name` AS `last_name`,`ia1_records`.`entity_name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,`ia1_records`.`npi` AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `ia1_records`) union (select 40 AS `exclusion_list_id`,`ga1_records`.`id` AS `exclusion_list_record_id`,`ga1_records`.`first_name` AS `first_name`,NULL AS `middle_name`,`ga1_records`.`last_name` AS `last_name`,`ga1_records`.`business_name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `ga1_records`) union (select 41 AS `exclusion_list_id`,`me1_records`.`id` AS `exclusion_list_record_id`,`me1_records`.`first_name` AS `first_name`,`me1_records`.`middle_initial` AS `middle_name`,`me1_records`.`last_name` AS `last_name`,`me1_records`.`entity` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `me1_records`) union (select 42 AS `exclusion_list_id`,`fdac_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`fdac_records`.`name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `fdac_records`) union (select 43 AS `exclusion_list_id`,`phs_records`.`id` AS `exclusion_list_record_id`,`phs_records`.`first_name` AS `first_name`,`phs_records`.`middle_name` AS `middle_name`,`phs_records`.`last_name` AS `last_name`,NULL AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `phs_records`) union (select 44 AS `exclusion_list_id`,`usdocdp_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`usdocdp_records`.`name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `usdocdp_records`) union (select 45 AS `exclusion_list_id`,`fdadl_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`fdadl_records`.`name` AS `entity1`,`fdadl_records`.`aka` AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `fdadl_records`) union (select 46 AS `exclusion_list_id`,`fehbp_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`fehbp_records`.`name` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,`fehbp_records`.`ssn_last_four` AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,`fehbp_records`.`date_of_birth` AS `date_of_birth` from `fehbp_records`) union (select 47 AS `exclusion_list_id`,`usdosd_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`usdosd_records`.`full_name` AS `entity1`,`usdosd_records`.`aka_name` AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `usdosd_records`) union (select 48 AS `exclusion_list_id`,`unsancindividuals_records`.`id` AS `exclusion_list_record_id`,`unsancindividuals_records`.`first_name` AS `first_name`,NULL AS `middle_name`,`unsancindividuals_records`.`second_name` AS `last_name`,`unsancindividuals_records`.`first_name` AS `entity1`,`unsancindividuals_records`.`second_name` AS `entity2`,`unsancindividuals_records`.`third_name` AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,`unsancindividuals_records`.`date_of_birth` AS `date_of_birth` from `unsancindividuals_records`) union (select 49 AS `exclusion_list_id`,`unsancentities_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,`unsancentities_records`.`entity_name` AS `entity1`,`unsancentities_records`.`entity_alias` AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `unsancentities_records`) union (select 50 AS `exclusion_list_id`,`healthmil_records`.`id` AS `exclusion_list_record_id`,`healthmil_records`.`first_name` AS `first_name`,`healthmil_records`.`middle_name` AS `middle_name`,`healthmil_records`.`last_name` AS `last_name`,`healthmil_records`.`companies` AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `healthmil_records`) union (select 51 AS `exclusion_list_id`,`csl_records`.`id` AS `exclusion_list_record_id`,NULL AS `first_name`,NULL AS `middle_name`,NULL AS `last_name`,NULL AS `entity1`,NULL AS `entity2`,NULL AS `entity3`,NULL AS `ssn_last_four`,NULL AS `npi`,NULL AS `upin`,NULL AS `date_of_birth` from `csl_records`) ;
