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


<div id="cc_multicolumn_{$section_id}"{if $options.equalize != 0} class="cc_multicolumn_eq"{/if}>
	{$count = 0}
	{foreach $columns column}
	{if $options.kind != 0 && $count % $options.kind == 0}
	<div class="cc_multicolumn_row">{/if}
		<div class="cc_multicolumn cc_column_{$options.kind}{if $count % $options.kind == ( $options.kind -1)} cc_last_column{/if}">
			<div class="cc_multicolumn_border">
				<div class="cc_multicolumn_content">
					{$column.content}
				</div>
			</div>
		</div>
	{if $options.kind != 0 && $count % $options.kind == ($options.kind-1)}
		<div class="clear"></div>
	</div>{/if}
	{$count = $count+1}
	{/foreach}
	{if $count % $options.kind > 0 }</div>{/if}
	<div class="clear"></div>
</div>