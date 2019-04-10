<?php

	namespace PiotrKu\CustomTablesCrud;


	class CustomSettingsGetter extends Plugin {

		function __construct() {
			$this->getUpdateSettings();
		}

		/**
			* Enqueue scripts used on frontend of site
			* @since 0.1.0
			*/
		public function getUpdateSettings()
		{
			$ctcrud_basic_settings = get_option('ctcrud_basic_settings');

			/*
			[per_page] => 20
			[search_enabled] => 1
			[filters_enabled] => 1
			*/

			foreach((array)$ctcrud_basic_settings as $skey => $sval)
			{
				$this->setConfig($skey, $sval);
			}
		}
	}
