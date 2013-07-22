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

// Insert an extra row into the database
global $database, $section_id, $page_id;

// Add a new WYSIWYG record
$database->query("INSERT INTO " . CAT_TABLE_PREFIX . "mod_cc_multicolumn
	(page_id, section_id, kind) VALUES
	('$page_id','$section_id','2')");

// Insert initial two-columns
$last_id	= $database->get_one("SELECT LAST_INSERT_ID()");

$database->query("INSERT INTO " . CAT_TABLE_PREFIX . "mod_cc_multicolumn_contents
		(column_id,page_id,section_id) VALUES
		('$last_id','$page_id','$section_id')");
$database->query("INSERT INTO " . CAT_TABLE_PREFIX . "mod_cc_multicolumn_contents
		(column_id,page_id,section_id) VALUES
		('$last_id','$page_id','$section_id')");

?>