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

$PageHelper = CAT_Helper_Page::getInstance();

require_once "inc/class.multicolumn.php";

$MulCol = new MultiColumn();

$parser_data = [
    "CAT_URL" => CAT_URL,
    "CAT_PATH" => CAT_PATH,
    "CAT_ADMIN_URL" => CAT_ADMIN_URL,
    "page_id" => $page_id,
    "section_id" => $section_id,
    "version" => CAT_Helper_Addons::getModuleVersion("cc_multicolumn"),
    "mc_id" => $MulCol->getID(),
    "variant" => $MulCol->getVariant(),
    "columns" => $MulCol->getContents(true, false),
    "options" => $MulCol->getOptions(),
    "module_variants" => $MulCol->getAllVariants(),
];

// ===============================
// ! Get columns in this section
// ===============================

$parser_data["WYSIWYG"] = [
    "width" => "100%",
    "height" => "300px",
    "name" => "content_" . $MulCol->getID(),
];

$module_path = "/modules/cc_multicolumn/";

if (
    file_exists(
        CAT_PATH .
            $module_path .
            "modify/" .
            $MulCol->getVariant() .
            "/modify.php"
    )
) {
    include CAT_PATH .
        $module_path .
        "modify/" .
        $MulCol->getVariant() .
        "/modify.php";
} elseif (file_exists(CAT_PATH . $module_path . "modify/default/modify.php")) {
    include CAT_PATH . $module_path . "modify/default/modify.php";
}

if (
    file_exists(
        CAT_PATH .
            $module_path .
            "templates/" .
            $MulCol->getVariant() .
            "/modify.tpl"
    )
) {
    $parser->setPath(dirname(__FILE__) . "/templates/" . $MulCol->getVariant());
} elseif (
    file_exists(CAT_PATH . $module_path . "templates/default/modify.tpl")
) {
    $parser->setPath(dirname(__FILE__) . "/templates/default/");
}

$parser->setFallbackPath(dirname(__FILE__) . "/templates/default");

$parser->output("modify", $parser_data);

?>
