<?php

	namespace PiotrKu\CustomTablesCrud\Database;

	// use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Database\DatabaseConnection;


	// The implementation we're currently using.
	final class MysqlDatabaseConnection implements DatabaseConnection
	{
	// 	public function query( ...$args ) {
	// 		// Execute query and return results.
	// 	}
	// }


	// class MysqlDBConnection
	// {

		// protected static $db			= NULL;
		protected $dbh;
		// private $instance;

		public function __construct()
		{
			\write_log('init MysqlDBConnection __construct');

			// self::$db = [
			// 	'prefix'		=> $GLOBALS['table_prefix'],
			// ];

			NULL === $this->dbh && $this->connect();
		}


		// public static function getInstance()
		// {
		// 	if (!isset(self::$instance)) {
		// 		self::$instance = new self();
		// 	}
		// 	return self::$instance;
		// }


		public function connect()
		{
			try {
				$dbh = new \PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD);
				$this->dbh = $dbh;
			} catch (PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
			}
  		}


		// public function disconnect()
		// {
		// }

		public function query( ...$args ) {
			// Execute query and return results.
		}

	}