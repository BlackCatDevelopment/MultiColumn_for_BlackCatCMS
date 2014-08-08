<?php
/**
 * This file is part of an ADDON for use with Black Cat CMS Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module			cc_multicolumn
 * @version			see info.php of this module
 * @author			Matthias Glienke, creativecat
 * @copyright		2014, Black Cat Development
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

global $section_id, $page_id;

$parser_data	= array(
	'page_id'		=> $page_id,
	'section_id'	=> $section_id
);


include_once( 'class.multicolumn.php' );

$MulCol		= new MultiColumn();

$variant		= $MulCol->getVariant();
$module_path	= '/modules/cc_multicolumn/';
$template		= 'view';


if ( file_exists( CAT_PATH . $module_path .'view/' . $variant . '/view.php' ) )
	include( CAT_PATH . $module_path .'view/' . $variant . '/view.php' );
elseif ( file_exists( CAT_PATH . $module_path .'view/default/view.php' ) )
	include( CAT_PATH . $module_path .'view/default/view.php' );

$parser->setPath( dirname(__FILE__) . '/templates/' . $MulCol->getVariant() );

$parser->output(
	$template,
	$parser_data
);


?>
