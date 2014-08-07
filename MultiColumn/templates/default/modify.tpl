{**
 *  @template       cc_multicolumn
 *  @version        see info.php of this template
 *  @author         Matthias Glienke, creativecat
 *  @copyright      2012 Matthias Glienke
 *  @license        Copyright by Matthias Glienke, creativecat
 *  @license terms  see info.php of this template
 *  @platform       see info.php of this template
 *  @requirements   PHP 5.2.x and higher
 *}

<div class="cc_multicolumn_form">
	<form action="{$CAT_URL}/modules/cc_multicolumn/save.php" method="post">
		<div class="cc_multicolumn_header fc_gradient1">
			Administration f&uuml;r MultiColumn <span class="small">(Version: {$version})</span>
			<input type="hidden" name="page_id" value="{$page_id}" />
			<input type="hidden" name="section_id" value="{$section_id}" />
			<input type="hidden" name="mc_id" value="{$mc_id}" />
			<input type="hidden" name="options" value="variant,equalize" />
		</div>
		<div class="cc_multicolumn_option_noclick fc_gradient1">
			Optionen
		</div>
		<div class="cc_multicolumn_option_content show_on_startup cc_multicolumn_options">
			<p>
			    {translate('Module Variante')}:
			    <select name="variant">
			    {foreach $module_variants index variants}
			    	<option value="{$index}"{if $index == $options.variant} selected="selected"{/if}>{$variants}</option>
			    {/foreach}
			    </select>
			</p>
			Anzahl der Spalten pro Zeile:
			{for counter 1 6}
			<input type="submit" name="set_kind" class="set_kind column_{$counter}{if $options.kind==$counter} active{/if}" value="{$counter}" />
			{/for}
			<br/><br/>
			<input type="checkbox" name="equalize" class="fc_checkbox_jq" value="1"{if $options.equalize != 0} checked="checked"{/if} id="equalize_{$section_id}" /><label for="equalize_{$section_id}" class="right">Passe Spaltenh&ouml;he an</label>
			<input type="submit" name="add_column" class="add_column cc_multicolumn_button" value="Spalte hinzuf&uuml;gen" />
			<div class="clear"></div>
		</div>
		{$counter=0}
		{$row_counter=1}
		<div class="cc_multicolumn_option_noclick fc_gradient1">
			Zeile Nr. {$row_counter}
		</div>
		{foreach $columns as column}
			{if $counter == $options.kind}
				{$row_counter = $row_counter+1}{$counter=0}
		<div class="cc_multicolumn_option_noclick fc_gradient1">
			Zeile Nr. {$row_counter}
		</div>
			{/if}
			{$counter=$counter+1}
		<div class="cc_multicolumn_option">
			<div class="cc_multicolumn_show"></div>
			Spalte Nr. {$counter}
			<input type="submit" name="remove_column" value="{$column.column_id}" class="remove_column" />
		</div>
		<div class="cc_multicolumn_option_content">
			<input type="hidden" name="content_id[]" value="{$column.column_id}" />
				{show_wysiwyg_editor($column.contentname,$column.contentname,$column.content,$WYSIWYG.width,$WYSIWYG.height)}
		</div>
		{/foreach}

		<div class="div_submit fc_gradient1">
			<input type="reset" class="abbrechen right" value="Abbrechen" onclick="javascript: window.location = 'index.php';" />
			<input type="submit" name="save_columns" class="submit left cc_multicolumn_button" value="Speichern" />
			<div class="clear"></div>
		</div>

	</form>
</div>