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
 *   @package			multiColumn
 *
 *}

<script type="text/javascript">
	if (typeof mcDefIDs === 'undefined')
	\{
		mcDefIDs	= [];
	}
	mcDefIDs.push(
	\{
		'page_id'		: {$page_id},
		'section_id'	: {$section_id},
		'mc_id'			: {$mc_id},
		'equalize'		: {if $options.equalize}true{else}false{/if}
	});
</script>

<div id="cc_MC_{$mc_id}"{if $options.equalize != 0} class="cc_MC_eq"{/if}>
	{$count = 0}
	{foreach $columns column}
	{if $options.kind != 0 && $count % $options.kind == 0}
	<div class="cc_MC_row">{/if}
		<div class="cc_MC cc_column_{$options.kind}{if $options.kind != 0 && $count % $options.kind == ( $options.kind -1)} cc_last_column{/if}">
			<div class="cc_MC_border">
				<div class="cc_MC_content">
					{$column.content}
				</div>
			</div>
		</div>
	{if $options.kind != 0 && $count % $options.kind == ($options.kind-1)}
		<div class="clear"></div>
	</div>{/if}
	{$count = $count+1}
	{/foreach}
	{if  $options.kind != 0 && $count % $options.kind > 0 }</div>{/if}
	<div class="clear"></div>
</div>