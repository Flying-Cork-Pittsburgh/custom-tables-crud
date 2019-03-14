<?php

	namespace PiotrKu\CustomTablesCrud\Models;

	use PiotrKu\CustomTablesCrud\Plugin;


	class TableStructureLoader extends Plugin
	{
		/**
		 * @var array Shortcode class name to register
		 * @since 0.4.0
		 */
		protected $tables;

		public function __construct()
		{
			$this->tables = ['test'];

			// foreach( $this->posttypes as $postTypesClass ) {
			// 	if( class_exists( $postTypesClass ) ) new $postTypesClass();
			// }
		}
	}