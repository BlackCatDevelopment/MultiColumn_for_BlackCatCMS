$(document).ready(function()
{
	if (typeof mcDefIDs !== 'undefined' && typeof mcDefLoaded === 'undefined')
	{
		mcDefLoaded	= true;
		$.each( mcDefIDs, function( index, mcDefID )
		{
			if ( mcDefID.equalize )
			{
				$('#cc_MC_' + mcDefID.mc_id ).children('.cc_MC_row').each(function()
				{
					var height		= 0,
						current_row	= $(this);
					current_row.children('.cc_MC').each(function()
					{
						var current_height=$(this).find('.cc_MC_content').outerHeight();
							height = height < current_height ? current_height:height;
					});
					current_row.find('.cc_MC_content').css({height:height});
				});
			}
		});
	}
});