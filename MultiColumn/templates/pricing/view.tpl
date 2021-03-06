<div class="container">
    {if $options.heading || $options.heading_text}
    <div class="row">
        <div class="col-md-12 heading">
            {if $options.heading}<h{$options.heading_level} class="title">{$options.heading}<span></span></h{$options.heading_level}>{/if}
            {if $options.heading_text}<p class="subtitle">{$options.heading_text}</p>{/if}
        </div>
    </div>{/if}
  	{$count = 0}{$width = $options.kind}{$max = 12}{$width = $max / $width}
	{foreach $columns column}
    {if $options.kind != 0 && $count % $options.kind == 0}<div class="row multi-columns-row">{/if}
        <div class="col-sm-{$width} col-md-{$width} col-lg-{$width}{if $options.grid} grid{/if} pts">
            <div class="pt">
                <div class="pt_main" style="background-color:{$column.options.bgcolor};">
                    {if $column.options.heading}{$column.options.heading}<span></span>{/if}
                    {if $column.options.sub_heading}<h3>{$column.options.sub_heading}</h3>{/if}
                </div>
                {$column.content}
                {if $column.options.button}
                <div class="pt_button">
                    <a href="#" class="btn btn-lg btn-primary" style="background-color:{$column.options.bgcolor};border-color:{$column.options.bgcolor};">{$column.options.button}</a>
                </div>
                {/if}
            </div>
        </div>
    {$count = $count+1}
	{/foreach}
	{if $count % $options.kind > 0 }</div>{/if}
    </div>
</div>