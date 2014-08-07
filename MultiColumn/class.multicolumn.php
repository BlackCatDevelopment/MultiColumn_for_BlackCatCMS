<?php
/**
 * This file is part of an ADDON for use with Black Cat CMS Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module			cc_multicolumn
 * @version			see info.php of this module
 * @author			Matthias Glienke, creativecat
 * @copyright		2014, Black Cat Development
 * @link			http://blackcat-cms.org
 * @license			http://www.gnu.org/licenses/gpl.html
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('CAT_PATH')) {	
	include(CAT_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
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
		protected static $mc_id		= NULL;
		protected static $page_id		= NULL;
		protected static $section_id	= NULL;

		public $contents		= array();
		public $options			= array();

		public static function getInstance()
		{
			if (!self::$instance)
				self::$instance = new self();
			else
				self::reset();
			return self::$instance;
		}

		public function __construct( $mc_id	= NULL )
		{
			global $page_id, $section_id;

			if ( !isset($section_id) )
			{
				$section_id	= $mc_id['section_id'];
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
			if ( CAT_Helper_Page::getInstance()->db()->query( sprintf(
					"INSERT INTO `%smod_cc_multicolumn`
						( `page_id`, `section_id` ) VALUES
						( '%s', '%s' )",
					CAT_TABLE_PREFIX,
					intval(self::$page_id),
					intval(self::$section_id)
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
						"DELETE FROM `%smod_cc_%s`
							WHERE `section_id` = '%s'",
						CAT_TABLE_PREFIX,
						$table,
						self::$section_id
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

			$values	= '';
			for($i=0;$i<$count;$i++)
			{
				$values	.= sprintf( "( '%s', '%s', '%s' ), ", self::$mc_id, self::$page_id, self::$section_id );
			}

			if ( $values != '' )
			{
				if( CAT_Helper_Page::getInstance()->db()->query( sprintf(
						"INSERT INTO `%smod_cc_multicolumn_contents`
							( `mc_id`, `page_id`, `section_id` ) VALUES
							%s",
						CAT_TABLE_PREFIX,
						substr( $values, 0, -2 )
					)
				) ) return true;
				else return false;
			} else return false;

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

			if( CAT_Helper_Page::getInstance()->db()->query( sprintf(
					"DELETE FROM `%smod_cc_multicolumn_contents`
						WHERE `section_id` = '%s' AND `column_id` = '%s'",
					CAT_TABLE_PREFIX,
					self::$section_id,
					$column_id
				)
			) && CAT_Helper_Page::getInstance()->db()->query( sprintf(
					"DELETE FROM `%smod_cc_multicolumn_content_options`
						WHERE `section_id` = '%s' AND `column_id` = '%s'",
					CAT_TABLE_PREFIX,
					self::$section_id,
					$column_id
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
			self::$mc_id		= CAT_Helper_Page::getInstance()->db()->get_one( sprintf(
					"SELECT `mc_id`
						FROM `%smod_cc_multicolumn`
						WHERE `section_id` = '%s'",
					CAT_TABLE_PREFIX,
					self::$section_id
				)
			);
			return self::$mc_id;
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
			$contents		= CAT_Helper_Page::getInstance()->db()->query( sprintf(
			    	"SELECT `content`, `column_id`
			    		FROM `%smod_cc_multicolumn_contents`
			    		WHERE `mc_id` = '%s'
			    		ORDER BY `column_id`",
			    	CAT_TABLE_PREFIX,
			    	self::$mc_id
			    )
			);
			
			if ( isset($contents) && $contents->numRows() > 0)
			{
			    while( !false == ($row = $contents->fetchRow( MYSQL_ASSOC ) ) )
			    {
			    	CAT_Helper_Page::preprocess( $row['content'] );
			
			    	$this->contents[$row['column_id']]	= array(
			    		'column_id'			=> $row['column_id'],
			    		'content'			=> $frontend ? stripslashes( $row['content'] ) : htmlspecialchars( $row['content'] ),
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
					"SELECT * FROM `%smod_%s`
						WHERE `section_id` = '%s'%s",
					CAT_TABLE_PREFIX,
					'cc_multicolumn_content_options',
					self::$section_id,
					$select
				)
			);

			$options	= array();

			if ( isset($opts) && $opts->numRows() > 0)
			{
				while( !false == ($row = $opts->fetchRow( MYSQL_ASSOC ) ) )
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

			$getOptions		= CAT_Helper_Page::getInstance()->db()->query( sprintf(
					"SELECT * FROM `%smod_%s`
						WHERE `section_id` = '%s'
						AND `column_id` = '%s'%s",
					CAT_TABLE_PREFIX,
					'cc_multicolumn_content_options',
					self::$section_id,
					$column_id,
					$name ? " AND `name` = '" . $this->toSQL( $name ) . "'" : ""
				)
			);

			if ( isset($getOptions) && $getOptions->numRows() > 0)
			{
				while( !false == ($row = $getOptions->fetchRow( MYSQL_ASSOC ) ) )
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

			if ( CAT_Helper_Page::getInstance()->db()->query( sprintf(
					"UPDATE `%smod_cc_multicolumn_contents`
						SET `content` = '%s',
							`text` = '%s'
						WHERE `mc_id` = '%s' AND
							`column_id` = '%s'",
					CAT_TABLE_PREFIX,
					$this->toSQL( $content),
					umlauts_to_entities( strip_tags( $content ), strtoupper(DEFAULT_CHARSET), 0),
					self::$mc_id,
					$column_id
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
			if ( CAT_Helper_Page::getInstance()->db()->query( sprintf(
					"REPLACE INTO `%smod_cc_%s`
						SET `page_id`		= '%s',
							`section_id`	= '%s',
							`column_id`		= '%s',
							`name`			= '%s',
							`value`			= '%s'",
					CAT_TABLE_PREFIX,
					'multicolumn_content_options',
					self::$page_id,
					self::$section_id,
					intval( $column_id ),
					$this->toSQL( $name ),
					$this->toSQL( $value )
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

			$getOptions		= CAT_Helper_Page::getInstance()->db()->query( sprintf(
					"SELECT * FROM `%smod_cc_%s`
						WHERE `section_id` = '%s'%s",
					CAT_TABLE_PREFIX,
					'multicolumn_options',
					self::$section_id,
					$name ? " AND `name` = '" . $this->toSQL( $name ) . "'" : ""
				)
			);

			if ( isset($getOptions) && $getOptions->numRows() > 0)
			{
				while( !false == ($row = $getOptions->fetchRow( MYSQL_ASSOC ) ) )
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

			if ( CAT_Helper_Page::getInstance()->db()->query( sprintf(
					"REPLACE INTO `%smod_%s` SET
						`page_id`		= '%s',
						`section_id`	= '%s',
						`name`			= '%s',
						`value`			= '%s'",
					CAT_TABLE_PREFIX,
					'cc_multicolumn_options',
					self::$page_id,
					self::$section_id,
					$this->toSQL( $name ),
					$this->toSQL( $value )
				)
			) ) return true;
			else return false;
		} // end saveOptions()




		/**
		 *
		 * @access public
		 * @return
		 **/
		private function toSQL( $value )
		{
			if ( !is_string( $value ) ) return $value;

			if ( get_magic_quotes_gpc() == 1 )
				return mysql_real_escape_string( stripslashes( $value ) );
			else {
				return mysql_real_escape_string( $value );
			}

			return NULL;
		}   // end function toSQL()


		public function getID()
		{
			return self::$mc_id;
		}

		public function getVariant()
		{
			if ( isset( $this->options['_variant'] ) )
				return $this->options['_variant'];

			$getInfo	= CAT_Helper_Addons::checkInfo( CAT_PATH . '/modules/cc_multicolumn/' );

			$this->getOptions('variant');

			$variant	= $this->options['variant'] != ''
				&& isset($getInfo['module_variants'][$this->options['variant']]) ?
						$getInfo['module_variants'][$this->options['variant']] : 
						'default';

			$this->options['_variant']	= $variant;

			return $this->options['_variant'];
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