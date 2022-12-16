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
{assign var=c value=count($columns)}
<section id="mC_iconColumns_{$section_id}" class="mC_iconColumns{if $options.left} mc_iCLeft{/if}{if $c==1} mC_iCOne{/if}">
	{if $c%4==0}{assign var=class value="mc_iC_4"}{elseif $c%3==0}{assign var=class value="mc_iC_3"}{elseif $c%2==0}{assign var=class value="mc_iC_2"}{elseif $c%5==0}{assign var=class value="mc_iC_3"}{/if}
	<div class="c_1024 {$class}">
	{if $options.title2}<h2>{$options.title2}</h2>{/if}
	{if $options.title1}<h3>{$options.title1}</h3>{/if}
	{assign var=i value=1}{foreach $columns column}
	<article data-aos="fade-up" data-aos-delay="{math(100*$i)}{assign var=i value=$i+1}" class="{if $column.options.pageURL}buttonVisible{/if}">
		{if $column.options.title}<h4{if $column.options.icon} class="{$column.options.icon}"{/if}>{$column.options.title}</h4>{/if}
		{$column.content}
		{if $column.options.pageURL}<a href="{if is_numeric($column.options.pageURL)}{cmsplink($column.options.pageURL)}{else}{$column.options.pageURL}{/if}" class="button{if $column.options.urlClass} {$column.options.urlClass}{/if} "{if !is_numeric($column.options.pageURL)} rel="external"{/if}>{if $column.options.urlText}{$column.options.urlText}{else}Mehr erfahren...{/if}</a>{/if}
	</article>{/foreach}</div>
    {if $options.pageLink}<p class="center"><a href="{if is_numeric($options.pageLink)}{cmsplink($options.pageLink)}{else}{$options.pageLink}{/if}" class="button{if $options.buttonClass} {$options.buttonClass}{/if}"{if !is_numeric($options.pageLink)} rel="external" target="_blank"{/if} data-aos="zoom-in">{if $options.buttonTitle}{$options.buttonTitle}{else}Mehr erfahren ...{/if}</a></p>{/if}
</section>