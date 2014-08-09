/**
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 3 of the License, or (at
 *   your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful, but
 *   WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 *   General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, see <http://www.gnu.org/licenses/>.
 *
 *   @author			Matthias Glienke
 *   @copyright			2014, Black Cat Development
 *   @link				http://blackcat-cms.org
 *   @license			http://www.gnu.org/licenses/gpl.html
 *   @category			CAT_Modules
 *   @package			multiColumn
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