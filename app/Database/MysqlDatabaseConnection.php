<?php

	namespace PiotrKu\CustomTablesCrud\Database;

	use PiotrKu\CustomTablesCrud\Database\DatabaseConnection;
	use PiotrKu\CustomTablesCrud\Database\QueryPrepareTool;


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

				$this->dbh->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING );
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


		public function fetchAll($table, $limit = null, $offset = null, $order = null, ...$args)
		{
			$cols = QueryPrepareTool::getAllowedCols($table);
			$where = QueryPrepareTool::getWhereFilter($table);
			$where = $where ? " {$where} " : " 1 = 1 ";

			$orderby = preg_replace('/[^a-z0-9_]/', '', $order['orderby']);
			$orderdir = in_array($order['order'], ['ASC', 'DESC']) ? $order['order'] : 'ASC';
			$order = $order && !empty($order['order']) ? " ORDER BY {$orderby} {$orderdir} " : '';

			$offsetLimit = $this->prepareLimitString($limit, $offset);

			try {
				$SQL = "SELECT ".  implode(', ', $cols) .
								" FROM {$table} " .
								" WHERE {$where} " .
								$order .
								" {$offsetLimit}";

				$stmt = $this->dbh->prepare($SQL);
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
			$cols = QueryPrepareTool::getAllowedCols($table);

			try {
				$stmt = $this->dbh->prepare("SELECT COUNT(".  $cols[0] . ") AS cnt FROM " . $table);
				$stmt->execute();
				$result = $stmt->fetchColumn();
			} catch (PDOException $e) {
				echo 'Action failed: ' . $e->getMessage();
			}

			return $result;
		}


		public function countQueryResults($query)
		{
			try {
				$stmt = $this->dbh->prepare($query);
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

			$limit = intval($limit);

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