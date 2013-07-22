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

$PageHelper	= CAT_Helper_Page::getInstance();

$parser_data	= array(
	'CAT_URL'				=> CAT_URL,
	'CAT_PATH'				=> CAT_PATH,
	'CAT_ADMIN_URL'			=> CAT_ADMIN_URL,
	'page_id'				=> $page_id,
	'section_id'			=> $section_id,
	'version'				=> CAT_Helper_Addons::getModuleVersion('cc_multicolumn')
);

// =============================== 
// ! Get columns in this section   
// =============================== 

$result		= $PageHelper->db()->query("SELECT column_id, kind, equalize FROM " . CAT_TABLE_PREFIX . "mod_cc_multicolumn WHERE section_id = '$section_id'");
if ( isset($result) && $result->numRows() > 0)
{
	while( !false == ($row = $result->fetchRow( MYSQL_ASSOC ) ) )
	{
		$parser_data['column_id']	= $row['column_id'];
		$parser_data['kind']		= $row['kind'];
		$parser_data['equalize']	= $row['equalize'];
	}
}

$contents	= $PageHelper->db()->query("SELECT content, id FROM " . CAT_TABLE_PREFIX . "mod_cc_multicolumn_contents 
					WHERE column_id = '" . $parser_data['column_id'] . "' ORDER BY id");

$counter = 0;
if ( isset($contents) && $contents->numRows() > 0)
{
	while( !false == ($row = $contents->fetchRow( MYSQL_ASSOC ) ) )
	{
		$parser_data['columns'][$counter]['content']		= htmlspecialchars($row['content']);
		$parser_data['columns'][$counter]['id']			= $row['id'];
		$parser_data['columns'][$counter]['contentname']	= 'content_' . $section_id . '_' . $row['id'];
		$counter++;
	}
}

$parser_data['WYSIWYG']		= array(
	'width'		=> '100%',
	'height'	=> '300px'
);

$parser->setPath( dirname(__FILE__) . '/templates/default' );

$parser->output(
	'modify',
	$parser_data
);

?>