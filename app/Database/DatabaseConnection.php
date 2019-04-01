<?php

	namespace PiotrKu\CustomTablesCrud\Database;

	use PiotrKu\CustomTablesCrud\Database\Query;

	// Interface, so that we can deal with multiple implementations and properly
	// mock for testing.
	interface DatabaseConnection
	{
		public function connect();
		public function query(...$args);
		public function fetchAllQuery(Query $query);
		// public function fetchAll($table, ...$args);
		public function countAll($table);
		public function updateField($table, $field, $id, $value);
	}
