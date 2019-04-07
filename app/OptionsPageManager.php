<?php

	namespace PiotrKu\CustomTablesCrud;

	use PiotrKu\CustomTablesCrud\Plugin;


	class OptionsPageManager extends Plugin {

		protected $pluginPage;


		public function __construct()
		{
			$this->pluginPage = $this->getConfig('prefix') . '-settings';

			$this->basic_settings = [
					[
						'label'		=> __('Rows per page', 'ctcrud'),
						'name'		=> 'per_page',
						'default'	=> '23',
					],
					[
						'label'		=> __('Search enabled', 'ctcrud'),
						'name'		=> 'search_enabled',
						'default'	=> 'true',
					],
					[
						'label'		=> __('Filters enabled', 'ctcrud'),
						'name'		=> 'filters_enabled',
						'default'		=> 'true',
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
					'ctcrud_pluginPage_section',						// must match add_settings_section($id)
					[															// passed to callback function
						'name'		=> $basic_setting['name'],
						'default'	=> $basic_setting['default'],
					]
				);
			}




			register_setting(
				$this->pluginPage,			// option_group	- must match settings_field($options_group)
				'ctcrud_per_page',			// option_name		- stored in DB
				[
					'type'					=> 'integer',
					'description'			=> 'Table rows per page',
					'sanitize_callback'	=> [$this, 'sanitizeInteger'],		// sanitize_integer_field_callback
					'show_in_rest'			=> false,
					'default'				=> 20,
				]
			);

			add_settings_field(
				'ctcrud_per_page',
				__('Rows per page ()', 'ctcrud'),
				[$this, 'ctcrud_per_page_render'],
				$this->pluginPage,
				'ctcrud_pluginPage_section',
				[
					'label'	=> __('Rows per page', 'ctcrud'),
					'name'	=> 'ctcrud_per_page',
					'value'	=> '- field value -',
				]
			);
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

			?>
			<input type='text'
				name='ctcrud_basic_settings[<?= esc_attr($opts['name']) ?>]'
				value='<?= esc_attr($values[$opts['name']]) ?>'>
			<?php
		}



		public function ctcrud_per_page_render($field)
		{
			$value = get_option($field['name']);

			?>
			<label><?php /* $field['label'] */ ?>
				<input type='text' name='<?= esc_attr($field['name']) ?>' value='<?= esc_attr($value) ?>'>
			</label>
			<?php
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
			?>
			<form action='options.php' method='post'>
				<h2><?= $this->getConfig('shortName') ?></h2>
				<?php /* settings_errors(); */ ?>
				<?php
					echo '___________________';
					settings_fields($this->pluginPage );
					// echo '========== outputs all sections =========';
					// do_settings_sections($this->pluginPage);
					// echo '---------- output specific section ---------';
					echo "<table>";
					do_settings_fields($this->pluginPage, 'ctcrud_pluginPage_section');
					echo "</table>";
					echo '..........................';
					submit_button();
					echo '====================';
				?>
			</form>
			<?php
		}


		public function sanitizeBasicSettings($el)
		{
			echo '<pre>';
			print_r($this->basic_settings);
			print_r($el);
			echo '</pre>';
			die('group sanitizer');
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
