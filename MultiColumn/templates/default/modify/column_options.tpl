{**
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
 *   @author			Matthias Glienke, letima development
 *   @copyright			2023, Black Cat Development
 *   @link				http://blackcat-cms.org
 *   @license			http://www.gnu.org/licenses/gpl.html
 *   @category			CAT_Modules
 *   @package			multiColumn
 *
 *}

<div class="MC_options">
	 <p class="drag_corner mCIcon-move" title="{translate('Reorder column')}"></p>
	 <p class="mCIcon-publish MC_publish{if $column.published} active{/if}" title="{translate('Publish this content')}"></p>
	 <div class="cc_MC_del">
		 <span class="mCIcon-remove" title="{translate('Delete this column')}"></span>
		 <p class="fc_br_right fc_shadow_small">
			 <span class="cc_MC_del_res">{translate('Keep it!')}</span>
			 <strong> | </strong>
			 <span class="cc_MC_del_conf">{translate('Confirm delete')}</span>
		 </p>
	 </div>
 </div>