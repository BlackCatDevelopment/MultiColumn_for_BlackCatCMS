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


$(document).ready(function(){
	$('.cc_multicolumn_option_content').not('.show_on_startup').slideUp(0);
	$('.cc_multicolumn_show').click( function()
	{
		var current	= $(this).closest('.cc_multicolumn_option'),
			content	= current.next('div.cc_multicolumn_option_content');
		if ( current.hasClass('active') )
		{
			content.slideUp(300);
			current.removeClass('active');
		}
		else {
			content.slideDown(300);
			current.addClass('active');
		}
	});
	$('.remove_column').click(function()
	{
		var answer = confirm("Möchten Sie dieses Sliderelement wirklich löschen?");
		if (!answer){
			return false;
		}
	});
});