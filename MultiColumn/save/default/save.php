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

if (CAT_Helper_Page::getPagePermission($page_id, "admin") !== true) {
    $backend->print_error("You do not have permissions to modify this page!");
}

// =============================
// ! Get the current mc_id
// =============================
if ($mc_id = $val->sanitizePost("mc_id", "numeric")) {
    $colID = $val->sanitizePost("colID", "numeric");
    $action = $val->sanitizePost("action");

    switch ($action) {
        case "addContent":
            $colCount = $val->sanitizePost("colCount");
            $added = $MulCol->addColumn($colCount);
            $ajax_return = [
                "message" =>
                    is_array($added) && count($added) > 0
                        ? $lang->translate("Column added successfully")
                        : $lang->translate("An error occoured"),
                "colIDs" => $added,
                "success" =>
                    is_array($added) && count($added) > 0 ? true : false,
            ];
            break;
        case "removeContent":
            $deleted = $MulCol->removeColumn($colID);
            $ajax_return = [
                "message" =>
                    $deleted === true
                        ? $lang->translate("Column deleted successfully")
                        : $lang->translate("An error occoured"),
                "success" => $deleted,
            ];
            break;
        case "saveColumn":
            $success = $MulCol->saveContent(
                $colID,
                $val->sanitizePost("content_" . $mc_id, false, true)
            );
            $ajax_return = [
                "message" => $lang->translate("Column saved successfully"),
                "success" => true,
            ];

            $entry_options = $val->sanitizePost("entry_options");
            if ($entry_options != "") {
                foreach (
                    array_filter(explode(",", $entry_options))
                    as $option
                ) {
                    if (
                        !$MulCol->saveContentOptions(
                            $colID,
                            $option,
                            $val->sanitizePost($option)
                        )
                    ) {
                        $success = false;
                    }
                }
            }

            $ajax_return = [
                "message" =>
                    $success == true
                        ? $lang->translate("Column saved successfully")
                        : $lang->translate("An error occoured"),
                "success" => $success,
            ];

            break;
        case "reorder":
            // ===========================
            // ! save options for images
            // ===========================
            $success = $MulCol->reorderCols($val->sanitizePost("positions"));

            $ajax_return = [
                "message" =>
                    $success === true
                        ? $lang->translate("Columns reordered successfully")
                        : $lang->translate("Reorder failed"),
                "success" => $success,
            ];
            break;
        case "saveOptions":
            $options = $val->sanitizePost("options");

            // ===========================
            // ! save options for gallery
            // ===========================
            if ($options != "") {
                foreach (array_filter(explode(",", $options)) as $option) {
                    if (
                        !$MulCol->saveOptions(
                            $option,
                            $val->sanitizePost($option)
                                ? $val->sanitizePost($option)
                                : ""
                        )
                    ) {
                        $error = true;
                    }
                }
            }
            $ajax_return = [
                "message" => $lang->translate("Options saved successfully"),
                "success" => true,
            ];
            break;
        case "publishContent":
            // ===========================
            // ! save options for gallery
            // ===========================
            $success = $MulCol->publishContent($colID);
            $ajax_return = [
                "message" => $success
                    ? $lang->translate("Content published successfully!")
                    : $lang->translate("Content unpublished successfully!"),
                "published" => $success,
                "success" => true,
            ];
            break;
        default:
            // ===========================
            // ! save variant of images
            // ===========================
            $MulCol->saveOptions("variant", $val->sanitizePost("variant"));

            $ajax_return = [
                "message" => $lang->translate("Variant saved successfully"),
                "success" => true,
            ];

            break;
    }
} else {
    $backend->print_error(
        $lang->translate("You sent an invalid ID"),
        CAT_ADMIN_URL . "/pages/modify.php?page_id=" . $page_id
    );
}
?>
