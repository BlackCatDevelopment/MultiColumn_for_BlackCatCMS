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
 *   along with this program; if not, see <http://www.gnu.org/licenses/>.
 *
 *   @author			Matthias Glienke, letima development
 *   @copyright			2023, Black Cat Development
 *   @link				https://blackcat-cms.org
 *   @license			https://www.gnu.org/licenses/gpl.html
 *   @category			CAT_Modules
 *   @package			multiColumn
 *}

<div class="mC_skin fc_br_top">
	<p class="mCIcon-skin cc_toggle_set"> {translate('Set skin')}<small>({$options.variant})</small></p>
	<form action="{$CAT_URL}/modules/cc_multicolumn/save.php" method="post" class="fc_gradient1 fc_border_all_light fc_br_bottom fc_shadow_small">
		<input type="hidden" name="page_id" value="{$page_id}" />
		<input type="hidden" name="section_id" value="{$section_id}" />
		<input type="hidden" name="mc_id" value="{$mc_id}" />
		<input type="hidden" name="options" value="variant" />
		<select name="variant">
		{foreach $module_variants variants}
			<option value="{$variants}"{if $variants == $options.variant} selected="selected"{/if}>{$variants}</option>
		{/foreach}
		</select>
		<input type="submit" name="speichern" value="{translate('Save skin &amp; reload')}" /><br/>
		<input type="reset" name="reset" value="{translate('Close')}" />
	</form>
</div>