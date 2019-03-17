<?php

	namespace PiotrKu\CustomTablesCrud\Database;

	use PiotrKu\CustomTablesCrud\Plugin;


	class QueryParametersTool
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

	}