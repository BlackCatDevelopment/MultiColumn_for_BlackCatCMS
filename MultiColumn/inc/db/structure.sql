-- --------------------------------------------------------
-- Please note:
-- The table prefix (cat_) will be replaced by the
-- installer! Do NOT use this file to create the tables
-- manually! (Or patch it to fit your needs first.)
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;


CREATE TABLE IF NOT EXISTS `:prefix:mod_cc_multicolumn` (
	`mc_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`section_id` INT(11) NOT NULL,
	PRIMARY KEY ( `mc_id` ),
	CONSTRAINT `:prefix:mC_sections` FOREIGN KEY (`section_id`) REFERENCES `:prefix:sections`(`section_id`) ON DELETE CASCADE
) COMMENT='Main table for multiColumn'
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE='utf8_general_ci';

CREATE TABLE IF NOT EXISTS `:prefix:mod_cc_multicolumn_contents` (
	`column_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`mc_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
	`content` TEXT,
	`text` TEXT,
	`published` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
	`position` INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY ( `column_id` ),
	CONSTRAINT `:prefix:content_mcID` FOREIGN KEY (`mc_id`) REFERENCES `:prefix:mod_cc_multicolumn`(`mc_id`) ON DELETE CASCADE
) COMMENT='Contents for multiColumn'
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE='utf8_general_ci';


CREATE TABLE IF NOT EXISTS `:prefix:mod_cc_multicolumn_options` (
	`mc_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
	`name` VARCHAR(255) NOT NULL DEFAULT '',
	`value` TEXT,
	`search` TEXT,
	PRIMARY KEY (`mc_id`, `name` ),
	CONSTRAINT `:prefix:options_mcID` FOREIGN KEY (`mc_id`) REFERENCES `:prefix:mod_cc_multicolumn`(`mc_id`) ON DELETE CASCADE
) COMMENT='Options for multiColumn'
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE='utf8_general_ci';


CREATE TABLE IF NOT EXISTS `:prefix:mod_cc_multicolumn_content_options`  (
	`column_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
	`name` VARCHAR(255) NOT NULL DEFAULT '',
	`value` TEXT,
	`search` TEXT,
	PRIMARY KEY ( `column_id`, `name` ),
	CONSTRAINT `:prefix:optContent_mcID` FOREIGN KEY (`column_id`) REFERENCES `:prefix:mod_cc_multicolumn_contents`(`column_id`) ON DELETE CASCADE
) COMMENT='Options for content'
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE='utf8_general_ci';

/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;