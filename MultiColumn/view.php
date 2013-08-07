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

$parser_data	= array(
	'page_id' => $page_id,
	'section_id' => $section_id
);

// Get columns in this section
$result		= CAT_Helper_Page::getInstance()->db()->query("SELECT column_id, kind, equalize FROM " . CAT_TABLE_PREFIX . "mod_cc_multicolumn WHERE section_id = '$section_id'");

if ( isset($result) && $result->numRows() > 0)
{
	while( !false == ( $row = $result->fetchRow( MYSQL_ASSOC ) ) )
	{
		$parser_data['column_id']	= $row['column_id'];
		$parser_data['kind']		= $row['kind'];
		$parser_data['equalize']	= $row['equalize'];
	}
	$contents = CAT_Helper_Page::getInstance()->db()->query("SELECT content, id FROM " . CAT_TABLE_PREFIX . "mod_cc_multicolumn_contents WHERE column_id = '" . $parser_data['column_id'] . "' ORDER BY id");
	
	if ( isset($contents) && $contents->numRows() > 0)
	{
		while( !false == ($row = $contents->fetchRow( MYSQL_ASSOC ) ) )
		{
			CAT_Helper_Page::preprocess( $row['content'] );
			$parser_data['columns'][]		= array(
				'content'			=> $row['content'],
				'id'				=> $row['id']
			);
		}
	}

	$parser->setPath( dirname(__FILE__) . '/templates/default' );

	$parser->output(
		'view',
		$parser_data
	);
}

?>
