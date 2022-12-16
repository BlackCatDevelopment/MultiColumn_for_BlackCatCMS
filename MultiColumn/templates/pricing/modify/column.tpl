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
		<input type="hidden" name="entry_options" value="image,symbol,cardtitle,price,button,link">
		<input type="hidden" name="_cat_ajax" value="1">
		<p>
			<strong class="cc_In300px">{translate('Header image')}:</strong><br />
            <select name="image"{if !$column} disabled{/if}>
{foreach $images img}
                <option value="{$img}"{if $column.options.image && $column.options.image == $img} selected="selected"{/if}>{$img}</option>
{/foreach}
            </select>
        <br />
            <strong class="cc_In300px">{translate('Symbol')}:</strong><br />
            <select name="symbol"{if !$column} disabled{/if}>
{foreach $symbols img}
                <option value="{$img}"{if $column.options.symbol && $column.options.symbol == $img} selected="selected"{/if}>{$img}</option>
{/foreach}
            </select>
        <br />
			<strong class="cc_In100px">{translate('Card title')}:</strong>
			<input type="text" name="cardtitle" value="{if $column.options.cardtitle}{$column.options.cardtitle}{/if}"{if !$column} disabled{/if}>
		<br />
			<strong class="cc_In100px">{translate('Price')}:</strong>
			<input type="text" name="price" value="{if $column.options.price}{$column.options.price}{/if}"{if !$column} disabled{/if}>
        <br />
			<strong class="cc_In100px">{translate('Button text')}:</strong>
			<input type="text" name="button" value="{if $column.options.button}{$column.options.button}{/if}"{if !$column} disabled{/if}>
        <br />
			<strong class="cc_In100px">{translate('Button link')}:</strong>
			<input type="text" name="link" value="{if $column.options.link}{$column.options.link}{/if}"{if !$column} disabled{/if}>
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
