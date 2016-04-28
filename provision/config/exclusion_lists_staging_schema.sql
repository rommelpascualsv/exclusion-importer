-- Table structure for ak1_records
-- ----------------------------

CREATE SCHEMA IF NOT EXISTS `exclusion_lists_staging`;

USE `exclusion_lists_staging`;

DROP TABLE IF EXISTS `ak1_records`;
CREATE TABLE `ak1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`exclusion_date`  date NULL DEFAULT NULL ,
`last_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`middle_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_authority`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_reason`  varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for ak1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `ak1_records_older`;
CREATE TABLE `ak1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`exclusion_date`  date NULL DEFAULT NULL ,
`last_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`middle_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_authority`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_reason`  varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for al1_records
-- ----------------------------
DROP TABLE IF EXISTS `al1_records`;
CREATE TABLE `al1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name_of_provider`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`suspension_effective_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`suspension_initiated_by`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`aka_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for al1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `al1_records_older`;
CREATE TABLE `al1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name_of_provider`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`suspension_effective_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`suspension_initiated_by`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`aka_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for ar1_records
-- ----------------------------
DROP TABLE IF EXISTS `ar1_records`;
CREATE TABLE `ar1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`Division`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`FacilityName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ProviderName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`City`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`State`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Zip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ar1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `ar1_records_older`;
CREATE TABLE `ar1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`Division`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`FacilityName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ProviderName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`City`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`State`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Zip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for az1_records
-- ----------------------------
DROP TABLE IF EXISTS `az1_records`;
CREATE TABLE `az1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`middle`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`last_name_company_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`term_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`specialty`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for az1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `az1_records_older`;
CREATE TABLE `az1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`middle`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`last_name_company_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`term_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`specialty`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ca1_records
-- ----------------------------
DROP TABLE IF EXISTS `ca1_records`;
CREATE TABLE `ca1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`last_name`  varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`first_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`middle_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`aka_dba`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`business`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`addresses`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`license_numbers`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_numbers`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`date_of_suspension`  date NULL DEFAULT NULL ,
`active_period`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`new_hash`  binary(16) NULL DEFAULT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ca1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `ca1_records_older`;
CREATE TABLE `ca1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`last_name`  varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`first_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`middle_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`aka_dba`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`business`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`addresses`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`license_numbers`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_numbers`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`date_of_suspension`  date NULL DEFAULT NULL ,
`active_period`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`new_hash`  binary(16) NULL DEFAULT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ct1_records
-- ----------------------------
DROP TABLE IF EXISTS `ct1_records`;
CREATE TABLE `ct1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`business`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`specialty`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`effective_date`  date NOT NULL ,
`period`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`action`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ct1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `ct1_records_older`;
CREATE TABLE `ct1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`business`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`specialty`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`effective_date`  date NOT NULL ,
`period`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`action`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for cus_spectrum_debar_records
-- ----------------------------
DROP TABLE IF EXISTS `cus_spectrum_debar_records`;
CREATE TABLE `cus_spectrum_debar_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`title`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`ssn`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`tin`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`npi`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`date_of_birth`  date NULL DEFAULT NULL ,
`street_address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`state`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`zip`  varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`debar_code`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`suspend_date`  date NULL DEFAULT NULL ,
`debar_date`  date NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for cus_spectrum_debar_records_older
-- ----------------------------
DROP TABLE IF EXISTS `cus_spectrum_debar_records_older`;
CREATE TABLE `cus_spectrum_debar_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`title`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`ssn`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`tin`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`npi`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`date_of_birth`  date NULL DEFAULT NULL ,
`street_address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`state`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`zip`  varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`debar_code`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`suspend_date`  date NULL DEFAULT NULL ,
`debar_date`  date NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for dc1_records
-- ----------------------------
DROP TABLE IF EXISTS `dc1_records`;
CREATE TABLE `dc1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`company_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`middle_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`last_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`principals`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`action_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`termination_date`  date NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for dc1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `dc1_records_older`;
CREATE TABLE `dc1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`company_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`middle_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`last_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`principals`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`action_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`termination_date`  date NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for epls_records
-- ----------------------------
DROP TABLE IF EXISTS `epls_records`;
CREATE TABLE `epls_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`Name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Prefix`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`First`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Middle`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Last`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Suffix`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Classification`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Exclusion_Type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Address_1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Address_2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`City`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`State`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Zip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Province`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Country`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`DUNS`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`CT_Code`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Agency`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Action_Date`  date NOT NULL ,
`Termination_Date`  date NOT NULL ,
`Action_Status`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for fl1_records
-- ----------------------------
DROP TABLE IF EXISTS `fl1_records`;
CREATE TABLE `fl1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`document_type`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`provider`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`ahca_case_number`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`formal_informal_case_number`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`date_rendered`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Table structure for fl2_records
-- ----------------------------
DROP TABLE IF EXISTS `fl2_records`;
CREATE TABLE `fl2_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`provider`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`medicaid_provider_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`license_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`date_rendered`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sanction_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`violation_code`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`fine_amount`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sanction_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ahca_case_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`formal_informal_case_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`document_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`new_hash`  binary(16) NULL DEFAULT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for fl2_records_older
-- ----------------------------
DROP TABLE IF EXISTS `fl2_records_older`;
CREATE TABLE `fl2_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`provider`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`medicaid_provider_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`license_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`date_rendered`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sanction_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`violation_code`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`fine_amount`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sanction_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ahca_case_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`formal_informal_case_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`document_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`new_hash`  binary(16) NULL DEFAULT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=latin1 COLLATE=latin1_swedish_ci

;

-- ----------------------------
-- Table structure for ga1_records
-- ----------------------------
DROP TABLE IF EXISTS `ga1_records`;
CREATE TABLE `ga1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`last_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`business_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`general`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`state`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`sanction_date`  date NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ga1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `ga1_records_older`;
CREATE TABLE `ga1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`last_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`business_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`general`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`state`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`sanction_date`  date NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for hi1_records
-- ----------------------------
DROP TABLE IF EXISTS `hi1_records`;
CREATE TABLE `hi1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`last_name_or_business`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`middle_initial`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`medicaid_provide_id_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`last_known_program_or_provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`exclusion_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`reinstatement_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for hi1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `hi1_records_older`;
CREATE TABLE `hi1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`last_name_or_business`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`middle_initial`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`medicaid_provide_id_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`last_known_program_or_provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`exclusion_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`reinstatement_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ia1_records
-- ----------------------------
DROP TABLE IF EXISTS `ia1_records`;
CREATE TABLE `ia1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sanction_start_date`  date NULL DEFAULT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`individual_last_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`individual_first_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`entity_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`sanction`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`sanction_end_date`  date NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ia1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `ia1_records_older`;
CREATE TABLE `ia1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sanction_start_date`  date NULL DEFAULT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`individual_last_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`individual_first_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`entity_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`sanction`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`sanction_end_date`  date NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for id1_records
-- ----------------------------
DROP TABLE IF EXISTS `id1_records`;
CREATE TABLE `id1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`exclusion_start_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`date_eligible_for_reinstatement`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`date_reinstated`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`additional_information`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for id1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `id1_records_older`;
CREATE TABLE `id1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`exclusion_start_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`date_eligible_for_reinstatement`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`date_reinstated`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`additional_information`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for il1_records
-- ----------------------------
DROP TABLE IF EXISTS `il1_records`;
CREATE TABLE `il1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`il1_id`  int(10) UNSIGNED NOT NULL ,
`ProvName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`LIC`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`AFFILIATION`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ACTION_DT`  date NOT NULL ,
`ACTION_TYPE`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ADDRESS`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ADDRESS2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`CITY`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`STATE`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ZIP_CODE`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for il1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `il1_records_older`;
CREATE TABLE `il1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`il1_id`  int(10) UNSIGNED NOT NULL ,
`ProvName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`LIC`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`AFFILIATION`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ACTION_DT`  date NOT NULL ,
`ACTION_TYPE`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ADDRESS`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ADDRESS2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`CITY`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`STATE`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ZIP_CODE`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ks1_records
-- ----------------------------
DROP TABLE IF EXISTS `ks1_records`;
CREATE TABLE `ks1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`termination_date`  date NULL DEFAULT NULL ,
`name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`d_b_a`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`kmap_provider_number`  varchar(70) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`npi`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`comments`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ks1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `ks1_records_older`;
CREATE TABLE `ks1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`termination_date`  date NULL DEFAULT NULL ,
`name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`d_b_a`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`kmap_provider_number`  varchar(70) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`npi`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`comments`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ky1_records
-- ----------------------------
DROP TABLE IF EXISTS `ky1_records`;
CREATE TABLE `ky1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`last_name_or_practice`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`license`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`effective_date`  date NULL DEFAULT NULL ,
`reason_for_term`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`timeframe_of_term`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ky1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `ky1_records_older`;
CREATE TABLE `ky1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`last_name_or_practice`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`license`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`effective_date`  date NULL DEFAULT NULL ,
`reason_for_term`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`timeframe_of_term`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for la1_records
-- ----------------------------
DROP TABLE IF EXISTS `la1_records`;
CREATE TABLE `la1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`last_or_entity_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`birthdate`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`affiliated_entity`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`title_or_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`period_of_exclusion`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`effective_date`  date NULL DEFAULT NULL ,
`reinstate_date`  varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`state_zip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for la1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `la1_records_older`;
CREATE TABLE `la1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`last_or_entity_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`birthdate`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`affiliated_entity`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`title_or_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`period_of_exclusion`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`effective_date`  date NULL DEFAULT NULL ,
`reinstate_date`  varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`state_zip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ma1_records
-- ----------------------------
DROP TABLE IF EXISTS `ma1_records`;
CREATE TABLE `ma1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`provider_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`npi`  int(10) UNSIGNED NULL DEFAULT NULL ,
`reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`effective_date`  date NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Table structure for ma1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `ma1_records_older`;
CREATE TABLE `ma1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`provider_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`npi`  int(10) UNSIGNED NULL DEFAULT NULL ,
`reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`effective_date`  date NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci

