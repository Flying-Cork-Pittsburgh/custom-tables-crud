<?php

	namespace PiotrKu\CustomTablesCrud\Models;

	use PiotrKu\CustomTablesCrud\Plugin;
	// use PiotrKu\CustomTablesCrud\Database\Connection;


	class TableDataGetter
	{
		public function __construct()
		{
		}

		public static function getElems($table, $limit = null, $offset = null)
		{
			$connection = Plugin::getConfig('connection');
			$results = $connection->fetchAll($table, $limit, $offset);

			return $results;
		}

		public static function countElems($table)
		{
			$connection = Plugin::getConfig('connection');
			$results = $connection->countAll($table);

			return $results;
		}
	}