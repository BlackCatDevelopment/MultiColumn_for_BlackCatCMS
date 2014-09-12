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

// include class.secure.php to protect this file and the whole CMS!
if (defined('CAT_PATH')) {
	include(CAT_PATH.'/framework/class.secure.php');
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
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


$parser_data['columns']		= $MulCol->getContents( true );


$parser_data['mc_id']		= $MulCol->getID();
$parser_data['options']		= $MulCol->getOptions();

if ( file_exists( CAT_PATH . $module_path .'view/' . $variant . '/view.php' ) )
	include( CAT_PATH . $module_path .'view/' . $variant . '/view.php' );
elseif ( file_exists( CAT_PATH . $module_path .'view/default/view.php' ) )
	include( CAT_PATH . $module_path .'view/default/view.php' );

$parser->setPath( dirname(__FILE__) . '/templates/' . $MulCol->getVariant() );
$parser->setFallbackPath( dirname( __FILE__ ) . '/templates/default' );


$parser->output(
	$template,
	$parser_data
);


?>