;

-- ----------------------------
-- Table structure for md1_records
-- ----------------------------
DROP TABLE IF EXISTS `md1_records`;
CREATE TABLE `md1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`last_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`specialty`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sanction_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sanction_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city_state_zip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for md1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `md1_records_older`;
CREATE TABLE `md1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`last_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`specialty`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sanction_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sanction_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city_state_zip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for me1_records
-- ----------------------------
DROP TABLE IF EXISTS `me1_records`;
CREATE TABLE `me1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`entity`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`last_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`middle_initial`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`prov_type`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`case_status`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`sanction_start_date`  date NULL DEFAULT NULL ,
`aka_list`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for me1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `me1_records_older`;
CREATE TABLE `me1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`entity`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`last_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`middle_initial`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`prov_type`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`case_status`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`sanction_start_date`  date NULL DEFAULT NULL ,
`aka_list`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for mi1_records
-- ----------------------------
DROP TABLE IF EXISTS `mi1_records`;
CREATE TABLE `mi1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`entity_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`last_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`middle_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_category`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`license_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`sanction_date_1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`sanction_date_2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for mi1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `mi1_records_older`;
CREATE TABLE `mi1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`entity_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`last_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`middle_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_category`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`license_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sanction_date_1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`sanction_date_2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for mn1_records
-- ----------------------------
DROP TABLE IF EXISTS `mn1_records`;
CREATE TABLE `mn1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`provider_type_description`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sort_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`last_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`first_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`middle_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`effective_date_of_exclusion`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`address_line1`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`address_line2`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`state`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`zip`  varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for mn1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `mn1_records_older`;
CREATE TABLE `mn1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`provider_type_description`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sort_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`last_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`first_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`middle_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`effective_date_of_exclusion`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`address_line1`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`address_line2`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`state`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`zip`  varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for mo1_records
-- ----------------------------
DROP TABLE IF EXISTS `mo1_records`;
CREATE TABLE `mo1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`termination_date`  date NULL DEFAULT NULL ,
`letter_date`  date NULL DEFAULT NULL ,
`provider_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ssn`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`license_number`  varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`termination_reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for mo1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `mo1_records_older`;
CREATE TABLE `mo1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`termination_date`  date NULL DEFAULT NULL ,
`letter_date`  date NULL DEFAULT NULL ,
`provider_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ssn`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`license_number`  varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`termination_reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ms1_records
-- ----------------------------
DROP TABLE IF EXISTS `ms1_records`;
CREATE TABLE `ms1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`entity_1`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`entity_2`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`address`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`address_2`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`specialty`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_from_date`  date NULL DEFAULT NULL ,
`exclusion_to_date`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ms1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `ms1_records_older`;
CREATE TABLE `ms1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`entity_1`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`entity_2`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`address`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`address_2`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`specialty`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_from_date`  date NULL DEFAULT NULL ,
`exclusion_to_date`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for mt1_records
-- ----------------------------
DROP TABLE IF EXISTS `mt1_records`;
CREATE TABLE `mt1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`provider_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_termination_date`  date NULL DEFAULT NULL ,
`exclusion_termination_agency`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for mt1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `mt1_records_older`;
CREATE TABLE `mt1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`provider_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_termination_date`  date NULL DEFAULT NULL ,
`exclusion_termination_agency`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for nc1_records
-- ----------------------------
DROP TABLE IF EXISTS `nc1_records`;
CREATE TABLE `nc1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`npi`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`last_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`address_1`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`city`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`state`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`zip`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`health_plan`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`date_excluded`  date NULL DEFAULT NULL ,
`exclusion_reason`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for nc1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `nc1_records_older`;
CREATE TABLE `nc1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`npi`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`last_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`address_1`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`city`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`state`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`zip`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`health_plan`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`date_excluded`  date NULL DEFAULT NULL ,
`exclusion_reason`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for nd1_records
-- ----------------------------
DROP TABLE IF EXISTS `nd1_records`;
CREATE TABLE `nd1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`provider_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_verification`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`business`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`business_name_address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`medicaid_provider_id`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`medicaid_provider_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`npi`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`state`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_date`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_reason`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
`exclusion_reason_2`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for nd1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `nd1_records_older`;
CREATE TABLE `nd1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`provider_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_verification`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`business`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`business_name_address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`medicaid_provider_id`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`medicaid_provider_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`npi`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`state`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_date`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_reason`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
`exclusion_reason_2`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ne1_records
-- ----------------------------
DROP TABLE IF EXISTS `ne1_records`;
CREATE TABLE `ne1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`provider_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`termination_or_suspension`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`effective_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`term`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`end_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`reason_for_action`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for ne1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `ne1_records_older`;
CREATE TABLE `ne1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`provider_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`termination_or_suspension`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`effective_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`term`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`end_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`reason_for_action`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for njcdr_records
-- ----------------------------
DROP TABLE IF EXISTS `njcdr_records`;
CREATE TABLE `njcdr_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`vendor_id`  varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firm_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firm_street`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firm_city`  varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firm_state`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firm_zip`  char(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firm_plus4`  varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`street`  varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`state`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`zip`  char(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`plus4`  varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`category`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`action`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`reason`  char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`debarring_dept`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`debarring_agency`  char(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`effective_date`  date NULL DEFAULT NULL ,
`expiration_date`  date NULL DEFAULT NULL ,
`permanent_debarment`  char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`new_hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for njcdr_records_older
-- ----------------------------
DROP TABLE IF EXISTS `njcdr_records_older`;
CREATE TABLE `njcdr_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`vendor_id`  varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firm_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firm_street`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firm_city`  varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firm_state`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firm_zip`  char(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firm_plus4`  varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`street`  varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`state`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`zip`  char(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`plus4`  varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`category`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`action`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`reason`  char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`debarring_dept`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`debarring_agency`  char(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`effective_date`  date NULL DEFAULT NULL ,
`expiration_date`  date NULL DEFAULT NULL ,
`permanent_debarment`  char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`new_hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for nv1_records
-- ----------------------------
DROP TABLE IF EXISTS `nv1_records`;
CREATE TABLE `nv1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`doing_business_as`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`legal_entity`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ownership_of_at_least_5_percent`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`medicaid_provider`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`termination_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sanction_tier`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sanction_period`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`reinstatement_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for nv1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `nv1_records_older`;
CREATE TABLE `nv1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`doing_business_as`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`legal_entity`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ownership_of_at_least_5_percent`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`medicaid_provider`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`termination_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sanction_tier`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sanction_period`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`reinstatement_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for nyomig_records
-- ----------------------------
DROP TABLE IF EXISTS `nyomig_records`;
CREATE TABLE `nyomig_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`business`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provtype`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`county`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`state`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`zip`  char(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`action_date`  date NULL DEFAULT NULL ,
`action_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`profession_desc`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for oh1_records
-- ----------------------------
DROP TABLE IF EXISTS `oh1_records`;
CREATE TABLE `oh1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`last_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`first_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`organization_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`date_of_birth`  date NULL DEFAULT NULL ,
`npi`  int(10) NULL DEFAULT NULL ,
`address1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`address2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`state`  varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`zip_code`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_id`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`status`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`action_date`  date NULL DEFAULT NULL ,
`date_added`  date NULL DEFAULT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`date_revised`  date NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for oh1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `oh1_records_older`;
CREATE TABLE `oh1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`last_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`first_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`organization_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`date_of_birth`  date NULL DEFAULT NULL ,
`npi`  int(10) NULL DEFAULT NULL ,
`address1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`address2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`state`  varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`zip_code`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_id`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`status`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`action_date`  date NULL DEFAULT NULL ,
`date_added`  date NULL DEFAULT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`date_revised`  date NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for oig_records
-- ----------------------------
DROP TABLE IF EXISTS `oig_records`;
CREATE TABLE `oig_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`lastname`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firstname`  varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`midname`  varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`busname`  varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`general`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`specialty`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`upin`  varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  int(10) UNSIGNED NULL DEFAULT NULL ,
`dob`  date NULL DEFAULT NULL ,
`address`  varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`state`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`zip`  char(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`excltype`  varchar(9) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`excldate`  date NULL DEFAULT NULL ,
`reindate`  date NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for oig_records_older
-- ----------------------------
DROP TABLE IF EXISTS `oig_records_older`;
CREATE TABLE `oig_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`lastname`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firstname`  varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`midname`  varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`busname`  varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`general`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`specialty`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`upin`  varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  int(10) UNSIGNED NULL DEFAULT NULL ,
`dob`  date NULL DEFAULT NULL ,
`address`  varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`state`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`zip`  char(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`excltype`  varchar(9) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`excldate`  date NULL DEFAULT NULL ,
`reindate`  date NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for pa1_records
-- ----------------------------
DROP TABLE IF EXISTS `pa1_records`;
CREATE TABLE `pa1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`ProviderName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`LicenseNumber`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Status`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`BeginDate`  date NULL DEFAULT NULL ,
`EndDate`  date NULL DEFAULT NULL ,
`CAO`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ListDate`  date NULL DEFAULT NULL ,
`IND_DELTD`  int(11) NOT NULL ,
`TXT_REASON_DELTD`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`DTE_DELTD`  date NULL DEFAULT NULL ,
`IND_CHGD`  int(11) NOT NULL ,
`DTE_CHANGE_LAST`  date NULL DEFAULT NULL ,
`NAM_LAST_PROVR`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`NAM_FIRST_PROVR`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`NAM_MIDDLE_PROVR`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`NAM_TITLE_PROVR`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`NAM_SUFFIX_PROVR`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`NAM_PROVR_ALT`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`NAM_BUSNS_MP`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`IDN_NPI`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`TXT_CMT`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for pa1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `pa1_records_older`;
CREATE TABLE `pa1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`ProviderName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`LicenseNumber`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Status`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`BeginDate`  date NULL DEFAULT NULL ,
`EndDate`  date NULL DEFAULT NULL ,
`CAO`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`ListDate`  date NULL DEFAULT NULL ,
`IND_DELTD`  int(11) NOT NULL ,
`TXT_REASON_DELTD`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`DTE_DELTD`  date NULL DEFAULT NULL ,
`IND_CHGD`  int(11) NOT NULL ,
`DTE_CHANGE_LAST`  date NULL DEFAULT NULL ,
`NAM_LAST_PROVR`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`NAM_FIRST_PROVR`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`NAM_MIDDLE_PROVR`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`NAM_TITLE_PROVR`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`NAM_SUFFIX_PROVR`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`NAM_PROVR_ALT`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`NAM_BUSNS_MP`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`IDN_NPI`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`TXT_CMT`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sam_records
-- ----------------------------
DROP TABLE IF EXISTS `sam_records`;
CREATE TABLE `sam_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`Classification`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Prefix`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`First`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Middle`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Last`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Suffix`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Address_1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Address_2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Address_3`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Address_4`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`City`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`State`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Country`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Zip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`DUNS`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Exclusion_Program`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Excluding_Agency`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`CT_Code`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Exclusion_Type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Additional_Comments`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Active_Date`  date NULL DEFAULT NULL ,
`Termination_Date`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Record_Status`  tinyint(1) NOT NULL DEFAULT 1 ,
`Cross_Reference`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`SAM_Number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`matching_OIG_hash`  binary(16) NOT NULL ,
`hash`  binary(16) NOT NULL ,
`new_hash`  binary(16) NULL DEFAULT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sam_records_temp
-- ----------------------------
DROP TABLE IF EXISTS `sam_records_temp`;
CREATE TABLE `sam_records_temp` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`Classification`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Prefix`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`First`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Middle`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Last`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Suffix`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Address_1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Address_2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Address_3`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Address_4`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`City`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`State`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Country`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Zip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`DUNS`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Exclusion_Program`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Excluding_Agency`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`CT_Code`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Exclusion_Type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Additional_Comments`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Active_Date`  date NULL DEFAULT NULL ,
`Termination_Date`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`Record_Status`  tinyint(1) NOT NULL DEFAULT 1 ,
`Cross_Reference`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`SAM_Number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`matching_OIG_hash`  binary(16) NOT NULL ,
`hash`  binary(16) NOT NULL ,
`new_hash`  binary(16) NULL DEFAULT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sc1_records
-- ----------------------------
DROP TABLE IF EXISTS `sc1_records`;
CREATE TABLE `sc1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`entity`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`state`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`zip`  char(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`date_excluded`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sc1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `sc1_records_older`;
CREATE TABLE `sc1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`entity`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`state`  char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`zip`  char(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`date_excluded`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_address_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_address_list`;
CREATE TABLE `sdn_address_list` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`address1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`address2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`address3`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`stateOrProvince`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`postalCode`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`country`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_address_list_older
-- ----------------------------
DROP TABLE IF EXISTS `sdn_address_list_older`;
CREATE TABLE `sdn_address_list_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`address1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`address2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`address3`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`stateOrProvince`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`postalCode`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`country`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_aka_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_aka_list`;
CREATE TABLE `sdn_aka_list` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`category`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`lastName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firstName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_aka_list_older
-- ----------------------------
DROP TABLE IF EXISTS `sdn_aka_list_older`;
CREATE TABLE `sdn_aka_list_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`category`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`lastName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`firstName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_citizenship_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_citizenship_list`;
CREATE TABLE `sdn_citizenship_list` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`country`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`mainEntry`  tinyint(1) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_citizenship_list_older
-- ----------------------------
DROP TABLE IF EXISTS `sdn_citizenship_list_older`;
CREATE TABLE `sdn_citizenship_list_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`country`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`mainEntry`  tinyint(1) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_date_of_birth_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_date_of_birth_list`;
CREATE TABLE `sdn_date_of_birth_list` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`dateOfBirth`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`mainEntry`  tinyint(1) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_date_of_birth_list_older
-- ----------------------------
DROP TABLE IF EXISTS `sdn_date_of_birth_list_older`;
CREATE TABLE `sdn_date_of_birth_list_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`dateOfBirth`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`mainEntry`  tinyint(1) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_entries
-- ----------------------------
DROP TABLE IF EXISTS `sdn_entries`;
CREATE TABLE `sdn_entries` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`firstName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`lastName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sdnType`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`remarks`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_entries_older
-- ----------------------------
DROP TABLE IF EXISTS `sdn_entries_older`;
CREATE TABLE `sdn_entries_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`firstName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`lastName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`sdnType`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`remarks`  text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_id_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_id_list`;
CREATE TABLE `sdn_id_list` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`idType`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`idNumber`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`idCountry`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`issueDate`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`expirationDate`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_id_list_older
-- ----------------------------
DROP TABLE IF EXISTS `sdn_id_list_older`;
CREATE TABLE `sdn_id_list_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`idType`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`idNumber`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`idCountry`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`issueDate`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`expirationDate`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_import_log
-- ----------------------------
DROP TABLE IF EXISTS `sdn_import_log`;
CREATE TABLE `sdn_import_log` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`publish_date`  date NULL DEFAULT NULL ,
`record_count`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`date_created`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_import_log_older
-- ----------------------------
DROP TABLE IF EXISTS `sdn_import_log_older`;
CREATE TABLE `sdn_import_log_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`publish_date`  date NULL DEFAULT NULL ,
`record_count`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`date_created`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_nationality_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_nationality_list`;
CREATE TABLE `sdn_nationality_list` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`country`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`mainEntry`  tinyint(1) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_nationality_list_older
-- ----------------------------
DROP TABLE IF EXISTS `sdn_nationality_list_older`;
CREATE TABLE `sdn_nationality_list_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`country`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`mainEntry`  tinyint(1) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_place_of_birth_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_place_of_birth_list`;
CREATE TABLE `sdn_place_of_birth_list` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`placeOfBirth`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`mainEntry`  tinyint(1) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_place_of_birth_list_older
-- ----------------------------
DROP TABLE IF EXISTS `sdn_place_of_birth_list_older`;
CREATE TABLE `sdn_place_of_birth_list_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`uid`  int(10) UNSIGNED NULL DEFAULT NULL ,
`placeOfBirth`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`mainEntry`  tinyint(1) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_program_list
-- ----------------------------
DROP TABLE IF EXISTS `sdn_program_list`;
CREATE TABLE `sdn_program_list` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`program`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_program_list_older
-- ----------------------------
DROP TABLE IF EXISTS `sdn_program_list_older`;
CREATE TABLE `sdn_program_list_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`program`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_vessel_info
-- ----------------------------
DROP TABLE IF EXISTS `sdn_vessel_info`;
CREATE TABLE `sdn_vessel_info` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`callSign`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`vesselType`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`vesselFlag`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`vesselOwner`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`tonnage`  int(11) NULL DEFAULT NULL ,
`grossRegisteredTonnage`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for sdn_vessel_info_older
-- ----------------------------
DROP TABLE IF EXISTS `sdn_vessel_info_older`;
CREATE TABLE `sdn_vessel_info_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`sdn_entry_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`callSign`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`vesselType`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`vesselFlag`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`vesselOwner`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`tonnage`  int(11) NULL DEFAULT NULL ,
`grossRegisteredTonnage`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for tn1_records
-- ----------------------------
DROP TABLE IF EXISTS `tn1_records`;
CREATE TABLE `tn1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`last_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`first_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`middle_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`begin_date`  date NOT NULL ,
`end_date`  date NULL DEFAULT NULL ,
`reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for tn1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `tn1_records_older`;
CREATE TABLE `tn1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`last_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`first_name`  varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`middle_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`npi`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`begin_date`  date NOT NULL ,
`end_date`  date NULL DEFAULT NULL ,
`reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for tx1_records
-- ----------------------------
DROP TABLE IF EXISTS `tx1_records`;
CREATE TABLE `tx1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`CompanyName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`LastName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`FirstName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`MidInitial`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`LicenseNumber`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`StartDate`  date NULL DEFAULT NULL ,
`AddDate`  date NULL DEFAULT NULL ,
`ReinstatedDate`  date NULL DEFAULT NULL ,
`WebComments`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for tx1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `tx1_records_older`;
CREATE TABLE `tx1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`CompanyName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`LastName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`FirstName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`MidInitial`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`LicenseNumber`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`StartDate`  date NULL DEFAULT NULL ,
`AddDate`  date NULL DEFAULT NULL ,
`ReinstatedDate`  date NULL DEFAULT NULL ,
`WebComments`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for wa1_records
-- ----------------------------
DROP TABLE IF EXISTS `wa1_records`;
CREATE TABLE `wa1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`last_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`middle_etc`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`entity`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_license`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`npi`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`termination_date`  date NULL DEFAULT NULL ,
`termination_reason`  varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for wa1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `wa1_records_older`;
CREATE TABLE `wa1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`last_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`middle_etc`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`entity`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_license`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`npi`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`termination_date`  date NULL DEFAULT NULL ,
`termination_reason`  varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for wv2_records
-- ----------------------------
DROP TABLE IF EXISTS `wv2_records`;
CREATE TABLE `wv2_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`npi_number`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`full_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`middle_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`last_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`generation`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`credentials`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`state`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_date`  date NULL DEFAULT NULL ,
`reason_for_exclusion`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`reinstatement_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`reinstatement_reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for wv2_records_older
-- ----------------------------
DROP TABLE IF EXISTS `wv2_records_older`;
CREATE TABLE `wv2_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`npi_number`  varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`full_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`middle_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`last_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`generation`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`credentials`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`state`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`exclusion_date`  date NULL DEFAULT NULL ,
`reason_for_exclusion`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`reinstatement_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`reinstatement_reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NULL DEFAULT NULL ,
`date_created` datetime NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for wy1_records
-- ----------------------------
DROP TABLE IF EXISTS `wy1_records`;
CREATE TABLE `wy1_records` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`last_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`business_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`state`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`exclusion_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`additional_info_1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`additional_info_2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for wy1_records_older
-- ----------------------------
DROP TABLE IF EXISTS `wy1_records_older`;
CREATE TABLE `wy1_records_older` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`last_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`first_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`business_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`provider_number`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`provider_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`state`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`exclusion_date`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`additional_info_1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`additional_info_2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`hash`  binary(16) NOT NULL ,
`date_created`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci

;

-- ----------------------------
-- Table structure for usdosd_records
-- ----------------------------
DROP TABLE IF EXISTS `usdosd_records`;
CREATE TABLE `usdosd_records` (
`id`            INT          NOT NULL AUTO_INCREMENT,
`full_name`     VARCHAR(100) NULL,
`aka_name`      VARCHAR(255) NULL,
`date_of_birth` VARCHAR(100) NULL,
`notice`        VARCHAR(100) NULL,
`notice_date`   DATE         NULL,
`hash`          BINARY(16)   NULL,
`date_modified` TIMESTAMP    NULL     DEFAULT CURRENT_TIMESTAMP,
`date_created`  TIMESTAMP    NULL     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE = INNODB
DEFAULT CHARACTER SET = UTF8
COLLATE = UTF8_UNICODE_CI;

-- ----------------------------
-- Table structure for files
-- ----------------------------  
DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`state_prefix`  varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`img_data`  longblob NULL DEFAULT NULL ,
`ready_for_update`  varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
`date_created`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`date_modified`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
)
ENGINE = INNODB
DEFAULT CHARACTER SET = UTF8
COLLATE = UTF8_UNICODE_CI;

-- ----------------------------
-- Indexes structure for table al1_records
-- ----------------------------
CREATE INDEX `provider` ON `al1_records`(`name_of_provider`) USING BTREE ;
CREATE INDEX `date_created` ON `al1_records`(`date_created`) USING BTREE ;
CREATE INDEX `hash` ON `al1_records`(`hash`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table al1_records_older
-- ----------------------------
CREATE INDEX `provider` ON `al1_records_older`(`name_of_provider`) USING BTREE ;
CREATE INDEX `date_created` ON `al1_records_older`(`date_created`) USING BTREE ;
CREATE INDEX `hash` ON `al1_records_older`(`hash`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table ar1_records
-- ----------------------------
CREATE INDEX `hash` ON `ar1_records`(`hash`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table ar1_records_older
-- ----------------------------
CREATE INDEX `hash` ON `ar1_records_older`(`hash`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table az1_records
-- ----------------------------
CREATE INDEX `hash` ON `az1_records`(`hash`) USING BTREE ;
CREATE INDEX `first_name` ON `az1_records`(`first_name`) USING BTREE ;
CREATE INDEX `last_name_company_name` ON `az1_records`(`last_name_company_name`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table az1_records_older
-- ----------------------------
CREATE INDEX `hash` ON `az1_records_older`(`hash`) USING BTREE ;
CREATE INDEX `first_name` ON `az1_records_older`(`first_name`) USING BTREE ;
CREATE INDEX `last_name_company_name` ON `az1_records_older`(`last_name_company_name`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table ca1_records
-- ----------------------------
CREATE FULLTEXT INDEX `first_middle` ON `ca1_records`(`first_name`, `middle_name`) ;
CREATE FULLTEXT INDEX `last_name` ON `ca1_records`(`last_name`) ;
CREATE FULLTEXT INDEX `business` ON `ca1_records`(`business`) ;

-- ----------------------------
-- Indexes structure for table ct1_records
-- ----------------------------
CREATE INDEX `hash` ON `ct1_records`(`hash`) USING BTREE ;
CREATE INDEX `name` ON `ct1_records`(`name`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table ct1_records_older
-- ----------------------------
CREATE INDEX `hash` ON `ct1_records_older`(`hash`) USING BTREE ;
CREATE INDEX `name` ON `ct1_records_older`(`name`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table epls_records
-- ----------------------------
CREATE INDEX `Name` ON `epls_records`(`Name`) USING BTREE ;
CREATE INDEX `Middle` ON `epls_records`(`Middle`) USING BTREE ;
CREATE INDEX `Last` ON `epls_records`(`Last`) USING BTREE ;
CREATE INDEX `First` ON `epls_records`(`First`) USING BTREE ;
CREATE INDEX `hash` ON `epls_records`(`hash`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table fl1_records
-- ----------------------------
CREATE INDEX `provider` ON `fl1_records`(`provider`) USING BTREE ;
CREATE INDEX `date_created` ON `fl1_records`(`date_created`) USING BTREE ;
CREATE INDEX `hash` ON `fl1_records`(`hash`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table fl2_records
-- ----------------------------
CREATE INDEX `provider` ON `fl2_records`(`provider`) USING BTREE ;
CREATE INDEX `date_created` ON `fl2_records`(`date_created`) USING BTREE ;
CREATE INDEX `hash` ON `fl2_records`(`hash`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table fl2_records_older
-- ----------------------------
CREATE INDEX `provider` ON `fl2_records_older`(`provider`) USING BTREE ;
CREATE INDEX `date_created` ON `fl2_records_older`(`date_created`) USING BTREE ;
CREATE INDEX `hash` ON `fl2_records_older`(`hash`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table hi1_records
-- ----------------------------
CREATE INDEX `last_name_or_business` ON `hi1_records`(`last_name_or_business`) USING BTREE ;
CREATE INDEX `first_name` ON `hi1_records`(`first_name`) USING BTREE ;
CREATE INDEX `middle_initial` ON `hi1_records`(`middle_initial`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table hi1_records_older
-- ----------------------------
CREATE INDEX `last_name_or_business` ON `hi1_records_older`(`last_name_or_business`) USING BTREE ;
CREATE INDEX `first_name` ON `hi1_records_older`(`first_name`) USING BTREE ;
CREATE INDEX `middle_initial` ON `hi1_records_older`(`middle_initial`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table id1_records
-- ----------------------------
CREATE INDEX `name` ON `id1_records`(`name`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table id1_records_older
-- ----------------------------
CREATE INDEX `name` ON `id1_records_older`(`name`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table il1_records
-- ----------------------------
CREATE INDEX `hash` ON `il1_records`(`hash`) USING BTREE ;
CREATE INDEX `ProvName` ON `il1_records`(`ProvName`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table il1_records_older
-- ----------------------------
CREATE INDEX `hash` ON `il1_records_older`(`hash`) USING BTREE ;
CREATE INDEX `ProvName` ON `il1_records_older`(`ProvName`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table ma1_records
-- ----------------------------
CREATE INDEX `npi` ON `ma1_records`(`npi`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table ma1_records_older
-- ----------------------------
CREATE INDEX `npi` ON `ma1_records_older`(`npi`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table mn1_records
-- ----------------------------
CREATE INDEX `sort_name` ON `mn1_records`(`sort_name`) USING BTREE ;
CREATE INDEX `last_name` ON `mn1_records`(`last_name`) USING BTREE ;
CREATE INDEX `middle_name` ON `mn1_records`(`middle_name`) USING BTREE ;
CREATE INDEX `hash` ON `mn1_records`(`hash`) USING BTREE ;
CREATE INDEX `date_created` ON `mn1_records`(`date_created`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table mn1_records_older
-- ----------------------------
CREATE INDEX `sort_name` ON `mn1_records_older`(`sort_name`) USING BTREE ;
CREATE INDEX `last_name` ON `mn1_records_older`(`last_name`) USING BTREE ;
CREATE INDEX `middle_name` ON `mn1_records_older`(`middle_name`) USING BTREE ;
CREATE INDEX `hash` ON `mn1_records_older`(`hash`) USING BTREE ;
CREATE INDEX `date_created` ON `mn1_records_older`(`date_created`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table ne1_records
-- ----------------------------
CREATE INDEX `provider_name` ON `ne1_records`(`provider_name`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table ne1_records_older
-- ----------------------------
CREATE INDEX `provider_name` ON `ne1_records_older`(`provider_name`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table njcdr_records
-- ----------------------------
CREATE INDEX `hash` ON `njcdr_records`(`hash`) USING BTREE ;
CREATE INDEX `firm_name` ON `njcdr_records`(`firm_name`) USING BTREE ;
CREATE INDEX `name` ON `njcdr_records`(`name`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table njcdr_records_older
-- ----------------------------
CREATE INDEX `hash` ON `njcdr_records_older`(`hash`) USING BTREE ;
CREATE INDEX `firm_name` ON `njcdr_records_older`(`firm_name`) USING BTREE ;
CREATE INDEX `name` ON `njcdr_records_older`(`name`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table nv1_records
-- ----------------------------
CREATE INDEX `doing_business_as` ON `nv1_records`(`doing_business_as`) USING BTREE ;
CREATE INDEX `legal_entity` ON `nv1_records`(`legal_entity`) USING BTREE ;
CREATE INDEX `ownership_of_at_least_5_percent` ON `nv1_records`(`ownership_of_at_least_5_percent`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table nv1_records_older
-- ----------------------------
CREATE INDEX `doing_business_as` ON `nv1_records_older`(`doing_business_as`) USING BTREE ;
CREATE INDEX `legal_entity` ON `nv1_records_older`(`legal_entity`) USING BTREE ;
CREATE INDEX `ownership_of_at_least_5_percent` ON `nv1_records_older`(`ownership_of_at_least_5_percent`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table nyomig_records
-- ----------------------------
CREATE INDEX `business` ON `nyomig_records`(`business`) USING BTREE ;
CREATE INDEX `hash` ON `nyomig_records`(`hash`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table oig_records
-- ----------------------------
CREATE INDEX `lastname` ON `oig_records`(`lastname`) USING BTREE ;
CREATE INDEX `upin` ON `oig_records`(`upin`) USING BTREE ;
CREATE INDEX `hash` ON `oig_records`(`hash`) USING BTREE ;
CREATE INDEX `firstname` ON `oig_records`(`firstname`) USING BTREE ;
CREATE INDEX `midname` ON `oig_records`(`midname`) USING BTREE ;
CREATE INDEX `busname` ON `oig_records`(`busname`) USING BTREE ;
CREATE INDEX `dob` ON `oig_records`(`dob`) USING BTREE ;
CREATE INDEX `date_created` ON `oig_records`(`date_created`) USING BTREE ;
CREATE INDEX `excldate` ON `oig_records`(`excldate`) USING BTREE ;
CREATE INDEX `npi` ON `oig_records`(`npi`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table oig_records_older
-- ----------------------------
CREATE INDEX `lastname` ON `oig_records_older`(`lastname`) USING BTREE ;
CREATE INDEX `upin` ON `oig_records_older`(`upin`) USING BTREE ;
CREATE INDEX `hash` ON `oig_records_older`(`hash`) USING BTREE ;
CREATE INDEX `firstname` ON `oig_records_older`(`firstname`) USING BTREE ;
CREATE INDEX `midname` ON `oig_records_older`(`midname`) USING BTREE ;
CREATE INDEX `busname` ON `oig_records_older`(`busname`) USING BTREE ;
CREATE INDEX `dob` ON `oig_records_older`(`dob`) USING BTREE ;
CREATE INDEX `date_created` ON `oig_records_older`(`date_created`) USING BTREE ;
CREATE INDEX `excldate` ON `oig_records_older`(`excldate`) USING BTREE ;
CREATE INDEX `npi` ON `oig_records_older`(`npi`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table pa1_records
-- ----------------------------
CREATE INDEX `hash` ON `pa1_records`(`hash`) USING BTREE ;
CREATE INDEX `ProviderName` ON `pa1_records`(`ProviderName`) USING BTREE ;
CREATE INDEX `NAM_LAST_PROVR` ON `pa1_records`(`NAM_LAST_PROVR`) USING BTREE ;
CREATE INDEX `NAM_FIRST_PROVR` ON `pa1_records`(`NAM_FIRST_PROVR`) USING BTREE ;
CREATE INDEX `NAM_MIDDLE_PROVR` ON `pa1_records`(`NAM_MIDDLE_PROVR`) USING BTREE ;
CREATE INDEX `NAM_BUSNS_MP` ON `pa1_records`(`NAM_BUSNS_MP`) USING BTREE ;
CREATE INDEX `NAM_PROVR_ALT` ON `pa1_records`(`NAM_PROVR_ALT`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table pa1_records_older
-- ----------------------------
CREATE INDEX `hash` ON `pa1_records_older`(`hash`) USING BTREE ;
CREATE INDEX `ProviderName` ON `pa1_records_older`(`ProviderName`) USING BTREE ;
CREATE INDEX `NAM_LAST_PROVR` ON `pa1_records_older`(`NAM_LAST_PROVR`) USING BTREE ;
CREATE INDEX `NAM_FIRST_PROVR` ON `pa1_records_older`(`NAM_FIRST_PROVR`) USING BTREE ;
CREATE INDEX `NAM_MIDDLE_PROVR` ON `pa1_records_older`(`NAM_MIDDLE_PROVR`) USING BTREE ;
CREATE INDEX `NAM_BUSNS_MP` ON `pa1_records_older`(`NAM_BUSNS_MP`) USING BTREE ;
CREATE INDEX `NAM_PROVR_ALT` ON `pa1_records_older`(`NAM_PROVR_ALT`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sam_records
-- ----------------------------
CREATE INDEX `Name` ON `sam_records`(`Name`) USING BTREE ;
CREATE INDEX `First` ON `sam_records`(`First`) USING BTREE ;
CREATE INDEX `Middle` ON `sam_records`(`Middle`) USING BTREE ;
CREATE INDEX `Last` ON `sam_records`(`Last`) USING BTREE ;
CREATE INDEX `Active_Date` ON `sam_records`(`Active_Date`) USING BTREE ;
CREATE INDEX `Excluding_Agency` ON `sam_records`(`Excluding_Agency`) USING BTREE ;
CREATE INDEX `date_created` ON `sam_records`(`date_created`) USING BTREE ;
CREATE INDEX `City` ON `sam_records`(`City`) USING BTREE ;
CREATE INDEX `State` ON `sam_records`(`State`) USING BTREE ;
CREATE INDEX `Country` ON `sam_records`(`Country`) USING BTREE ;
CREATE INDEX `Zip` ON `sam_records`(`Zip`) USING BTREE ;
CREATE INDEX `hash` ON `sam_records`(`hash`) USING BTREE ;
CREATE INDEX `new_hash` ON `sam_records`(`new_hash`) USING BTREE ;
CREATE INDEX `matching_oig_hash` ON `sam_records`(`matching_OIG_hash`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sam_records_temp
-- ----------------------------
CREATE INDEX `Name` ON `sam_records_temp`(`Name`) USING BTREE ;
CREATE INDEX `First` ON `sam_records_temp`(`First`) USING BTREE ;
CREATE INDEX `Middle` ON `sam_records_temp`(`Middle`) USING BTREE ;
CREATE INDEX `Last` ON `sam_records_temp`(`Last`) USING BTREE ;
CREATE INDEX `Active_Date` ON `sam_records_temp`(`Active_Date`) USING BTREE ;
CREATE INDEX `Excluding_Agency` ON `sam_records_temp`(`Excluding_Agency`) USING BTREE ;
CREATE INDEX `date_created` ON `sam_records_temp`(`date_created`) USING BTREE ;
CREATE INDEX `City` ON `sam_records_temp`(`City`) USING BTREE ;
CREATE INDEX `State` ON `sam_records_temp`(`State`) USING BTREE ;
CREATE INDEX `Country` ON `sam_records_temp`(`Country`) USING BTREE ;
CREATE INDEX `Zip` ON `sam_records_temp`(`Zip`) USING BTREE ;
CREATE INDEX `hash` ON `sam_records_temp`(`hash`) USING BTREE ;
CREATE INDEX `new_hash` ON `sam_records_temp`(`new_hash`) USING BTREE ;
CREATE INDEX `matching_oig_hash` ON `sam_records_temp`(`matching_OIG_hash`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sc1_records
-- ----------------------------
CREATE INDEX `hash` ON `sc1_records`(`hash`) USING BTREE ;
CREATE INDEX `entity` ON `sc1_records`(`entity`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sc1_records_older
-- ----------------------------
CREATE INDEX `hash` ON `sc1_records_older`(`hash`) USING BTREE ;
CREATE INDEX `entity` ON `sc1_records_older`(`entity`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_address_list
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_address_list`(`uid`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_address_list`(`sdn_entry_id`, `uid`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_address_list_older
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_address_list_older`(`uid`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_address_list_older`(`sdn_entry_id`, `uid`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_aka_list
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_aka_list`(`uid`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_aka_list`(`sdn_entry_id`) USING BTREE ;
CREATE FULLTEXT INDEX `firstName` ON `sdn_aka_list`(`firstName`) ;
CREATE FULLTEXT INDEX `lastName` ON `sdn_aka_list`(`lastName`) ;

-- ----------------------------
-- Indexes structure for table sdn_aka_list_older
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_aka_list_older`(`uid`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_aka_list_older`(`sdn_entry_id`) USING BTREE ;
CREATE FULLTEXT INDEX `firstName` ON `sdn_aka_list_older`(`firstName`) ;
CREATE FULLTEXT INDEX `lastName` ON `sdn_aka_list_older`(`lastName`) ;

-- ----------------------------
-- Indexes structure for table sdn_citizenship_list
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_citizenship_list`(`uid`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_citizenship_list`(`sdn_entry_id`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_citizenship_list_older
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_citizenship_list_older`(`uid`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_citizenship_list_older`(`sdn_entry_id`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_date_of_birth_list
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_date_of_birth_list`(`uid`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_date_of_birth_list`(`sdn_entry_id`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_date_of_birth_list_older
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_date_of_birth_list_older`(`uid`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_date_of_birth_list_older`(`sdn_entry_id`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_entries
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_entries`(`uid`) USING BTREE ;
CREATE FULLTEXT INDEX `firstName` ON `sdn_entries`(`firstName`) ;
CREATE FULLTEXT INDEX `lastName` ON `sdn_entries`(`lastName`) ;

-- ----------------------------
-- Indexes structure for table sdn_entries_older
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_entries_older`(`uid`) USING BTREE ;
CREATE FULLTEXT INDEX `firstName` ON `sdn_entries_older`(`firstName`) ;
CREATE FULLTEXT INDEX `lastName` ON `sdn_entries_older`(`lastName`) ;

-- ----------------------------
-- Indexes structure for table sdn_id_list
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_id_list`(`uid`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_id_list`(`sdn_entry_id`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_id_list_older
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_id_list_older`(`uid`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_id_list_older`(`sdn_entry_id`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_nationality_list
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_nationality_list`(`uid`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_nationality_list`(`sdn_entry_id`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_nationality_list_older
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_nationality_list_older`(`uid`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_nationality_list_older`(`sdn_entry_id`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_place_of_birth_list
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_place_of_birth_list`(`uid`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_place_of_birth_list`(`sdn_entry_id`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_place_of_birth_list_older
-- ----------------------------
CREATE UNIQUE INDEX `uid` ON `sdn_place_of_birth_list_older`(`uid`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_place_of_birth_list_older`(`sdn_entry_id`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_program_list
-- ----------------------------
CREATE UNIQUE INDEX `program` ON `sdn_program_list`(`sdn_entry_id`, `program`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_program_list`(`sdn_entry_id`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_program_list_older
-- ----------------------------
CREATE UNIQUE INDEX `program` ON `sdn_program_list_older`(`sdn_entry_id`, `program`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_program_list_older`(`sdn_entry_id`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_vessel_info
-- ----------------------------
CREATE UNIQUE INDEX `vessel` ON `sdn_vessel_info`(`sdn_entry_id`, `callSign`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_vessel_info`(`sdn_entry_id`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table sdn_vessel_info_older
-- ----------------------------
CREATE UNIQUE INDEX `vessel` ON `sdn_vessel_info_older`(`sdn_entry_id`, `callSign`) USING BTREE ;
CREATE INDEX `sdn_entry_id` ON `sdn_vessel_info_older`(`sdn_entry_id`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table tn1_records
-- ----------------------------
CREATE INDEX `hash` ON `tn1_records`(`hash`) USING BTREE ;
CREATE INDEX `last` ON `tn1_records`(`last_name`) USING BTREE ;
CREATE INDEX `first` ON `tn1_records`(`first_name`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table tn1_records_older
-- ----------------------------
CREATE INDEX `hash` ON `tn1_records_older`(`hash`) USING BTREE ;
CREATE INDEX `last` ON `tn1_records_older`(`last_name`) USING BTREE ;
CREATE INDEX `first` ON `tn1_records_older`(`first_name`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table tx1_records
-- ----------------------------
CREATE INDEX `hash` ON `tx1_records`(`hash`) USING BTREE ;
CREATE INDEX `CompanyName` ON `tx1_records`(`CompanyName`) USING BTREE ;
CREATE INDEX `LastName` ON `tx1_records`(`LastName`) USING BTREE ;
CREATE INDEX `FirstName` ON `tx1_records`(`FirstName`) USING BTREE ;
CREATE INDEX `MidInitial` ON `tx1_records`(`MidInitial`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table tx1_records_older
-- ----------------------------
CREATE INDEX `hash` ON `tx1_records_older`(`hash`) USING BTREE ;
CREATE INDEX `CompanyName` ON `tx1_records_older`(`CompanyName`) USING BTREE ;
CREATE INDEX `LastName` ON `tx1_records_older`(`LastName`) USING BTREE ;
CREATE INDEX `FirstName` ON `tx1_records_older`(`FirstName`) USING BTREE ;
CREATE INDEX `MidInitial` ON `tx1_records_older`(`MidInitial`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table wy1_records
-- ----------------------------
CREATE INDEX `first_name` ON `wy1_records`(`first_name`) USING BTREE ;
CREATE INDEX `last_name` ON `wy1_records`(`last_name`) USING BTREE ;
CREATE INDEX `business_name` ON `wy1_records`(`business_name`) USING BTREE ;

-- ----------------------------
-- Indexes structure for table wy1_records_older
-- ----------------------------
CREATE INDEX `first_name` ON `wy1_records_older`(`first_name`) USING BTREE ;
CREATE INDEX `last_name` ON `wy1_records_older`(`last_name`) USING BTREE ;
CREATE INDEX `business_name` ON `wy1_records_older`(`business_name`) USING BTREE ;

# exclusion_lists table
# ------------------------------------------------------------

DROP TABLE IF EXISTS `exclusion_lists`;

CREATE TABLE `exclusion_lists` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`prefix` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`accr` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`import_url` varchar(500) DEFAULT NULL,
`is_auto_import` int(10) UNSIGNED NULL DEFAULT NULL,
`is_active` int(10) UNSIGNED NULL DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `exclusion_lists` (`id`, `prefix`, `accr`, `description`, `import_url`, `is_auto_import`, `is_active`) VALUES
(1,'oig','Federal OIG','Office of the Inspector General',NULL,0,0),
(2,'nyomig','NY OMIG','New York Office of the Medical Inspector General',NULL,1,0),
(3,'njcdr','NJ DCR','New Jersey Division of Civil Rights',NULL,0,0),
(4,'pa1','PA Medicheck','Pennslyvania Department of Public Welfare',NULL,0,0),
(5,'oh1','OH Medicaid','Ohio Department of Job and Family Services',NULL,0,0),
(6,'il1','IL OIG','Illinois Department of human Services, Office of the Inspector General',NULL,0,0),
(7,'ky1','KY CHFS','Kentucky Cabinet for Health and Family Services',NULL,0,0),
(8,'ma1','MA HHS','Massachusetts Health and Human Services',NULL,0,0),
(9,'sc1','SC HHS','South Carolina Department Health and Human Services',NULL,0,0),
(10,'tx1','TX OIG','Texas Office of the Inspector General',NULL,0,0),
(11,'sam','SAM.gov','System for Award Management',NULL,0,0),
(12,'ct1','CT DSS','Connecticut Department of Social Services',NULL,0,0),
(13,'ar1','AR DHS','Arkansas Department of human Services',NULL,0,0),
(14,'fl2','FL AHCA','Florida Agency for Health Care Administration',NULL,0,0),
(15,'mi1','MI MDCH','Michigan Department of Community Health',NULL,0,0),
(16,'al1','AL Medicaid Agency','Alabam Medicaid Agency',NULL,0,0),
(17,'wv2','WV MMS','West Virginia Medicaid Program',NULL,0,0),
(18,'md1','MD DHMH','Maryland Department of Health &amp; Mental Hygiene',NULL,0,0),
(19,'id1','ID DHW','Idaho Department of Health &amp; Welfare',NULL,0,0),
(20,'ne1','NE DHHS','Nebrask Department of Health &amp; Humna Services',NULL,0,0),
(21,'wy1','WY DOH','Wyoming Department of health',NULL,0,0),
(22,'nv1','NV DHHS','Nevada Department of Health &amp; Humna Services',NULL,0,0),
(23,'ca1','CA Medi-Cal','California Department of Health Care Services, Medi-cal',NULL,0,0),
(24,'hi1','Quest Hawaii','Department of Human Services Med Quest Division',NULL,0,0),
(25,'az1','AZ AHCCCS','Arizona Office of the Inspector General',NULL,0,0),
(26,'tn1','TennCare','Tennesee State Medicaid Program',NULL,0,0),
(27,'mn1','Minnesota MHCP','Minnesota Department of Human Resources',NULL,0,0),
(28,'os1','OFAC SDN','Specially Designated Nationals',NULL,0,0),
(29,'ak1','AK DHSS','Alaska Department of Health and Social Services',NULL,0,0),
(30,'ms1','MS DOM','Mississppi Division of Medicaid',NULL,0,0),
(31,'nc1','NC DMA','North Carolina Division of Medical Assistance',NULL,0,0),
(32,'mo1','MMAC','Missouri Department of Social Services',NULL,0,0),
(33,'wa1','WS HCA','Washington State Health Care Authority',NULL,0,0),
(34,'ks1','KS DHE','Kansas Department of Health and Environment',NULL,0,0),
(35,'la1','LA DHH','Louisiana Department of Health & Hospitals',NULL,0,0),
(36,'nd1','ND DHS','ND Dept of Human Services',NULL,0,0),
(37,'mt1','Montana DPHHS','Montana Department of Public Health and Human services',NULL,0,0),
(38,'dc1','DC OCP','DC Office of Contracting and Procurement',NULL,0,0),
(39,'ia1','IA DHS','Iowa Department of Human Services',NULL,0,0),
(40,'ga1','GA DCH','Georgia Department of Community Health',NULL,0,0),
(41,'me1','ME DHHS','Maine Department of Health and Human Services',NULL,0,0),
(42,'fdac','FDA CI','FDA Clinical Investigators',NULL,0,0),
(43,'phs','NHH PHS','NHH PHS',NULL,0,0),
(44,'usdocdp','UDoC DP','US Department of Commerce, Bureau of Industry and Security, Denied Persons',NULL,0,0),
(45,'fdadl','FDA DL','FDA Debarment List (Drug Product Applications)',NULL,0,0),
(46,'cus_spectrum_debar','CUS SD','Custom Debar List',NULL,0,0),
(47,'usdosd','US Dos DL','US Department of State Debar List','https://www.pmddtc.state.gov/compliance/documents/debar.xlsx',0,0);

# unsancindividuals_records table
# ------------------------------------------------------------


CREATE TABLE `unsancindividuals_records` (
`id` INT NOT NULL AUTO_INCREMENT,
`dataid` VARCHAR(100) NULL,
`first_name` VARCHAR(100) NULL,
`second_name` VARCHAR(100) NULL,
`third_name` VARCHAR(100) NULL,
`fourth_name` VARCHAR(100) NULL,
`un_list_type` VARCHAR(45) NULL,
`reference_number` VARCHAR(45) NULL,
`listed_on` DATE NULL,
`nationality` VARCHAR(255) NULL,
`nationality2` VARCHAR(45) NULL,
`designation` TEXT NULL,
`last_updated` DATE NULL,
`alias` TEXT NULL,
`address` TEXT NULL,
`date_of_birth` VARCHAR(45) NULL,
`place_of_birth` VARCHAR(45) NULL,
`comments` TEXT NULL,
`hash` BINARY(16) NULL,
`date_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
`date_updated` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)  ENGINE=INNODB DEFAULT CHARACTER SET=UTF8 COLLATE = UTF8_UNICODE_CI;

# unsancentities_records table
# ------------------------------------------------------------

CREATE TABLE `unsancentities_records` (
`id` INT NOT NULL AUTO_INCREMENT,
`dataid` VARCHAR(100) NULL,
`entity_name` VARCHAR(255) NULL,
`un_list_type` VARCHAR(45) NULL,
`reference_number` VARCHAR(45) NULL,
`listed_on` DATE NULL,
`submitted_on` DATE NULL,
`comments` TEXT NULL,
`entity_alias` TEXT NULL,
`entity_address` TEXT NULL,
`last_updated` DATE NULL,
`hash` BINARY(16) NULL,
`date_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
`date_updated` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)  ENGINE=INNODB DEFAULT CHARACTER SET=UTF8 COLLATE = UTF8_UNICODE_CI;

INSERT INTO exclusion_lists
(`id`, `prefix`, `accr`, `description`, `import_url`, `is_auto_import`, `is_active`)
VALUES (48, 'unsancindividuals',    'UN Sanc Individuals',  'UN Sanctions Individuals List',    'https://www.un.org/sc/suborg/sites/www.un.org.sc.suborg/files/consolidated.xml',   0,  0);


INSERT INTO exclusion_lists
(`id`, `prefix`, `accr`, `description`, `import_url`, `is_auto_import`, `is_active`)
VALUES (49, 'unsancentities',   'UN Sanc Entities', 'UN Sanctions Entities List',   'https://www.un.org/sc/suborg/sites/www.un.org.sc.suborg/files/consolidated.xml',   0,  0);

CREATE TABLE `fdac_records` (
`id`                  INT          NOT NULL AUTO_INCREMENT,
`name` VARCHAR(100) NULL,
`center` VARCHAR(50) NULL,
`city` VARCHAR(50) NULL,
`state` VARCHAR(50) NULL,
`status` VARCHAR(100) NULL,
`date_of_status` DATE NULL,
`date_of_nidpoe_issued` DATE NULL,
`date_of_nooh_issued` DATE NULL,
`hash` BINARY(16)   NULL,
`date_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE = INNODB
DEFAULT CHARACTER SET = UTF8
COLLATE = UTF8_UNICODE_CI;

CREATE TABLE `fdadl_records` (
`id` INT NOT NULL AUTO_INCREMENT,
`name` VARCHAR(100) NULL,
`aka` VARCHAR(100) NULL,
`effective_date` DATE NULL,
`term_of_debarment` VARCHAR(100) NULL,
`from_date` DATE NULL,
`hash` BINARY(16)   NULL,
`date_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE = INNODB
DEFAULT CHARACTER SET = UTF8
COLLATE = UTF8_UNICODE_CI;

CREATE TABLE `phs_records` (
`id`                  INT          NOT NULL AUTO_INCREMENT,
`last_name` VARCHAR(100) NULL,
`first_name` VARCHAR(100) NULL,
`middle_name` VARCHAR(100) NULL,
`state` VARCHAR(50) NULL,
`status` VARCHAR(100) NULL,
`debarment_until` DATE NULL,
`no_phs_advisory_until` DATE NULL,
`certification_of_work_until` DATE NULL,
`supervision_until` DATE NULL,
`memo` VARCHAR(1000) NULL,
`hash` BINARY(16)   NULL,
`date_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)
ENGINE = INNODB
DEFAULT CHARACTER SET = UTF8
COLLATE = UTF8_UNICODE_CI;

CREATE TABLE `healthmil_records` (
`id` INT NOT NULL AUTO_INCREMENT,
`date_excluded` DATE NULL,
`term` VARCHAR(100) NULL,
`exclusion_end_date` DATE NULL,
`companies` VARCHAR(100) NULL,
`first_name` VARCHAR(50) NULL,
`middle_name` VARCHAR(50) NULL,
`last_name` VARCHAR(50) NULL,
`addresses` VARCHAR(1000) NULL,
`summary` TEXT NULL,
`hash` BINARY(16) NULL,
`date_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)  ENGINE=INNODB DEFAULT CHARACTER SET=UTF8 COLLATE = UTF8_UNICODE_CI;

INSERT INTO exclusion_lists
(`id`, `prefix`, `accr`, `description`, `import_url`, `is_auto_import`, `is_active`)
VALUES (50,   'healthmil',    'Health Mil',   'Military Health System',   '', 0,  0);

ALTER TABLE `tx1_records`
CHANGE COLUMN `CompanyName` `company_name` VARCHAR(255) NOT NULL ,
CHANGE COLUMN `LastName` `last_name` VARCHAR(255) NOT NULL ,
CHANGE COLUMN `FirstName` `first_name` VARCHAR(255) NOT NULL ,
CHANGE COLUMN MidInitial `mid_initial` VARCHAR(255)  NOT NULL ,
CHANGE COLUMN `LicenseNumber` `license_number` VARCHAR(255)  NOT NULL ,
CHANGE COLUMN `StartDate` `start_date` DATE NULL ,
CHANGE COLUMN `AddDate` `add_date` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `ReinstatedDate` `reinstated_date` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `WebComments` `web_comments` VARCHAR(255)  NOT NULL,
ADD COLUMN `occupation` VARCHAR(45) NOT NULL AFTER `mid_initial`,
ADD COLUMN `npi` VARCHAR(10) NULL DEFAULT NULL AFTER `License_number`,
ENGINE = INNODB DEFAULT CHARACTER SET=UTF8 COLLATE = UTF8_UNICODE_CI;

ALTER TABLE `mi1_records`
ADD COLUMN `sanction_source_1` VARCHAR(255) CHARACTER SET 'utf8' NULL DEFAULT NULL AFTER `sanction_date_1`,
ADD COLUMN `sanction_source_2` VARCHAR(255) CHARACTER SET 'utf8' NULL DEFAULT NULL AFTER `sanction_date_2`,
CHANGE COLUMN `sanction_date_1` `sanction_date_1` DATE NULL DEFAULT NULL,
CHANGE COLUMN `sanction_date_2` `sanction_date_2` DATE NULL DEFAULT NULL;
