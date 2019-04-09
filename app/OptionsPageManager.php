<?php

	namespace PiotrKu\CustomTablesCrud;

	use PiotrKu\CustomTablesCrud\Plugin;

	// https://code.tutsplus.com/tutorials/the-wordpress-settings-api-part-5-tabbed-navigation-for-settings--wp-24971


	class OptionsPageManager extends Plugin {

		protected $pluginPage;


		public function __construct()
		{
			$this->pluginPage = $this->getConfig('prefix') . '-settings';

			$this->basic_settings = [
					'per_page' => [
						'label'		=> __('Rows per page', 'ctcrud'),
						'name'		=> 'per_page',
						'fieldtype'	=> 'text',
						'datatype'	=> 'unsigned',
						'default'	=> '23',
					],
					'search_enabled' => [
						'label'		=> __('Search enabled', 'ctcrud'),
						'name'		=> 'search_enabled',
						'fieldtype'	=> 'radio',
						'datatype'	=> 'boolean',
						'values'		=> [0 => 'no', 1 => 'yes'],
						'default'	=> 1,
					],
					'filters_enabled' => [
						'label'		=> __('Filters enabled', 'ctcrud'),
						'name'		=> 'filters_enabled',
						'fieldtype'	=> 'radio',
						'datatype'	=> 'boolean',
						'values'		=> [0 => 'no', 1 => 'yes'],
						'default'	=> 1,
					]
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
					'sanitize_callback'	=> [$this, 'sanitizeBasicSettings'],		// sanitize_integer_field_callback
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
						'class'		=> $this->prefix('__fieldWrapper--') . $basic_setting['name'],
						'default'	=> $basic_setting['default'],
					]
				);
			}




			// register_setting(
			// 	$this->pluginPage,			// option_group	- must match settings_field($options_group)
			// 	'ctcrud_per_page',			// option_name		- stored in DB
			// 	[
			// 		'type'					=> 'integer',
			// 		'description'			=> 'Table rows per page',
			// 		'sanitize_callback'	=> [$this, 'sanitizeInteger'],		// sanitize_integer_field_callback
			// 		'show_in_rest'			=> false,
			// 		'default'				=> 20,
			// 	]
			// );

			// add_settings_field(
			// 	'ctcrud_per_page',
			// 	__('Rows per page ()', 'ctcrud'),
			// 	[$this, 'ctcrud_per_page_render'],
			// 	$this->pluginPage,
			// 	'ctcrud_basic_settings_section',
			// 	[
			// 		'label'	=> __('Rows per page', 'ctcrud'),
			// 		'name'	=> 'ctcrud_per_page',
			// 		'value'	=> '- field value -',
			// 	]
			// );
		}



		public function ctcrud_basic_settings_render($opts)
		{
			$values = get_option('ctcrud_basic_settings');


			/* opts
				[name] => per_page
				[default] => 23
			*/

			/* values
				[per_page] => 25
				[search_enabled] => true
				[filters_enabled] => false
			*/

			// echo '<pre>';
			// print_r($opts);
			// echo '</pre>';
			// die('');

			?>
			<input type='text'
				id="<?= esc_attr($opts['name']) ?>"
				name='ctcrud_basic_settings[<?= esc_attr($opts['name']) ?>]'
				value='<?= esc_attr($values[$opts['name']]) ?>'>
			<?php
		}



		/*
		public function ctcrud_per_page_render($field)
		{
			$value = get_option($field['name']);

			?>
			<label>
				<input type='text' name='<?= esc_attr($field['name']) ?>' value='<?= esc_attr($value) ?>'>
			</label>
			<?php
		}
		*/



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
			</h2>

			<form action='options.php' method='post'>
				<?php /* settings_errors(); */ ?>

				<?php if ($active_tab == 'ctcrud_basic_settings_section'): ?>
					<?php settings_fields($this->pluginPage) ?>
					<table>
						<?php do_settings_fields($this->pluginPage, 'ctcrud_basic_settings_section') ?>
					</table>
				<?php elseif ($active_tab == 'ctcrud_tables_settings_section'): ?>
					<table>table options here</table>
				<?php else: ?>
					<table>other options here</table>
				<?php endif ?>

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
			echo '<pre>';


			foreach ((array)$els as $elkey => $el)
			{
				// print_r($elkey);
				if (!isset($this->basic_settings[$elkey])) continue;

				$el_cnf = $this->basic_settings[$elkey];

				switch ($el_cnf['datatype']) {
					case 'unsigned':
						if (filter_var($el, FILTER_VALIDATE_INT) != $el)
							add_settings_error('my-settings', 'invalid-integer',
									'You have a value that is not an unsigned integer.<br>Concerns field labeled: ' . $el_cnf['label'], 'error');

						$out_els[$elkey] = filter_var($el, FILTER_VALIDATE_INT) ? filter_var($el, FILTER_VALIDATE_INT) : $el_cnf['default'];
						break;

					case 'boolean':
						$out_els[$elkey] = filter_var($el, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
						break;
				}
				//switch ()
			}


			// print_r($this->basic_settings);
			// print_r($out_els);
			// echo '</pre>';
			// die('group sanitizer');



			return $out_els;
		}


		public function sanitizeInteger($el)
		{
			// if we would know the option name we would
			// $output = get_option( 'my-setting' );
			// if (is_ok( $input['email']))
			// 		$output['email'] = $input['email'];
	  		// else
			// 		add_settings_error( 'my-settings', 'invalid-email', 'You have entered an invalid e-mail address.' );
			// return $output;

			$el = trim($el);

			if (empty($el) || intval($el) === 0 || intval($el) != $el) {
				$message = __( 'Validation error - value should be an integer > 0', $this->getConfig('prefix'));
				$type = 'error';
			} else {
				$message = __( 'Successfully saved', $this->getConfig('prefix'));
				$type = 'updated';
			}
			add_settings_error( 'ctcrud_per_page', esc_attr( 'settings_updated' ), $message, $type );

			// return $type == 'error' ? null : intval($el);
			return $type == 'error' ? null : intval($el);
		}
	}
