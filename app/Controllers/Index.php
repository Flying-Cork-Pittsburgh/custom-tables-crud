<?php

	namespace PiotrKu\CustomTablesCrud\Controllers;

	use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Models\TableDataGetter;
	use PiotrKu\CustomTablesCrud\Models\Paginator;
	// use PiotrKu\CustomTablesCrud\Controllers\Core;


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
			$items = TableDataGetter::getElems($table, 5, intval($_GET['paged'] ?? 1));

			$paginator = new Paginator($table);		// should be by query not just $table
			$pagination = $paginator->paginate();

			$pluginDir		= Plugin::getConfig('pluginDir');
			$tablesConfig 	= Plugin::getConfig('tables');

			if (empty($tablesConfig[$table])) die('config for table not found, exiting...');


			$vData = [
				'pageTitle'		=>	$tablesConfig[$table]['pageTitle'] ?? '-',
				'table'			=>	$table,
				'tableFields'	=>	$tablesConfig[$table]['fields'],
				'items'			=>	$items,
				'pagination'	=> $pagination,
			];

			include $pluginDir.'/views/table__index.php';
		}
	}
