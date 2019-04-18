<?php

	namespace PiotrKu\CustomTablesCrud\OptionsPage;

	use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\OptionsPage\DBHelper;

	// https://code.tutsplus.com/tutorials/the-wordpress-settings-api-part-5-tabbed-navigation-for-settings--wp-24971	- tutorial
	// http://cdn.kjodle.net/techblog/pdf/Settings_API_cheatsheet.pdf																	- cheat sheet
	// http://wpsettingsapi.jeroensormani.com/																								- options generator


	class OptionsCore extends Plugin {

		protected $pluginPage;

		public function __construct()
		{
			$this->pluginPage = $this->getConfig('prefix') . '-settings';

			/*

{"wholesaler_prods":{"tableName":"wholesaler_prods","pageTitle":"Edycja produkt\u00f3w hurtowni","menuTitle":"Produkty hurtow+","capability":"manage_product_import","where_filter":" wholesaler_id IS NOT NULL && wholesaler_id > 0 ","filters":{"wholesaler_id":{"title":"- kt\u00f3ra hurtownia -","filter_type":"wp_select","post_type":"wholesales","select_value":"ID","select_name":"post_title","where_filter":" wholesaler_id = {value} "},"product_producer_id":{"title":"- kt\u00f3ry producent -","filter_type":"wp_select","post_type":"producers","select_value":"ID","select_name":"post_title","where_filter":" product_producer_id = {value} "},"wholesaler_offered":{"title":"- czy w ofercie -","filter_type":"table_vals","post_type":null,"select_value":null,"select_name":null,"where_filter":" wholesaler_offered = {value} "},"wholesaler_on_demand":{"title":"- czy na zam\u00f3wienie -","filter_type":"table_vals","post_type":null,"select_value":null,"select_name":null,"where_filter":" wholesaler_on_demand = {value} "}},"fields":{"id":{"title":"Id","type":"bigint(20) unsigned","cast":"int","editable":false,"orderable":true,"searchable":false,"default":null,"null":false,"showas":null},"wholesaler_id":{"title":"Hurtownia","type":"int(10)","cast":"int","editable":false,"orderable":false,"searchable":false,"default":null,"null":false,"showas":{"posttype":"wholesales","joinon":"ID","display":"post_title","link":false}},"wholesaler_product_title":{"title":"Nazwa produktu","type":"varchar(255)","cast":"string","editable":false,"orderable":true,"searchable":true,"default":null,"null":true,"showas":null},"wholesaler_price":{"title":"Cena (hurt)","type":"float","cast":"float","editable":true,"orderable":true,"searchable":true,"default":null,"null":true,"showas":null},"wholesaler_price_promo":{"title":"Cena (hurt) bez promo.","type":"float","cast":"float","editable":true,"orderable":true,"searchable":true,"default":null,"null":true,"showas":null},"wholesaler_offered":{"title":"W ofercie","type":"tinyint(3) unsigned","cast":"boolean_int","editable":true,"orderable":true,"searchable":false,"default":0,"null":false,"showas":null},"wholesaler_on_demand":{"title":"Na zam\u00f3wienia","type":"tinyint(3) unsigned","cast":"boolean_int","editable":true,"orderable":true,"searchable":false,"default":0,"null":false,"showas":null},"product_id":{"title":"Id produktu","type":"int(10) unsigned","cast":"int","editable":false,"orderable":false,"searchable":false,"default":null,"null":false,"showas":{"posttype":"products","joinon":"ID","display":"ID","link":true}},"product_producer_id":{"title":"Producent","type":"int(10) unsigned","cast":"int","editable":false,"orderable":false,"searchable":false,"default":null,"null":false,"showas":{"posttype":"producers","joinon":"ID","display":"post_title","link":true}}}},"postal_codes":{"tableName":"postal_codes","pageTitle":"Edycja kod\u00f3w pocztowych","menuTitle":"Kody pocztowe","capability":"manage_product_import","where_filter":"","filters":[],"fields":{"id":{"title":"Id","type":"int(10) unsigned","cast":"int","editable":false,"orderable":false,"searchable":false,"default":null,"null":false,"showas":null},"postal":{"title":"Kod pocztowy","type":"char(6)","cast":"string","editable":true,"orderable":false,"searchable":false,"default":null,"null":true,"showas":null},"address":{"title":"Adres","type":"varchar(120)","cast":"string","editable":true,"orderable":false,"searchable":false,"default":null,"null":true,"showas":null}}}}

			*/

			$this->basic_settings = [
					'perPage' => [
						'label'		=> __('Rows per page', 'ctcrud'),
						'name'		=> 'perPage',
						'fieldtype'	=> 'text',
						'datatype'	=> 'unsigned',
						'default'	=> '23',
					],
					'searchEnabled' => [
						'label'		=> __('Search enabled', 'ctcrud'),
						'name'		=> 'searchEnabled',
						'fieldtype'	=> 'checkbox',
						'datatype'	=> 'boolean',
						'values'		=> [0 => 'no', 1 => 'yes'],
						'default'	=> 1,
					],
					'filtersEnabled' => [
						'label'		=> __('Filters enabled', 'ctcrud'),
						'name'		=> 'filtersEnabled',
						'fieldtype'	=> 'checkbox',
						'datatype'	=> 'boolean',
						'values'		=> [0 => 'no', 1 => 'yes'],
						'default'	=> 1,
					]
			];


			$this->tables_settings = [
				'tablesAlowed' => [
					'label'		=> __('Tables allowed', 'ctcrud'),
					'name'		=> 'tablesAllowed',
					'fieldtype'	=> 'multiselect',
					'datatype'	=> 'array',
					'values'		=> DBHelper::getTablesAllowed(),
					'default'	=> [],
				],
				'tablesConfig' => [
					'label'		=> __('Tables config', 'ctcrud'),
					'name'		=> 'tablesConfig',
					'fieldtype'	=> 'textarea',
					'datatype'	=> 'text',
					'values'		=> '',
					'default'	=> '',
				],
			];

			$this->tables_helps = [
				'tablesHelp' => [
					'label'		=> __('Tables help', 'ctcrud'),
					'name'		=> 'tablesHelp',
					'fieldtype'	=> 'info',
					'datatype'	=> 'info',
					'values'		=> self::getDefaultTablesConfig(),
					'default'	=> '',
				],
			];

			add_action('admin_menu', [$this, 'RegisterOptionsPage']);
			add_action('admin_init', [$this, 'SettingsInit']);
		}



		public function SettingsInit()
		{
			register_setting(
				$this->pluginPage,			// option_group	- must match settings_field($options_group)
				'ctcrud_basic_settings',	// option_name		- stored in DB
				[
					'type'					=> 'integer',
					'description'			=> 'Table rows per page',
					'sanitize_callback'	=> [$this, 'sanitizeBasicSettings'],
					'show_in_rest'			=> false,
					'default'				=> 20,
				]
			);

			foreach ($this->basic_settings as $basic_setting)
			{
				add_settings_field(
					$basic_setting['name'],
					$basic_setting['label'], //__('Basic settings', 'ctcrud'),
					[$this, 'ctcrud_basic_settings_render'],
					$this->pluginPage,									// must match do_settings_sections($page) & add_settings_section($page)
					'ctcrud_basic_settings_section',					// must match add_settings_section($id)
					[															// passed to callback function
						'label_for'	=> $basic_setting['name'],
						'id'			=> $basic_setting['name'],
						'type'		=> $basic_setting['fieldtype'],
						'values'		=> $basic_setting['values'] ?? null,
						'name'		=> $basic_setting['name'],
						'class'		=> $this->prefix('_fieldWrapper--') . $basic_setting['name'],
						'default'	=> $basic_setting['default'],
					]
				);
			}


			register_setting(
				$this->pluginPage,			// option_group	- must match settings_field($options_group)
				'ctcrud_tables_settings',	// option_name		- stored in DB
				[
					'type'					=> 'integer',
					'description'			=> 'Tables settings',
					'sanitize_callback'	=> [$this, 'sanitizeTablesSettings'],
					'show_in_rest'			=> false,
					'default'				=> 20,
				]
			);

			foreach ($this->tables_settings as $tables_setting)
			{
				add_settings_field(
					$tables_setting['name'],
					$tables_setting['label'], //__('Basic settings', 'ctcrud'),
					[$this, 'ctcrud_tables_settings_render'],
					$this->pluginPage,									// must match do_settings_sections($page) & add_settings_section($page)
					'ctcrud_tables_settings_section',				// must match add_settings_section($id)
					[															// passed to callback function
						'label_for'	=> $tables_setting['name'],
						'id'			=> $tables_setting['name'],
						'type'		=> $tables_setting['fieldtype'],
						'values'		=> $tables_setting['values'] ?? null,
						'name'		=> $tables_setting['name'],
						'class'		=> $this->prefix('_fieldWrapper--') . $tables_setting['name'],
						'default'	=> $tables_setting['default'],
					]
				);
			}


			register_setting(
				$this->pluginPage,			// option_group	- must match settings_field($options_group)
				'ctcrud_tables_help',		// option_name		- stored in DB
				[
					'type'					=> 'integer',
					'description'			=> 'Tables config help',
					'sanitize_callback'	=> [$this, 'sanitizeTablesHelp'],
					'show_in_rest'			=> false,
					'default'				=> null,
				]
			);

			foreach ($this->tables_helps as $tables_help)
			{
				add_settings_field(
					$tables_help['name'],
					$tables_help['label'], //__('Basic settings', 'ctcrud'),
					[$this, 'ctcrud_tables_helps_render'],
					$this->pluginPage,									// must match do_settings_sections($page) & add_settings_section($page)
					'ctcrud_tables_helps_section',						// must match add_settings_section($id)
					[															// passed to callback function
						'label_for'	=> $tables_help['name'],
						'id'			=> $tables_help['name'],
						'type'		=> $tables_help['fieldtype'],
						'values'		=> $tables_help['values'] ?? null,
						'name'		=> $tables_help['name'],
						'class'		=> $this->prefix('_fieldWrapper--') . $tables_help['name'],
						'default'	=> $tables_help['default'],
					]
				);
			}
		}


		public function ctcrud_basic_settings_render($opts)
		{
			$options = get_option('ctcrud_basic_settings');

			/* opts
				[label_for] => per_page
				[id] => per_page
				[type] => text
				[values] =>
				[name] => per_page
				[class] => ctcrud___fieldWrapper--per_page
				[default] => 23
			*/

			/* values
				[per_page] => 25
				[search_enabled] => true
				[filters_enabled] => false
			*/

			switch ($opts['type'])
			{
				case 'text':
					?>
					<input type='<?= $opts['type'] ?>'
						id="<?= esc_attr($opts['name']) ?>"
						name='ctcrud_basic_settings[<?= esc_attr($opts['name']) ?>]'
						value='<?= esc_attr($options[$opts['name']] ?? $opts['default']) ?>'>
					<?php
					break;

				case 'checkbox':
					?>
					<input type="hidden"
						name="ctcrud_basic_settings[<?= esc_attr($opts['name']) ?>]"
						value='0'>
					<input type='<?= $opts['type'] ?>'
						id="<?= esc_attr($opts['name']) ?>"
						name="ctcrud_basic_settings[<?= esc_attr($opts['name']) ?>]"
						value='1' <?php checked($options[$opts['name']] ?? $opts['default'], 1) ?>>
					<?php
					break;

				default:
					die('unsupported field type 1');
			}
		}


		public function ctcrud_tables_settings_render($opts)
		{
			$options_set = 'ctcrud_tables_settings';
			$options = get_option($options_set);

			/* opts
				[label_for] => tablesAllowed
				[id] => tablesAllowed
				[type] => multiselect
				[values] => [
						[0] => postal_codes
						[1] => wholesaler_prods
						[2] => wp_b8d_wpf_stats
					]
				[name] => tablesAllowed
				[class] => ctcrud___fieldWrapper--tablesAllowed
				[default] =>
			*/

			switch ($opts['type'])
			{
				case 'multiselect':
					?>
					<select
						id="<?= esc_attr($opts['name']) ?>"
						name="<?= $options_set ?>[<?= esc_attr($opts['name']) ?>]" multiple>
						<?php foreach ($opts['values'] as $value): ?>
							<option value="<?= $value ?>" <?php selected($options[esc_attr($opts['name'])], $value ); ?>><?= $value ?></option>
						<?php endforeach ?>
					</select>
					<?php
					break;

				case 'textarea':
					?>
					<textarea
						id="<?= esc_attr($opts['name']) ?>"
						name="<?= $options_set ?>[<?= esc_attr($opts['name']) ?>]"><?= esc_attr($options[$opts['name']] ?? $opts['default']) ?></textarea>
					<?php
					break;

				default:
					die('unsupported field type 1');
			}
		}


		public function ctcrud_tables_helps_render($opts)
		{
			$options_set = 'ctcrud_tables_helps_render';
			$options = get_option($options_set);

			echo "<p class=\"optionsPage__helpText\">This is an examplary configuration for two tables <span>wholesaler_prods</span> and <span>postal_codes</span>.</p>";
			echo "<p class=\"optionsPage__helpText\">Presence of each field from the list is obligatory, the fields are:<br>
				- <span>tableName</span><br>
				- <span>pageTitle</span><br>
				- <span>menuTitle</span><br>
				- <span>capability</span><br>
				- <span>where_filter</span><br>
				- <span>filters</span><br>
				- <span>fields</span><br>
			.</p>";
			echo "<p class=\"optionsPage__helpText\">Configuration should be in a form of JSON encoded array in following exemplary form:</p>";


			echo '<pre>';
			print_r($opts['values']);
			echo '</pre>';
			die('');
		}




		public function RegisterOptionsPage()
		{
			add_options_page(
				$this->getConfig('shortName') . ' - settings',		// page_title
				$this->getConfig('shortName'),							// menu_title
				'manage_options',												// capability
				$this->pluginPage,											// menu_slug
				[$this, 'RenderOptionsPage']							// function
			);
		}



		public function RenderOptionsPage()
		{
			$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'ctcrud_basic_settings_section';

			?>
			<h2><?= $this->getConfig('shortName') ?></h2>

			<h2 class="nav-tab-wrapper">
				<a href="?page=ctcrud-settings&tab=ctcrud_basic_settings_section"
					class="nav-tab <?php echo $active_tab == 'ctcrud_basic_settings_section' ? 'nav-tab-active' : ''; ?>">Basic Options</a>
				<a href="?page=ctcrud-settings&tab=ctcrud_tables_settings_section"
					class="nav-tab <?php echo $active_tab == 'ctcrud_tables_settings_section' ? 'nav-tab-active' : ''; ?>">Table Configuration</a>
				<a href="?page=ctcrud-settings&tab=ctcrud_tables_helps_section"
					class="nav-tab <?php echo $active_tab == 'ctcrud_tables_helps_section' ? 'nav-tab-active' : ''; ?>">Configuration Help</a>
			</h2>

			<form action='options.php' method='post'>
				<?php /* settings_errors(); */ ?>
				<?php

					settings_fields($this->pluginPage);

					echo '<div class="optionsPage__tabContent ' .
						($active_tab == 'ctcrud_basic_settings_section' ? 'optionsPage__tabContent--visible' : '') . '"><table>';
					do_settings_fields($this->pluginPage, 'ctcrud_basic_settings_section');
					echo '</table></div>';

					echo '<div class="optionsPage__tabContent ' .
						($active_tab == 'ctcrud_tables_settings_section' ? 'optionsPage__tabContent--visible' : '') . '"><table>';
					do_settings_fields($this->pluginPage, 'ctcrud_tables_settings_section');
					echo '</table></div>';

					echo '<div class="optionsPage__tabContent ' .
						($active_tab == 'ctcrud_tables_helps_section' ? 'optionsPage__tabContent--visible' : '') . '"><table>';
					do_settings_fields($this->pluginPage, 'ctcrud_tables_helps_section');
					echo '</table></div>';

				?>

				<?php
					// echo '========== outputs all sections =========';
					// do_settings_sections($this->pluginPage);
					// echo '---------- output specific section ---------';
					// echo "<table>";
					// do_settings_fields($this->pluginPage, 'ctcrud_basic_settings_section');
					// echo "</table>";
				?>
				<?php submit_button() ?>
			</form>
			<?php
		}


		public function sanitizeBasicSettings($els)
		{
			$out_els = [];
			$ctcrud_basic_settings = get_option('ctcrud_basic_settings');

			/*
				[per_page] => 22
				[search_enabled] => 0
				[filters_enabled] => 1
			*/
			foreach ((array)$els as $elkey => $el)
			{
				if (!isset($this->basic_settings[$elkey])) continue;

				$el_cnf = $this->basic_settings[$elkey];

				switch ($el_cnf['datatype']) {
					case 'unsigned':
						if (filter_var($el, FILTER_VALIDATE_INT) != $el)
							add_settings_error('my-settings', 'invalid-integer',
									'You have entered a value that is not an unsigned integer. ('. $el_cnf['label'] . ')', 'error');

						$out_els[$elkey] = filter_var($el, FILTER_VALIDATE_INT) ?
																			filter_var($el, FILTER_VALIDATE_INT) :
																			$ctcrud_basic_settings[$elkey];
						break;

					case 'boolean':
						$out_els[$elkey] = filter_var($el, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
						break;
				}
			}

			return $out_els;
		}


		public function sanitizeTablesSettings($els)
		{
			$out_els = [];
			$out_els = $els;
			return $out_els;
		}


		public function sanitizeTablesHelp($els)
		{
			$out_els = [];
			$out_els = $els;
			return $out_els;
		}
	}
