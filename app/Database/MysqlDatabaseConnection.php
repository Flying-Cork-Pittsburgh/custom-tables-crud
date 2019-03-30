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


		public function fetchAll($table, $where = '', $limit = null, $offset = null, $order = null, $groupby = null, $cols = [],
					...$args)
		{
			$cols_allowed = QueryPrepareTool::getAllowedCols($table);

			if (empty($cols))
			{
				$cols = $cols_allowed;
			}
			else
			{
				$cols = array_intersect($cols_allowed, $cols);
			}

			// $where = QueryPrepareTool::getBaseWhereFilter($table);
			// $where = $where ? " {$where} " : " 1 = 1 ";

			$orderby = preg_replace('/[^a-z0-9_]/', '', $order['orderby']);
			$orderdir = in_array($order['order'], ['ASC', 'DESC']) ? $order['order'] : 'ASC';
			$groupby = !empty($groupby) ? 'GROUP BY ' . preg_replace('/[^a-z0-9_]/', '', $groupby) : '';
			$order = $order && !empty($order['order']) ? " ORDER BY {$orderby} {$orderdir} " : '';

			$offsetLimit = $this->prepareLimitString($limit, $offset);

			try {
				$SQL = "SELECT ".  implode(', ', $cols) .
								" FROM {$table} " .
								" {$where} " .
								" {$groupby} " .
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


		public function updateField($table, $field, $id, $value)
		{
			$table		= QueryPrepareTool::verifyAllowedTable($table);
			$fieldname	= QueryPrepareTool::verifyAllowedField($table, $field['fieldname']);
			$id			= intval($id);

			if (!$table || !$fieldname || !$id) return;

			$data = [
					$fieldname	=> $value,
					'id'			=> $id,
			];

			$count = 0;
			try {
				$SQL = "UPDATE {$table}
								SET {$fieldname} = :{$fieldname}
								WHERE id = :id
								LIMIT 1";
				// $dpo->prepare($sql)->execute($data);
				$stmt = $this->dbh->prepare($SQL);
				$stmt->execute($data);
				$count = $stmt->rowCount();
			} catch (PDOException $e) {
				echo 'Update action failed: ' . $e->getMessage();
			}

			return $count;
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