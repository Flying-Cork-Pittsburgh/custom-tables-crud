<?php

	namespace PiotrKu\CustomTablesCrud\Controllers;

	use PiotrKu\CustomTablesCrud\Plugin;
	// use PiotrKu\CustomTablesCrud\Models\TableDataGetter;
	// use PiotrKu\CustomTablesCrud\Database\QueryPrepareTool;


	class Filter
	{
		protected $_filters;

		public function __construct()
		{
			// $this->tables = ['test'];
		}


		public function getFiltersHTML($table)
		{
			$tablesConfig 	= Plugin::getConfig('tables');

			if (empty($tablesConfig[$table])) die('config for table not found, exiting...');
			if (!$tablesConfig[$table]['filters']) return '';

			$this->_filters = $tablesConfig[$table]['filters'];

			$this->getFilterData();


			echo '<pre>';
			print_r($this->_filters);
			echo '</pre>';
			die('');

			// [wholesaler_id] => Array
			// (
			// 	 [title] => Dowolna hurtownia
			// 	 [filtertype] => wp_select
			// 	 [posttype] => wholesales
			// 	 [return] => id
			// 	 [display] => title
			// 	 [where_filter] =>  wholesaler_id = {value}
			// )

			// $cols = QueryPrepareTool::getAllowedCols($table);
			// $where = QueryPrepareTool::getWhereFilter($table);
			// $where = $where ? " WHERE {$where} " : '';
			// $query = "SELECT COUNT(".  $cols[0] . ") AS cnt FROM " . $table . " {$where} ";
		}

		protected function getFilterData()
		{
			foreach ((array)$this->_filters as $filter)
			{
				switch ($filter['filtertype'])
				{
					case 'wp_select':
						die('time to get some data');
						break;
					default:
						die('unsupported filter type');
				}

			}

		}
	}
