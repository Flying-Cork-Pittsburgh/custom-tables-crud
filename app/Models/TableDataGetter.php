<?php

	namespace PiotrKu\CustomTablesCrud\Models;

	use PiotrKu\CustomTablesCrud\Plugin;
	// use PiotrKu\CustomTablesCrud\Database\Connection;


	class TableDataGetter
	{
		public function __construct()
		{
		}

		public static function getElems($table)
		{
			$connection = Plugin::getConfig('connection');
			$results = $connection->fetchAll($table);

			return $results;
		}
	}