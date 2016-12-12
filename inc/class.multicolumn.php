<?php

/*
   ____  __      __    ___  _  _  ___    __   ____     ___  __  __  ___
  (  _ \(  )    /__\  / __)( )/ )/ __)  /__\ (_  _)   / __)(  \/  )/ __)
   ) _ < )(__  /(__)\( (__  )  (( (__  /(__)\  )(    ( (__  )    ( \__ \
  (____/(____)(__)(__)\___)(_)\_)\___)(__)(__)(__)    \___)(_/\/\_)(___/

   @author          Black Cat Development
   @copyright       2016 Black Cat Development
   @link            http://blackcat-cms.org
   @license         http://www.gnu.org/licenses/gpl.html
   @category        CAT_Core
   @package         CAT_Core

*/

// ADD Precheck $PRECHECK['CAT_VERSION'] = array('VERSION' => '2.0', 'OPERATOR' => '>=');

if (!class_exists('MultiColumn', false))
{
	class MultiColumn extends CAT_Addon_Page
	{
		protected $instance = NULL;

		/**
		 * @var void
		 */
		protected static $name			= 'MultiColumn';
		protected static $directory		= 'multicolumn';
		protected static $version		= '0.1';
		protected static $author		= 'Matthias Glienke, BlackCat Development';
		protected static $license		= 'GNU General Public License';
		protected static $description	= 'The addon "MultiColumn" provides a simple way to integrate multiple columns. You don\'t need to customize your frontend template. For details see <a href="https://github.com/BlackCatDevelopment/MultiColumn_for_BlackCatCMS" target="_blank">GitHub</a>.<br/><br/>Done by Matthias Glienke, <a class="icon-creativecat" href="http://creativecat.de"> creativecat</a>';
		protected static $guid			= 'b9db6fee-da6a-4203-aac4-9c50dc866ae3';
		protected static $home			= 'https://github.com/BlackCatDevelopment/MultiColumn_for_BlackCatCMS';
		protected static $platform		= '2.x';

		protected static $mc_id			= NULL;
		protected static $page_id		= NULL;
		protected static $section_id	= NULL;

		public $contents		= array();
		public $options			= array();
		public $module_variants	= array();

		protected static $initOptions		= array(
			'variant'		=> '0',
			'kind'			=> '2'
		);

		public function __construct( $mc_id	= NULL, $is_header	= false )
		{
			parent::__construct();
/*			require_once(CAT_PATH . '/framework/functions.php');

		Need to rework this code to handle headers etc..!!!!
				if ( !isset($section_id) || $is_header )
			{
				$section_id	= is_numeric($mc_id) ? $mc_id : $mc_id['section_id'];
			}

			self::$section_id	= intval($section_id);
			self::$page_id		= intval($page_id);

			if ( $mc_id === true )
			{
			}
			elseif ( is_numeric($mc_id) )
			{
				self::$mc_id	= $mc_id;
			}
			elseif ( is_numeric($section_id) && $section_id > 0 )
			{
				$this->setColumnID();
			}
			else return false;*/
		}
		public function __destruct()
		{
			parent::__destruct();
		}


		public static function getInstance()
		{
			if (!self::$instance)
			{
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 *
		 */
		public static function add()
		{
			if ( !self::$section_id || !self::$page_id ) return false;

			// Add a new MultiColum
			if ( $this->db()->query(
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
			return self::$instance;
		}
	
		/**
		 *
		 */
		public static function remove()
		{
			if ( !self::$section_id ||
				!self::$page_id ||
				!self::$mc_id ) return false;

			// Delete complete record from the database
			if( $this->db()->query(
				'DELETE FROM `:prefix:mod_multicolumn`'
					. ' WHERE `section_id` = :section_id',
				array(
					'section_id'	=> self::$section_id
				)
			) ) $return = true;
			return false;
		}
	
		/**
		 *
		 */
		public static function view()
		{
			self::$tpl_data['columns']		= $this->getContents( true );
			self::$tpl_data['mc_id']		= $this->getID();
			self::$tpl_data['options']		= $this->getOptions();

			$template		= 'view';
			
			/*	Need to include this function later again... 
				
				if ( file_exists( CAT_PATH . /modules/cc_multicolumn/ .'view/' . $variant . '/view.php' ) )
				include( CAT_PATH . /modules/cc_multicolumn/ .'view/' . $variant . '/view.php' );
			elseif ( file_exists( CAT_PATH . /modules/cc_multicolumn/ .'view/default/view.php' ) )
				include( CAT_PATH . /modules/cc_multicolumn/ .'view/default/view.php' );*/

			self::tpl()->setPath( CAT_URL . '/modules/multicolumn/templates/' . $this->getVariant() );
			self::tpl()->setFallbackPath( CAT_URL . '/modules/multicolumn/templates/default' );

			self::tpl()->output(
				$template,
				self::$tpl_data
			);
		}
	
		/**
		 *
		 */
		public static function save()
		{
			$is_ajax	= $this->sanitizePost( '_cat_ajax','numeric' );
			/*
				Maybe this should be not needed any more...
				$backend	= $is_ajax == 1
					? CAT_Backend::getInstance('Pages', 'pages_modify', false)
					: CAT_Backend::getInstance('Pages', 'pages_modify');*/

			$ajax_return	= array();

			// =============
			// ! Get perms
			// =============
			/*
				Should be done by the CAT_Page
				
				if ( CAT_Helper_Page::getPagePermission( $page_id, 'admin' ) !== true )
			{
				$backend->print_error( 'You do not have permissions to modify this page!' );
			}*/


			self::lang()->addFile( self::lang()->getLang().'.php', CAT_PATH . '/modules/cc_multicolumn/languages/' );

			/*if ( file_exists( CAT_PATH . '/modules/cc_multicolumn/save/' . $this->getVariant() . '/save.php' ) )
				include_once( CAT_PATH . '/modules/cc_multicolumn/save/' . $this->getVariant() . '/save.php' );
			elseif ( file_exists( CAT_PATH .'/modules/cc_multicolumn/save/default/save.php' ) )
				include_once( CAT_PATH . '/modules/cc_multicolumn/save/default/save.php' );*/
/*

// ============================= 
// ! Get the current mc_id   
// ============================= 
if ( $mc_id = $val->sanitizePost( 'mc_id','numeric' ) )
{
	$colID	= $val->sanitizePost( 'colID','numeric' );
	$action	= $val->sanitizePost( 'action' );

	switch ( $action )
	{
		case 'addContent':
			$colCount	= $val->sanitizePost( 'colCount' );
			$added		= $this->addColumn( $colCount );
			$ajax_return	= array(
				'message'	=> is_array($added) && count($added) > 0
					? $lang->translate( 'Column added successfully' )
					: $lang->translate( 'An error occoured' ),
				'colIDs'	=> $added,
				'success'	=> is_array($added) && count($added) > 0 ? true : false
			);
			break;
		case 'removeContent':
			$deleted	= $this->removeColumn( $colID );
			$ajax_return	= array(
				'message'	=> $deleted === true
					? $lang->translate( 'Column deleted successfully' )
					: $lang->translate( 'An error occoured' ),
				'success'	=> $deleted
			);
			break;
		case 'saveColumn':
			$success	= $this->saveContent( $colID, $val->sanitizePost('content_' . $mc_id, false, true  ) );
			$ajax_return	= array(
				'message'	=> $lang->translate( 'Column saved successfully' ),
				'success'	=> true
			);

			$entry_options	= $val->sanitizePost( 'entry_options' );
			if ( $entry_options != '' )
			{
				foreach( array_filter( explode(',', $entry_options) ) as $option )
				{
					if( !$this->saveContentOptions( $colID, $option, $val->sanitizePost( $option ) ) ) $success = false;
				}
			}

			$ajax_return	= array(
				'message'	=> $success == true
					? $lang->translate( 'Column saved successfully' )
					: $lang->translate( 'An error occoured' ),
				'success'	=> $success
			);

			break;
		case 'reorder':
			// =========================== 
			// ! save options for images   
			// =========================== 
			$success	= $this->reorderCols( $val->sanitizePost('positions') );

			$ajax_return	= array(
				'message'	=> $success === true ?
						$lang->translate( 'Columns reordered successfully' )
						: $lang->translate( 'Reorder failed' ),
				'success'	=> $success
			);
			break;
		case 'saveOptions':
			$options		= $val->sanitizePost('options');

			// =========================== 
			// ! save options for gallery   
			// =========================== 
			if ( $options != '' )
			{
				foreach( array_filter( explode(',', $options) ) as $option )
				{
					if( !$this->saveOptions( $option, $val->sanitizePost( $option ) )) $error = true;
				}
			}
			$ajax_return	= array(
				'message'	=> $lang->translate( 'Options saved successfully' ),
				'success'	=> true
			);
			break;
		default:
			// =========================== 
			// ! save variant of images   
			// =========================== 
			$this->saveOptions( 'variant', $val->sanitizePost('variant') );

			$ajax_return	= array(
				'message'	=> $lang->translate( 'Variant saved successfully' ),
				'success'	=> true
			);

			break;
	}
} else {
	$backend->print_error(
		$lang->translate( 'You sent an invalid ID' ),
		CAT_ADMIN_URL . '/pages/modify.php?page_id=' . $page_id
	);
}*/


			/*
				Check whether this is needed any more...
				 $update_when_modified = true;
				 CAT_Backend::getInstance()->updateWhenModified();
			*/


			if( $is_ajax == 1 )
			{
				print json_encode( $ajax_return );
				exit();
			} else {
				$this->print_success(
					$ajax_return['message'] ? $ajax_return['message'] : self::lang()->translate( 'Saved successfully' ),
					CAT_ADMIN_URL . '/pages/modify.php?page_id=' . self::$page_id
				);
				// Print admin footer
				$this->print_footer();	
			}

		}
	
		/**
		 *
		 */
		public static function modify()
		{
		
			self::$tpl_data	= array(
		/*		'CAT_URL'				=> CAT_URL,
				'CAT_PATH'				=> CAT_PATH,
				'CAT_ADMIN_URL'			=> CAT_ADMIN_URL,
				'page_id'				=> $page_id,
				'section_id'			=> $section_id,*/
				'version'				=> CAT_Helper_Addons::getModuleVersion('cc_multicolumn'),
				'mc_id'					=> $this->getID(),
				'variant'				=> $this->getVariant(),
				'columns'				=> $this->getContents( true, NULL ),
				'options'				=> $this->getOptions(),
				'module_variants'		=> $this->getVariants()
			);
			
			
			// =============================== 
			// ! Get columns in this section   
			// =============================== 
			
			self::$tpl_data['WYSIWYG']		= array(
				'width'		=> '100%',
				'height'	=> '300px',
				'name'		=> 'content_' . $this->getID()
			);
			
			
			/*if ( file_exists( CAT_PATH . /modules/cc_multicolumn/ .'modify/' . $this->getVariant() . '/modify.php' ) )
				include( CAT_PATH . /modules/cc_multicolumn/ .'modify/' . $this->getVariant() . '/modify.php' );
			elseif ( file_exists( CAT_PATH . /modules/cc_multicolumn/ .'modify/default/modify.php' ) )
				include( CAT_PATH . /modules/cc_multicolumn/ .'modify/default/modify.php' );
			
			if ( file_exists( CAT_PATH . /modules/cc_multicolumn/ .'templates/' . $this->getVariant() . '/modify.tpl' ) )
				$parser->setPath( dirname(__FILE__) . '/templates/' . $this->getVariant() );
			elseif ( file_exists( CAT_PATH . /modules/cc_multicolumn/ .'templates/default/modify.tpl' ) )
				$parser->setPath( dirname(__FILE__) . '/templates/default/' );*/
			
			self::tpl()->setFallbackPath( dirname( __FILE__ ) . '/templates/default' );
			
			self::tpl()->output(
				'modify',
				self::$tpl_data
			);
		}

		/**
		 *
		 */
		/*public static function install()
		{
			// This should be done automatically by the CAT_Addons
		}*/

		/**
		 *
		 */
		/*public static function uninstall()
		{
			// This should be done automatically by the CAT_Addons
		}*/

		/**
		 *
		 */
		public static function upgrade()
		{
/*
if(!isset($module_version))
{
	$details		= CAT_Helper_Addons::getAddonDetails('cc_multicolumn');
	$module_version	= $details['version'];
}

if ( CAT_Helper_Addons::versionCompare( $module_version, '2.0', '<' ) )
{
	$checkExists	= CAT_Helper_Page::getInstance()->db()->query(
		"SELECT * FROM INFORMATION_SCHEMA.COLUMNS" .
			" WHERE table_name = ':prefix:mod_cc_multicolumn_contents'" .
				" AND column_name = 'position'");
	if( $checkExists && $checkExists->rowCount() == 0 )
	{
		CAT_Helper_Page::getInstance()->db()->query("ALTER TABLE :prefix:mod_cc_multicolumn_contents ADD `position` INT NOT NULL DEFAULT '0'");
	}
}
*/
		}



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

			$pos	= $this->db()->query(
				'SELECT MAX(position) AS pos FROM `:prefix:mod_cc_multicolumn_contents`
					WHERE `mc_id` = :mc_id',
				array(
					'mc_id'			=> self::$mc_id
				)
			)->fetchColumn();

			for($i=0;$i<$count;$i++)
			{
				if( $this->db()->query(
						'INSERT INTO `:prefix:mod_cc_multicolumn_contents`
							( `mc_id`, `position` ) VALUES
							( :mc_id, :position )',
					array( 
						'mc_id'			=> self::$mc_id,
						'position'		=> ++$pos
					)
				) ) $success = true;
				$newIDs[]	= $this->db()->lastInsertId();
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

			if( $this->db()->query(
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
			$getID = $this->db()->query(
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
			$contents		= $this->db()->query(
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


			$opts	= $this->db()->query( sprintf(
					'SELECT * FROM `:prefix:mod_cc_multicolumn_content_options`
							WHERE `column_id` = :column_id',
					$select
				),
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
		 * @param  string			$thisue		- value for option
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
				$this->db()->query(
					'SELECT * FROM `:prefix:mod_cc_multicolumn_content_options`
						WHERE `column_id` = :column_id AND
							`name` = :name',
					array(
						'column_id'		=> self::$gallery_id,
						'name'			=> $name
					)
				) : 
				$this->db()->query(
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
		 * @param  string			$thisue - value for option
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
				$check = $this->db()->query(
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

			if ( $this->db()->query(
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
		 * @param  string			$thisue - value for option
		 * @return bool true/false
		 *
		 **/
		public function saveContentOptions( $column_id = NULL, $name = NULL, $thisue = '' )
		{
			if ( !$name || !$column_id ) return false;
			if ( $this->db()->query(
				'REPLACE INTO `:prefix:mod_cc_multicolumn_content_options`
					SET `column_id`		= :column_id,
						`name`			= :name,
						`value`			= :value',
				array(
					'column_id'	=> intval( $column_id ),
					'name'		=> $name,
					'value'		=> $thisue ? $thisue : ''
				)
			) ) return true;
			else return false;

		} // end saveContentOptions()

		/**
		 * get options for MultiColumn
		 *
		 * @access public
		 * @param  string			$name - name for option
		 * @param  string			$thisue - value for option
		 * @return array()
		 *
		 **/
		public function getOptions( $name = NULL )
		{
			if ( $name && isset($this->options[$name]) ) return $this->options[$name];

			$getOptions		= $name ? 
				$this->db()->query(
					'SELECT * FROM `:prefix:mod_cc_multicolumn_options`
						WHERE `mc_id` = :mc_id AND
							`name` = :name',
					array(
						'mc_id'	=> self::$mc_id,
						'name'	=> $name
					)
				) : 
				$this->db()->query(
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
		 * @param  string			$thisue - value for option
		 * @return bool true/false
		 *
		 **/
		public function saveOptions( $name = NULL, $thisue = '' )
		{
			if ( !$name ) return false;

			if ( $this->db()->query(
				'REPLACE INTO `:prefix:mod_cc_multicolumn_options`
					SET `mc_id`		= :mc_id,
						`name`		= :name,
						`value`		= :value',
				array(
					'mc_id'		=> self::$mc_id,
					'name'		=> $name,
					'value'		=> is_null($thisue) ? '' : $thisue
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

				if( !$this->db()->query(
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

/*		public function getVariant()
		{
			if ( isset( $this->options['_variant'] ) )
				return $this->options['_variant'];

			$this->getModuleVariants();
			$this->getOptions('variant');

			$variant	= $this->options['variant'] != ''
				&& isset($this->module_variants[$this->options['variant']]) ?
						$this->module_variants[$this->options['variant']] : 
						'default';

			$this->options['_variant']	= $variant;

			return $this->options['_variant'];
		}*/

		public function getHeader()
		{
			
//			$this			= new MultiColumn( $section, true );
			
			$variant		= $this->getVariant();
			
			if ( file_exists( CAT_PATH . '/modules/cc_multicolumn/headers_inc/' . $variant . '/headers.inc.php' ) )
				include( CAT_PATH . '/modules/cc_multicolumn/headers_inc/' . $variant . '/headers.inc.php' );
			elseif ( file_exists( CAT_PATH . '/modules/cc_multicolumn/headers_inc/default/headers.inc.php' ) )
				include( CAT_PATH . '/modules/cc_multicolumn/headers_inc/default/headers.inc.php' );
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
		 * copied from old version... needs to be implemented
		 *
		 **/
/*		public function search( $url = NULL )
		{
function cc_multicolumn_search($func_vars)
{
	extract($func_vars, EXTR_PREFIX_ALL, 'func');
	
	// how many lines of excerpt we want to have at most
	$max_excerpt_num = $func_default_max_excerpt;
	$divider         = ".";
	$result          = false;
	
	// we have to get 'content' instead of 'text', because strip_tags()
    // doesn't remove scripting well.
	// scripting will be removed later on automatically
	$query = $func_database->query(sprintf(
        "SELECT `content` FROM `%smod_cc_multicolumn_contents` WHERE section_id='%d'",
        CAT_TABLE_PREFIX, $func_section_id
	));

	if($query->numRows() > 0)
    {
		if($res = $query->fetchRow())
        {
            if(CAT_Helper_Addons::isModuleInstalled('kit_framework'))
            {
                // remove all kitCommands from the content
                preg_match_all('/(~~)( |&nbsp;)(.){3,512}( |&nbsp;)(~~)/', $res['content'], $matches, PREG_SET_ORDER);
                foreach ($matches as $match) {
                    $res['content'] = str_replace($match[0], '', $res['content']);
                }
            }
			$mod_vars = array(
				'page_link'          => $func_page_link,
				'page_link_target'   => SEC_ANCHOR."#section_$func_section_id",
				'page_title'         => $func_page_title,
				'page_description'   => $func_page_description,
				'page_modified_when' => $func_page_modified_when,
				'page_modified_by'   => $func_page_modified_by,
				'text'               => $res['content'].$divider,
				'max_excerpt_num'    => $max_excerpt_num
			);
			if(print_excerpt2($mod_vars, $func_vars)) {
				$result = true;
			}
		}
	}
	return $result;
}
		}
*/
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