<?php

	namespace PiotrKu\CustomTablesCrud\Views;

	use PiotrKu\CustomTablesCrud\Plugin;
	// use PiotrKu\CustomTablesCrud\Views\Base as View_Base;


	class MenuLinksProvider
	{
		/**
		 * @var array Shortcode class name to register
		 * @since 0.4.0
		 */

		protected $menuLinks = [];


		public function __construct()
		{
		}

		/**
		 *
		 */
		public function addMenuPages()
		{
			foreach (Plugin::getConfig('tables') as $table_name => $table)
			{
				$table['page_slug'] = Plugin::getConfig('prefix').'_'.$table_name;

				$view_hook_name = add_menu_page(
					$table['page_title'],
					$table['menu_title'],
					$table['capability'],
					$table['page_slug'],
					[$this, 'loadView'],
					'dashicons-products',
					34
				);

				$this->menuLinks[$view_hook_name] = $table;
			}
		}

		/**
		 *
		 */
		public function loadView()
		{
			// current_filter() also returns the current action
			// i.e. 'toplevel_page_ctcrud_wholesaler_prods'
			$current_view = $this->menuLinks[current_filter()];

			// include(dirname(__FILE__).'/views/'.$current_view['table_name'].'.php');
			// include(/home/vagrant/code/budio/cms/wp-content/plugins/custom-tables-crud/app/Views/views/wholesaler_prods.php):

			echo 'now time to get some data from the DB table for: ' . $current_view['table_name'];
			// die(' / test');
		}

		/**
		 *
		 */
		public function getMenuLinks($raw = false)
		{
			if ($raw)
			{
				return $this->menuLinks;
			}
			else
			{
				$menuLinks = [];
				foreach((array)$this->menuLinks as $key => $menuLink)
				{
					$menuLink['page_hook_id'] = $key;
					$menuLinks[$menuLink['table_name']] = $menuLink;
				}
				return $menuLinks;
			}
		}
	}