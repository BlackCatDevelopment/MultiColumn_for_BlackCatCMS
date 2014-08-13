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

// ============================= 
// ! Get the current mc_id   
// ============================= 
if ( $mc_id = $val->sanitizePost( 'mc_id','numeric' ) )
{

	$options		= $val->sanitizePost('options');
	$entry_options	= $val->sanitizePost('entry_options');

	if ( $options != '' )
	{
		foreach( array_filter( explode(',', $options) ) as $option )
		{
			if( !$MulCol->saveOptions( $option, $val->sanitizePost( $option ) )) $error = true;
		}
	}

	if ( $entry_options != '' )
	{
		foreach( array_filter( explode(',', $entry_options) ) as $option )
		{
			if( !$MulCol->saveContentOptions( $option, $val->sanitizePost( $option ) )) $error = true;
		}
	}


	// =========================== 
	// ! save content of columns   
	// =========================== 
	if ( $val->sanitizePost( 'save_columns') != '' )
	{
		$ids	= $val->sanitizePost( 'content_id', 'array', false );

		if( is_array($ids) )
			foreach( $ids as $id )
			{
				$contentname	= sprintf( "content_%s_%s", $section_id, intval( $id ) );
				$content		= $val->sanitizePost( $contentname, false, true );
			
				$MulCol->saveContent( $id, $content );
			}
	}

	// ================== 
	// ! add new column   
	// ================== 
	if ( $val->sanitizePost( 'add_column') != '' )
	{
		$MulCol->addColumn(); // ADD OPTION FOR ADDING MORE THAN ONE COLUMN
	}

	// =================== 
	// ! remove a column   
	// =================== 
	elseif ( $id = $val->sanitizePost( 'remove_column','numeric') )
	{
		$MulCol->removeColumn( $id );
	}



	// ================================================================ 
	// ! Check if there is a database error, otherwise say successful   
	// ================================================================ 
	if ( $backend->is_error() )
	{
		CAT_Backend::getInstance()->print_error($backend->get_error(), $js_back);
	}
	else
	{
		$update_when_modified = true;
	CAT_Backend::getInstance()->updateWhenModified();

		CAT_Backend::getInstance()->print_success('Page saved successfully', CAT_ADMIN_URL . '/pages/modify.php?page_id=' . $page_id);
	}
}
else $backend->print_error('An error occured while saving!', false);

?>