<?php

	namespace PiotrKu\CustomTablesCrud\Views;

	use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Views\Base as View_Base;


	class InitMenuLinks
	{
		/**
		 * @var array Shortcode class name to register
		 * @since 0.4.0
		 */
		protected $tables;
		protected $views = [];

		public function __construct()
		{
			// $this->tables = ['test'];

			foreach (Plugin::getConfig('tables') as $table_name => $table)
			{
				$view_hook_name = add_menu_page(
					$table['page_title'],
					$table['menu_title'],
					$table['capability'],
					Plugin::getConfig('prefix').'_'.$table_name,
					[$this, 'loadView'],
					'dashicons-products',
					34
				);

				$this->views[$view_hook_name] = $table;
			}

		}

		public function loadView()
		{
			// current_filter() also returns the current action
			$current_view = $this->views[current_filter()];

			// include(dirname(__FILE__).'/views/'.$current_view['table_name'].'.php');
			// include(/home/vagrant/code/budio/cms/wp-content/plugins/custom-tables-crud/app/Views/views/wholesaler_prods.php):

			echo 'now time to get some data from the DB';

			// die(' / test');

	  	}
	}