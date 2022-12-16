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
 *   @package			catMCallery
 *
 *}

<li class="fc_border_all fc_shadow_small fc_br_all {if !$column}prevTemp{/if}" id="catMC_{if !$column}__column_id__{else}{$column.column_id}{/if}">
	{include(../../default/modify/column_options.tpl)}
	<form action="{$CAT_URL}/modules/cc_multicolumn/save.php" method="post" class="ajaxForm">
		<input type="hidden" name="page_id" value="{$page_id}">
		<input type="hidden" name="section_id" value="{$section_id}">
		<input type="hidden" name="mc_id" value="{$mc_id}">
		<input type="hidden" name="colID" value="{if !$column}__column_id__{else}{$column.column_id}{/if}">
		<input type="hidden" name="action" value="saveColumn">
		<input type="hidden" name="entry_options" value="title,icon,pageURL,urlText,urlClass,color">
		<input type="hidden" name="_cat_ajax" value="1">
		<p>
			<strong class="cc_In100px">Überschrift:</strong>
			<input type="text" name="title" class="cc_In300px" value="{if $column.options.title}{$column.options.title}{/if}"{if !$column} disabled{/if}>
			<select name="icon">
				<option value="">Kein Icon</option>
				{foreach $icons icon}<option value="{$icon}"{if $column.options.icon == $icon} selected="selected"{/if}>{$icon}</option>{/foreach}
			</select>
		</p>
		<p>
			<strong class="cc_In200px">Link Seite (optional):</strong>
			<input type="text" name="pageURL" class="cc_In100px" value="{if $column.options.pageURL}{$column.options.pageURL}{/if}"{if !$column} disabled{/if}>
			<strong class="cc_In100px">Button Text:</strong>
			<input type="text" name="urlText" class="cc_In300px" value="{if $column.options.urlText}{$column.options.urlText}{/if}"{if !$column} disabled{/if}>	
			<strong class="cc_In100px">Klasse:</strong>
			<input type="text" name="urlClass" class="cc_In100px" value="{if $column.options.urlClass}{$column.options.urlClass}{/if}"{if !$column} disabled{/if}>

		</p>
		<p>

			   <label class="cc_In200px" for="color_{if !$column}__column_id__{else}{$column.column_id}{/if}">Individuelles Farbschema:</label>
			   <select name="color" id="color_{if !$column}__column_id__{else}{$column.column_id}{/if}">
					  <option value=""{if $column.options.color==""} selected="selected"{/if}>Keines</option>
					  <option value="uni"{if $column.options.color=="uni"} selected="selected"{/if}>Hochschule</option>
					  <option value="unt"{if $column.options.color=="unt"} selected="selected"{/if}>Unternehmen</option>
					  <option value="stud"{if $column.options.color=="stud"} selected="selected"{/if}>Studierende</option>
					  <option value="alu"{if $column.options.color=="alu"} selected="selected"{/if}>Students&Alumni</option>
			   </select>
		</p>
		{*<p class="cc_In300px">
			<input type="checkbox" name="center" class="fc_checkbox_jq" value="1"{if $column.options.center != 0} checked="checked"{/if} id="center_{$section_id}" ><label for="center_{$section_id}" class="cc_In300px">Inhalt zentrieren</label>
			<input type="checkbox" name="isH1" class="fc_checkbox_jq" value="1"{if $column.options.isH1 != 0} checked="checked"{/if} id="isH1_{$section_id}" ><label for="isH1_{$section_id}" class="cc_In300px">Ist eine H1-Überschrift</label>
		</p>
		{*<p>
			<strong class="cc_In100px">Hintergrundfarbe:</strong>
			<select name="background">
				<option value="0">weiß</option>
				<option value="1"{if $column.options.background == 1} selected="selected"{/if}>gelb</option>
				<option value="2"{if $column.options.background == 2} selected="selected"{/if}>grau</option>
			</select>
		</p>
		<p>
			<strong class="cc_In100px">Hauptinhalt:</strong>
			<select name="showContent">
				<option value="0">links</option>
				<option value="1"{if $column.options.showContent == 1} selected="selected"{/if}>rechts</option>
				<option value="2"{if $column.options.showContent == 2} selected="selected"{/if}>mittig</option>
			</select>
		</p>

		<p>
			<strong class="cc_In100px">Linkbutton:</strong>
			<input type="text" name="url" class="cc_In200px" value="{if $column.options.url}{$column.options.url}{/if}"{if !$column} disabled{/if} placeholder="URL">
			<input type="text" name="urlTitle" class="cc_In200px" value="{if $column.options.urlTitle}{$column.options.urlTitle}{/if}"{if !$column} disabled{/if} placeholder="Titel">
			<input type="text" name="urlImage" class="cc_In200px" value="{if $column.options.urlImage}{$column.options.urlImage}{/if}"{if !$column} disabled{/if} placeholder="Bild"><br>

			<strong class="cc_In100px">Animation:</strong>
			<select name="animation">
				<option value="0">keine</option>
				<option value="1"{if $column.options.animation == 1} selected="selected"{/if}>Links-Rechts</option>
				<option value="2"{if $column.options.animation == 2} selected="selected"{/if}>Hoch-Runter</option>
			</select>
		</p>
		<p>
			<strong class="cc_In100px">Zusätzliches Bild:</strong>
			<input type="text" name="optImage" class="cc_In300px" value="{if $column.options.optImage}{$column.options.optImage}{/if}"{if !$column} disabled{/if}>
		</p>*}
		
		<hr>

		<div class="cc_MC_left">
			<div id="MC_cont_{if !$column}__column_id__{else}{$column.column_id}{/if}" class="MC_content">{$column.content}</div>
		</div>
		
		<button class="showWYSIWYG input_50p fc_gradient1 fc_gradient_hover left" {if !$column}disabled{/if}>{translate('Modify content')}</button>
		<input type="submit" class="input_50p fc_br_bottomright saveCol left" value="{translate('Save column')}" {if !$column}disabled{/if}>
	</form>
	<div class="clear"></div>
</li>
