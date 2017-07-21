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
 *   @author			Matthias Glienke
 *   @copyright			2014, Black Cat Development
 *   @link				http://blackcat-cms.org
 *   @license			http://www.gnu.org/licenses/gpl.html
 *   @category			CAT_Modules
 *   @package			multiColumn
 *
 *}

<div class="cc_multicolumn_form">
	<form action="{$CAT_URL}/modules/cc_multicolumn/save.php" method="post">
		<div class="cc_multicolumn_header fc_gradient1">
			{translate('Administration for')} MultiColumn <span class="small">({translate('Version')}: {$version})</span>
			<input type="hidden" name="page_id" value="{$page_id}" />
			<input type="hidden" name="section_id" value="{$section_id}" />
			<input type="hidden" name="mc_id" value="{$mc_id}" />
			<input type="hidden" name="options" value="variant" />
			<input type="hidden" name="entry_options" value="image,height" />
		</div>
		<div class="cc_multicolumn_option_noclick fc_gradient1">
			{translate('Options for frontend')}
		</div>
		<div class="cc_multicolumn_option_content show_on_startup cc_multicolumn_options">
			<p>
			    {translate('Module variant')}:
			    <select name="variant">
			    {foreach $module_variants index variants}
			    	<option value="{$index}"{if $index == $options.variant} selected="selected"{/if}>{$variants}</option>
			    {/foreach}
			    </select>
			</p>
			<input type="submit" name="add_column" class="add_column cc_multicolumn_button" value="{translate('Add column')}" />
			<div class="clear"></div>
		</div>
		{$counter=0}
		{$row_counter=1}
		<div class="cc_multicolumn_option_noclick fc_gradient1">
			{translate('Row No.')} {$row_counter}
		</div>
		{foreach $columns as column}
			{if $counter == $options.kind}
				{$row_counter = $row_counter+1}{$counter=0}
		<div class="cc_multicolumn_option_noclick fc_gradient1">
			{translate('Row No.')} {$row_counter}
		</div>
			{/if}
			{$counter=$counter+1}
		<div class="cc_multicolumn_option">
			<div class="cc_multicolumn_show"></div>
			{translate('Column No.')} {$counter}
			<input type="submit" name="remove_column" value="{$column.column_id}" class="remove_column" />
		</div>
		<div class="cc_multicolumn_option_content">
			<input type="hidden" name="content_id[]" value="{$column.column_id}" />
				Bild: <input type="text" name="image_{$column.column_id}" value="{if $column.options.image}{$column.options.image}{/if}" /><br/>
				Höhe: <input type="text" name="height_{$column.column_id}" value="{if $column.options.height}{$column.options.height}{else}500{/if}" /><br/>

				{show_wysiwyg_editor($column.contentname,$column.contentname,$column.content,$WYSIWYG.width,$WYSIWYG.height)}
		</div>
		{/foreach}

		<div class="div_submit fc_gradient1">
			<input type="reset" class="abbrechen right" value="{translate('Cancel')}" onclick="javascript: window.location = 'index.php';" />
			<input type="submit" name="save_columns" class="submit left cc_multicolumn_button" value="{translate('Save')}" />
			<div class="clear"></div>
		</div>

	</form>
</div>