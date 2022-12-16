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
	<input type="hidden" name="options" value="color,image,kind,side" >
	<p class="hidden"><input type="checkbox" name="kind" class="set_kind column_3 active" checked="checked" value="3" ></p>
	<p>
		<label for="mc_image_{$section_id}">Bild:</label>
		<input type="text" name="image" id="mc_image_{$section_id}" value="{if $options.image}{$options.image}{/if}"><br>
		<small>Pfad: /media/[Bildname/Pfad]</small>
	</p>
	<p class="cc_In200px">
		<input type="radio" name="side" id="mc_side_{$section_id}_0" class="fc_radio_jq" value="0"{if !$options.side} checked="checked"{/if}>
		<label for="mc_side_{$section_id}_0">Bild links platzieren</label>
		<input type="radio" name="side" id="mc_side_{$section_id}_1" class="fc_radio_jq" value="1"{if $options.side == 1} checked="checked"{/if}>
		<label for="mc_side_{$section_id}_1">Bild rechts platzieren</label>
	</p>

	<p>
		<label for="mc_color_{$section_id}">Hintergrundfarbe:</label>
		<input type="text" name="color" id="mc_color_{$section_id}" value="{if $options.color}{$options.color}{/if}">
	</p>
	<p class="cc_In300px">
		<input type="submit" name="speichern" value="{translate('Save')}" >
	</p>
</form>
