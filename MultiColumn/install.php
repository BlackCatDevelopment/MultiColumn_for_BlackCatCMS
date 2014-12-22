<?php
/**
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 3 of the License, or (at
 *   your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful, but
 *   WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 *   General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, see <http://www.gnu.org/licenses/>.
 *
 *   @author			Matthias Glienke
 *   @copyright			2014, Black Cat Development
 *   @link				http://blackcat-cms.org
 *   @license			http://www.gnu.org/licenses/gpl.html
 *   @category			CAT_Modules
 *   @package			multiColumn
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('CAT_PATH')) {
	include(CAT_PATH.'/framework/class.secure.php');
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) {
		include($root.'/framework/class.secure.php');
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

if(defined('CAT_URL'))
{
	$pageHelper	= CAT_Helper_Page::getInstance();

	// Create table for basic informations
	$pageHelper->db()->query("DROP TABLE IF EXISTS `" . CAT_TABLE_PREFIX . "mod_cc_multicolumn`");
	$mod_cc_multicolumn = 'CREATE TABLE  `'.CAT_TABLE_PREFIX.'mod_cc_multicolumn` ('
		. ' `mc_id` INT NOT NULL AUTO_INCREMENT,'
		. ' `page_id` INT NOT NULL DEFAULT \'0\','
		. ' `section_id` INT NOT NULL DEFAULT \'0\','
		. ' PRIMARY KEY ( `mc_id`, `section_id` )'
		. ' )';
	$pageHelper->db()->query($mod_cc_multicolumn);

	// Create table for contents
	$pageHelper->db()->query("DROP TABLE IF EXISTS `" . CAT_TABLE_PREFIX . "mod_cc_multicolumn_contents`");
	$mod_cc_multicolumn_contents = 'CREATE TABLE  `'.CAT_TABLE_PREFIX.'mod_cc_multicolumn_contents` ('
		. ' `column_id` INT NOT NULL AUTO_INCREMENT,'
		. ' `mc_id` INT NOT NULL DEFAULT \'0\','
		. ' `page_id` INT NOT NULL DEFAULT \'0\','
		. ' `section_id` INT NOT NULL DEFAULT \'0\','
		. ' `content` TEXT NOT NULL,'
		. ' `text` TEXT NOT NULL ,'
		. ' PRIMARY KEY ( `column_id` )'
		. ' )';
	$pageHelper->db()->query($mod_cc_multicolumn_contents);

	// Create table for options
	$pageHelper->db()->query("DROP TABLE IF EXISTS `" . CAT_TABLE_PREFIX . "mod_cc_multicolumn_options`");
	$mod_create_table = 'CREATE TABLE  `'.CAT_TABLE_PREFIX.'mod_cc_multicolumn_options` ('
		. ' `page_id` INT NOT NULL DEFAULT \'0\','
		. ' `section_id` INT NOT NULL DEFAULT \'0\','
		. ' `name` VARCHAR(255) NOT NULL DEFAULT \'\','
		. ' `value` VARCHAR(2047) NOT NULL DEFAULT \'\','
		. ' PRIMARY KEY ( `page_id`, `section_id`, `name` )'
		. ' )';
	$pageHelper->db()->query($mod_create_table);

	// Create table
	$pageHelper->db()->query("DROP TABLE IF EXISTS `" . CAT_TABLE_PREFIX . "mod_cc_multicolumn_content_options`");
	$mod_create_table = 'CREATE TABLE  `'.CAT_TABLE_PREFIX.'mod_cc_multicolumn_content_options` ('
		. ' `column_id` INT NOT NULL DEFAULT \'0\','
		. ' `page_id` INT NOT NULL DEFAULT \'0\','
		. ' `section_id` INT NOT NULL DEFAULT \'0\','
		. ' `name` VARCHAR(255) NOT NULL DEFAULT \'\','
		. ' `value` VARCHAR(2047) NOT NULL DEFAULT \'\','
		. ' PRIMARY KEY ( `column_id`, `page_id`, `section_id`, `name` )'
		. ' )';
	$pageHelper->db()->query($mod_create_table);

	$insert_search = $pageHelper->db()->query( sprintf(
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

		$pageHelper->query( sprintf(
				"INSERT INTO `%ssearch`
					( `name`, `value`, `extra` ) VALUES
					( 'module', 'cc_multicolumn', '%s' )",
				CAT_TABLE_PREFIX,
				$field_info
			)
		);
		// Query start
		$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title, [TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by FROM [TP]mod_cc_multicolumn_contents, [TP]pages WHERE ";

		$pageHelper->query( sprintf(
				"INSERT INTO `%ssearch`
					( `name`, `value`, `extra` ) VALUES
					( 'query_start', '%s', '%s' )",
				CAT_TABLE_PREFIX,
				$query_start_code,
				'cc_multicolumn'
			)
		);
		// Query body
		$query_body_code = " [TP]pages.page_id = [TP]mod_cc_multicolumn_contents.page_id AND [TP]mod_cc_multicolumn_contents.text [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'";

		$pageHelper->query( sprintf(
				"INSERT INTO `%ssearch`
					( `name`, `value`, `extra` ) VALUES
					( 'query_body', '%s', '%s' )",
				CAT_TABLE_PREFIX,
				$query_body_code,
				'mod_cc_multicolumn_contents'
			)
		);

		// Query end
		$query_end_code = "";
		$pageHelper->query( sprintf(
				"INSERT INTO `%ssearch`
					( `name`, `value`, `extra` ) VALUES
					( 'query_end', '%s', '%s' )",
				CAT_TABLE_PREFIX,
				$query_end_code,
				'mod_cc_multicolumn_contents'
			)
		);


		// Insert blank row (there needs to be at least on row for the search to work)
		$pageHelper->query( sprintf(
				"INSERT INTO `%smod_cc_multicolumn_contents`
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
}

?>