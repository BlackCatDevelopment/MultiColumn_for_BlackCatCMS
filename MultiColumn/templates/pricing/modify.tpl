{include(modify/javascript.tpl)}

<div class="cc_MC_form" id="cc_MC_{$mc_id}">
	{include(modify/set_skin.tpl)}
	<div class="clear"></div>
	<div class="cc_MC_settings">
		<ul class="cc_MC_nav fc_br_left" id="cc_MC_nav_{$mc_id}">
			<li class="active fc_br_left">{translate('Options for frontend')}</li>
		</ul>
		<ul class="cc_MC_tabs fc_br_right">
			<li class="active cc_MC_tab">{include(modify/set_frontend.tpl)}</li>
		</ul>
		<div class="clear"></div>
	</div>
	{include(modify/addCol.tpl)}
	<p class="cc_MC_y">{translate('Existing rows')}</p>
	<p class="cc_MC_n">{translate('No rows available')}</p>
	<ul id="cc_MC_cols_{$mc_id}" class="cc_MC_cols MC_col{$options.kind}" data-cols="{$options.kind}">{$c=1}{$r=1}
		<li class="clear">Column {$r}</li>
		{foreach $columns column}
		{include(modify/column.tpl)}
		{if $c == $options.kind && ($r*$options.kind) < count($columns)}{$r=$r+1}<li class="clear">Column {$r}</li>{$c=1}{else}{$c=$c+1}{/if}
		{/foreach}
		{$column = NULL}
		{include(modify/column.tpl)}
	</ul>
</div>

{include(modify/wysiwyg.tpl)}