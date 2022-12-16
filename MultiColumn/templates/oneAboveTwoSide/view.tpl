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

<section id="oaT_{$section_id}" class="oaT oaT{if !$options.side}L{else}R{/if}"{if $options.color} style="background: {$options.color};"{/if}>
	{assign var=count value=0}
	<div class="c_1024">
		{foreach $columns column}
		<div class="oaT_content">{if $count > 0}<div class="oaT_two {if $count == 1}oaT_left{else}oaT_right{/if}">{/if}{$column.content}{if $count > 0}</div>{/if}{assign var=count value=$count+1}</div>
		{/foreach}
	</div>
	<div class="oaT_IMG" style="{if $options.image && $options.image != ''}background-image: url({cat_url}/media/{$options.image});{/if}{if $column.options.height && $column.options.height > 0 && !$options.is_mobile}min-height:{$column.options.height}px;{/if}"></div>
</section>