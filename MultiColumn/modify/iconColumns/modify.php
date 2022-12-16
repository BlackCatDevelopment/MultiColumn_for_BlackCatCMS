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
    while ($level < 10 && !file_exists($root . "/framework/class.secure.php")) {
        $root .= "../";
        $level += 1;
    }
    if (file_exists($root . "/framework/class.secure.php")) {
        include $root . "/framework/class.secure.php";
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

$parser_data["pages"] = CAT_Helper_ListBuilder::sort(
    CAT_Helper_Page::getPages(CAT_Backend::isBackend()),
    0
);

$parser_data["icons"] = [
    "iconIC-responsive",
    "iconIC-home",
    "iconIC-letima",
    "iconIC-design",
    "iconIC-quill",
    "iconIC-bucket",
    "iconIC-images",
    "iconIC-price-tag",
    "iconIC-price-tags",
    "iconIC-envelop",
    "iconIC-alarm",
    "iconIC-stopwatch",
    "iconIC-display",
    "iconIC-mobile",
    "iconIC-tablet",
    "iconIC-bubbles",
    "iconIC-spinner",
    "iconIC-search",
    "iconIC-zoom-in",
    "iconIC-zoom-out",
    "iconIC-lock",
    "iconIC-wrench",
    "iconIC-cogs",
    "iconIC-hammer",
    "iconIC-screwdriver",
    "iconIC-magic-wand",
    "iconIC-magic-wand2",
    "iconIC-trophy",
    "iconIC-coffee",
    "iconIC-fire",
    "iconIC-lab",
    "iconIC-atom",
    "iconIC-lamp",
    "iconIC-bin",
    "iconIC-puzzle",
    "iconIC-brain",
    "iconIC-power-cord",
    "iconIC-clipboard",
    "iconIC-clipboard2",
    "iconIC-more",
    "iconIC-cloud",
    "iconIC-cloud-download",
    "iconIC-cloud-upload",
    "iconIC-cloud-check",
    "iconIC-link",
    "iconIC-star-empty",
    "iconIC-star-half",
    "iconIC-star-full",
    "iconIC-man-woman",
    "iconIC-smile",
    "iconIC-warning",
    "iconIC-plus-circle",
    "iconIC-minus-circle",
    "iconIC-cancel-circle",
    "iconIC-arrow-up",
    "iconIC-arrow-right",
    "iconIC-arrow-down",
    "iconIC-arrow-left",
    "iconIC-checkbox-checked",
    "iconIC-checkbox-unchecked",
    "iconIC-pencil-ruler",
    "iconIC-embed",
    "iconIC-code",
    "iconIC-facebook",
    "iconIC-github",
    "iconIC-apple",
    "iconIC-chrome",
    "iconIC-firefox",
    "iconIC-edge",
    "iconIC-safari",
    "iconIC-html-five",
    "iconIC-palette",
    "iconIC-new",
    "iconIC-archive",
    "iconIC-library2",
    "iconIC-profile",
    "iconIC-file-empty",
    "iconIC-file-plus",
    "iconIC-file-minus",
    "iconIC-file-download",
    "iconIC-file-upload",
    "iconIC-file-check",
    "iconIC-file-text2",
    "iconIC-file-presentation",
    "iconIC-certificate",
    "iconIC-lifebuoy",
    "iconIC-location",
    "iconIC-calendar",
    "iconIC-vcard",
    "iconIC-chart",
    "iconIC-stairs-up",
    "iconIC-stars",
    "iconIC-medal",
    "iconIC-trophy1",
    "iconIC-gift",
    "iconIC-atom1",
    "iconIC-briefcase3",
    "iconIC-target",
    "iconIC-list",
    "iconIC-grid3",
    "iconIC-menu",
    "iconIC-eye3",
    "iconIC-eye-blocked3",
    "iconIC-sun",
    "iconIC-heart4",
    "iconIC-thumbs-up",
    "iconIC-question3",
    "iconIC-info",
    "iconIC-cross",
    "iconIC-checkmark",
    "iconIC-rulers",
    "iconIC-file-pdf",
    "iconIC-qrcode",
    "iconIC-spinner9",
    "iconIC-stats-bars4",
    "iconIC-medal-star",
    "iconIC-switch",
];

sort($parser_data["icons"]);
?>
