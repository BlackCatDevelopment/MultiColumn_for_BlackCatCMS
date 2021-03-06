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
        <div class="col-sm-{$width} col-md-{$width} col-lg-{$width}{if $options.grid} grid{/if}">
            <div class="feature">
                {$column.content}
            </div>
        </div>
    {$count = $count+1}
	{/foreach}
	{if $count % $options.kind > 0 }</div>{/if}
    </div>
</div>

