$(document).ready(function()
{
	if (typeof mcTabsIDs !== 'undefined' && typeof mcTabsLoaded === 'undefined')
	{
		mcTabsLoaded	= true;
		$.each( mcTabsIDs, function( index, TabID )
		{
			$('#mcTab_' + TabID.mc_id ).tabs();
		});
	}
});