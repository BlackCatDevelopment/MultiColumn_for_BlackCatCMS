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
 *   @author			Matthias Glienke, letima development
 *   @copyright			2023, Black Cat Development
 *   @link				https://blackcat-cms.org
 *   @license			https://www.gnu.org/licenses/gpl.html
 *   @category			CAT_Modules
 *   @package			multiColumn
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined("CAT_PATH")) {
    include CAT_PATH . "/framework/class.secure.php";
} else {
    $root = "../";
    $level = 1;
    while ($level < 10 && !file_exists($root . "framework/class.secure.php")) {
        $root .= "../";
        $level += 1;
    }
    if (file_exists($root . "framework/class.secure.php")) {
        include $root . "framework/class.secure.php";
    } else {
        trigger_error(
            sprintf(
                "[ <b>%s</b> ] Can't include class.secure.php!",
                $_SERVER["SCRIPT_NAME"]
            ),
            E_USER_ERROR
        );
    }
}
// end include class.secure.php

$val		= CAT_Helper_Validate::getInstance();
$lang		= CAT_Helper_I18n::getInstance();
$is_ajax	= $val->sanitizePost( '_cat_ajax','numeric' );
$backend	= $is_ajax == 1
					? CAT_Backend::getInstance('Pages', 'pages_modify', false)
					: CAT_Backend::getInstance('Pages', 'pages_modify');


$ajax_return	= array();

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

require_once "inc/class.multicolumn.php";

$MulCol	= new MultiColumn();

$variant		= $MulCol->getVariant();

$module_path	= '/modules/cc_multicolumn/';

$lang->addFile( $lang->getLang().'.php', CAT_PATH . $module_path . 'languages/' );

if ( file_exists( CAT_PATH . $module_path .'save/' . $variant . '/save.php' ) )
	include_once( CAT_PATH . $module_path .'save/' . $variant . '/save.php' );
elseif ( file_exists( CAT_PATH . $module_path .'save/default/save.php' ) )
	include_once( CAT_PATH . $module_path .'save/default/save.php' );

$update_when_modified = true;
CAT_Backend::getInstance()->updateWhenModified();

if( $is_ajax == 1 )
{
	print json_encode( $ajax_return );
	exit();
} else {
	$backend->print_success(
		$ajax_return['message'] ? $ajax_return['message'] : $lang->translate( 'Saved successfully' ),
		CAT_ADMIN_URL . '/pages/modify.php?page_id=' . $page_id
	);
	// Print admin footer
	$backend->print_footer();	
}

?>
