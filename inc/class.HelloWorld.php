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

if (!class_exists('HelloWorld', false))
{
	class HelloWorld extends CAT_Module_Page
	{
		protected $instance = NULL;
		/**
		 * @var void
		 */
		protected static $name			= 'HelloWorld';
		protected static $directory		= 'helloworld';
		protected static $version		= '0.1';
		protected static $author		= 'BlackCat Development';
		protected static $license		= 'GNU General Public License';
		protected static $description	= 'Hello World Module of BlackCat 2.0';
		protected static $guid			= '66e8f438-680d-4fab-b236-a577edce6231';
		protected static $home			= 'https://github.com/BlackCatDevelopment/HelloWorld';
		protected static $platform		= '2.x';

		public function __construct()
		{
			parent::__construct();
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
			// TODO: implement here
			echo 'called add<br>';
			return self::$instance;
		}
	
		/**
		 *
		 */
		public static function remove()
		{
			// TODO: implement here
			return self::$instance;
		}
	
		/**
		 *
		 */
		public static function view()
		{
			// TODO: implement here
			echo 'called view<br>';
			return self::$instance;
		}
	
		/**
		 *
		 */
		public static function save()
		{
			// TODO: implement here
		}
	
		/**
		 *
		 */
		public static function modify()
		{
			// TODO: implement here
		}

		/**
		 *
		 */
		public static function install()
		{
			// TODO: implement here
		}

		/**
		 *
		 */
		public static function uninstall()
		{
			// TODO: implement here
		}

		/**
		 *
		 */
		public static function upgrade()
		{
			// TODO: implement here
		}

		/**
		 *
		 */
		public static function customFunction()
		{
			// TODO: implement here
			return self::$instance;
		}
	}
}
?>