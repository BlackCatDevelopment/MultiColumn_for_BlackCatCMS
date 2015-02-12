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
{include(modify/javascript.tpl)}

<div class="cc_MC_form" id="cc_MC_{$mc_id}">
	{include(modify/set_skin.tpl)}
	<div class="clear"></div>
	<div class="cc_MC_settings">
		<ul class="cc_MC_nav fc_br_left" id="cc_MC_nav_{$mc_id}">
			<li class="active fc_br_topleft">{translate('Options for frontend')}</li>
			{*<li class="fc_br_bottomleft">{translate('Column option')}</li>*}
		</ul>
		<ul class="cc_MC_tabs fc_br_right">
			<li class="active cc_MC_tab">{include(modify/set_frontend.tpl)}</li>
		</ul>
		<div class="clear"></div>
	</div>
	<div class="right cc_In300px">
		<input name="colCount" class="colCount" type="text" value="1">
		<button type="submit" id="add_C_{$mc_id}" class="icon-plus cc_In200px"> {translate('Add column')}</button>
	</div>
	<p class="cc_MC_y">{translate('Existing columns')}</p>
	<p class="cc_MC_n">{translate('No columns available')}</p>
	<ul id="cc_MC_cols_{$mc_id}" class="cc_MC_cols MC_col{$options.kind}">{$c=1}{$r=1}
		<li class="clear">Column {$r}</li>
		{foreach $columns column}
		{include(modify/column.tpl)}
		{if $c == $options.kind && ($r*$options.kind) < count($columns)}{$r=$r+1}<li class="clear">Column {$r}</li>{$c=1}{else}{$c=$c+1}{/if}
		{/foreach}
		{$column = NULL}
		{include(modify/column.tpl)}
	</ul>
</div>

{include(modify/wysiwyg.tpl)}