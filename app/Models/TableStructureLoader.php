<?php

	namespace PiotrKu\CustomTablesCrud\Models;

	// use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Database\Connection;


	class TableStructureLoader// extends Plugin
	{
		/**
		 * @var array Shortcode class name to register
		 * @since 0.4.0
		 */
		protected $tables;
		protected $db;

		public function __construct()
		{
			$this->tables = ['test'];

			// Setup database connection
			$this->db = new Connection();
		}

		public static function getElems(){
			return ['test', 'tost', 'tast'];
		}
	}