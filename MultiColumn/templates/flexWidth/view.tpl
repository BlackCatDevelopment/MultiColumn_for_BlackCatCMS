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

<script type="text/javascript">
	if (typeof mcFWIDs === 'undefined')
	\{
		mcFWIDs	= [];
	}
	mcFWIDs.push(
	\{
		'page_id'		: {$page_id},
		'section_id'	: {$section_id},
		'mc_id'			: {$mc_id},
		'equalize'		: {if $options.equalize}true{else}false{/if}
	});
</script>

<div id="mcFlexWidth_{$mc_id}" class="mcFlexWidth">
	{foreach $columns ind column}
	<div{if $column.options.col_width} style="width:{$column.options.col_width}%;"{/if}>{$column.content}</div>
	{/foreach}
	<div class="clear"></div>
</div>