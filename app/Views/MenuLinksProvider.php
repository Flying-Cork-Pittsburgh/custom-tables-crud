<?php

	namespace PiotrKu\CustomTablesCrud\Views;

	use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Controllers\Index as Controller_Index;


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

			// delegate action where it belongs - view controller
			Controller_Index::renderPage($current_view['table_name']);
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