<?php

	namespace PiotrKu\CustomTablesCrud\Controllers;

	use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Models\TableStructureLoader;


	class Index// extends Plugin
	{
		/**
		 * @var array Shortcode class name to register
		 * @since 0.4.0
		 */
		protected $tables;
		// protected $db;

		public function __construct()
		{
			// $this->tables = ['test'];
		}

		public static function renderPage($table)
		{

			// include(/home/vagrant/code/budio/cms/wp-content/plugins/custom-tables-crud/app/Views/views/wholesaler_prods.php):
			$pluginDir = Plugin::getConfig('pluginDir');

			$viewData = [
				'table' =>	$table,
			];
			include($pluginDir.'/views/table__index.php');
			// die(' / test');
		}

		public function getElems()
		{
			$elems = TableStructureLoader::getElems();

			return $elems;
		}
	}