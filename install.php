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
		. ' `content` TEXT NOT NULL,'
		. ' PRIMARY KEY ( `id` )'
		. ' )';
	CAT_Helper_Page::getInstance()->db()->query($mod_cc_multicolumn_contents);

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