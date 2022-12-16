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

if (!isset($module_version)) {
    $details = CAT_Helper_Addons::getAddonDetails("cc_multicolumn");
    $module_version = $details["version"];
}

if (CAT_Helper_Addons::versionCompare($module_version, "2.0.0.2", "<=")) {
    $checkPosition = CAT_Helper_Page::getInstance()
        ->db()
        ->query(
            "SELECT * FROM INFORMATION_SCHEMA.COLUMNS" .
                " WHERE table_name = ':prefix:mod_cc_multicolumn_contents'" .
                " AND column_name = 'position'"
        );

    # Add option to reorder contents
    if ($checkPosition && $checkPosition->rowCount() == 0) {
        CAT_Helper_Page::getInstance()
            ->db()
            ->query(
                "ALTER TABLE `:prefix:mod_cc_multicolumn_contents` ADD `position` INT(11) UNSIGNED NOT NULL DEFAULT 0"
            );
    }

    # Change mc_id to int(11) UNSIGNED
    CAT_Helper_Page::getInstance()
        ->db()
        ->query(
            "ALTER TABLE `:prefix:mod_cc_multicolumn` MODIFY `mc_id` INT(11) UNSIGNED NOT NULL DEFAULT 0"
        );
    CAT_Helper_Page::getInstance()
        ->db()
        ->query(
            "ALTER TABLE `:prefix:mod_cc_multicolumn_contents` MODIFY `mc_id` INT(11) UNSIGNED NOT NULL DEFAULT 0"
        );

    # Add option to publish/unpublish contents
    $checkPublish = CAT_Helper_Page::getInstance()
        ->db()
        ->query(
            "SELECT * FROM INFORMATION_SCHEMA.COLUMNS" .
                " WHERE table_name = ':prefix:mod_cc_multicolumn_contents'" .
                " AND column_name = 'published'"
        );
    if ($checkPublish && $checkPublish->rowCount() == 0) {
        CAT_Helper_Page::getInstance()
            ->db()
            ->query(
                "ALTER TABLE `:prefix:mod_cc_multicolumn_contents` ADD `published` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0"
            );
        CAT_Helper_Page::getInstance()
            ->db()
            ->query(
                "UPDATE `:prefix:mod_cc_multicolumn_contents` SET `published` = 1"
            );
    }

    # Change to InnoDB
    foreach (
        [
            "mod_cc_multicolumn",
            "mod_cc_multicolumn_contents",
            "mod_cc_multicolumn_options",
            "mod_cc_multicolumn_content_options",
        ]
        as $table
    ) {
        $getTable = CAT_Helper_Page::getInstance()
            ->db()
            ->query(
                "SELECT * FROM INFORMATION_SCHEMA.TABLES
 WHERE table_name = ':prefix:" .
                    $table .
                    "'"
            );
        if (
            $getTable &&
            $getTable->rowCount() > 0 &&
            !false == ($row = $getTable->fetchRow())
        ) {
            if ($row["ENGINE"] != "InnoDB") {
                CAT_Helper_Page::getInstance()
                    ->db()
                    ->query(
                        "ALTER TABLE `:prefix:" . $table . "` ENGINE=InnoDB"
                    );
            }
        }
    }

    $getColumns = CAT_Helper_Page::getInstance()->db()
        ->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS
 WHERE table_name = ':prefix:mod_cc_multicolumn_contents'");
    $columns = [];
    if ($getColumns && $getColumns->rowCount() > 0) {
        while (!false == ($row = $getColumns->fetchRow())) {
            $columns[] = $row["COLUMN_NAME"];
        }
        if (in_array("page_id", $columns)) {
            CAT_Helper_Page::getInstance()
                ->db()
                ->query(
                    "ALTER TABLE `:prefix:mod_cc_multicolumn_contents` DROP COLUMN `page_id`; ALTER TABLE `:prefix:mod_cc_multicolumn_contents` DROP COLUMN `section_id`"
                );
        }
    }

    $getColumns = CAT_Helper_Page::getInstance()->db()
        ->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS
 WHERE table_name = ':prefix:mod_cc_multicolumn_options'");
    $columns = [];
    if ($getColumns && $getColumns->rowCount() > 0) {
        while (!false == ($row = $getColumns->fetchRow())) {
            $columns[] = $row["COLUMN_NAME"];
        }
        if (in_array("page_id", $columns)) {
            if (!in_array("mc_id", $columns)) {
                CAT_Helper_Page::getInstance()
                    ->db()
                    ->query(
                        "ALTER TABLE `:prefix:mod_cc_multicolumn_options` ADD COLUMN `mc_id` INT(11) UNSIGNED NOT NULL DEFAULT 0"
                    );
                CAT_Helper_Page::getInstance()
                    ->db()
                    ->query(
                        "UPDATE `:prefix:mod_cc_multicolumn_options` AS org " .
                            "SET `mc_id` = ( " .
                            "SELECT `mc_id` FROM `:prefix:mod_cc_multicolumn` AS par " .
                            "WHERE org.`page_id` = par.`page_id` " .
                            "AND org.`section_id` = par.`section_id` )"
                    );
                CAT_Helper_Page::getInstance()
                    ->db()
                    ->query(
                        "ALTER TABLE `:prefix:mod_cc_multicolumn_options` DROP PRIMARY KEY; " .
                            "ALTER TABLE `:prefix:mod_cc_multicolumn_options` ADD PRIMARY KEY ( `mc_id`, `name` )"
                    );
            }
            CAT_Helper_Page::getInstance()
                ->db()
                ->query(
                    "ALTER TABLE `:prefix:mod_cc_multicolumn_options` DROP COLUMN `page_id`; ALTER TABLE `:prefix:mod_cc_multicolumn_options` DROP COLUMN `section_id`"
                );
        }
    }

    $getColumns = CAT_Helper_Page::getInstance()->db()
        ->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS
 WHERE table_name = ':prefix:mod_cc_multicolumn_content_options'");
    $columns = [];
    if ($getColumns && $getColumns->rowCount() > 0) {
        while (!false == ($row = $getColumns->fetchRow())) {
            $columns[] = $row["COLUMN_NAME"];
        }
        if (in_array("page_id", $columns)) {
            CAT_Helper_Page::getInstance()
                ->db()
                ->query(
                    "ALTER TABLE `:prefix:mod_cc_multicolumn_content_options` DROP COLUMN `page_id`; ALTER TABLE `:prefix:mod_cc_multicolumn_content_options` DROP COLUMN `section_id`"
                );
        }
    }

    # Add Constraints
    $getConstraints = CAT_Helper_Page::getInstance()
        ->db()
        ->query(
            "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS"
        );

    $constraints = [];
    if ($getConstraints && $getConstraints->rowCount() > 0) {
        while (!false == ($row = $getConstraints->fetchRow())) {
            $constraints[] = $row["CONSTRAINT_NAME"];
        }
    }
    if (!in_array(CAT_TABLE_PREFIX . "mc_pages", $constraints)) {
        CAT_Helper_Page::getInstance()
            ->db()
            ->query(
                "ALTER TABLE `:prefix:mod_cc_multicolumn` ADD CONSTRAINT `:prefix:mc_pages` FOREIGN KEY (`page_id`) REFERENCES `:prefix:pages`(`page_id`) ON DELETE CASCADE"
            );
    }

    if (!in_array(CAT_TABLE_PREFIX . "mc_sections", $constraints)) {
        CAT_Helper_Page::getInstance()
            ->db()
            ->query(
                "ALTER TABLE `:prefix:mod_cc_multicolumn` ADD CONSTRAINT `:prefix:mc_sections` FOREIGN KEY (`section_id`) REFERENCES `:prefix:sections`(`section_id`) ON DELETE CASCADE"
            );
    }

    if (!in_array(CAT_TABLE_PREFIX . "content_mcID", $constraints)) {
        CAT_Helper_Page::getInstance()
            ->db()
            ->query(
                "ALTER TABLE `:prefix:mod_cc_multicolumn_contents` ADD CONSTRAINT `:prefix:content_mcID` FOREIGN KEY (`mc_id`) REFERENCES `:prefix:mod_cc_multicolumn`(`mc_id`) ON DELETE CASCADE"
            );
    }

    if (!in_array(CAT_TABLE_PREFIX . "options_mcID", $constraints)) {
        CAT_Helper_Page::getInstance()
            ->db()
            ->query(
                "ALTER TABLE `:prefix:mod_cc_multicolumn_options` ADD CONSTRAINT `:prefix:options_mcID` FOREIGN KEY (`mc_id`) REFERENCES `:prefix:mod_cc_multicolumn`(`mc_id`) ON DELETE CASCADE"
            );
    }

    if (!in_array(CAT_TABLE_PREFIX . "optContent_mcID", $constraints)) {
        CAT_Helper_Page::getInstance()
            ->db()
            ->query(
                "ALTER TABLE `:prefix:mod_cc_multicolumn_content_options` ADD CONSTRAINT `:prefix:optContent_mcID` FOREIGN KEY (`column_id`) REFERENCES `:prefix:mod_cc_multicolumn_contents`(`column_id`) ON DELETE CASCADE"
            );
    }

    $path = CAT_PATH . "/modules/cc_multicolumn/classes/";
    if (file_exists($path)) {
        CAT_Helper_Directory::getInstance()->removeDirectory($path);
    }

    # change save of variant to new automatic detected variants
    $getInfo = CAT_Helper_Addons::checkInfo(
        CAT_PATH . "/modules/cc_multicolumn/"
    );

    $getVariant = CAT_Helper_Page::getInstance()->db()
        ->query("SELECT `mc_id`, `value` FROM `:prefix:mod_cc_multicolumn_options`
 WHERE `name` = 'variant'");
    if ($getVariant && $getVariant->rowCount() > 0) {
        while (!false == ($row = $getVariant->fetchRow())) {
            if ($row["value"] == "" || $row["value"] == "0") {
                $variant = "default";
            } elseif (
                is_numeric($row["value"]) &&
                isset($getInfo["module_variants"][$row["value"]])
            ) {
                $variant = $getInfo["module_variants"][$row["value"]];
            } elseif (is_numeric($row["value"])) {
                $variant = "default";
            } else {
                $variant = $row["value"];
            }
            CAT_Helper_Page::getInstance()
                ->db()
                ->query(
                    "UPDATE `:prefix:mod_cc_multicolumn_options` " .
                        "SET `value` = :val " .
                        "WHERE `mc_id` = :mcID AND `name` = 'variant'",
                    [
                        "val" => $variant,
                        "mcID" => $row["mc_id"],
                    ]
                );
        }
    }
}

?>
