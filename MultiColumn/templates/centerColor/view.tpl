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

<section id="centerColor_{$section_id}" class="c_1024 centerColor{if $options.wHeight} mc_cC_eq{/if}{if $options.checkColor < 100} mc_cC_light{/if}" style="{if $options.color && $options.color != ''}background: {$options.color};{/if}" data-aos="zoom-in">
	{foreach $columns as column}<article {if $column.options.height && $column.options.height > 0 && !$options.is_mobile}style="min-height:{$column.options.height}px;"{/if}>{$column.content}</article>
	{/foreach}
</section>
