$(document).ready(function()
{
	if (typeof mcAccIDs !== 'undefined' && typeof mcAccLoaded === 'undefined')
	{
		mcAccLoaded	= true;
		$.each( mcAccIDs, function( index, TabID )
		{
			$('#mcAcc_' + TabID.mc_id ).accordion();
		});
	}
});