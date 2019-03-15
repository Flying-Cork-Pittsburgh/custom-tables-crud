<?php

	namespace PiotrKu\CustomTablesCrud\Views;

	// use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Views\IndexView;


	class InitMenuLinks
	{
		/**
		 * @var array Shortcode class name to register
		 * @since 0.4.0
		 */
		protected $tables;

		public function __construct()
		{
			$this->tables = ['test'];

			add_menu_page('Edycja produktów hurtowni', 'Prod hurtowni (2)',
				'manage_product_import',		// rights
				'custom_table_index',
				[new IndexView(), 'customTableIndex'],
				'dashicons-external', 42);
		}
	}