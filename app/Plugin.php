<?php

	namespace PiotrKu\CustomTablesCrud;

	// use WordPress_ToolKit\ObjectCache;
	// use WordPress_ToolKit\ConfigRegistry;
	// use WordPress_ToolKit\Helpers\ArrayHelper;

	use PiotrKu\CustomTablesCrud\Views\MenuLinksProvider;
	// use PiotrKu\CustomTablesCrud\Database\DatabaseConnection;
	use PiotrKu\CustomTablesCrud\Database\DatabaseConnectionFactory;
	use PiotrKu\CustomTablesCrud\AjaxHandler;


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
			// \write_log('init Plugin requiest/action ' . serialize($_REQUEST));

			// no need to init the plugin when WP is running cron by cron
			if (wp_doing_cron()) return;

			self::$config = [
				'prefix'					=> 'ctcrud',
				'shortName'				=> 'Custom Tables CRUD',
				'pluginIdentifier'	=> 'custom-tables-crud/custom-tables-crud.php',
				'pluginDir'				=> dirname(dirname(__FILE__), 1),
				'dependencies' 		=>
									[
										'php' => '7.0',
										'wp' => '4.8',
									],
				'connection'			=> null,
				'perPage'				=> 10,
				'tables'					=>
									[
										'wholesaler_prods' => [
											'tableName'			=> 'wholesaler_prods',
											'pageTitle'			=> 'Edycja produktów hurtowni',
											'menuTitle'			=> 'Produkty hurtow+',
											'capability'		=> 'manage_product_import',
											'where_filter'		=> ' wholesaler_id IS NOT NULL && wholesaler_id > 0 ',
											'fields' => [
												'id' => [
													'title'		=> 'Id',
													'type'		=> 'bigint(20) unsigned',
													'cast'		=> 'int',
													'editable'	=>	false,
													'default'	=> null,
													'null'		=> false,
												],
												'wholesaler_id' => [
													'title'		=> 'Id hurtowni',
													'type'		=> 'int(10)',
													'cast'		=> 'int',
													'editable'	=>	false,
													'default'	=> null,
													'null'		=> false,
												],
												// 'wholesaler_product_id' => [
												// 	'title'		=> 'Produkt (id)',
												// 	'type'		=> 'int(10)',
												// 	'cast'		=> 'int',
												// 	'default'	=> null,
												// 	'null'		=> true,
												// ],
												'wholesaler_product_title' => [
													'title'		=> 'Nazwa produktu',
													'type'		=> 'varchar(255)',
													'cast'		=> 'string',
													'editable'	=>	false,
													'default'	=> null,
													'null'		=> true,
												],
												'wholesaler_price' => [
													'title'		=> 'Cena (hurt)',
													'type'		=> 'float',
													'cast'		=>	'float',
													'editable'	=>	true,
													'default'	=> null,
													'null'		=> true,
												],
												'wholesaler_price_promo' => [
													'title'		=> 'Cena (hurt) bez promo.',
													'type'		=> 'float',
													'cast'		=>	'float',
													'editable'	=>	true,
													'default'	=> null,
													'null'		=> true,
												],
												'wholesaler_offered' => [
													'title'		=> 'W ofercie',
													'type'		=> 'tinyint(3) unsigned',
													'cast'		=> 'boolean',
													'editable'	=>	true,
													'default'	=> 0,
													'null'		=> false,
												],
												'wholesaler_on_demand' => [
													'title'		=> 'Na zamówienia',
													'type'		=> 'tinyint(3) unsigned',
													'cast'		=> 'boolean',
													'editable'	=>	true,
													'default'	=> 0,
													'null'		=> false,
												],
												'product_id' => [
													'title'		=> 'Id produktu',
													'type'		=> 'int(10) unsigned',
													'cast'		=> 'int',
													'editable'	=>	false,
													'default'	=> null,
													'null'		=> false,
												],
												'product_producer_id' => [
													'title'		=> 'Id producenta',
													'type'		=> 'int(10) unsigned',
													'cast'		=> 'int',
													'editable'	=>	false,
													'default'	=> null,
													'null'		=> false,
												],
											],
										],
										'postal_codes' => [
											'tableName'		=> 'postal_codes',
											'pageTitle'		=> 'Edycja kodów pocztowych',
											'menuTitle'		=> 'Kody pocztowe',
											'capability'		=> 'manage_product_import',
											'where_filter'		=> '',
											'fields' => [
												'id' => [
													'title'		=> 'Id',
													'type'		=> 'int(10) unsigned',
													'cast'		=> 'int',
													'editable'	=>	false,
													'default'	=> null,
													'null'		=> false,
												],
												'postal' => [
													'title'		=> 'Kod pocztowy',
													'type'		=> 'char(6)',
													'cast'		=> 'string',
													'editable'	=>	true,
													'default'	=> null,
													'null'		=> true,
												],
												'address' => [
													'title'		=> 'Adres',
													'type'		=> 'varchar(120)',
													'cast'		=>	'string',
													'editable'	=>	true,
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

			// Load table setup
			// new Models\TableStructureLoader();

			// Setup DB
			// $database = (new DatabaseConnectionFactory)->getConnection();
			$databaseConnectionFactory = new DatabaseConnectionFactory();
			$connection = $databaseConnectionFactory->makeConnection('mysql');
			$this->setConfig('connection', $connection);

			// Setup Admin page
			$menuLinksProvider = new MenuLinksProvider();
			$menuLinksProvider->addMenuPages();
			$this->setConfig('menuLinks', $menuLinksProvider->getMenuLinks());

			// Setup Ajax hook(s)
			new AjaxHandler();

			// echo 'test';
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


		public static function tableExists($table_name)
		{
			foreach (self::getConfig('tables') as $table => $foo)
			{
				if ($table_name === $table) return true;
			}

			return;
		}


		public static function getFieldInfo($table_name, $field_name)
		{
			if (empty(self::getConfig('tables')[$table_name]['fields'])) return;
			if (empty(self::getConfig('tables')[$table_name]['fields'][$field_name])) return;

			$field_data = self::getConfig('tables')[$table_name]['fields'][$field_name];
			$field_data['fieldname'] = $field_name;

			return $field_data;
		}
	}
