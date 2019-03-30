<?php

	namespace PiotrKu\CustomTablesCrud\Models;

	use PiotrKu\CustomTablesCrud\Plugin;
	// use PiotrKu\CustomTablesCrud\Database\Connection;


	class TableDataGetter
	{
		public function __construct()
		{
		}


		public static function getElems($table, $where = '', $limit = null, $offset = null,
			$order = null, $groupby = null, $cols = null)
		{
			$connection = Plugin::getConfig('connection');
			$results = $connection->fetchAll($table, $where, $limit, $offset, $order, $groupby, $cols);

			return $results;
		}


		public static function countElems($table)
		{
			$connection = Plugin::getConfig('connection');
			$results = $connection->countAll($table);

			return $results;
		}


		public static function countQueryResults($query)
		{
			$connection = Plugin::getConfig('connection');
			$results = $connection->countQueryResults($query);

			return $results;
		}

		public function isFieldEditable($table, $field)
		{
			# code...
		}
	}