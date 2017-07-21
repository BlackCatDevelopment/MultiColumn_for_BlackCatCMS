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


<div id="wide_content_{$section_id}" class="wide_content" {if $options.equalize != 0} class="wide_content_eq"{/if}>
	<div class="c_960">
		{$count = 0}
		{foreach $columns column}
		{if $options.kind != 0 && $count % $options.kind == 0}
		<div class="wide_content_row">{/if}
			<div class=" cc_column_{$options.kind}{if $count % $options.kind == ( $options.kind -1)} cc_last_column{/if}">
			{$column.content}
			</div>
		{if $options.kind != 0 && $count % $options.kind == ($options.kind-1)}
			<div class="clear"></div>
		</div>{/if}
		{$count = $count+1}
		{/foreach}
		{if $count % $options.kind > 0 }</div>{/if}
		<div class="clear"></div>
	</div>
</div>