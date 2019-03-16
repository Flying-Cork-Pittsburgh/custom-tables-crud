<?php

	namespace PiotrKu\CustomTablesCrud\Views;

	// use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Controllers\IndexController;


	class Base
	{
		/**
		 * @var array Shortcode class name to register
		 * @since 0.4.0
		 */
		protected $tables;

		public function __construct()
		{
		}

		/**
		 * Creates wholesalers product import page
		 */
		public static function customTableIndex()
		{

			$indexController = new IndexController();
			$elems = $indexController->getElems();

			echo '<div class="wrap">';

			echo 'some rows from the DB here:<br>';

			foreach ($elems as $elem){
				echo 'elem: ' . $elem . '<br>';
			}

			echo '</div>';
		}
	}