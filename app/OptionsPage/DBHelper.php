<?php

	namespace PiotrKu\CustomTablesCrud\OptionsPage;

	use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Models\TableDataGetter;
	use PiotrKu\CustomTablesCrud\Database\Query;

	// https://code.tutsplus.com/tutorials/the-wordpress-settings-api-part-5-tabbed-navigation-for-settings--wp-24971


	class DBHelper extends Plugin {

		protected $pluginPage;


		public function __construct()
		{

		}


		public static function getTablesAllowed()
		{

			$possibleTables = TableDataGetter::getPossibleTables();

			return $possibleTables;
		}
	}
