<?php

	namespace PiotrKu\CustomTablesCrud\Controllers;

	use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Database\Query;
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

			$get_filters = empty($_GET[Plugin::prefix() . 'filter']) ? null : $_GET[Plugin::prefix() . 'filter'];

			$where			= " WHERE 1=1 ";
			$where_get		= QueryPrepareTool::getGetWhereFilter($table, $get_filters);
			$where_get		= $where_get ?? ' 2=2 ';
			$where_base		= QueryPrepareTool::getBaseWhereFilter($table);
			$where_base		= $where_base ?? ' 3=3 ';

			$where .= " && {$where_base}";
			$where .= " && {$where_get}";

			$offset = (intval($_GET['paged'] ?? 1) - 1) * $perPage;

			$query = new Query();
			$query->table = $table;
			$query->where = $where;
			$query->limit = $perPage;
			$query->offset = $offset;
			$query->orderdir = 'ASC';
			$query->orderby = 'id';

			$items = TableDataGetter::getElemsQuery($query);
			$items = (new DataShowAsRetriever)->retrieve($table, $items);

			foreach ($tablesConfig[$table]['fields'] as $fkey => $field) {
				$editable_fields[$fkey] = $field['editable'];
			}

			$cols = QueryPrepareTool::getAllowedCols($table);
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
