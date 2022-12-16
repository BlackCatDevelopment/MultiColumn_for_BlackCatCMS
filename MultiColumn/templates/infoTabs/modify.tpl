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

{include(../default/modify/javascript.tpl)}

<div class="cc_MC_form" id="cc_MC_{$mc_id}">
	{include(../default/modify/set_skin.tpl)}
	<div class="clear"></div>
	<div class="cc_MC_settings">
		<ul class="cc_MC_nav fc_br_left" id="cc_MC_nav_{$mc_id}">
			<li class="active fc_br_left">{translate('Options for frontend')}</li>
		</ul>
		<ul class="cc_MC_tabs fc_br_right">
			<li class="active cc_MC_tab">{include(modify/set_frontend.tpl)}</li>
		</ul>
		<div class="clear"></div>
	</div>
	<hr>
	{include(../default/modify/addCol.tpl)}
	<p class="cc_MC_y">{translate('Existing rows')}</p>
	<p class="cc_MC_n">{translate('No rows available')}</p>
	<ul id="cc_MC_cols_{$mc_id}" class="cc_MC_cols MC_col{$options.kind}" data-cols="{$options.kind}">{assign var=c value=1}{assign var=r value=1}
		<li class="clear">Column {$r}</li>
		{foreach $columns column}
		{include(modify/column.tpl)}
		{if $c == $options.kind && ($r*$options.kind) < count($columns)}{assign var=r value=$r+1}<li class="clear">Column {$r}</li>{assign var=c value=1}{else}{assign var=c value=$c+1}{/if}
		{/foreach}
		{assign var=column value=NULL}
		{include(../default/modify/column.tpl)}
	</ul>
</div>

{include(../default/modify/wysiwyg.tpl)}