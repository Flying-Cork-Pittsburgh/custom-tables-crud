<?php

	namespace PiotrKu\CustomTablesCrud\Controllers;

	use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Database\QueryPrepareTool;
	use PiotrKu\CustomTablesCrud\Models\TableDataGetter;
	use PiotrKu\CustomTablesCrud\Models\Paginator;
	use PiotrKu\CustomTablesCrud\Controllers\Filter;


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
			$perPage			= Plugin::getConfig('perPage');
			$pluginDir		= Plugin::getConfig('pluginDir');
			$tablesConfig 	= Plugin::getConfig('tables');

			if (empty($tablesConfig[$table])) die('config for table not found, exiting...');


			$offset = (intval($_GET['paged'] ?? 1) - 1) * $perPage;
			$items = TableDataGetter::getElems($table, $perPage, $offset,
							[
								'order'	=> 'ASC',
								'orderby'	=> 'id',
							]
			);

			foreach ($tablesConfig[$table]['fields'] as $fkey => $field) {
				$editable_fields[$fkey] = $field['editable'];
			}

			$cols = QueryPrepareTool::getAllowedCols($table);
			$where = QueryPrepareTool::getWhereFilter($table);
			$where = $where ? " WHERE {$where} " : '';
			$query = "SELECT COUNT(".  $cols[0] . ") AS cnt FROM " . $table . " {$where} ";

			$paginator = new Paginator($query, $perPage);



			$vData = [
				'pageTitle'			=>	$tablesConfig[$table]['pageTitle'] ?? '-',
				'table'				=>	$table,
				'tableFields'		=>	$tablesConfig[$table]['fields'],
				'items'				=>	$items,
				'paginator'			=> $paginator,
				'editable_fields'	=> $editable_fields,
				'filters'			=> (new Filter)->getFiltersHTML($table),
			];

			include $pluginDir.'/views/table__index.php';
		}
	}
