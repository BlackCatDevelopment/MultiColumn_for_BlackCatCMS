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

<script >
	if (typeof mcInfoTabsIDs === 'undefined')
	\{
		mcInfoTabsIDs	= [];
	}
	mcInfoTabsIDs.push(
	\{
		'section_id'	: {$section_id},
		'mc_id'			: {$mc_id}
	});
</script>
<section id="mC_infoTabs_{$section_id}" class="mC_infoTabs">
	<div class="c_1024">
		{if $options.title2}<h2>{$options.title2}</h2>{/if}
		{if $options.title1}<h3>{$options.title1}</h3>{/if}
		<div class="infoTab-Image">
			<img src="{cat_url}/media/{$options.image}" alt="" width="" height="" data-aos="zoom-in-right">
		</div>
		<div data-aos="zoom-in-left">
			{*<nav class="mC_infoTabs_Nav">{foreach $columns column}{if $column.options.title}<a class="{if $column.options.icon} {$column.options.icon}{/if}"></a>{/if}{/foreach}</nav>*}
			{assign var=i value=1}{foreach $columns column}<article class="{if $column.options.pageURL}buttonVisible{/if}">
				{if $column.options.title}<header>
					<h4 class="mc_iT_Nav button{if $column.options.icon} {$column.options.icon}{/if}"> {$column.options.title}</h4>
				</header>{/if}
				<div class="mc_iT_Content">{$column.content}{if $column.options.pageURL}<p><a href="{if is_numeric($column.options.pageURL)}{cmsplink($column.options.pageURL)}{else}{$column.options.pageURL}{/if}" class="button{if $column.options.urlClass} {$column.options.urlClass}{/if}"{if !is_numeric($column.options.pageURL)} rel="external"{/if}>{if $column.options.urlText}{$column.options.urlText}{else}Mehr erfahren ...{/if}</a></p>{/if}
				</div>
			</article>{/foreach}
		</div>
	</div>
</section>