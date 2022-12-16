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

$module_directory = "cc_multicolumn";
$module_name = "Mehrspalten Layout";
$module_function = "page";
$module_version = "2.1";
$module_platform = "1.x";
$module_author = "Matthias Glienke, letima development";
$module_license =
    '<a href="http://www.gnu.org/licenses/gpl.html">GNU General Public License</a>';
$module_description =
    'The add on "MultiColumn" provides a simple way to integrate multiple columns. You don\'t need to customize your frontend template. For details see <a href="https://github.com/BlackCatDevelopment/MultiColumn_for_BlackCatCMS" target="_blank">GitHub</a>.<br/><br/>Done by Matthias Glienke, <a class="icon-letima" href="https://letima.de"> letima development</a>';
$module_guid = "b9db6fee-da6a-4203-aac4-9c50dc866ae3";

?>
