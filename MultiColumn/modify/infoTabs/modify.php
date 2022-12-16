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
    "iconIT-responsive",
    "iconIT-home",
    "iconIT-letima",
    "iconIT-design",
    "iconIT-quill",
    "iconIT-bucket",
    "iconIT-images",
    "iconIT-price-tag",
    "iconIT-price-tags",
    "iconIT-envelop",
    "iconIT-alarm",
    "iconIT-stopwatch",
    "iconIT-display",
    "iconIT-mobile",
    "iconIT-tablet",
    "iconIT-bubbles",
    "iconIT-spinner",
    "iconIT-search",
    "iconIT-zoom-in",
    "iconIT-zoom-out",
    "iconIT-lock",
    "iconIT-wrench",
    "iconIT-cogs",
    "iconIT-hammer",
    "iconIT-screwdriver",
    "iconIT-magic-wand",
    "iconIT-magic-wand2",
    "iconIT-trophy",
    "iconIT-coffee",
    "iconIT-fire",
    "iconIT-lab",
    "iconIT-atom",
    "iconIT-lamp",
    "iconIT-bin",
    "iconIT-puzzle",
    "iconIT-brain",
    "iconIT-power-cord",
    "iconIT-clipboard",
    "iconIT-clipboard2",
    "iconIT-more",
    "iconIT-cloud",
    "iconIT-cloud-download",
    "iconIT-cloud-upload",
    "iconIT-cloud-check",
    "iconIT-link",
    "iconIT-star-empty",
    "iconIT-star-half",
    "iconIT-star-full",
    "iconIT-man-woman",
    "iconIT-smile",
    "iconIT-warning",
    "iconIT-plus-circle",
    "iconIT-minus-circle",
    "iconIT-cancel-circle",
    "iconIT-arrow-up",
    "iconIT-arrow-right",
    "iconIT-arrow-down",
    "iconIT-arrow-left",
    "iconIT-checkbox-checked",
    "iconIT-checkbox-unchecked",
    "iconIT-pencil-ruler",
    "iconIT-embed",
    "iconIT-code",
    "iconIT-facebook",
    "iconIT-github",
    "iconIT-apple",
    "iconIT-chrome",
    "iconIT-firefox",
    "iconIT-edge",
    "iconIT-safari",
    "iconIT-html-five",
    "iconIT-palette",
    "iconIT-new",
    "iconIT-archive",
    "iconIT-library2",
    "iconIT-profile",
    "iconIT-file-empty",
    "iconIT-file-plus",
    "iconIT-file-minus",
    "iconIT-file-download",
    "iconIT-file-upload",
    "iconIT-file-check",
    "iconIT-file-text2",
    "iconIT-file-presentation",
    "iconIT-certificate",
    "iconIT-lifebuoy",
    "iconIT-location",
    "iconIT-calendar",
    "iconIT-vcard",
    "iconIT-chart",
    "iconIT-stairs-up",
    "iconIT-stars",
    "iconIT-medal",
    "iconIT-trophy1",
    "iconIT-gift",
    "iconIT-atom1",
    "iconIT-briefcase3",
    "iconIT-target",
    "iconIT-list",
    "iconIT-grid3",
    "iconIT-menu",
    "iconIT-eye3",
    "iconIT-eye-blocked3",
    "iconIT-sun",
    "iconIT-heart4",
    "iconIT-thumbs-up",
    "iconIT-question3",
    "iconIT-info",
    "iconIT-cross",
    "iconIT-checkmark",
    "iconIT-rulers",
    "iconIT-file-pdf",
    "iconIT-qrcode",
    "iconIT-spinner9",
    "iconIT-stats-bars4",
    "iconIT-medal-star",
    "iconIT-switch",
];

sort($parser_data["icons"]);
?>
