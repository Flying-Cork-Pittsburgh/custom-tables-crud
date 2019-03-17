<?php

	namespace PiotrKu\CustomTablesCrud\Controllers;

	use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Models\TableDataGetter;
	use PiotrKu\CustomTablesCrud\Controllers\Core;


	class Index extends Core
	{
		// protected $tables;
		// protected $db;

		public function __construct()
		{
			// $this->tables = ['test'];
		}

		public static function renderPage($table)
		{
			$items = TableDataGetter::getElems($table);

			// include(/home/vagrant/code/budio/cms/wp-content/plugins/custom-tables-crud/app/Views/views/wholesaler_prods.php):
			$pluginDir		= Plugin::getConfig('pluginDir');
			$tablesConfig 	= Plugin::getConfig('tables');

			if (empty($tablesConfig[$table])) die('config for table not found, exiting...');


			$vData = [
				'pageTitle'		=>	$tablesConfig[$table]['pageTitle'] ?? '-',
				'table'			=>	$table,
				'tableFields'	=>	$tablesConfig[$table]['fields'],
				'items'			=>	$items,
			];

			include $pluginDir.'/views/table__index.php';
		}
	}
