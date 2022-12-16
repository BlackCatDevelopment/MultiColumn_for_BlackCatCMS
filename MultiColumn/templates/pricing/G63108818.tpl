<div class="mc_pricing_container">
    {if $options.heading || $options.heading_text}
    <div class="row">
        <div class="col-md-12 heading">
            {if $options.heading}<h{$options.heading_level} class="title">{$options.heading}<span></span></h{$options.heading_level}>{/if}
            {if $options.heading_text}<p class="subtitle">{$options.heading_text}</p>{/if}
        </div>
    </div>{/if}
    {foreach $columns column}
    <section class='mc_pricing_card'>
        <div class='mc_pricing_card_inner'>
            <div class='mc_pricing_card_inner__circle'>
                {if $column.options.symbol}
                <img src='{CAT_URL}/modules/cc_multicolumn/css/pricing/symbols/{$column.options.symbol}' />
                {/if}
            </div>
            <div class='mc_pricing_card_inner__header'>
                {if $column.options.image}
                <img src='{CAT_URL}/modules/cc_multicolumn/css/pricing/images/{$column.options.image}' />
                {/if}
            </div>
            <div class='mc_pricing_card_inner__content'>
                <div class='title'>{if $column.options.cardtitle}{$column.options.cardtitle}{/if}</div>
                <div class='price'>{if $column.options.price}{$column.options.price}{/if}</div>
                <div class='text'>{$column.content}</div>
            </div>
            <div class='mc_pricing_card_inner__cta'>
            {if $column.options.button}
                <a href="{if $column.options.link}{$column.options.link}{else}#{/if}²">{$column.options.button}</a>
            {/if}
            </div>
        </div>
    </section>
{/foreach}
</div>