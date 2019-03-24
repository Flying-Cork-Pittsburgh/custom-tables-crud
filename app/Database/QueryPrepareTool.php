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


		public static function getWhereFilter($table)
		{
			$tables = Plugin::getConfig('tables');

			if (empty($tables[$table]) || empty($tables[$table]['where_filter'])) return;

			return $tables[$table]['where_filter'];
		}


		public static function verifyAllowedTable($table)
		{
			$tables = Plugin::getConfig('tables');

			if (empty($tables[$table])) return;

			return $table;
		}


		public static function verifyAllowedField($table, $fieldname)
		{
			$tables = Plugin::getConfig('tables');

			if (empty($tables[$table]) ||
					empty($tables[$table]['fields']) ||
					empty($tables[$table]['fields'][$fieldname]) ||
					!$tables[$table]['fields'][$fieldname]['editable']) return [];

			return $fieldname;
		}

	}