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