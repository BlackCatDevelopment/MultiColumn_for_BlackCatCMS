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
 *   @author			Matthias Glienke
 *   @copyright			2014, Black Cat Development
 *   @link				http://blackcat-cms.org
 *   @license			http://www.gnu.org/licenses/gpl.html
 *   @category			CAT_Modules
 *   @package			multiColumn
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('CAT_PATH')) {
	include(CAT_PATH.'/framework/class.secure.php');
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) {
		include($root.'/framework/class.secure.php');
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php


$module_description	  = 'Das Add on "MultiColumn" bietet eine einfache M&ouml;glichkeit ein Mehrspaltenlayout zu integrieren. F&uuml;r mehr Informationen lesen Sie <a class="icon-github" href="https://github.com/BlackCatDevelopment/MultiColumn_for_BlackCatCMS" target="_blank">GitHub</a>.<br/><br/>Done by Matthias Glienke, <a class="icon-creativecat" href="http://creativecat.de"> creativecat</a>';


$LANG = array(
// --- view no image ---

// --- modify ---
	'You sent an invalid ID'			=> 'Es wurde eine ung&uuml;ltige ID &uuml;bermittelt',
	'Administration for'				=> 'Verwaltung für',
	'Options for frontend'				=> 'Optionen f&uuml;rs Frontend',
	'Module variant'					=> 'Modulvariante',
	'Count of columns per row'			=> 'Anzahl der Spalten pro Zeile',
	'Add column'						=> 'Spalte hinzuf&uuml;gen',
	'Add columns'						=> 'Spalten hinzuf&uuml;gen',
	'Equalize columns in one row'		=> 'Passe Spaltenh&ouml;he an',
	'Row No.'							=> 'Zeile Nr.',
	'Column No.'						=> 'Spalte Nr.',
	'Set skin'							=> 'Variante setzen',
	'Save skin &amp; reload'			=> 'Speichern &amp; Neuladen',
	'Save column'						=> 'Spalte speichern',
	'An error occoured'					=> 'Ein Fehler ist aufgetreten',
	'Column added successfully'			=> 'Spalte erfolgreich hinzugefügt',
	'Column saved successfully'			=> 'Spalte erfolgreich gespeichert',
	'Column deleted successfully'		=> 'Spalte erfolgreich gelöscht',
	'Columns reordered successfully'	=> 'Spalten erfolgreich sortiert',
	'Reorder failed'					=> 'Sortierung fehlgeschlagen',
	'Options saved successfully'		=> 'Optionen erfolgreich gespeichert',
	'Variant saved successfully'		=> 'Variante erfolgreich gespeichert',
	'Existing rows'						=> 'Vorhandene Zeilen',
	'No rows available'					=> 'Keine Zeilen vorhanden',
	'Column'							=> 'Spalte',
	'Row'								=> 'Zeile',
	'Tab title'							=> 'Tab-Titel',
	'Column width in percent'			=> 'Spaltenbreite in Prozent'
);