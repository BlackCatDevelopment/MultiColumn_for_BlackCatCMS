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

if (!class_exists("MultiColumn", false)) {
    class MultiColumn
    {
        private static $instance;

        protected static $mc_id = null;
        protected static $section_id = null;

        protected static $db;
        protected static $logger;
        public static $val;

        public $contents = [];
        public $options = [];

        public $variant = "default";
        public static $modulePath;
        public static $directory = "cc_multicolumn";
        public static $allVariants = [];

        protected static $initOptions;

        public static function getInstance(): object
        {
            if (!self::$instance) {
                self::$instance = new self();
            } else {
                self::reset();
            }
            return self::$instance;
        }

        public static function init(): void
        {
            // Connection to DB
            self::$db = CAT_Helper_DB::getInstance();
            // ValidateHelper
            self::$val = CAT_Helper_Validate::getInstance();
            // Logger 7 = debug, 8 = off
            self::$logger = new CAT_Helper_KLogger(CAT_PATH . "/temp", 7);

            self::$modulePath =
                CAT_PATH . "/modules/" . static::$directory . "/";

            self::$initOptions = ["variant" => "default", "kind" => "2"];
        }

        public function __construct($mc_id = null, $is_header = false)
        {
            global $section_id;

            require_once CAT_PATH . "/framework/functions.php";

            if (!isset($section_id) || $is_header) {
                $section_id = is_numeric($mc_id)
                    ? $mc_id
                    : $mc_id["section_id"];
            }

            self::$section_id = intval($section_id);

            if ($mc_id === true) {
                return $this->initAdd();
            } elseif (is_numeric($mc_id) && !$is_header) {
                self::$mc_id = $mc_id;
            } elseif (is_numeric($section_id) && $section_id > 0) {
                $this->setColumnID();
            } else {
                return false;
            }
        }

        public function __destruct()
        {
        }

        public static function getClassInfo(string $value): string
        {
            return static::$$value;
        }

        /**
         * return, if in a current object all important values are existing (section_id, gallery_id)
         *
         * @access public
         * @param  integer  $image_id - optional check for $image_id to be numeric
         * @return boolean true/false
         *
         **/
        private function checkIDs(int $colID = null): bool
        {
            if (
                !self::$section_id ||
                !self::$mc_id ||
                ($colID && !is_numeric($colID))
            ) {
                return false;
            } else {
                return true;
            }
        }

        /**
         * add new MultiColumn
         *
         * @access public
         * @return integer
         *
         **/
        private function initAdd(): ?int
        {
            if (!self::$section_id) {
                return false;
            }

            // Add a new MultiColum
            if (
                self::$db->query(
                    "INSERT INTO `:prefix:mod_cc_multicolumn` " .
                        "( `section_id` ) VALUES " .
                        "( :section_id )",
                    [
                        "section_id" => self::$section_id,
                    ]
                )
            ) {
                $this->setColumnID();
                // Add initial options for multicolumn
                foreach (self::$initOptions as $name => $val) {
                    if (!$this->saveOptions($name, $val)) {
                        $return = false;
                    }
                }

                $this->addColumn(self::$initOptions["kind"]);

                return self::$mc_id;
            } else {
                return false;
            }
        } // initAdd()

        /**
         * delete a MultiColumn
         *
         * @access public
         * @return integer
         *
         **/
        public function deleteMC(): bool
        {
            if (!self::$section_id || !self::$mc_id) {
                return false;
            }

            // Delete complete record from the database
            if (
                self::$db->query(
                    "DELETE FROM `:prefix:mod_cc_multicolumn`" .
                        " WHERE `section_id` = :section_id",
                    [
                        "section_id" => self::$section_id,
                    ]
                )
            ) {
                $return = true;
            }
            return false;
        }

        /**
         * add new column
         *
         * @access public
         * @return integer
         *
         **/
        public function addColumn(int $count = 1)
        {
            if (!self::$mc_id || !is_numeric($count)) {
                return false;
            }

            $newIDs = [];

            $pos = self::$db
                ->query(
                    'SELECT MAX(position) AS pos FROM `:prefix:mod_cc_multicolumn_contents`
					WHERE `mc_id` = :mc_id',
                    [
                        "mc_id" => self::$mc_id,
                    ]
                )
                ->fetchColumn();

            for ($i = 0; $i < $count; $i++) {
                if (
                    self::$db->query(
                        'INSERT INTO `:prefix:mod_cc_multicolumn_contents`
							( `mc_id`, `position`, `content`, `text` ) VALUES
							( :mc_id, :position, "", "" )',
                        [
                            "mc_id" => self::$mc_id,
                            "position" => ++$pos,
                        ]
                    )
                ) {
                    $success = true;
                }
                $newIDs[] = self::$db->lastInsertId();
            }
            if ($success) {
                return $newIDs;
            } else {
                return false;
            }
        }

        /**
         * remove Column
         *
         * @access public
         * @return integer
         *
         **/
        public function removeColumn(int $column_id = null): bool
        {
            if (!self::$mc_id || !$column_id || !is_numeric($column_id)) {
                return false;
            }

            if (
                self::$db->query(
                    'DELETE FROM `:prefix:mod_cc_multicolumn_contents`
					WHERE `column_id` = :column_id',
                    [
                        "column_id" => $column_id,
                    ]
                )
            ) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * set the $mc_id by self:$sectionid
         *
         * @access private
         * @return integer
         *
         **/
        private function setColumnID()
        {
            // Get columns in this section
            $getID = self::$db->query(
                "SELECT `mc_id` " .
                    "FROM `:prefix:mod_cc_multicolumn` " .
                    "WHERE `section_id` = :section_id",
                [
                    "section_id" => self::$section_id,
                ]
            );
            if ($getID && $getID->rowCount() > 0) {
                if (!false == ($row = $getID->fetch())) {
                    self::$mc_id = $row["mc_id"];
                    return self::$mc_id;
                } else {
                    return false;
                }
            }
        } // end setColumnID()

        /**
		 * get all offers from database
		 *
		 * @access public
		 * @param  string  $option		-
		 									true => get all active posts
		 									NULL => get all inactive and active posts
		 									numeric => get all inactive and active posts
		 * @param  string  $addContent	- if table to print - default false
		 * @return array()
		 *
		 **/
        public function getContents(
            bool $addOptions = false,
            bool $frontend = true
        ): array {
            $contents = self::$db->query(
                'SELECT `content`, `column_id`, `published`
					FROM `:prefix:mod_cc_multicolumn_contents`
					WHERE `mc_id` = :mc_id
					ORDER BY `position`',
                [
                    "mc_id" => self::$mc_id,
                ]
            );
            if ($contents && $contents->numRows() > 0) {
                while (!false == ($row = $contents->fetchRow())) {
                    if ($frontend) {
                        // Remove if content is not published
                        if ($row["published"] != 1) {
                            continue;
                        }

                        CAT_Helper_Page::preprocess($row["content"]);
                    }
                    $this->contents[$row["column_id"]] = [
                        "column_id" => $row["column_id"],
                        "published" => $row["published"],
                        "content" => stripslashes($row["content"]),
                        "contentname" => sprintf(
                            "content_%s_%s",
                            self::$section_id,
                            $row["column_id"]
                        ),
                    ];
                }
            }

            if ($addOptions) {
                $this->getContentOptions(null, $frontend);
            }
            return $this->contents;
        } // end getContents()

        /**
         * get all offers from database
         *
         * @access public
         * @param  string/array  $id - id/ids of offer
         * @param  string  $output - if table to print - default false
         * @return array()
         *
         **/
        private function getContentOptions(
            int $column_id = null,
            bool $frontend = false
        ) {
            $select = "";

            if (!$column_id && count($this->contents) > 0) {
                foreach (array_keys($this->contents) as $id) {
                    $select .= " OR `column_id` = '" . intval($id) . "'";
                }
                $select = "(" . substr($select, 3) . ")";
            } elseif ($column_id) {
                $select = "AND `column_id` = '" . intval($column_id) . "'";
            } else {
                return false;
            }

            if (isset($column_id)) {
                $opts = self::$db->query(
                    sprintf(
                        'SELECT * FROM `:prefix:mod_cc_multicolumn_content_options`
								WHERE `column_id` = :column_id ',
                        $select
                    ),
                    [
                        "column_id" => $column_id,
                    ]
                );
            } else {
                $opts = self::$db->query(
                    'SELECT * FROM `:prefix:mod_cc_multicolumn_content_options`
							WHERE ' . $select
                );
            }

            $options = [];

            if ($opts && $opts->numRows() > 0) {
                while (!false == ($row = $opts->fetchRow())) {
                    $options[$row["column_id"]][$row["name"]] = $frontend
                        ? htmlspecialchars_decode($row["value"])
                        : htmlspecialchars($row["value"]);

                    if (isset($this->contents[$row["column_id"]]["options"])) {
                        $this->contents[$row["column_id"]][
                            "options"
                        ] = array_merge(
                            $this->contents[$row["column_id"]]["options"],
                            [
                                $row["name"] => $frontend
                                    ? htmlspecialchars_decode($row["value"])
                                    : htmlspecialchars($row["value"]),
                            ]
                        );
                    } else {
                        $this->contents[$row["column_id"]]["options"] = [
                            $row["name"] => $frontend
                                ? htmlspecialchars_decode($row["value"])
                                : htmlspecialchars($row["value"]),
                        ];
                    }
                }
            }
            if ($column_id) {
                return $this->contents[$column_id]["options"];
            } else {
                return $options;
            }
        } // end getOptions()

        /**
         * get all offers from database
         *
         * @param  string/array		$column_id	- id for content column
         * @param  string			$name		- name for option
         * @param  string			$value		- value for option
         * @return array()
         *
         **/
        public function getSingContentOptions(
            int $column_id = null,
            string $name = null,
            bool $frontend = false
        ) {
            if (!$column_id) {
                return false;
            }

            if ($name && isset($this->contents[$column_id]["options"][$name])) {
                return $this->contents[$column_id]["options"][$name];
            }

            $getOptions = $name
                ? self::$db->query(
                    'SELECT * FROM `:prefix:mod_cc_multicolumn_content_options`
						WHERE `column_id` = :column_id AND
							`name` = :name',
                    [
                        "column_id" => self::$gallery_id,
                        "name" => $name,
                    ]
                )
                : self::$db->query(
                    'SELECT * FROM `:prefix:mod_cc_multicolumn_content_options`
						WHERE `column_id` = :column_id',
                    [
                        "column_id" => self::$gallery_id,
                    ]
                );

            if (isset($getOptions) && $getOptions->numRows() > 0) {
                while (!false == ($row = $getOptions->fetchRow())) {
                    $this->contents[$row["column_id"]]["options"][
                        $row["name"]
                    ] = $frontend
                        ? htmlspecialchars_decode($row["value"])
                        : htmlspecialchars($row["value"]);
                }
            }
            if (
                $name &&
                $column_id &&
                isset($this->contents[$column_id]["options"][$name])
            ) {
                return $this->contents[$column_id]["options"][$name];
            }
            if ($column_id && isset($this->contents[$column_id]["options"])) {
                return $this->contents[$column_id]["options"];
            }
            return false;
        } // end getSingContentOptions()

        /**
         * save options for single columns to database
         *
         * @access public
         * @param  string/array		$column_id - id/ids of content column
         * @param  string			$name - name for option
         * @param  string			$value - value for option
         * @return bool true/false
         *
         **/
        public function saveContent(
            int $column_id = null,
            string $content = ""
        ): bool {
            if (
                !self::$section_id ||
                !self::$mc_id ||
                !$column_id ||
                !is_numeric($column_id)
            ) {
                return false;
            }

            // for non-admins only
            if (!CAT_Users::getInstance()->ami_group_member(1)) {
                // if HTMLPurifier is enabled...
                $check = self::$db->query(
                    'SELECT * FROM `:prefix:mod_wysiwyg_admin_v2`
						WHERE `set_name`= :name
						AND `set_value`= :val',
                    [
                        "name" => "enable_htmlpurifier",
                        "val" => 1,
                    ]
                );
                if ($check && $check->numRows() > 0) {
                    // use HTMLPurifier to clean up the output
                    $content = CAT_Helper_Protect::getInstance()->purify(
                        $content,
                        ["Core.CollectErrors" => true]
                    );
                }
            }

            if (
                self::$db->query(
                    'UPDATE `:prefix:mod_cc_multicolumn_contents`
					SET `content` = :content,
						`text` = :text
					WHERE `mc_id` = :mc_id AND
						`column_id` = :column_id',
                    [
                        "content" => $content,
                        "text" => umlauts_to_entities(
                            strip_tags($content),
                            strtoupper(DEFAULT_CHARSET),
                            0
                        ),
                        "mc_id" => self::$mc_id,
                        "column_id" => $column_id,
                    ]
                )
            ) {
                return true;
            } else {
                return false;
            }
        } // end saveContent()

        /**
         * save options for single colums to database
         *
         * @access public
         * @param  string/array		$column_id - id/ids of content column
         * @param  string			$name - name for option
         * @param  string			$value - value for option
         * @return bool true/false
         *
         **/
        public function saveContentOptions(
            int $column_id = null,
            string $name = null,
            string $value = ""
        ): bool {
            if (!$name || !$column_id) {
                return false;
            }
            if (
                self::$db->query(
                    'REPLACE INTO `:prefix:mod_cc_multicolumn_content_options`
					SET `column_id`		= :column_id,
						`name`			= :name,
						`value`			= :value',
                    [
                        "column_id" => intval($column_id),
                        "name" => $name,
                        "value" => $value ? $value : "",
                    ]
                )
            ) {
                return true;
            } else {
                return false;
            }
        } // end saveContentOptions()

        /**
         * (un)publish single column
         *
         * @access public
         * @param  integer		$colID - id of image
         * @return bool true/false
         *
         **/
        public function publishContent(int $colID = null): int
        {
            self::$db->query(
                "UPDATE `:prefix:mod_cc_multicolumn_contents`" .
                    " SET `published` = 1 - `published`" .
                    " WHERE `column_id`		= :colID",
                [
                    "colID" => intval($colID),
                ]
            );
            return self::$db
                ->query(
                    "SELECT `published` FROM `:prefix:mod_cc_multicolumn_contents`" .
                        " WHERE `column_id` = :colID",
                    [
                        "colID" => intval($colID),
                    ]
                )
                ->fetchColumn();
        } // end publishContent()

        /**
         * get options for MultiColumn
         *
         * @access public
         * @param  string			$name - name for option
         * @param  string			$value - value for option
         * @return array()
         *
         **/
        public function getOptions(string $name = null, bool $frontend = false)
        {
            if ($name && isset($this->options[$name])) {
                return $this->options[$name];
            }

            $getOptions = $name
                ? self::$db->query(
                    'SELECT * FROM `:prefix:mod_cc_multicolumn_options`
						WHERE `mc_id` = :mc_id AND
							`name` = :name',
                    [
                        "mc_id" => self::$mc_id,
                        "name" => $name,
                    ]
                )
                : self::$db->query(
                    'SELECT * FROM `:prefix:mod_cc_multicolumn_options`
						WHERE `mc_id` = :mc_id',
                    [
                        "mc_id" => self::$mc_id,
                    ]
                );

            if (isset($getOptions) && $getOptions->numRows() > 0) {
                while (!false == ($row = $getOptions->fetchRow())) {
                    $this->options[$row["name"]] = $frontend
                        ? htmlspecialchars_decode($row["value"])
                        : htmlspecialchars($row["value"]);
                }
            }
            if ($name) {
                if (isset($this->options[$name])) {
                    return $this->options[$name];
                } else {
                    return "";
                }
            }
            return $this->options;
        } // end getOptions()

        /**
         * save options for MultiColumn
         *
         * @access public
         * @param  string			$name - name for option
         * @param  string			$value - value for option
         * @return bool true/false
         *
         **/
        public function saveOptions(string $name = "", string $value = ""): bool
        {
            if (!$name) {
                return false;
            }

            if (
                self::$db->query(
                    'REPLACE INTO `:prefix:mod_cc_multicolumn_options`
					SET `mc_id`		= :mc_id,
						`name`		= :name,
						`value`		= :value',
                    [
                        "mc_id" => self::$mc_id,
                        "name" => $name,
                        "value" => $value ? $value : "",
                    ]
                )
            ) {
                return true;
            } else {
                return false;
            }
        } // end saveOptions()

        /**
         * reorder columns
         *
         * @access public
         * @param  array			$colIDs - Strings from jQuery sortable()
         * @return bool true/false
         *
         **/
        public function reorderCols(array $colIDs = []): bool
        {
            if (
                !$this->checkIDs() ||
                !is_array($colIDs) ||
                count($colIDs) == 0
            ) {
                return false;
            }

            $return = true;

            foreach ($colIDs as $index => $colStr) {
                $colID = explode("_", $colStr);

                if (
                    !self::$db->query(
                        'UPDATE `:prefix:mod_cc_multicolumn_contents`
						SET `position` = :position
						WHERE `mc_id`			= :mc_id
							AND `column_id`		= :column_id',
                        [
                            "position" => $index,
                            "mc_id" => self::$mc_id,
                            "column_id" => $colID[count($colID) - 1],
                        ]
                    )
                ) {
                    $return = false;
                }
            }
            return $return;
        } // end reorderCols()

        public function getID(): int
        {
            return self::$mc_id;
        }

        public function getVariant(): string
        {
            if (isset($this->options["_variant"])) {
                return $this->options["_variant"];
            }

            $this->getOptions("variant");

            $this->options["_variant"] =
                isset($this->options["variant"]) &&
                $this->options["variant"] != ""
                    ? $this->options["variant"]
                    : "default";

            return $this->options["_variant"];
        }

        /**
         * Get all available variants of an addon by checking the templates-folder
         */
        public static function getAllVariants(): array
        {
            if (count(self::$allVariants) > 0) {
                return self::$allVariants;
            }
            self::$allVariants = [];
            $templatePath =
                CAT_PATH . "/modules/" . static::$directory . "/templates/";

            if (!file_exists($templatePath)) {
                $templatePath = dirname(__DIR__, 1) . "/templates/";
                if (!file_exists($templatePath)) {
                    return [];
                }
            }
            foreach (
                CAT_Helper_Directory::getInstance()
                    ->setRecursion(false)
                    ->scanDirectory($templatePath)
                as $path
            ) {
                self::$allVariants[] = basename($path);
            }
            return self::$allVariants;
        }

        /**
         *
         *
         *
         *
         **/
        public function sanitizeURL(string $url = ""): string
        {
            if (!$url) {
                return "";
            }
            $parts = array_filter(explode("/", $url));
            return implode("/", $parts);
        }

        /**
         * Funktion zum initialen Installieren des Moduls
         */
        public static function install(): void
        {
            // Install tables for flexElement
            self::_installSQL(
                CAT_PATH .
                    "/modules/" .
                    static::$directory .
                    "/inc/db/structure.sql"
            );

            // add files to class_secure
            $addons_helper = new CAT_Helper_Addons();
            foreach (["save.php"] as $file) {
                if (
                    false ===
                    $addons_helper->sec_register_file(static::$directory, $file)
                ) {
                    error_log("Unable to register file -$file-!");
                }
            }
        }

        /**
         * Funktion zum initialen Installieren des Moduls
         */
        public static function uninstall(): void
        {
            // Delete all tables if exists
            self::$db->query(
                "DROP TABLE IF EXISTS" .
                    " `:prefix:mod_cc_multicolumn_options`," .
                    " `:prefix:mod_cc_multicolumn_content_options`," .
                    " `:prefix:mod_cc_multicolumn_contents`;"
            );
            self::$db->query(
                "DROP TABLE IF EXISTS" . " `:prefix:mod_cc_multicolumn`;"
            );
        }

        /**
         * temporäre Funktion zum initialen Installieren der structure.sql
         */
        private static function _installSQL(string $file): bool
        {
            $errors = [];

            $import = file_get_contents($file);

            $import = preg_replace("%/\*(.*)\*/%Us", "", $import);
            $import = preg_replace("%^--(.*)\n%mU", "", $import);
            $import = preg_replace("%^$\n%mU", "", $import);
            foreach (self::_split_sql_file($import, ";") as $imp) {
                if ($imp != "" && $imp != " ") {
                    $ret = self::$db->query($imp);
                    if (self::$db->isError()) {
                        $errors[] = self::$db->getError();
                    }
                }
            }
            return count($errors) ? false : true;
        } // end function _installSQL()

        /**
         * Credits: http://stackoverflow.com/questions/147821/loading-sql-files-from-within-php
         **/
        private static function _split_sql_file(
            string $sql,
            string $delimiter
        ): array {
            // Split up our string into "possible" SQL statements.
            $tokens = explode($delimiter, $sql);

            // try to save mem.
            $sql = "";
            $output = [];

            // we don't actually care about the matches preg gives us.
            $matches = [];

            // this is faster than calling count($oktens) every time thru the loop.
            $token_count = count($tokens);
            for ($i = 0; $i < $token_count; $i++) {
                // Don't wanna add an empty string as the last thing in the array.
                if ($i != $token_count - 1 || strlen($tokens[$i] > 0)) {
                    // This is the total number of single quotes in the token.
                    $total_quotes = preg_match_all(
                        "/'/",
                        $tokens[$i],
                        $matches
                    );
                    // Counts single quotes that are preceded by an odd number of backslashes,
                    // which means they're escaped quotes.
                    $escaped_quotes = preg_match_all(
                        "/(?<!\\\\)(\\\\\\\\)*\\\\'/",
                        $tokens[$i],
                        $matches
                    );

                    $unescaped_quotes = $total_quotes - $escaped_quotes;

                    // If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
                    if ($unescaped_quotes % 2 == 0) {
                        // It's a complete sql statement.
                        $output[] = $tokens[$i];
                        // save memory.
                        $tokens[$i] = "";
                    } else {
                        // incomplete sql statement. keep adding tokens until we have a complete one.
                        // $temp will hold what we have so far.
                        $temp = $tokens[$i] . $delimiter;
                        // save memory..
                        $tokens[$i] = "";

                        // Do we have a complete statement yet?
                        $complete_stmt = false;

                        for (
                            $j = $i + 1;
                            !$complete_stmt && $j < $token_count;
                            $j++
                        ) {
                            // This is the total number of single quotes in the token.
                            $total_quotes = preg_match_all(
                                "/'/",
                                $tokens[$j],
                                $matches
                            );
                            // Counts single quotes that are preceded by an odd number of backslashes,
                            // which means they're escaped quotes.
                            $escaped_quotes = preg_match_all(
                                "/(?<!\\\\)(\\\\\\\\)*\\\\'/",
                                $tokens[$j],
                                $matches
                            );

                            $unescaped_quotes = $total_quotes - $escaped_quotes;

                            if ($unescaped_quotes % 2 == 1) {
                                // odd number of unescaped quotes. In combination with the previous incomplete
                                // statement(s), we now have a complete statement. (2 odds always make an even)
                                $output[] = $temp . $tokens[$j];

                                // save memory.
                                $tokens[$j] = "";
                                $temp = "";

                                // exit the loop.
                                $complete_stmt = true;
                                // make sure the outer loop continues at the right point.
                                $i = $j;
                            } else {
                                // even number of unescaped quotes. We still don't have a complete statement.
                                // (1 odd and 1 even always make an odd)
                                $temp .= $tokens[$j] . $delimiter;
                                // save memory.
                                $tokens[$j] = "";
                            }
                        } // for..
                    } // else
                }
            }

            // remove empty
            for ($i = count($output) + 1; $i >= 0; $i--) {
                if (isset($output[$i]) && trim($output[$i]) == "") {
                    array_splice($output, $i, 1);
                }
            }

            return $output;
        }
    }
    MultiColumn::init();
}

?>
