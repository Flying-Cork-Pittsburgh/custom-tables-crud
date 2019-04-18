<?php

	namespace PiotrKu\CustomTablesCrud;

	// use WordPress_ToolKit\ObjectCache;
	// use WordPress_ToolKit\ConfigRegistry;
	// use WordPress_ToolKit\Helpers\ArrayHelper;

	use PiotrKu\CustomTablesCrud\Views\MenuLinksProvider;
	// use PiotrKu\CustomTablesCrud\Database\DatabaseConnection;
	use PiotrKu\CustomTablesCrud\Database\DatabaseConnectionFactory;
	use PiotrKu\CustomTablesCrud\AjaxHandler;
	use PiotrKu\CustomTablesCrud\OptionsPage\OptionsCore;



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
				'pluginSlug'			=> 'custom-tables-crud',
				'pluginVersion'		=> '0.0.1',
				'pluginIdentifier'	=> 'custom-tables-crud/custom-tables-crud.php',
				'pluginDir'				=> dirname(dirname(__FILE__), 1),
				'pluginUrl'				=> dirname(plugin_dir_url(__FILE__), 1),
				'pluginPath'			=> dirname(plugin_dir_path(__FILE__), 1),
				'dependencies' 		=>
									[
										'php' => '7.0',
										'wp' => '5.0',
									],
				'connection'			=> null,
				'perPage'				=> get_option('ctcrud_per_page') ?? 20,
				'searchEnabled'		=> get_option('ctcrud_search_enabled') ?? true,
				'filtersEnabled'		=> get_option('ctcrud_filters_enabled') ?? true,
				'tables'					=> self::getDefaultTablesConfig(),
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

			new CustomSettingsGetter();

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
			new EnqueueScripts();
			new OptionsCore();

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
			return true;// die('CRUD plugin cleanUp');
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

		public static function getDefaultTablesConfig()
		{
			return [
				'wholesaler_prods' => [
					'tableName'			=> 'wholesaler_prods',
					'pageTitle'			=> 'Edycja produktów hurtowni',
					'menuTitle'			=> 'Produkty hurtow+',
					'capability'		=> 'manage_product_import',
					'where_filter'		=> ' wholesaler_id IS NOT NULL && wholesaler_id > 0 ',
					'filters'			=>	[
						'wholesaler_id'	=> [
							'title'			=> '- która hurtownia -',
							'filter_type'	=> 'wp_select',
							'post_type'		=> 'wholesales',
							'select_value'	=> 'ID',
							'select_name'	=> 'post_title',
							'where_filter'	=> ' wholesaler_id = {value} ',
						],
						'product_producer_id'	=> [
							'title'			=> '- który producent -',
							'filter_type'	=> 'wp_select',
							'post_type'		=> 'producers',
							'select_value'	=> 'ID',
							'select_name'	=> 'post_title',
							'where_filter'	=> ' product_producer_id = {value} ',
						],
						'wholesaler_offered'	=> [
							'title'			=> '- czy w ofercie -',
							'filter_type'	=> 'table_vals',
							'post_type'		=> null,
							'select_value'	=> null,
							'select_name'	=> null,
							'where_filter'	=> ' wholesaler_offered = {value} ',
						],
						'wholesaler_on_demand'	=> [
							'title'			=> '- czy na zamówienie -',
							'filter_type'	=> 'table_vals',
							'post_type'		=> null,
							'select_value'	=> null,
							'select_name'	=> null,
							'where_filter'	=> ' wholesaler_on_demand = {value} ',
						],
					],
					'fields'				=> [
						'id' => [
							'title'			=> 'Id',
							'type'			=> 'bigint(20) unsigned',
							'cast'			=> 'int',
							'editable'		=>	false,
							'orderable'		=> true,
							'searchable'	=> false,
							'default'		=> null,
							'null'			=> false,
							'showas'			=> null,
						],
						'wholesaler_id' => [
							'title'			=> 'Hurtownia',
							'type'			=> 'int(10)',
							'cast'			=> 'int',
							'editable'		=>	false,
							'orderable'		=> false,
							'searchable'	=> false,
							'default'		=> null,
							'null'			=> false,
							'showas'			=> [
									'posttype'	=> 'wholesales',
									'joinon'		=> 'ID',
									'display'	=> 'post_title',
									'link'		=> false,
							],
						],
						// 'wholesaler_product_id' => [
						// 	'title'		=> 'Produkt (id)',
						// 	'type'		=> 'int(10)',
						// 	'cast'		=> 'int',
						// 	'default'	=> null,
						// 	'null'		=> true,
						// ],
						'wholesaler_product_title' => [
							'title'			=> 'Nazwa produktu',
							'type'			=> 'varchar(255)',
							'cast'			=> 'string',
							'editable'		=>	false,
							'orderable'		=> true,
							'searchable'	=> true,
							'default'		=> null,
							'null'			=> true,
							'showas'			=> null,
						],
						'wholesaler_price' => [
							'title'			=> 'Cena (hurt)',
							'type'			=> 'float',
							'cast'			=>	'float',
							'editable'		=>	true,
							'orderable'		=> true,
							'searchable'	=> true,
							'default'		=> null,
							'null'			=> true,
							'showas'			=> null,
						],
						'wholesaler_price_promo' => [
							'title'			=> 'Cena (hurt) bez promo.',
							'type'			=> 'float',
							'cast'			=>	'float',
							'editable'		=>	true,
							'orderable'		=> true,
							'searchable'	=> true,
							'default'		=> null,
							'null'			=> true,
							'showas'			=> null,
						],
						'wholesaler_offered' => [
							'title'			=> 'W ofercie',
							'type'			=> 'tinyint(3) unsigned',
							'cast'			=> 'boolean_int',
							'editable'		=>	true,
							'orderable'		=> true,
							'searchable'	=> false,
							'default'		=> 0,
							'null'			=> false,
							'showas'			=> null,
						],
						'wholesaler_on_demand' => [
							'title'			=> 'Na zamówienia',
							'type'			=> 'tinyint(3) unsigned',
							'cast'			=> 'boolean_int',
							'editable'		=>	true,
							'orderable'		=> true,
							'searchable'	=> false,
							'default'		=> 0,
							'null'			=> false,
							'showas'			=> null,
						],
						'product_id' => [
							'title'			=> 'Id produktu',
							'type'			=> 'int(10) unsigned',
							'cast'			=> 'int',
							'editable'		=>	false,
							'orderable'		=> false,
							'searchable'	=> false,
							'default'		=> null,
							'null'			=> false,
							'showas'			=> [
									'posttype'	=> 'products',
									'joinon'		=> 'ID',
									'display'	=> 'ID',
									'link'		=> true,
							],
						],
						'product_producer_id' => [
							'title'			=> 'Producent',
							'type'			=> 'int(10) unsigned',
							'cast'			=> 'int',
							'editable'		=>	false,
							'orderable'		=> false,
							'searchable'	=> false,
							'default'		=> null,
							'null'			=> false,
							'showas'			=> [
									'posttype'	=> 'producers',
									'joinon'		=> 'ID',
									'display'	=> 'post_title',
									'link'		=> true,
							],
						],
					],
				],
				'postal_codes' => [
					'tableName'			=> 'postal_codes',
					'pageTitle'			=> 'Edycja kodów pocztowych',
					'menuTitle'			=> 'Kody pocztowe',
					'capability'		=> 'manage_product_import',
					'where_filter'		=> '',
					'filters'			=> [
					],
					'fields' => [
						'id' => [
							'title'			=> 'Id',
							'type'			=> 'int(10) unsigned',
							'cast'			=> 'int',
							'editable'		=>	false,
							'orderable'		=> false,
							'searchable'	=> false,
							'default'		=> null,
							'null'			=> false,
							'showas'			=> null,
						],
						'postal' => [
							'title'			=> 'Kod pocztowy',
							'type'			=> 'char(6)',
							'cast'			=> 'string',
							'editable'		=>	true,
							'orderable'		=> false,
							'searchable'	=> false,
							'default'		=> null,
							'null'			=> true,
							'showas'			=> null,
						],
						'address' => [
							'title'			=> 'Adres',
							'type'			=> 'varchar(120)',
							'cast'			=>	'string',
							'editable'		=>	true,
							'orderable'		=> false,
							'searchable'	=> false,
							'default'		=> null,
							'null'			=> true,
							'showas'			=> null,
						],
					],
				],
			];
		}
	}
