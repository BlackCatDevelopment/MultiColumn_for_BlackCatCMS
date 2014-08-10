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

// =============
// ! Get perms
// =============
if ( CAT_Helper_Page::getPagePermission( $page_id, 'admin' ) !== true )
{
	$backend->print_error( 'You do not have permissions to modify this page!' );
}

include_once( 'class.multicolumn.php' );

$MulCol	= new MultiColumn();

$variant		= $MulCol->getVariant();

$module_path	= '/modules/cc_multicolumn/';

if ( file_exists( CAT_PATH . $module_path .'save/' . $variant . '/save.php' ) )
	include_once( CAT_PATH . $module_path .'save/' . $variant . '/save.php' );
elseif ( file_exists( CAT_PATH . $module_path .'save/default/save.php' ) )
	include_once( CAT_PATH . $module_path .'save/default/save.php' );

$update_when_modified = true;
CAT_Backend::getInstance()->updateWhenModified();


// ====================== 
// ! Print success   
// ====================== 
$backend->print_success('Seite erfolgreich gespeichert', CAT_ADMIN_URL . '/pages/modify.php?page_id=' . $page_id);

?>
