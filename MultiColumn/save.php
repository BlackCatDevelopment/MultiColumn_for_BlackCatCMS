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

if (defined('CAT_PATH')) {	
	if (defined('CAT_VERSION')) include(CAT_PATH.'/framework/class.secure.php');
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php')) {
	include($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php');
} else {
	$subs = explode('/', dirname($_SERVER['SCRIPT_NAME']));	$dir = $_SERVER['DOCUMENT_ROOT'];
	$inc = false;
	foreach ($subs as $sub) {
		if (empty($sub)) continue; $dir .= '/'.$sub;
		if (file_exists($dir.'/framework/class.secure.php')) {
			include($dir.'/framework/class.secure.php'); $inc = true;	break;
	}
	}
	if (!$inc) trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}

$val		= CAT_Helper_Validate::getInstance();
$backend	= CAT_Backend::getInstance('Pages', 'pages_modify');

// ==============================
// ! Get page id and section_id
// ==============================
$page_id	= $val->sanitizePost('page_id','numeric');
$section_id	= $val->sanitizePost('section_id','numeric');

include_once( 'class.multicolumn.php' );

$MulCol	= new MultiColumn();

// =============
// ! Get perms
// =============
if ( CAT_Helper_Page::getPagePermission( $page_id, 'admin' ) !== true )
{
	$backend->print_error( 'You do not have permissions to modify this page!' );
}

$variant		= $MulCol->getVariant();
$module_path	= '/modules/cc_multicolumn/';

if ( file_exists( CAT_PATH . $module_path .'save/' . $variant . '/save.php' ) )
	include_once( CAT_PATH . $module_path .'save/' . $variant . '/save.php' );
elseif ( file_exists( CAT_PATH . $module_path .'save/default/save.php' ) )
	include_once( CAT_PATH . $module_path .'save/default/save.php' );


// ====================== 
// ! Print admin footer   
// ====================== 
$backend->print_footer();

?>
