/**
 * This file is part of an ADDON for use with Black Cat CMS Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module			cc_multicolumn
 * @version			see info.php of this module
 * @author			Matthias Glienke, creativecat
 * @copyright		2013, Black Cat Development
 * @link			http://blackcat-cms.org
 * @license			http://www.gnu.org/licenses/gpl.html
 *
 */


$(document).ready(function()
{
	$('.cc_multicolumn_eq .cc_multicolumn_row').each( function()
	{
		var height			= 0,
			current_row		= $(this);
		current_row.children('.cc_multicolumn').each( function()
		{
			var current_height		= $(this).find('.cc_multicolumn_content').outerHeight();
			height					= height < current_height ? current_height : height;
		});
		current_row.find('.cc_multicolumn_content').css({height: height});
	});
});