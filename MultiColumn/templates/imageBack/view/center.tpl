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

<article class="MC_imgC">
	<div class="c_1000">
		<div style="{if $column.options.imgBack && $column.options.imgBack != ''}background: url({cat_url}/media/{$column.options.imgBack}) center bottom no-repeat;{/if}{if $column.options.height && $column.options.height > 0 && !$options.is_mobile}min-height:{$column.options.height}px;{/if}">
			<div class="MC_imgC-content">
				{$column.content}
			</div>
			<div class="clear"></div>
		</div>
	</div>
</article>