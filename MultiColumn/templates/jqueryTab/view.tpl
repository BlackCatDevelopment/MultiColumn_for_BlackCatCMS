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
	if (typeof mcTabsIDs === 'undefined')
	\{
		mcTabsIDs	= [];
	}
	mcTabsIDs.push(
	\{
		'page_id'		: {$page_id},
		'section_id'	: {$section_id},
		'mc_id'			: {$mc_id},
		'kind'			: {if $options.kind}{$options.kind}{else}1{/if}
	});
</script>

<div id="mcTab_{$mc_id}">
	<ul>
		{foreach $columns ind column}
		<li><a href="#mcTab-{$mc_id}-{$ind}">{$column.options.tab_title}</a></li>
		{/foreach}
	</ul>
	{foreach $columns ind column}
	<div id="mcTab-{$mc_id}-{$ind}">{$column.content}</div>
	{/foreach}
</div>