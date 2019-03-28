<?php

	namespace PiotrKu\CustomTablesCrud\Controllers;

	use PiotrKu\CustomTablesCrud\Plugin;
	// use PiotrKu\CustomTablesCrud\Models\TableDataGetter;
	// use PiotrKu\CustomTablesCrud\Database\QueryPrepareTool;


	class Filter
	{
		protected $_filters;
		protected $_filter_data;

		public function __construct()
		{
			// $this->tables = ['test'];
		}


		public function getFiltersHTML($table)
		{
			$tablesConfig 	= Plugin::getConfig('tables');
			$tablePrefix	= Plugin::prefix();

			if (empty($tablesConfig[$table])) die('config for table not found, exiting...');
			if (!$tablesConfig[$table]['filters']) return '';

			$get_filters = empty($_GET[Plugin::prefix() . 'filter']) ? null : $_GET[Plugin::prefix() . 'filter'];

			$this->_filters = $tablesConfig[$table]['filters'];

			$filter_data = $this->getFilterData();

			$out	= "";
			$hash	= substr(md5(time()), 5, 8);

			foreach ($this->_filters as $field_id => $filter)
			{

				$out .= "<label for=\"{$tablePrefix}filter_{$field_id}\" class=\"screen-reader-text\">
				Filter by {$filter['title']}";
				$out .= "</label>\n";
				$out .= "<select name=\"{$tablePrefix}filter[{$field_id}]\" id=\"{$tablePrefix}filter_{$field_id}\" class=\"postform {$tablePrefix}filter\" data-current=\"\">";
				$out .= "<option value=\"\" selected=\"selected\">{$filter['title']}</option>";

				foreach ((array)$filter_data[$field_id] as $item_key => $item)
				{
					$selected = (!empty($get_filters[$field_id]) && $get_filters[$field_id] == $item_key) ? ' selected=\"selected\" ' : '';
					$out .= "<option value=\"{$item_key}\" {$selected}>{$item}</option>";
				}

				$out .= "</select>\n";
			}

			return $out;

			// [wholesaler_id] => Array
			// (
			// 	 [title] => Dowolna hurtownia
			// 	 [filtertype] => wp_select
			// 	 [posttype] => wholesales
			// 	 [return] => id
			// 	 [display] => title
			// 	 [where_filter] =>  wholesaler_id = {value}
			// )

			// $cols = QueryPrepareTool::getAllowedCols($table);
			// $where = QueryPrepareTool::getBaseWhereFilter($table);
			// $where = $where ? " WHERE {$where} " : '';
			// $query = "SELECT COUNT(".  $cols[0] . ") AS cnt FROM " . $table . " {$where} ";
		}

		protected function getFilterData()
		{
			$filter_data = [];

			foreach ((array)$this->_filters as $filter_key => $filter)
			{
				switch ($filter['filter_type'])
				{
					case 'wp_select':
						/*
							[title] => Dowolna hurtownia
							[filter_type] => wp_select
							[post_type] => wholesales
							[select_value] => id
							[select_name] => post_title
							[where_filter] =>  wholesaler_id = {value}
						*/
						$args = [
							'posts_per_page'   => -1,
							'orderby'          => 'title',
							'order'            => 'ASC',
							'post_type'        => $filter['post_type'],
							'post_status'      => 'publish',
						];
						$posts_array = get_posts($args);
						if (!$posts_array) return;

						foreach ($posts_array as $filter_post)
						{
							$filter_data[$filter_key][$filter_post->{$filter['select_value']}] = $filter_post->{$filter['select_name']};
						}

						break;
					default:
						die('unsupported filter type');
				}
			}

			return $filter_data;

		}
	}
