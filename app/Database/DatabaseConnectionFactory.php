<?php

	namespace PiotrKu\CustomTablesCrud\Database;

	use PiotrKu\CustomTablesCrud\Database\DatabaseConnection;
	use PiotrKu\CustomTablesCrud\Database\MysqlDatabaseConnection;


	final class DatabaseConnectionFactory
	{

		// public function makeConnection($dbType = 'mysql'): DatabaseConnection
		public function makeConnection($dbType = 'mysql'): DatabaseConnection
		{
			static $connection = null;

			if (null === $connection)
			{
				switch ($dbType)
				{
					case 'mysql':
						$connection = new MysqlDatabaseConnection();
						break;
					defaut:
						die('unsupported dbType!');
				}
			}

			return $connection;
		}
	}
