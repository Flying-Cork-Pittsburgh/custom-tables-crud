<?php

	namespace PiotrKu\CustomTablesCrud\Views;

	use PiotrKu\CustomTablesCrud\Plugin;


	class TemplateHelper
	{
		protected $_table;

		public function __construct($table)
		{
			$this->_table = $table;
		}


		public function getNewOrderLink($field)
		{
			$vars = [];

			$vars = $this->getNewOrderVars($field);

			$get_vars = $_GET;
			unset($get_vars['order']);
			unset($get_vars['orderby']);

			$vars = array_merge($vars, $get_vars);

			// get rid of the possible get vars in the URI
			$path = explode("?", $_SERVER['REQUEST_URI']);

			return basename($path[0]) .'?' . http_build_query($vars);
		}


		public function getNewOrderVars($field)
		{
			$vars = [];

			if (empty($_GET['orderby']) || $_GET['orderby'] != $field)
			{
				$vars['orderby']	= $field;
				$vars['order']		= 'asc';
			}
			elseif ($_GET['orderby'] == $field)
			{
				$vars['orderby']	= $field;

				if (empty($_GET['order'])) {
					$vars['order'] = 'asc';
				} else {
					$vars['order'] = ($_GET['order'] === 'asc') ? 'desc' : 'asc';
				}
			}

			return $vars;
		}


		public function getOrderBeingChosenClass($field, $tag = 'a')
		{
			switch ($tag)
			{
				case 'a':
					if (!empty($_GET['orderby']) && $_GET['orderby'] == $field) return 'active';
					break;
				case 'th':
					if (!empty($_GET['orderby']) && $_GET['orderby'] == $field) return 'sorted';
					break;
			}

			return '';
		}


		public function getOrderIndicator($field)
		{
			return '<span class="sorting-indicator"></span>';
		}
	}
