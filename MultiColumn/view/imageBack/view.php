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

// Get columns in this section

include_once CAT_PATH . '/modules/lib_mdetect/mdetect/mdetect.php';

if ( class_exists('uagent_info'))
{
	$uagent_obj = new uagent_info();
	
	if( $uagent_obj->DetectMobileQuick() )
	{
		foreach( $parser_data['columns'] as $index => $column )
		{
			if ( isset($column['options']['image']) )
			{
				$image	= explode( '.', $column['options']['image'] );
				$image[count($image)-2]	= $image[count($image)-2] . '_mobile';
				$newIMG	= implode( '.', $image);
				if ( file_exists( CAT_PATH . '/media/images/' . $newIMG ) )
					$parser_data['columns'][$index]['options']['image']	= $newIMG;
			}
		}
		$parser_data['options']['is_mobile']	= true;
	}
}

?>
