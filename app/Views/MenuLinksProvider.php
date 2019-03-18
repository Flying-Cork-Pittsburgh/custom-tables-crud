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
			if (!function_exists('add_menu_page')) return;	// prevent runtime errors when user not logged in

			foreach (Plugin::getConfig('tables') as $tableName => $table)
			{
				$table['pageSlug'] = Plugin::getConfig('prefix').'_'.$tableName;

				$view_hook_name = add_menu_page(
					$table['pageTitle'],
					$table['menuTitle'],
					$table['capability'],
					$table['pageSlug'],
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
			Controller_Index::renderPage($current_view['tableName']);
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
					$menuLink['pageHookId'] = $key;
					$menuLinks[$menuLink['tableName']] = $menuLink;
				}
				return $menuLinks;
			}
		}
	}