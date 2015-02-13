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

{include(../default/modify/javascript.tpl)}

<div class="cc_MC_form" id="cc_MC_{$mc_id}">
	{include(modify/set_skin.tpl)}
	<div class="clear"></div>
	<div class="cc_MC_settings"></div>
	<div class="right cc_In300px fc_gradient1 fc_border_all_light fc_br_top colCountCont">
		<button type="submit" id="add_C_{$mc_id}" class="icon-plus cc_In200px right fc_br_right"> {translate('Add column')}</button>
		<input name="colCount" class="colCount right fc_br_left" type="text" value="1">
	</div>
	<p class="cc_MC_y">{translate('Existing rows')}</p>
	<p class="cc_MC_n">{translate('No rows available')}</p>
	<ul id="cc_MC_cols_{$mc_id}" class="cc_MC_cols MC_col{$options.kind}" data-cols="{$options.kind}">{$c=1}{$r=1}
		<li class="clear">Column {$r}</li>
		{foreach $columns column}
		{include(modify/column.tpl)}
		{if $c == $options.kind && ($r*$options.kind) < count($columns)}{$r=$r+1}<li class="clear">Column {$r}</li>{$c=1}{else}{$c=$c+1}{/if}
		{/foreach}
		{$column = NULL}
		{include(modify/column.tpl)}
	</ul>
</div>

{include(../default/modify/wysiwyg.tpl)}