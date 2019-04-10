<?php

	namespace PiotrKu\CustomTablesCrud\Controllers;

	use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Database\Query;
	use PiotrKu\CustomTablesCrud\Database\QueryPrepareTool;
	use PiotrKu\CustomTablesCrud\Models\TableDataGetter;
	use PiotrKu\CustomTablesCrud\Models\Paginator;
	use PiotrKu\CustomTablesCrud\Views\TemplateHelper;
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
			$pluginDir			= Plugin::getConfig('pluginDir');

			$perPage				= Plugin::getConfig('perPage');
			$searchEnabled		= Plugin::getConfig('searchEnabled');
			$filtersEnabled	= Plugin::getConfig('filtersEnabled');

			$tablesConfig 		= Plugin::getConfig('tables');

			if (empty($tablesConfig[$table])) die('config for table not found, exiting...');

			$get_filters = empty($_GET[Plugin::prefix() . 'filter']) ? null : $_GET[Plugin::prefix() . 'filter'];
			$search_filter = empty($_GET['s']) ? null : $_GET['s'];

			$where				= " WHERE 1=1 ";

			$where_filters		= QueryPrepareTool::getGetWhereFilter($table, $get_filters);
			$where_filters		= empty($where_filters) ? ' 2=2 ' : $where_filters;

			$where_search		= QueryPrepareTool::getSearchWhereFilter($table, $search_filter);
			$where_search		= empty($where_search) ? ' 3=3 ' : $where_search;

			$where_base			= QueryPrepareTool::getBaseWhereFilter($table);
			$where_base			= $where_base ?? ' 4=4 ';

			$where .= " && {$where_base}";
			$where .= " && {$where_filters}";
			$where .= " && {$where_search}";

			$offset = (intval($_GET['paged'] ?? 1) - 1) * $perPage;

			$query = new Query();
			$query->table = $table;
			$query->where = $where;
			$query->limit = $perPage;
			$query->offset = $offset;
			$query->orderdir = QueryPrepareTool::getOrderDirFromQueryString();
			$query->orderby = QueryPrepareTool::getOrderByFromQueryString($table, 'id');

			$items = TableDataGetter::getElemsQuery($query);
			// translate results where needed ie: 'product_id' -> 'product_title'
			$items = (new DataShowAsRetriever)->retrieve($table, $items);

			foreach ($tablesConfig[$table]['fields'] as $fkey => $field) {
				$editable_fields[$fkey] = $field['editable'];
			}

			$cols = QueryPrepareTool::getAllowedCols($table);
			$query = "SELECT COUNT(".  $cols[0] . ") AS cnt FROM " . $table . " {$where} ";

			$paginator = new Paginator($query, $perPage);

			$vData = [
				'searchEnabled'	=>	$searchEnabled,
				'filtersEnabled'	=>	$filtersEnabled,
				'pageTitle'			=>	$tablesConfig[$table]['pageTitle'] ?? '-',
				'table'				=>	$table,
				'tableFields'		=>	$tablesConfig[$table]['fields'],
				'items'				=>	$items,
				'paginator'			=> $paginator,
				'editableFields'	=> $editable_fields,
				'templateHelper'	=> new TemplateHelper($table),
				'filters'			=> (new Filter)->getFiltersHTML($table),
			];

			include $pluginDir.'/views/table__index.php';
		}
	}
