<?php

	namespace PiotrKu\CustomTablesCrud\Controllers;

	use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Models\TableDataGetter;
	use PiotrKu\CustomTablesCrud\Database\Query;
	// use PiotrKu\CustomTablesCrud\Database\QueryPrepareTool;


	class DataShowAsRetriever
	{
		protected $_items;


		public function __construct()
		{
			// $this->tables = ['test'];
		}


		public function retrieve($table, $items)
		{
			$this->_table = $table;
			$this->_items = $items;

			$tablesConfig 	= Plugin::getConfig('tables');
			$tablePrefix	= Plugin::prefix();

			if (empty($tablesConfig[$table])) die('config for table not found, exiting...');
			if (!$tablesConfig[$table]['fields']) return '';

			$tableFields = $tablesConfig[$table]['fields'];

			foreach ($tableFields as $fieldKey => $fieldData)
			{
				if (!empty($fieldData['showas']))
				{
					$idsToQuery = $this->getIdsByField($fieldKey);
					$dataById = $this->retrieveDataByJoinOn($fieldData['showas'], $idsToQuery);

					$this->enrichField($fieldKey, $dataById, ['link' => $fieldData['showas']['link']]);
				}
			}

			return $this->_items;
		}


		/**
		 * Adds retrieved data info to the field replacing original contents, like
		 *
		 * from:
		 * 	[wholesaler_id] => 243
		 * to:
		 * 	[wholesaler_id] => [
		 * 		[id] => 243,
		 * 		[value] => Budchem Górecka
		 * 	]
		 *
		 */
		public function enrichField($fieldKey, $fieldRichData, $options)
		{
			foreach ((array)$this->_items as $ikey => $ival)
			{
				if (empty($ival[$fieldKey])) continue;

				$data = [
					'id'		=> $ival[$fieldKey],
					'value'	=> $fieldRichData[$ival[$fieldKey]],
				];

				if ($options['link'])
				{
					$data['link'] = get_permalink($ival[$fieldKey]);
				}

				$this->_items[$ikey][$fieldKey] = $data;
			}
		}


		public function retrieveDataByJoinOn($showAsConfig, $idsToQuery)
		{

			$postType	= $showAsConfig['posttype'];
			$getField	= $showAsConfig['display'];
			$joinOn		= $showAsConfig['joinon'];
			$data = [];

			$args = [
				'posts_per_page'	=> -1,
				'orderby'			=> 'title',
				'order'				=> 'ASC',
				'post_type'			=> $postType,
				'post_status'		=> 'publish',
				'post__in'			=> $idsToQuery,
			];

			$posts_array = get_posts($args);
			if (!$posts_array) return $data;

			foreach ($posts_array as $item)
			{
				$data[$item->$joinOn] = $item->$getField;
			}

			return $data;
		}


		public function getIdsByField($fieldKey)
		{
			$ids = [];

			foreach ($this->_items as $item)
			{
				$ids[$item[$fieldKey]] = $item[$fieldKey];
			}

			return $ids;
		}

	}