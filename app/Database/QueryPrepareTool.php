<?php

	namespace PiotrKu\CustomTablesCrud\Database;

	use PiotrKu\CustomTablesCrud\Plugin;


	class QueryPrepareTool
	{
		public function __construct()
		{
			// $this->tables = ['test'];
		}


		public static function getAllowedCols($table)
		{
			$tables = Plugin::getConfig('tables');

			if (empty($tables[$table]) || empty($tables[$table]['fields'])) return [];

			return array_keys($tables[$table]['fields']);
		}


		public static function getBaseWhereFilter($table)
		{
			$tables = Plugin::getConfig('tables');

			if (empty($tables[$table]) || empty($tables[$table]['where_filter'])) return;

			return $tables[$table]['where_filter'];
		}


		public static function getGetWhereFilter($table, $filters_get)
		{
			if (empty($filters_get)) return;

			$tables = Plugin::getConfig('tables');
			if (empty($tables[$table]) || empty($tables[$table]['filters'])) return;


			$filters_cfg = $tables[$table]['filters'];
			$where_arr = [];

			foreach ((array)$filters_get as $filter_field => $filter_value)
			{
				if (empty($filter_value)) continue;

				if (!empty($filters_cfg[$filter_field]) && !empty($filters_cfg[$filter_field]['where_filter']))
				{
					$where_arr[] = str_replace('{value}', $filter_value, $filters_cfg[$filter_field]['where_filter']);
				}
			}

			return implode(' && ' , $where_arr);
		}


		public static function verifyAllowedTable($table)
		{
			$tables = Plugin::getConfig('tables');

			if (empty($tables[$table])) return;

			return $table;
		}


		public static function verifyAllowedField($table, $fieldname, $type = 'editable')
		{
			$tables = Plugin::getConfig('tables');

			switch ($type) {
				case 'editable':
				case 'orderable':

					if (empty($tables[$table]) ||
						empty($tables[$table]['fields']) ||
						empty($tables[$table]['fields'][$fieldname]) ||
						!$tables[$table]['fields'][$fieldname][$type]) return [];

					break;
			}

			return $fieldname;
		}


		public static function getOrderDirFromQueryString()
		{
			return empty($_GET['order']) ? 'ASC' : (strtoupper($_GET['order']) === 'ASC' ? 'ASC' : 'DESC');
		}


		public static function getOrderByFromQueryString($table, $default = 'id')
		{
			if (empty($_GET['orderby'])) return $default;

			return empty(self::verifyAllowedField($table, $_GET['orderby'], 'orderable')) ? $default : $_GET['orderby'];
		}

	}