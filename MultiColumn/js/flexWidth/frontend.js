$(document).ready(function()
{
	if (typeof mcFWIDs !== 'undefined' && typeof mcFWLoaded === 'undefined')
	{
		mcFWLoaded	= true;
		$.each( mcFWIDs, function( index, mcFWID )
		{
			if ( mcFWID.equalize )
			{
				var height		= 0;
				$('#mcFlexWidth_' + mcFWID.mc_id ).children('div').not('.clear').each(function()
				{
					var current_height=$(this).outerHeight();
					height=height<current_height ? current_height:height;
				});
				$('#mcFlexWidth_' + mcFWID.mc_id ).children('div').css({height:height});
			}
		});
	}
});