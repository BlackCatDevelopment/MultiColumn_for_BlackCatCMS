<div class="container">
    {if $options.heading || $options.heading_text}
    <div class="row">
        <div class="col-md-12 heading">
            {if $options.heading}<h{$options.heading_level} class="title">{$options.heading}<span></span></h{$options.heading_level}>{/if}
            {if $options.heading_text}<p class="subtitle">{$options.heading_text}</p>{/if}
        </div>
    </div>{/if}
  	{assign var=count value=0}{assign var=width value=$options.kind}{assign var=max value=12}{assign var=width value= $max / $width}
	{foreach $columns column}
    {if $column.published}{if $options.kind != 0 && $count % $options.kind == 0}<div class="row multi-columns-row">{/if}
        <div class="col-sm-{$width} col-md-{$width} col-lg-{$width}{if $options.grid} grid{/if}">
            <div class="feature">
                {$column.content}
            </div>
        </div>
    {assign var=count value=$count+1}
	{/if}{/foreach}
	{if $count % $options.kind > 0 }</div>{/if}
    </div>
</div>

