<?php
/**
 * This file is part of an ADDON for use with Black Cat CMS Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module			cc_multicolumn
 * @version			see info.php of this module
 * @author			Matthias Glienke, creativecat
 * @copyright		2013, Black Cat Development
 * @link			http://blackcat-cms.org
 * @license			http://www.gnu.org/licenses/gpl.html
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('CAT_PATH')) {	
	include(CAT_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

if(defined('CAT_URL')) {
	
	// Create table
	CAT_Helper_Page::getInstance()->db()->query("DROP TABLE IF EXISTS `" . CAT_TABLE_PREFIX . "mod_cc_multicolumn`");
	$mod_cc_multicolumn = 'CREATE TABLE  `'.CAT_TABLE_PREFIX.'mod_cc_multicolumn` ('
		. ' `column_id` INT NOT NULL AUTO_INCREMENT,'
		. ' `page_id` INT NOT NULL DEFAULT \'0\','
		. ' `section_id` INT NOT NULL DEFAULT \'0\','
		. ' `kind` SMALLINT NOT NULL DEFAULT \'0\','
		. ' `equalize` BOOLEAN NOT NULL DEFAULT \'1\','
		. ' PRIMARY KEY ( `column_id` )'
		. ' )';
	CAT_Helper_Page::getInstance()->db()->query($mod_cc_multicolumn);

	// Create table
	CAT_Helper_Page::getInstance()->db()->query("DROP TABLE IF EXISTS `" . CAT_TABLE_PREFIX . "mod_cc_multicolumn_contents`");
	$mod_cc_multicolumn_contents = 'CREATE TABLE  `'.CAT_TABLE_PREFIX.'mod_cc_multicolumn_contents` ('
		. ' `id` INT NOT NULL AUTO_INCREMENT,'
		. ' `column_id` INT NOT NULL DEFAULT \'0\','
		. ' `page_id` INT NOT NULL DEFAULT \'0\','
		. ' `section_id` INT NOT NULL DEFAULT \'0\','
		. ' `content` TEXT NOT NULL,'
		. ' `text` TEXT NOT NULL ,'
		. ' PRIMARY KEY ( `id` )'
		. ' )';
	CAT_Helper_Page::getInstance()->db()->query($mod_cc_multicolumn_contents);

	$mod_search = "SELECT * FROM " . CAT_TABLE_PREFIX . "search  WHERE value = 'cc_multicolumn'";
	$insert_search = $database->query($mod_search);
	if( $insert_search->numRows() == 0 )
	{
		// Insert info into the search table
		// Module query info
		$field_info = array();
		$field_info['page_id']			= 'page_id';
		$field_info['title']			= 'page_title';
		$field_info['link']				= 'link';
		$field_info['description']		= 'description';
		$field_info['modified_when']	= 'modified_when';
		$field_info['modified_by']		= 'modified_by';

		$field_info = serialize($field_info);

		$database->query("INSERT INTO " . CAT_TABLE_PREFIX . "search
			(name,value,extra) VALUES
			('module', 'cc_multicolumn', '$field_info')");
		// Query start
		$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title, [TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by FROM [TP]mod_cc_multicolumn_contents, [TP]pages WHERE ";
		$database->query("INSERT INTO " . CAT_TABLE_PREFIX . "search (name,value,extra) VALUES ('query_start', '$query_start_code', 'cc_multicolumn')");
		// Query body
		$query_body_code = " [TP]pages.page_id = [TP]mod_cc_multicolumn_contents.page_id AND [TP]mod_cc_multicolumn_contents.text [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'";
		$database->query("INSERT INTO " . CAT_TABLE_PREFIX . "search
			(name,value,extra) VALUES
			('query_body', '$query_body_code', 'mod_cc_multicolumn_contents')");

		// Query end
		$query_end_code = "";
		$database->query("INSERT INTO " . CAT_TABLE_PREFIX . "search
			(name,value,extra) VALUES
			('query_end', '$query_end_code', 'mod_cc_multicolumn_contents')");

		// Insert blank row (there needs to be at least on row for the search to work)
		$database->query("INSERT INTO " . CAT_TABLE_PREFIX . "mod_cc_multicolumn_contents
			(page_id,section_id, `content`, `text`) VALUES
			('0','0', '', '')");
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