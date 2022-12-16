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
 *   along with this program; if not, see <http://www.gnu.org/licenses>.
 *
 *   @author			Matthias Glienke
 *   @copyright			2017, Black Cat Development
 *   @link				http://blackcat-cms.org
 *   @license			http://www.gnu.org/licenses/gpl.html
 *   @category			CAT_Modules
 *   @package			catGallery
 *
 *}


<form action="{$CAT_URL}/modules/cc_multicolumn/save.php" method="post" class="ajaxForm">
	<input type="hidden" name="page_id" value="{$page_id}" >
	<input type="hidden" name="section_id" value="{$section_id}" >
	<input type="hidden" name="mc_id" value="{$mc_id}" >
	<input type="hidden" name="action" value="saveOptions" >
	<input type="hidden" name="_cat_ajax" value="1" >
	<input type="hidden" name="options" value="title1,title2,left,pageLink,buttonTitle,buttonClass">
	<p>
		<label class="cc_In100px" for="title1_{$section_id}">Titel 1 (h3):</label>
		<input type="text" name="title1" class="cc_In300px" value="{if $options.title1}{$options.title1}{/if}" id="title2_{$section_id}">
        </p>
        <p>
		<label class="cc_In100px" for="title2_{$section_id}">Titel 2 (h2):</label>
		<input type="text" name="title2" class="cc_In300px" value="{if $options.title2}{$options.title2}{/if}" id="title2_{$section_id}">
        </p>
        <p>
                <input type="checkbox" name="left" class="fc_checkbox_jq" value="1"{if $options.left} checked="checked"{/if} id="left{$section_id}" ><label for="left{$section_id}" class="cc_In300px">Überschriften Linksbündig</label>
        </p>
        <hr>
        <p>
                <label class="cc_In100px" for="pageLink{$section_id}">Link (optional):</label>
                <select name="pageLink" id="pageLink{$section_id}">
                        <option value="">--- Kein Link ---</option>
                        {foreach $pages page}
                        <option value="{$page.page_id}"{if $options.pageLink == $page.page_id} selected="selected"{/if}>{if $page.level > 0}{for i 0 $page.level-1}|--{/for}{/if}{$page.menu_title}</option>
                        {/foreach}
                </select>
        </p>
        <p>
                <label class="cc_In100px" for="buttonTitle{$section_id}">Beschriftung:</label>
                <input type="text" name="buttonTitle" class="cc_In300px" value="{if $options.buttonTitle}{$options.buttonTitle}{/if}" id="buttonTitle{$section_id}">
        </p>
        <p>
                <label class="cc_In100px" for="buttonClass{$section_id}">Icon:</label>
                <select name="buttonClass" id="buttonClass{$section_id}">
                        <option value="">Kein Icon</option>
                {foreach $icons icon}<option value="{$icon}"{if $options.buttonClass == $icon} selected="selected"{/if}>{$icon}</option>{/foreach}
                </select>
        </p>
        <hr>
	<p class="cc_In300px">
		<input type="submit" name="speichern" value="{translate('Save')}" >
	</p>
</form>
