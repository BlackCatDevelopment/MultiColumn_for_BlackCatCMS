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
 *   @link				http://blackcat-cms.org
 *   @license			http://www.gnu.org/licenses/gpl.html
 *   @category			CAT_Modules
 *   @package			multiColumn
 *
 *}

<article class="MC_imgL">
	<div style="{if $column.options.height && $column.options.height > 0 && !$options.is_mobile}min-height:{$column.options.height}px;{/if}">
		<div class="c_1000">
			<div class="MC_imgL-content">
				{$column.content}
			</div>
		</div>
		{if $column.options.imgBack && $column.options.imgBack != ''}<figure class="MC_imgL-IMG" style="{if $column.options.height && $column.options.height > 0 && !$options.is_mobile}min-height:{$column.options.height}px;{/if}">
			<img src="{cat_url}/media/{$column.options.imgBack}" alt="{$column.options.alt}">
		</figure>{/if}
	</div>
</article>