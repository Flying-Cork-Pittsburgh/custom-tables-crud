<?php

	namespace PiotrKu\CustomTablesCrud\Controllers;

	use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Models\TableStructureLoader;


	class IndexController extends Plugin
	{
		/**
		 * @var array Shortcode class name to register
		 * @since 0.4.0
		 */
		protected $tables;
		// protected $db;

		public function __construct()
		{
			$this->tables = ['test'];

		}

		public function getElems()
		{
			$elems = TableStructureLoader::getElems();

			return $elems;
		}
	}