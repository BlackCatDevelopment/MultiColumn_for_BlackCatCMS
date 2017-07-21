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
 *   @copyright			2017, Black Cat Development
 *   @link				http://blackcat-cms.org
 *   @license			http://www.gnu.org/licenses/gpl.html
 *   @category			CAT_Modules
 *   @package			catMCallery
 *
 *}

<li class="fc_border_all fc_shadow_small fc_br_all {if !$column}prevTemp{/if}" id="catMC_{if !$column}__column_id__{else}{$column.column_id}{/if}">
	<div class="MC_options">
		<p class="drag_corner icon-resize" title="{translate('Reorder column')}"></p>
		<div class="cc_MC_del">
			<span class="icon-remove" title="{translate('Delete this column')}"></span>
			<p class="fc_br_right fc_shadow_small">
				<span class="cc_MC_del_res">{translate('Keep it!')}</span>
				<strong> | </strong>
				<span class="cc_MC_del_conf">{translate('Confirm delete')}</span>
			</p>
		</div>
		{*<p class="icon-eye"></p>
		<p class="icon-scissors"></p>*}
	</div>
	<form action="{$CAT_URL}/modules/cc_multicolumn/save.php" method="post" class="ajaxForm">
		<input type="hidden" name="page_id" value="{$page_id}">
		<input type="hidden" name="section_id" value="{$section_id}">
		<input type="hidden" name="mc_id" value="{$mc_id}">
		<input type="hidden" name="colID" value="{if !$column}__column_id__{else}{$column.column_id}{/if}">
		<input type="hidden" name="action" value="saveColumn">
		<input type="hidden" name="entry_options" value="tab_title">
		<input type="hidden" name="_cat_ajax" value="1">
		<p>
			<strong class="cc_In100px">{translate('Tab title')}:</strong>
			<input type="text" name="tab_title" value="{if $column.options.tab_title}{$column.options.tab_title}{/if}"{if !$column} disabled{/if}>
		</p>
		<hr>
		<div class="cc_MC_left">
			<div id="MC_cont_{if !$column}__column_id__{else}{$column.column_id}{/if}" class="MC_content">{$column.content}</div>
		</div>
		<button class="showWYSIWYG input_50p fc_gradient1 fc_gradient_hover left" {if !$column}disabled{/if}>{translate('Modify content')}</button>
		<input type="submit" class="input_50p fc_br_bottomright saveCol left" value="{translate('Save column')}" {if !$column}disabled{/if}>
	</form>
	<div class="clear"></div>
</li>
