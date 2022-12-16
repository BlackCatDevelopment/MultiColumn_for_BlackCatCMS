{**
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
 *   along with this program; if not, see <http://www.gnu.org/licenses>.
 *
 *   @author			Matthias Glienke, letima development
   *   @copyright			2023, Black Cat Development
   *   @link				https://blackcat-cms.org
   *   @license			https://www.gnu.org/licenses/gpl.html
   *   @category			CAT_Modules
   *   @package			multiColumn
 *
 *}


<form action="{$CAT_URL}/modules/cc_multicolumn/save.php" method="post" class="ajaxForm">
	<input type="hidden" name="page_id" value="{$page_id}" >
	<input type="hidden" name="section_id" value="{$section_id}" >
	<input type="hidden" name="mc_id" value="{$mc_id}" >
	<input type="hidden" name="action" value="saveOptions" >
	<input type="hidden" name="_cat_ajax" value="1" >
	<input type="hidden" name="options" value="title1,title2,image">
	<p>
		<label class="cc_In100px" for="title1_{$section_id}">Titel 1 (h3):</label>
		<input class="cc_In300px" type="text" name="title1" class="" value="{if $options.title1}{$options.title1}{/if}" id="title2_{$section_id}">
        </p>
        <p>
		<label class="cc_In100px" for="title2_{$section_id}">Titel 2 (h2):</label>
		<input class="cc_In300px" type="text" name="title2" class="" value="{if $options.title2}{$options.title2}{/if}" id="title2_{$section_id}">
        </p>
        <hr>
        <p>
                <label class="cc_In100px" for="title2_{$section_id}">Bild:</label>
                <input type="text" name="image" class="cc_In300px" value="{if $options.image}{$options.image}{/if}" id="title2_{$section_id}" placeholder="relativ zum Ordner /media/images/..."><br>
                <small>Bild relativ zu /media/images/...</small><br>
        </p>

	<p class="cc_In300px">
		<input type="submit" name="speichern" value="{translate('Save')}" >
	</p>
</form>
