<?php

	namespace PiotrKu\CustomTablesCrud\Database;

	use PiotrKu\CustomTablesCrud\Database\DatabaseConnection;
	use PiotrKu\CustomTablesCrud\Database\QueryParametersTool;


	// The implementation we're currently using.
	final class MysqlDatabaseConnection implements DatabaseConnection
	{

		protected $dbh;

		public function __construct()
		{
			\write_log('init MysqlDBConnection __construct');

			NULL === $this->dbh && $this->connect();
		}

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
			return 'query results here';
			// Execute query and return results.
		}

		public function fetchAll($table, ...$args) {

			$cols = QueryParametersTool::getAllowedCols($table);

			try {
				$stmt = $this->dbh->prepare("SELECT ".  implode(', ', $cols) . " FROM " . $table . " LIMIT 6");
				$stmt->execute();
				$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				echo 'Action failed: ' . $e->getMessage();
			}

			return $result;
		}
	}