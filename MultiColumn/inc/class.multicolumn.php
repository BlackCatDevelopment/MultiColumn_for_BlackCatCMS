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
 *   @copyright			2017, Black Cat Development
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

		public $variant				= 'default';
		public static $directory	= 'cc_multicolumn';
		public static $allVariants	= array();

		protected static $initOptions		= array(
			'variant'		=> 'default',
			'kind'			=> '2'
		);

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
			elseif ( is_numeric($mc_id) && !$is_header )
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
			) ){
				$this->setColumnID();
				// Add initial options for multicolumn
				foreach( self::$initOptions as $name => $val )
				{
					if( !$this->saveOptions( $name, $val ) )
						$return	= false;
				}

				$this->addColumn(self::$initOptions['kind']);

				return self::$mc_id;
			}
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

			// Delete complete record from the database
			if( CAT_Helper_Page::getInstance()->db()->query(
				'DELETE FROM `:prefix:mod_cc_multicolumn`'
					. ' WHERE `section_id` = :section_id',
				array(
					'section_id'	=> self::$section_id
				)
			) ) $return = true;
			return false;
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
			if ( !self::$mc_id ||
				!is_numeric($count) ) return false;

			$newIDs	= array();

			$pos	= CAT_Helper_Page::getInstance()->db()->query(
				'SELECT MAX(position) AS pos FROM `:prefix:mod_cc_multicolumn_contents`
					WHERE `mc_id` = :mc_id',
				array(
					'mc_id'			=> self::$mc_id
				)
			)->fetchColumn();

			for($i=0;$i<$count;$i++)
			{
				if( CAT_Helper_Page::getInstance()->db()->query(
						'INSERT INTO `:prefix:mod_cc_multicolumn_contents`
							( `mc_id`, `position` ) VALUES
							( :mc_id, :position )',
					array( 
						'mc_id'			=> self::$mc_id,
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
			if ( !self::$mc_id ||
				!$column_id ||
				!is_numeric( $column_id ) ) return false;

			if( CAT_Helper_Page::getInstance()->db()->query(
				'DELETE FROM `:prefix:mod_cc_multicolumn_contents`
					WHERE `column_id` = :column_id',
				array(
					'column_id'	=> $column_id
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
						'published'			=> $row['published'],
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
				$select		= "(" . substr( $select, 3 ) . ")";
			}
			elseif ( $column_id )
			{
				$select		= "AND `column_id` = '" . intval( $column_id ) . "'";
			}
			else return false;

			if ($column_id)
				$opts	= CAT_Helper_Page::getInstance()->db()->query( sprintf(
						'SELECT * FROM `:prefix:mod_cc_multicolumn_content_options`
								WHERE `column_id` = :column_id ',
						$select
					),
					array(
						'column_id'	=> $column_id
					)
				);
			else $opts	= CAT_Helper_Page::getInstance()->db()->query(
					'SELECT * FROM `:prefix:mod_cc_multicolumn_content_options`
							WHERE ' .
					$select,
				array(
					'column_id'	=> $column_id
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
						WHERE `column_id` = :column_id AND
							`name` = :name',
					array(
						'column_id'		=> self::$gallery_id,
						'name'			=> $name
					)
				) : 
				CAT_Helper_Page::getInstance()->db()->query(
					'SELECT * FROM `:prefix:mod_cc_multicolumn_content_options`
						WHERE `column_id` = :column_id',
					array(
						'column_id'	=> self::$gallery_id
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
					SET `column_id`		= :column_id,
						`name`			= :name,
						`value`			= :value',
				array(
					'column_id'	=> intval( $column_id ),
					'name'		=> $name,
					'value'		=> $value ? $value : ''
				)
			) ) return true;
			else return false;

		} // end saveContentOptions()

		/**
		 * (un)publish single image
		 *
		 * @access public
		 * @param  integer		$colID - id of image
		 * @return bool true/false
		 *
		 **/
		public function publishContent( $colID = NULL )
		{
			CAT_Helper_Page::getInstance()->db()->query(
				'UPDATE `:prefix:mod_cc_multicolumn_content`' .
					' SET `published` = 1 - `published`' .
				' WHERE `column_id`		= :colID',
				array(
					'colID'		=> intval( $colID )
				)
			);
			return CAT_Helper_Page::getInstance()->db()->query(
				'SELECT `published` FROM `:prefix:mod_cc_multicolumn_content`' .
				' WHERE `column_id`		= :colID',
				array(
					'colID'		=> intval( $colID )
				)
			)->fetchColumn();
		} // end publishContent()

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
						WHERE `mc_id` = :mc_id AND
							`name` = :name',
					array(
						'mc_id'	=> self::$mc_id,
						'name'	=> $name
					)
				) : 
				CAT_Helper_Page::getInstance()->db()->query(
					'SELECT * FROM `:prefix:mod_cc_multicolumn_options`
						WHERE `mc_id` = :mc_id',
					array(
						'mc_id'	=> self::$mc_id,
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
					SET `mc_id`		= :mc_id,
						`name`		= :name,
						`value`		= :value',
				array(
					'mc_id'		=> self::$mc_id,
					'name'		=> $name,
					'value'		=> is_null($value) ? '' : $value
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
							AND `column_id`		= :column_id',
					array(
						'position'		=> $index,
						'mc_id'			=> self::$mc_id,
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

			$this->getOptions('variant');

			$this->options['_variant']	= $this->options['variant'] != '' ? $this->options['variant'] : 'default';

			return $this->options['_variant'];
		}


		/**
		 * Get all available variants of an addon by checking the templates-folder
		 */
		public static function getAllVariants()
		{
			if ( count(self::$allVariants) > 0 )  return self::$allVariants;
			foreach( CAT_Helper_Directory::getInstance()->setRecursion(false)
				->scanDirectory( CAT_PATH . '/modules/' . static::$directory . '/templates/' ) as $path)
			{
				self::$allVariants[]	= basename($path);
			}
			return self::$allVariants;
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