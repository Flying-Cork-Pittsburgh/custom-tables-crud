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

		public function query(...$args)
		{
			return 'query results here';
			// Execute query and return results.
		}


		public function fetchAll($table, $limit = null, $offset = null, ...$args)
		{
			$cols = QueryParametersTool::getAllowedCols($table);

			$offsetLimit = $this->prepareLimitString($limit, $offset);

			try {
				$stmt = $this->dbh->prepare("SELECT ".  implode(', ', $cols) . " FROM " . $table . " {$offsetLimit}");
				$stmt->execute();
				$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				echo 'Action failed: ' . $e->getMessage();
			}

			return $result;
		}


		public function countRowsInQuery($query)
		{
			try {
				$stmt = $this->dbh->query($query);
				//$stmt->execute();
				$result = $stmt->fetchColumn();
			} catch (PDOException $e) {
				echo 'Action failed: ' . $e->getMessage();
			}

			return $result;
		}

		public function countAll($table)
		{
			$cols = QueryParametersTool::getAllowedCols($table);

			try {
				$stmt = $this->dbh->prepare("SELECT COUNT(".  $cols[0] . ") AS cnt FROM " . $table);
				$stmt->execute();
				$result = $stmt->fetchColumn();
			} catch (PDOException $e) {
				echo 'Action failed: ' . $e->getMessage();
			}

			return $result;
		}


		public function prepareLimitString($limit = null, $offset = null)
		{
			if (null === $limit) return '';

			$limit	= intval($limit);

			if ($limit && $offset !== null) {
				$offset = intval($offset);
				return "LIMIT {$offset}, {$limit}";
			} elseif ($limit) {
				return "LIMIT {$limit}";
			} else {
				return '';
			}
		}
	}