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
 *   @copyright			2014, Black Cat Development
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
	<input type="hidden" name="options" value="kind,grid,heading,heading_level,heading_text" >
	<p class="cc_In200px">
		<input type="checkbox" name="grid" class="fc_checkbox_jq" value="1"{if $options.grid != 0} checked="checked"{/if} id="grid_{$section_id}" ><label for="grid_{$section_id}" class="cc_In300px">{translate('Use grid')}</label>
	</p><br>
	<p>
		<span class="cc_In200px">{translate('Count of columns per row')}:</span>
		{for counter 1 6}<label for="mc_kind_{$counter}">&nbsp;&nbsp;&nbsp;{$counter}&nbsp;&nbsp;</label><input id="mc_kind_{$counter}" type="radio" name="kind" class="set_kind column_{$counter}{if $options.kind==$counter} active{/if}"{if $options.kind==$counter} checked{/if} value="{$counter}" >&nbsp;&nbsp;&nbsp;|{/for}
	</p><br />

    <label for="heading" class="cc_In200px">{translate('Optional heading')}:</label>
    <input class="cc_In50pc" type="text" name="heading" id="heading_{$section_id}" placeholder="{translate('Optional heading')}" value="{if $options.heading}{$options.heading}{/if}" />
    <select name="heading_level">
    {foreach array(1,2,3,4,5,6) i}
    <option value="{$i}"{if $options.heading_level && $i == $options.heading_level} selected="selected"{/if}>h{$i}</option>
    {/foreach}
    </select><br />

    <label for="heading_text" class="cc_In200px">{translate('Optional header text')}:</label>
    <textarea class="cc_In50pc" name="heading_text">{if $options.heading_text}{$options.heading_text}{/if}</textarea><br />

	<p class="cc_In300px">
		<input type="submit" name="speichern" value="{translate('Save')}" >
	</p>
</form>
