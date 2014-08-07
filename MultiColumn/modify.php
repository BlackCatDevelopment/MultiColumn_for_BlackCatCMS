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

$PageHelper	= CAT_Helper_Page::getInstance();

include_once( 'class.multicolumn.php' );

$MulCol	= new MultiColumn();

$parser_data	= array(
	'CAT_URL'				=> CAT_URL,
	'CAT_PATH'				=> CAT_PATH,
	'CAT_ADMIN_URL'			=> CAT_ADMIN_URL,
	'page_id'				=> $page_id,
	'section_id'			=> $section_id,
	'version'				=> CAT_Helper_Addons::getModuleVersion('cc_multicolumn'),
	'mc_id'					=> $MulCol->getID(),
	'variant'				=> $MulCol->getVariant(),
	'columns'				=> $MulCol->getContents( true, NULL ),
	'options'				=> $MulCol->getOptions(),
	'module_variants'		=> $MulCol->getModuleVariants()
);



// =============================== 
// ! Get columns in this section   
// =============================== 

$parser_data['WYSIWYG']		= array(
	'width'		=> '100%',
	'height'	=> '300px'
);

$module_path	= '/modules/cc_multicolumn/';

if ( file_exists( CAT_PATH . $module_path .'templates/' . $MulCol->getVariant() . '/modify.tpl' ) )
	$parser->setPath( dirname(__FILE__) . '/templates/' . $MulCol->getVariant() );
elseif ( file_exists( CAT_PATH . $module_path .'templates/default/modify.tpl' ) )
	$parser->setPath( dirname(__FILE__) . '/templates/default/' );

$parser->output(
	'modify',
	$parser_data
);

?>