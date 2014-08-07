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

// ============================= 
// ! Get the current mc_id   
// ============================= 
if ( $mc_id = $val->sanitizePost( 'mc_id','numeric' ) )
{
	$equalize	= $val->sanitizePost( 'equalize' ) != '' ? 1 : 0;
	$MulCol->saveOptions( 'equalize', $equalize );

	// ======================= 
	// ! Set kind of columns   
	// ======================= 
	if ( $kind = $val->sanitizePost( 'set_kind','numeric') )
	{
		$MulCol->saveOptions( 'kind', $kind );
	}

	// =========================== 
	// ! save content of columns   
	// =========================== 
	if ( $val->sanitizePost( 'save_columns') != '' )
	{
		$ids	= $val->sanitizePost( 'content_id', 'array', false );

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
		$backend->print_error($backend->get_error(), $js_back);
	}
	else
	{
		$update_when_modified = true;
	CAT_Backend::getInstance()->updateWhenModified();

		$backend->print_success('Page saved successfully', CAT_ADMIN_URL . '/pages/modify.php?page_id=' . $page_id);
	}
}
else $backend->print_error('An error occured while saving!', false);

?>