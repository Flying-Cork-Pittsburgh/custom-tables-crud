<?php

	namespace PiotrKu\CustomTablesCrud;

	// use WordPress_ToolKit\ObjectCache;
	// use WordPress_ToolKit\ConfigRegistry;
	// use WordPress_ToolKit\Helpers\ArrayHelper;

	use PiotrKu\CustomTablesCrud\Views\MenuLinksProvider;
	// use PiotrKu\CustomTablesCrud\Database\DatabaseConnection;
	use PiotrKu\CustomTablesCrud\Database\DatabaseConnectionFactory;


	class Plugin {

		private static $instance;
		public static $textdomain;
		private static $config;
		// public $db = NULL;

		/**
		* Construct the plugin object
		*/
		private function __construct()
		{

			\write_log('init Plugin __construct');

			self::$config = [
				'prefix'					=> 'ctcrud',
				'shortName'				=> 'Custom Tables CRUD',
				'pluginIdentifier'	=> 'custom-tables-crud/custom-tables-crud.php',
				'dependencies' 		=>
									[
										'php' => '7.0',
										'wp' => '4.8',
									],
				'tables'					=>
									[
										'wholesaler_prods' => [
											'table_name'		=> 'wholesaler_prods',
											'page_title'		=> 'Edycja produktów hurtowni',
											'menu_title'		=> 'Produkty hurtow+',
											'capability'		=> 'manage_product_import',
											'fields' => [
												'wholesaler_product_title' => [
													'type'		=> 'varchar(255)',
													'cast'		=> 'string',
													'default'	=> null,
													'null'		=> true,
												],
												'wholesaler_price' => [
													'type'		=> 'float',
													'cast'		=>	'float',
													'default'	=> null,
													'null'		=> true,
												],
												'wholesaler_offered' => [
													'type'		=> 'tinyint(3) unsigned',
													'cast'		=> 'boolean',
													'default'	=> 0,
													'null'		=> false,
												],
											],
										],
										'postal_codes' => [
											'table_name'		=> 'postal_codes',
											'page_title'		=> 'Edycja kodów pocztowych',
											'menu_title'		=> 'Kody pocztowe',
											'capability'		=> 'manage_product_import',
											'fields' => [
												'postal' => [
													'type'		=> 'char(6)',
													'cast'		=> 'string',
													'default'	=> null,
													'null'		=> true,
												],
												'address' => [
													'type'		=> 'varchar(120)',
													'cast'		=>	'string',
													'default'	=> null,
													'null'		=> true,
												],
											],
										],
									],
			];

			self::$textdomain			= self::$config['prefix'];

			register_activation_hook(self::$config['pluginIdentifier'], [$this, 'activate']);
			register_activation_hook(self::$config['pluginIdentifier'], [$this, 'deactivate']);

			add_action('plugins_loaded', [$this, 'loadDependencies']);
		}


		public static function getInstance()
		{
			if (!isset(self::$instance)) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		public static function getConfig($key)
		{
			return $key && isset(self::$config[$key]) ? self::$config[$key] : self::$config;
		}


		public static function setConfig($key, $value)
		{
			self::$config[$key] = $value;
		}




		/**
			* Initialize dependet libs and load plugin logic
			*
			*/
		public static function loadDependencies()
		{
			self::loadPlugin();
		}


		/**
			* Load plugin classes - Modify as needed, remove features that you don't need.
			*
			*/
		public function loadPlugin() {

			if (!$this->verifyDependencies()) {
				deactivate_plugins($this->config['pluginIdentifier']);
				return;
			}

			// Load shortcodes
			// new Shortcodes\Shortcode_Loader();

			// Load table setup
			// new Models\TableStructureLoader();

			// Setup DB
			// $database = (new DatabaseConnectionFactory)->getConnection();
			$databaseConnectionFactory = new DatabaseConnectionFactory();
			$database = $databaseConnectionFactory->makeConnection('mysql');
			$this->setConfig('database', $database);

			// Setup Admin page
			$menuLinksProvider = new MenuLinksProvider();
			$menuLinksProvider->addMenuPages();
			$this->setConfig('menuLinks', $menuLinksProvider->getMenuLinks());

			echo 'test';
			// Setup Admin page
			// new Controllers\IndexController();


			// die('test');
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
		public function prefix( $field_name = null, $before = '', $after = '_' ) {

			$prefix = $before . $this->config['prefix'] . $after;
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
					die( sprintf( __( '<strong>%s</strong>: One or more dependencies failed to load', $this->textdomain ), __( $this->config->get( 'plugin/meta/Name' ) ) ) );
				} else {
					return false;
				}
			}

			$requirements = new \underDEV_Requirements( __( $this->config->get( 'plugin/meta/Name' ), $this->textdomain ), $this->config->get( 'dependencies' ) );

			// Check for WordPress Toolkit
			$requirements->add_check( 'wordpress-toolkit', function( $val, $res ) {
				$wordpress_toolkit_version = defined( '\WordPress_ToolKit\VERSION' ) ? \WordPress_ToolKit\VERSION : null;
				if( !$wordpress_toolkit_version ) {
					$res->add_error( __( 'WordPress ToolKit not loaded.', $this->textdomain ) );
				} else if( version_compare( $wordpress_toolkit_version, $this->config->get( 'dependencies/wordpress-toolkit' ), '<' ) ) {
					$res->add_error( sprintf( __( 'An outdated version of WordPress ToolKit has been detected: %s (&gt;= %s required).', $this->textdomain ), $wordpress_toolkit_version, $this->config->get( 'dependencies/wordpress-toolkit' ) ) );
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
