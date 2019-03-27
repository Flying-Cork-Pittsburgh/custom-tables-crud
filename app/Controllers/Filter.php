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

			$this->_filters = $tablesConfig[$table]['filters'];

			$filter_data = $this->getFilterData();

			$out	= "";
			$hash	= substr(md5(time()), 5, 8);

			foreach ($this->_filters as $field_id => $filter)
			{
				$out .= "<label for=\"{$tablePrefix}-{$field_id}-filter-{$hash}\" class=\"screen-reader-text\">
				Filter by {$filter['title']}";
				$out .= "</label>\n";
				$out .= "<select name=\"{$tablePrefix}-{$field_id}-filter[{$hash}]\" id=\"{$tablePrefix}-filter-{$hash}\" class=\"postform {$tablePrefix}-filter\" data-current=\"\">";
				$out .= "<option value=\"\" selected=\"selected\">Dowolna hurtownia</option>";

				foreach ((array)$filter_data as $item_key => $item)
				{
					$out .= "<option value=\"{$item_key}\">{$item}</option>";
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
			// $where = QueryPrepareTool::getWhereFilter($table);
			// $where = $where ? " WHERE {$where} " : '';
			// $query = "SELECT COUNT(".  $cols[0] . ") AS cnt FROM " . $table . " {$where} ";
		}

		protected function getFilterData()
		{
			foreach ((array)$this->_filters as $filter)
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

						$filter_data = [];
						foreach ($posts_array as $filter_post)
						{
							$filter_data[$filter_post->{$filter['select_value']}] = $filter_post->{$filter['select_name']};
						}

						return $filter_data;

						break;
					default:
						die('unsupported filter type');
				}

			}

		}
	}
