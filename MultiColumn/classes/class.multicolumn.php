<?php
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

// include class.secure.php to protect this file and the whole CMS!
if (defined('CAT_PATH')) {
	include(CAT_PATH.'/framework/class.secure.php');
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) {
		include($root.'/framework/class.secure.php');
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

if ( ! class_exists( 'MultiColumn', false ) ) {
	class MultiColumn
	{
		protected static $mc_id			= NULL;
		protected static $page_id		= NULL;
		protected static $section_id	= NULL;

		public $contents		= array();
		public $options			= array();
		public $module_variants	= array();

		public static function getInstance()
		{
			if (!self::$instance)
				self::$instance = new self();
			else
				self::reset();
			return self::$instance;
		}

		public function __construct( $mc_id	= NULL, $is_header	= false )
		{
			global $page_id, $section_id;
			require_once(CAT_PATH . '/framework/functions.php');
			if ( !isset($section_id) || $is_header )
			{
				$section_id	= is_numeric($mc_id) ? $mc_id : $mc_id['section_id'];
			}

			self::$section_id	= intval($section_id);
			self::$page_id		= intval($page_id);

			if ( $mc_id === true )
			{
				return $this->initAdd();
			}
			elseif ( is_numeric($mc_id) )
			{
				self::$mc_id	= $mc_id;
			}
			elseif ( is_numeric($section_id) && $section_id > 0 )
			{
				$this->setColumnID();
			}
			else return false;

		}

		public function __destruct() {}

		/**
		 * return, if in a current object all important values are existing (page_id, section_id, gallery_id)
		 *
		 * @access public
		 * @param  integer  $image_id - optional check for $image_id to be numeric
		 * @return boolean true/false
		 *
		 **/
		private function checkIDs( $colID = NULL )
		{
			if ( !self::$section_id ||
				!self::$page_id ||
				!self::$mc_id ||
				( $colID && !is_numeric( $colID ) )
			) return false;
			else return true;
		}

		/**
		 * add new MultiColumn
		 *
		 * @access public
		 * @return integer
		 *
		 **/
		private function initAdd()
		{
			if ( !self::$section_id || !self::$page_id ) return false;

			// Add a new MultiColum
			if ( CAT_Helper_Page::getInstance()->db()->query(
				'INSERT INTO `:prefix:mod_cc_multicolumn`
					( `page_id`, `section_id` ) VALUES
					( :page_id, :section_id )',
				array(
					'page_id'		=> self::$page_id,
					'section_id'	=> self::$section_id
				)
			) ) return $this->setColumnID();
			else return false;
		} // initAdd()

		/**
		 * delete a MultiColumn
		 *
		 * @access public
		 * @return integer
		 *
		 **/
		public function deleteMC()
		{
			if ( !self::$section_id ||
				!self::$page_id ||
				!self::$mc_id ) return false;

			$return	= true;

			foreach(
				array( 'multicolumn', 'multicolumn_contents', 'multicolumn_options', 'multicolumn_content_options' )
				as $table )
			{
				// Delete complete record from the database
				if( !CAT_Helper_Page::getInstance()->db()->query( sprintf(
						"DELETE FROM `:prefix:mod_cc_%s`
							WHERE `section_id` = :section_id",
						$table
					),
					array(
						'section_id'	=> self::$section_id
					)
				) ) $return = false;
			}
			return $return;
		}

		/**
		 * add new column
		 *
		 * @access public
		 * @return integer
		 *
		 **/
		public function addColumn( $count = 1 )
		{
			if ( !self::$section_id ||
				!self::$page_id ||
				!self::$mc_id ||
				!is_numeric($count) ) return false;

			$newIDs	= array();

			$pos	= CAT_Helper_Page::getInstance()->db()->query(
				'SELECT MAX(position) AS pos FROM `:prefix:mod_cc_multicolumn_contents`
					WHERE `page_id`			= :page_id
						AND `section_id`	= :section_id
						AND `mc_id`			= :mc_id',
				array(
					'page_id'		=> self::$page_id,
					'section_id'	=> self::$section_id,
					'mc_id'			=> self::$mc_id
				)
			)->fetchColumn();

			for($i=0;$i<$count;$i++)
			{
				if( CAT_Helper_Page::getInstance()->db()->query(
						'INSERT INTO `:prefix:mod_cc_multicolumn_contents`
							( `mc_id`, `page_id`, `section_id`, `position` ) VALUES
							( :mc_id, :page_id, :section_id, :position )',
					array( 
						'mc_id'			=> self::$mc_id,
						'page_id'		=> self::$page_id,
						'section_id'	=> self::$section_id,
						'position'		=> ++$pos
					)
				) ) $success = true;
				$newIDs[]	= CAT_Helper_Page::getInstance()->db()->lastInsertId();
			}
			if ( $success )
				return $newIDs;
			else return false;
		}



		/**
		 * remove Column
		 *
		 * @access public
		 * @return integer
		 *
		 **/
		public function removeColumn( $column_id = NULL )
		{
			if ( !self::$section_id ||
				!self::$page_id ||
				!self::$mc_id ||
				!$column_id ||
				!is_numeric( $column_id ) ) return false;

			if( CAT_Helper_Page::getInstance()->db()->query(
				'DELETE FROM `:prefix:mod_cc_multicolumn_contents`
					WHERE `section_id` = :section_id AND `column_id` = :column_id',
				array(
					'section_id'	=> self::$section_id,
					'column_id'		=> $column_id
				)
			) && CAT_Helper_Page::getInstance()->db()->query(
				'DELETE FROM `:prefix:mod_cc_multicolumn_content_options`
					WHERE `section_id` = :section_id AND `column_id` = :column_id',
				array(
					'section_id'	=> self::$section_id,
					'column_id'		=> $column_id
				)
			) ) return true;
			else return false;
		}

		/**
		 * set the $mc_id by self:$sectionid
		 *
		 * @access private
		 * @return integer
		 *
		 **/
		private function setColumnID()
		{
			// Get columns in this section
			$getID = CAT_Helper_Page::getInstance()->db()->query(
				'SELECT `mc_id`
					FROM `:prefix:mod_cc_multicolumn`
					WHERE `section_id` = :section_id',
				array(
					'section_id'	=> self::$section_id
				)
			);
			if ( ( $getID && $getID->rowCount() > 0 ) )
				if ( !false == ($row = $getID->fetch() ) )
			{
				self::$mc_id	= $row['mc_id'];
				return self::$mc_id;
			} else return false;
		} // end setColumnID()


		/**
		 * get all offers from database
		 *
		 * @access public
		 * @param  string  $option		-
		 									true => get all active posts
		 									NULL => get all inactive and active posts
		 									numeric => get all inactive and active posts
		 * @param  string  $addContent	- if table to print - default false
		 * @return array()
		 *
		 **/
		public function getContents( $addOptions = false, $frontend = true )
		{
			$contents		= CAT_Helper_Page::getInstance()->db()->query(
				'SELECT `content`, `column_id`
					FROM `:prefix:mod_cc_multicolumn_contents`
					WHERE `mc_id` = :mc_id
					ORDER BY `position`',
				array(
					'mc_id'	=> self::$mc_id
				)
			);
			
			if ( $contents && $contents->numRows() > 0)
			{
			    while( !false == ($row = $contents->fetchRow() ) )
			    {
			    	if ($frontend) CAT_Helper_Page::preprocess( $row['content'] );
			
			    	$this->contents[$row['column_id']]	= array(
			    		'column_id'			=> $row['column_id'],
			    		'content'			=> stripslashes( $row['content'] ),
			    		'contentname'		=> sprintf( 'content_%s_%s', self::$section_id, $row['column_id'] )
			    	);
			    }
			}

			if ( $addOptions )
			{
				$this->getContentOptions();
			}
			return $this->contents;
		} // end getContents()


		/**
		 * get all offers from database
		 *
		 * @access public
		 * @param  string/array  $id - id/ids of offer
		 * @param  string  $output - if table to print - default false
		 * @return array()
		 *
		 **/
		private function getContentOptions( $column_id = NULL )
		{

			$select	= '';

			if ( !$column_id && count( $this->contents ) > 0 )
			{
				foreach ( array_keys( $this->contents ) as $id )
				{
					$select	.= " OR `column_id` = '" . intval( $id ) . "'";
				}
				$select		= "AND (" . substr( $select, 3 ) . ")";
			}
			elseif ( $column_id )
			{
				$select		= "AND `column_id` = '" . intval( $column_id ) . "'";
			}
			else return false;


			$opts	= CAT_Helper_Page::getInstance()->db()->query( sprintf(
					'SELECT * FROM `:prefix:mod_cc_multicolumn_content_options`
							WHERE `section_id` = :section_id',
					$select
				),
				array(
					'section_id'	=> self::$section_id
				)
			);

			$options	= array();

			if ( $opts && $opts->numRows() > 0)
			{
				while( !false == ($row = $opts->fetchRow() ) )
				{
					$options[$row['column_id']][$row['name']]		= $row['value'];

					if ( isset($this->contents[$row['column_id']]['options']) )
						$this->contents[$row['column_id']]['options']	= array_merge(
							$this->contents[$row['column_id']]['options'],
							array(
								$row['name']		=> $row['value']
							)
						);
					else $this->contents[$row['column_id']]['options']	= array(
							$row['name']	=> $row['value']
						);

				}
			}
			if ( $column_id )
				return $this->contents[$column_id]['options'];
			else
				return $options;
		} // end getOptions()

		/**
		 * get all offers from database
		 *
		 * @param  string/array		$column_id	- id for content column
		 * @param  string			$name		- name for option
		 * @param  string			$value		- value for option
		 * @return array()
		 *
		 **/
		public function getSingContentOptions( $column_id = NULL, $name = NULL )
		{
			if ( !$column_id ) return false;

			if ( $name &&
				isset($this->contents[$column_id]['options'][$name])
			) return $this->contents[$column_id]['options'][$name];

			$getOptions		= $name ? 
				CAT_Helper_Page::getInstance()->db()->query(
					'SELECT * FROM `:prefix:mod_cc_multicolumn_content_options`
						WHERE `section_id` = :section_id AND
							`column_id` = :column_id AND
							`name` = :name',
					array(
						'section_id'	=> self::$section_id,
						'column_id'		=> self::$gallery_id,
						'name'			=> $name
					)
				) : 
				CAT_Helper_Page::getInstance()->db()->query(
					'SELECT * FROM `:prefix:mod_cc_multicolumn_content_options`
						WHERE `section_id` = :section_id AND
							`column_id` = :column_id',
					array(
						'section_id'	=> self::$section_id,
						'column_id'		=> self::$gallery_id
					)
			);

			if ( isset($getOptions) && $getOptions->numRows() > 0)
			{
				while( !false == ($row = $getOptions->fetchRow() ) )
				{
					$this->contents[$row['column_id']]['options'][$row['name']]	= $row['value'];
				}
			}
			if ( $name
				&& $column_id
				&& isset($this->contents[$column_id]['options'][$name]) )
					return $this->contents[$column_id]['options'][$name];
			if ( $column_id 
				&& isset($this->contents[$column_id]['options']) )
					return $this->contents[$column_id]['options'];
			return false;
		} // end getSingContentOptions()


		/**
		 * save options for single colums to database
		 *
		 * @access public
		 * @param  string/array		$column_id - id/ids of content column
		 * @param  string			$name - name for option
		 * @param  string			$value - value for option
		 * @return bool true/false
		 *
		 **/
		public function saveContent( $column_id = NULL, $content = '' )
		{
		
			if ( !self::$section_id ||
				!self::$page_id ||
				!self::$mc_id ||
				!$column_id ||
				!is_numeric( $column_id ) ) return false;

			// for non-admins only
			if(!CAT_Users::getInstance()->ami_group_member(1))
			{
				// if HTMLPurifier is enabled...
				$check = CAT_Helper_Page::getInstance()->db()->query(
					'SELECT * FROM `:prefix:mod_wysiwyg_admin_v2`
						WHERE `set_name`= :name
						AND `set_value`= :val',
					array(
						'name'	=> 'enable_htmlpurifier',
						'val'	=> 1
					)
				);
				if ( $check && $check->numRows() > 0)
				{
					// use HTMLPurifier to clean up the output
					$content = CAT_Helper_Protect::getInstance()->purify($content,array('Core.CollectErrors'=>true));
				}
			}

			if ( CAT_Helper_Page::getInstance()->db()->query(
				'UPDATE `:prefix:mod_cc_multicolumn_contents`
					SET `content` = :content,
						`text` = :text
					WHERE `mc_id` = :mc_id AND
						`column_id` = :column_id',
				array(
					'content'	=> $content,
					'text'		=> umlauts_to_entities( strip_tags( $content ), strtoupper(DEFAULT_CHARSET), 0),
					'mc_id'		=> self::$mc_id,
					'column_id'	=> $column_id
				)
			) ) return true;
			else return false;

		} // end saveContent()

		/**
		 * save options for single colums to database
		 *
		 * @access public
		 * @param  string/array		$column_id - id/ids of content column
		 * @param  string			$name - name for option
		 * @param  string			$value - value for option
		 * @return bool true/false
		 *
		 **/
		public function saveContentOptions( $column_id = NULL, $name = NULL, $value = '' )
		{
			if ( !$name || !$column_id ) return false;
			if ( CAT_Helper_Page::getInstance()->db()->query(
				'REPLACE INTO `:prefix:mod_cc_multicolumn_content_options`
					SET `page_id`		= :page_id,
						`section_id`	= :section_id,
						`column_id`		= :column_id,
						`name`			= :name,
						`value`			= :value',
				array(
					'page_id'		=> self::$page_id,
					'section_id'	=> self::$section_id,
					'column_id'		=> intval( $column_id ),
					'name'			=> $name,
					'value'			=> $value ? $value : ''
				)
			) ) return true;
			else return false;

		} // end saveContentOptions()

		/**
		 * get options for MultiColumn
		 *
		 * @access public
		 * @param  string			$name - name for option
		 * @param  string			$value - value for option
		 * @return array()
		 *
		 **/
		public function getOptions( $name = NULL )
		{
			if ( $name && isset($this->options[$name]) ) return $this->options[$name];


			$getOptions		= $name ? 
				CAT_Helper_Page::getInstance()->db()->query(
					'SELECT * FROM `:prefix:mod_cc_multicolumn_options`
						WHERE `section_id` = :section_id AND
							`name` = :name',
					array(
						'section_id'	=> self::$section_id,
						'name'			=> $name
					)
				) : 
				CAT_Helper_Page::getInstance()->db()->query(
					'SELECT * FROM `:prefix:mod_cc_multicolumn_options`
						WHERE `section_id` = :section_id',
					array(
						'section_id'	=> self::$section_id,
					)
				);

			if ( isset($getOptions) && $getOptions->numRows() > 0)
			{
				while( !false == ($row = $getOptions->fetchRow() ) )
				{
					$this->options[$row['name']]	= $row['value'];
				}
			}
			if ( $name )
			{
				if ( isset( $this->options[$name] ) )
					return $this->options[$name];
				else
					return NULL;
			}
			return $this->options;
		} // end getOptions()


		/**
		 * save options for MultiColumn
		 *
		 * @access public
		 * @param  string			$name - name for option
		 * @param  string			$value - value for option
		 * @return bool true/false
		 *
		 **/
		public function saveOptions( $name = NULL, $value = '' )
		{
			if ( !$name ) return false;

			if ( CAT_Helper_Page::getInstance()->db()->query(
				'REPLACE INTO `:prefix:mod_cc_multicolumn_options`
					SET `page_id`		= :page_id,
						`section_id`	= :section_id,
						`name`			= :name,
						`value`			= :value',
				array(
					'page_id'		=> self::$page_id,
					'section_id'	=> self::$section_id,
					'name'			=> $name,
					'value'			=> $value ? $value : ''
				)
			) ) return true;
			else return false;
		} // end saveOptions()

		/**
		 * reorder columns
		 *
		 * @access public
		 * @param  array			$colIDs - Strings from jQuery sortable()
		 * @return bool true/false
		 *
		 **/
		public function reorderCols( $colIDs = array() )
		{
			if ( !$this->checkIDs()
				|| !is_array($colIDs)
				|| count($colIDs) == 0
			) return false;

			$return	= true;

			foreach( $colIDs as $index => $colStr )
			{
				$colID	= explode('_', $colStr);

				if( !CAT_Helper_Page::getInstance()->db()->query(
					'UPDATE `:prefix:mod_cc_multicolumn_contents`
						SET `position` = :position
						WHERE `mc_id`			= :mc_id
							AND `page_id`		= :page_id
							AND `section_id`	= :section_id
							AND `column_id`		= :column_id',
					array(
						'position'		=> $index,
						'mc_id'			=> self::$mc_id,
						'page_id'		=> self::$page_id,
						'section_id'	=> self::$section_id,
						'column_id'		=> $colID[count($colID)-1]
					)
				) ) $return = false;
			}
			return $return;
		} // end reorderCols()

		public function getID()
		{
			return self::$mc_id;
		}

		public function getVariant()
		{
			if ( isset( $this->options['_variant'] ) )
				return $this->options['_variant'];

			$this->getModuleVariants();
			$this->getOptions('variant');

			$variant	= isset($this->options['variant'])
                && $this->options['variant'] != ''
				&& isset($this->module_variants[$this->options['variant']]) ?
						$this->module_variants[$this->options['variant']] : 
						'default';

			$this->options['_variant']	= $variant;

			return $this->options['_variant'];
		}

		public function getModuleVariants()
		{
			if ( count($this->module_variants) > 0 ) return $this->module_variants;
			$getInfo	= CAT_Helper_Addons::checkInfo( CAT_PATH . '/modules/cc_multicolumn/' );

			$this->module_variants	= $getInfo['module_variants'];

			return $this->module_variants;
		}

		/**
		 *
		 *
		 *
		 *
		 **/
		public function sanitizeURL( $url = NULL )
		{
			if ( !$url ) return false;
			$parts	= array_filter( explode( '/', $url ) );
			return	implode('/', $parts);
		}

	}
}

?>