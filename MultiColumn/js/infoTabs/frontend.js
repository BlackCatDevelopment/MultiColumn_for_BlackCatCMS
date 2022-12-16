$(document).ready(function()
{
	if (typeof mcInfoTabsIDs !== 'undefined' && typeof mcInfoTabsIDsLoaded === 'undefined')
	{
		mcInfoTabsIDsLoaded	= true;
		$.each( mcInfoTabsIDs, function( index, mcinfoTab )
		{
			var $cur	= $('#mC_infoTabs_'+mcinfoTab.section_id),
				$arts	= $cur.find('article'),
				$navB	= $cur.find('.mc_iT_Nav');

			$arts.filter(':first').addClass('active');
			$navB.filter(':first').addClass('greenButton');

			$cur.on('click','.mc_iT_Nav',function(e) {
				e.preventDefault();
				var $t	= $(this).closest('article');
				$arts.removeClass('active');
				$t.addClass('active');
				$navB.removeClass('greenButton');
				$(this).addClass('greenButton');
			});
		});
	}
});