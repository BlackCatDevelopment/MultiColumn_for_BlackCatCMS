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
 *   @link				http://blackcat-cms.org
 *   @license			http://www.gnu.org/licenses/gpl.html
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

function checkColor(string $color): bool
{
    $hex = str_replace("#", "", $color);

    $c_r = hexdec(substr($hex, 0, 2));
    $c_g = hexdec(substr($hex, 2, 2));
    $c_b = hexdec(substr($hex, 4, 2));

    $brightness = ($c_r * 299 + $c_g * 587 + $c_b * 114) / 1000;

    return $brightness > 155 ? false : true;
}
if ($parser_data["options"]["color"]) {
    $parser_data["options"]["checkColor"] = checkColor(
        $parser_data["options"]["color"]
    );
}

?>
