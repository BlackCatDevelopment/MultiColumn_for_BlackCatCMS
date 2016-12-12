-- --------------------------------------------------------
-- Please note:
-- The table prefix (cat_) will be replaced by the
-- installer! Do NOT use this file to create the tables
-- manually! (Or patch it to fit your needs first.)
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;


DROP TABLE IF EXISTS `mod_multicolumn`;
DROP TABLE IF EXISTS `mod_multicolumn_contents`;
DROP TABLE IF EXISTS `mod_multicolumn_options`;
DROP TABLE IF EXISTS `mod_multicolumn_content_options`;

CREATE TABLE IF NOT EXISTS `cat_addons` (
  `addon_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(128) NOT NULL DEFAULT '',
  `directory` varchar(128) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NULL,
  `function` varchar(255) NOT NULL DEFAULT '',
  `version` varchar(255) NOT NULL DEFAULT '',
  `guid` varchar(50) NOT NULL DEFAULT '',
  `platform` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(255) NOT NULL DEFAULT '',
  `license` varchar(255) NOT NULL DEFAULT '',
  `installed` VARCHAR(255) NOT NULL DEFAULT '',
  `upgraded` VARCHAR(255) NOT NULL DEFAULT '',
  `removable` ENUM('Y','N') NOT NULL DEFAULT 'Y',
  `bundled` ENUM('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`addon_id`),
  UNIQUE INDEX `type_directory` (`type`,`directory`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `:prefix:mod_multicolumn` (
  `mc_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL DEFAULT '',
  `section_id` int(11) NOT NULL DEFAULT '',
  PRIMARY KEY (`mc_id`),
  CONSTRAINT `mc_pages` FOREIGN KEY (`page_id`) REFERENCES `:prefix:pages`(`page_id`) ON DELETE CASCADE,
  CONSTRAINT `mc_sections` FOREIGN KEY (`section_id`) REFERENCES `:prefix:sections`(`section_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `:prefix:mod_multicolumn_contents` (
  `column_id` int(11) NOT NULL AUTO_INCREMENT,
  `mc_id` int(11) NOT NULL DEFAULT '',
  `content` text NULL,
  `text` text NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`column_id`),
  CONSTRAINT `content_mcID` FOREIGN KEY (`mc_id`) REFERENCES `:prefix:mod_multicolumn`(`mc_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `:prefix:mod_multicolumn_options` (
  `mc_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(2047) NOT NULL DEFAULT '',
  `text` text NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`mc_id`, `name`),
  CONSTRAINT `options_mcID` FOREIGN KEY (`mc_id`) REFERENCES `:prefix:mod_multicolumn`(`mc_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `:prefix:mod_multicolumn_content_options` (
  `column_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(2047) NOT NULL DEFAULT '',
  `text` text NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`column_id`, `name`),
  CONSTRAINT `optContent_mcID` FOREIGN KEY (`column_id`) REFERENCES `:prefix:mod_multicolumn_contents`(`column_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*
	$insert_search = CAT_Helper_Page::getInstance()->db()->query( sprintf(
			"SELECT * FROM `%ssearch`
				WHERE `value` = '%s'",
			CAT_TABLE_PREFIX,
			'cc_multicolumn'
		)
	);
	if( $insert_search->numRows() == 0 )
	{
		// Insert info into the search table
		// Module query info
		$field_info = array(
			'page_id'			=> 'page_id',
			'title'				=> 'page_title',
			'link'				=> 'link',
			'description'		=> 'description',
			'modified_when'		=> 'modified_when',
			'modified_by'		=> 'modified_by'
		);

		$field_info = serialize($field_info);

		CAT_Helper_Page::getInstance()->db()->query( sprintf(
				"INSERT INTO `%ssearch`
					( `name`, `value`, `extra` ) VALUES
					( 'module', 'cc_multicolumn', '%s' )",
				CAT_TABLE_PREFIX,
				$field_info
			)
		);
		// Query start
		$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title, [TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by FROM [TP]mod_multicolumn_contents, [TP]pages WHERE ";

		CAT_Helper_Page::getInstance()->db()->query( sprintf(
				"INSERT INTO `%ssearch`
					( `name`, `value`, `extra` ) VALUES
					( 'query_start', '%s', '%s' )",
				CAT_TABLE_PREFIX,
				$query_start_code,
				'cc_multicolumn'
			)
		);
		// Query body
		$query_body_code = " [TP]pages.page_id = [TP]mod_multicolumn_contents.page_id AND [TP]mod_multicolumn_contents.text [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'";

		CAT_Helper_Page::getInstance()->db()->query( sprintf(
				"INSERT INTO `%ssearch`
					( `name`, `value`, `extra` ) VALUES
					( 'query_body', '%s', '%s' )",
				CAT_TABLE_PREFIX,
				$query_body_code,
				'mod_multicolumn_contents'
			)
		);

		// Query end
		$query_end_code = "";
		CAT_Helper_Page::getInstance()->db()->query( sprintf(
				"INSERT INTO `%ssearch`
					( `name`, `value`, `extra` ) VALUES
					( 'query_end', '%s', '%s' )",
				CAT_TABLE_PREFIX,
				$query_end_code,
				'mod_multicolumn_contents'
			)
		);


		// Insert blank row (there needs to be at least on row for the search to work)
		CAT_Helper_Page::getInstance()->db()->query( sprintf(
				"INSERT INTO `%smod_multicolumn_contents`
					( `page_id`, `section_id`, `content`, `text` ) VALUES
					( '0', '0', '', '' )",
				CAT_TABLE_PREFIX
			)
		);
	}
	// add files to class_secure
	$addons_helper = new CAT_Helper_Addons();
	foreach(
		array(
			'save.php'
		)
		as $file
	) {
		if ( false === $addons_helper->sec_register_file( 'cc_multicolumn', $file ) )
		{
			 error_log( "Unable to register file -$file-!" );
		}
	}
*/