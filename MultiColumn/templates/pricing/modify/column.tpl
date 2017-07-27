<li class="fc_border_all fc_shadow_small fc_br_all {if !$column}prevTemp{/if}" id="catMC_{if !$column}__column_id__{else}{$column.column_id}{/if}">
	<div class="MC_options">
		<p class="drag_corner icon-resize" title="{translate('Reorder column')}"></p>
		<div class="cc_MC_del">
			<span class="icon-remove" title="{translate('Delete this column')}"></span>
			<p class="fc_br_right fc_shadow_small">
				<span class="cc_MC_del_res">{translate('Keep it!')}</span>
				<strong> | </strong>
				<span class="cc_MC_del_conf">{translate('Confirm delete')}</span>
			</p>
		</div>
		{*<p class="icon-eye"></p>
		<p class="icon-scissors"></p>*}
	</div>
	<form action="{$CAT_URL}/modules/cc_multicolumn/save.php" method="post" class="ajaxForm">
		<input type="hidden" name="page_id" value="{$page_id}">
		<input type="hidden" name="section_id" value="{$section_id}">
		<input type="hidden" name="mc_id" value="{$mc_id}">
		<input type="hidden" name="colID" value="{if !$column}__column_id__{else}{$column.column_id}{/if}">
		<input type="hidden" name="action" value="saveColumn">
		<input type="hidden" name="entry_options" value="heading,bgcolor,sub_heading,button">
		<input type="hidden" name="_cat_ajax" value="1">
		<p>
			<strong class="cc_In300px">{translate('Background color')}:</strong><br />
            <select name="bgcolor"{if !$column} disabled{/if}>
                <option value="#5bc0de"{if $column.options.bgcolor && $column.options.bgcolor == '#5bc0de'} selected="selected"{/if}>#5bc0de ({translate('light blue')})</option>
                <option value="#f74d4e"{if $column.options.bgcolor && $column.options.bgcolor == '#f74d4e'} selected="selected"{/if}>#f74d4e ({translate('reddish')})</option>
                <option value="#31ddb7"{if $column.options.bgcolor && $column.options.bgcolor == '#31ddb7'} selected="selected"{/if}>#31ddb7 ({translate('light green')})</option>
            </select>
        <br />
			<strong class="cc_In100px">{translate('Main heading')}:</strong>
			<input type="text" name="heading" value="{if $column.options.heading}{$column.options.heading}{/if}"{if !$column} disabled{/if}>
		<br />
			<strong class="cc_In100px">{translate('Sub heading')}:</strong>
			<input type="text" name="sub_heading" value="{if $column.options.sub_heading}{$column.options.sub_heading}{/if}"{if !$column} disabled{/if}>
        <br />
			<strong class="cc_In100px">{translate('Button text')}:</strong>
			<input type="text" name="button" value="{if $column.options.button}{$column.options.button}{/if}"{if !$column} disabled{/if}>
		</p>
		<hr />
		<div class="cc_MC_left">
			<div id="MC_cont_{if !$column}__column_id__{else}{$column.column_id}{/if}" class="MC_content">{$column.content}</div>
		</div>
		<button class="showWYSIWYG input_50p fc_gradient1 fc_gradient_hover left" {if !$column}disabled{/if}>{translate('Modify content')}</button>
		<input type="submit" class="input_50p fc_br_bottomright saveCol left" value="{translate('Save column')}" {if !$column}disabled{/if}>
	</form>
	<div class="clear"></div>
</li>
