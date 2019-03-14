<?php

	namespace PiotrKu\CustomTablesCrud\Database;

	use PiotrKu\CustomTablesCrud\Plugin;


	class Connection
	{
		/**
		 * @var array Shortcode class name to register
		 * @since 0.4.0
		 */
		protected $dbh		= NULL;
		protected $db		= [];

		public function __construct()
		{
			$this->db = [
				'prefix'		=> $GLOBALS['table_prefix'],
			];

			NULL === $this->dbh && $this->connectDB();
		}

		protected function connectDB()
		{
			try {
				$dbh = new \PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD);
				$this->dbh = $dbh;
			} catch (PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
			}
  		}


		protected function disconnectDB()
		{
		}
	}