<?php

	namespace PiotrKu\CustomTablesCrud;

	// use WordPress_ToolKit\ObjectCache;
	// use WordPress_ToolKit\ConfigRegistry;
	// use WordPress_ToolKit\Helpers\ArrayHelper;

	class Plugin {

		protected static $instance = NULL;
		public static $textdomain = NULL;
		public static $config = [];
		public static $db = NULL;

		/**
		* Construct the plugin object
		*/
		public function __construct()
		{
			self::$config = [
				'prefix'					=> 'ctcrud',
				'shortName'				=> 'Custom Tables CRUD',
				'pluginIdentifier'	=> 'custom-tables-crud/custom-tables-crud.php',
				'dependencies' 		=>
									[
										'php' => '7.0',
										'wp' => '4.8',
									],
			];

			self::$textdomain			= self::$config['prefix'];

			register_activation_hook(self::$config['pluginIdentifier'], [$this, 'activate']);
			register_activation_hook(self::$config['pluginIdentifier'], [$this, 'deactivate']);

			add_action('plugins_loaded', [$this, 'loadDependencies']);

		}


		public static function getInstance()
		{
			NULL === self::$instance && self::$instance = new self;
			return self::$instance;
		}


		/**
			* Initialize dependet libs and load plugin logic
			*
			*/
		public function loadDependencies()
		{
			$this->loadPlugin();
		}


		/**
			* Load plugin classes - Modify as needed, remove features that you don't need.
			*
			*/
		public function loadPlugin() {

			if (!$this->verifyDependencies()) {
				deactivate_plugins(self::$config['pluginIdentifier']);
				return;
			}

			// Load shortcodes
			// new Shortcodes\Shortcode_Loader();

			// Setup database connection
			// self::$db = new Database\Connection();

			// Load table setup
			// new Models\TableStructureLoader();


			die('test');
			// Add Customizer panels and options
			// new Settings\Customizer_Options();

			// Enqueue scripts and stylesheets
			// new EnqueueScripts();

			// Perform core plugin logic
			// new Core();
		}


		/**
			* Append a field prefix as defined in $config
			*
			* @param string $field_name The string/field to prefix
			* @param string $before String to add before the prefix
			* @param string $after String to add after the prefix
			* @return string Prefixed string/field value
			* @since 0.1.0
			*/
		public static function prefix( $field_name = null, $before = '', $after = '_' ) {

			$prefix = $before . self::$config['prefix'] . $after;
			return $field_name !== null ? $prefix . $field_name : $prefix;

		}






		/**
			* Check plugin dependencies on activation.
			*
			* @since 0.2.0
			*/
		public function activate()
		{
			$this->verifyDependencies( true, true );
		}


		/**
			* Check plugin dependencies on deactivation.
			*
			* @since 0.2.0
			*/
		public function deactivate()
		{
			$this->cleanUp();
		}


		private function cleanUp( $die = false, $activate = false )
		{
			die('CRUD plugin cleanUp');
		}
		/**
			* Function to verify dependencies, such as if an outdated version of Carbon
			*    Fields is detected.
			*
			* @param bool $die If true, plugin execution is halted with die(), useful for
			*    outputting error(s) in during activate()
			* @return bool
			* @since 0.2.0
			*/
		private function verifyDependencies( $die = false, $activate = false )
		{
			// Check if underDEV_Requirements class is loaded
			/* if( !class_exists( 'underDEV_Requirements' ) ) {
				if( $die ) {
					die( sprintf( __( '<strong>%s</strong>: One or more dependencies failed to load', self::$textdomain ), __( self::$config->get( 'plugin/meta/Name' ) ) ) );
				} else {
					return false;
				}
			}

			$requirements = new \underDEV_Requirements( __( self::$config->get( 'plugin/meta/Name' ), self::$textdomain ), self::$config->get( 'dependencies' ) );

			// Check for WordPress Toolkit
			$requirements->add_check( 'wordpress-toolkit', function( $val, $res ) {
				$wordpress_toolkit_version = defined( '\WordPress_ToolKit\VERSION' ) ? \WordPress_ToolKit\VERSION : null;
				if( !$wordpress_toolkit_version ) {
					$res->add_error( __( 'WordPress ToolKit not loaded.', self::$textdomain ) );
				} else if( version_compare( $wordpress_toolkit_version, self::$config->get( 'dependencies/wordpress-toolkit' ), '<' ) ) {
					$res->add_error( sprintf( __( 'An outdated version of WordPress ToolKit has been detected: %s (&gt;= %s required).', self::$textdomain ), $wordpress_toolkit_version, self::$config->get( 'dependencies/wordpress-toolkit' ) ) );
				}
			});

			// Display errors if requirements not met
			if( !$requirements->satisfied() ) {
				if( $die ) {
					die( $requirements->notice() );
				} else {
					add_action( 'admin_notices', array( $requirements, 'notice' ) );
					return false;
				}
			} */

			return true;
		}



	}
