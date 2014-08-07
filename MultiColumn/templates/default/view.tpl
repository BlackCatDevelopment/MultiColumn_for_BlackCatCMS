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

{$counter=0}
<div id="cc_multicolumn_{$section_id}"{if $options.equalize != 0} class="cc_multicolumn_eq"{/if}>
	{foreach $columns as column}
		{if $counter == 0}<div class="cc_multicolumn_row">{/if}
		{$counter = $counter + 1}
		{if $column.content != ''}
	<div class="cc_multicolumn cc_column_{$options.kind}{if $counter==$options.kind} cc_last_column{/if}">
		<div class="cc_multicolumn_border">
			<div class="cc_multicolumn_content">
				{$column.content}
			</div>
		</div>
	</div>
		{/if}
		{if $counter == $options.kind}
			<div class="clear"></div>
		</div>{$counter = 0}{/if}
	{/foreach}
	{if $counter < $options.kind && $counter != 0}</div>{/if}
	<div class="clear"></div>
</div>