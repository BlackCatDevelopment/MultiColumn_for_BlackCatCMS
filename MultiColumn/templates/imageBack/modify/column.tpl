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
 *
 *}

<li class="fc_border_all fc_shadow_small fc_br_all {if !$column}prevTemp{/if}" id="catMC_{if !$column}__column_id__{else}{$column.column_id}{/if}">
	{include(../../default/modify/column_options.tpl)}
	<form action="{$CAT_URL}/modules/cc_multicolumn/save.php" method="post">
		<input type="hidden" name="page_id" value="{$page_id}">
		<input type="hidden" name="section_id" value="{$section_id}">
		<input type="hidden" name="mc_id" value="{$mc_id}">
		<input type="hidden" name="colID" value="{if !$column}__column_id__{else}{$column.column_id}{/if}">
		<input type="hidden" name="action" value="saveColumn">
		<input type="hidden" name="entry_options" value="imgBack,type,height,alt">
		<input type="hidden" name="_cat_ajax" value="1">
		<div class="cc_MC_left">
			<div id="MC_cont_{if !$column}__column_id__{else}{$column.column_id}{/if}" class="MC_content">{$column.content}</div>
			<hr>
			<p class="{if !$column}cc_catG_disabled{/if}">
				<strong>Bild platzieren:<br></strong>
				<select name="type" {if !$column}disabled{/if}>
					<option value="0"{if $column.options.type == 0} selected="selected"{/if}>Mittig</option>
					<option value="1"{if $column.options.type == 1} selected="selected"{/if}>Links</option>
					<option value="2"{if $column.options.type == 2} selected="selected"{/if}>Rechts</option>
				</select>

				<strong>Hintergrundbild - relativer Link im Mediaverzeichnis: /media/...:<br></strong>
				<input name="imgBack" type="text" {if !$column}disabled{/if} value="{$column.options.imgBack}">
				<strong>Alternativtext<br></strong>
				<input name="alt" type="text" {if !$column}disabled{/if} value="{$column.options.alt}">
				<strong>Mindesthöhe<br></strong>
				<input name="height" type="text" {if !$column}disabled{/if} value="{$column.options.height}">px

			</p>
		</div>
		<button class="showWYSIWYG input_50p fc_gradient1 fc_gradient_hover left" {if !$column}disabled{/if}>{translate('Modify content')}</button>
		<input type="submit" class="input_50p fc_br_bottomright saveCol left" value="{translate('Save column')}" {if !$column}disabled{/if}>
	</form>
	<div class="clear"></div>
</li>
