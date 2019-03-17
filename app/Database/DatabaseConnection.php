<?php

	namespace PiotrKu\CustomTablesCrud\Database;


	// Interface, so that we can deal with multiple implementations and properly
	// mock for testing.
	interface DatabaseConnection
	{
		public function connect();
		public function query(...$args);
		public function fetchAll($table, ...$args);
	}
