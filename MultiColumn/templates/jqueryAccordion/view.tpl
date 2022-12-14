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
	if (typeof mcAccIDs === 'undefined')
	\{
		mcAccIDs	= [];
	}
	mcAccIDs.push(
	\{
		'page_id'		: {$page_id},
		'section_id'	: {$section_id},
		'mc_id'			: {$mc_id},
		'kind'			: {if $options.kind}{$options.kind}{else}1{/if}
	});
</script>

<div id="mcAcc_{$mc_id}">
	{foreach $columns ind column}{if $column.published}
	<h3>{$column.options.tab_title}</h3>
	<div>{$column.content}</div>
	{/if}{/foreach}
</div>