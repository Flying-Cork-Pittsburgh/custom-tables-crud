<?php

	namespace PiotrKu\CustomTablesCrud;

	use PiotrKu\CustomTablesCrud\Plugin;


	class OptionsPageManager extends Plugin {

		protected $pluginPage;


		public function __construct()
		{
			$this->pluginPage = $this->getConfig('prefix') . '-settings';

			add_action('admin_menu', [$this, 'RegisterOptionsPage']);
			add_action('admin_init', [$this, 'SettingsInit']);
		}



		public function SettingsInit()
		{

			register_setting(
				$this->pluginPage,		// option_group
				'ctcrud_settings'			// option_name
			);

			add_settings_section(
				'ctcrud_pluginPage_section',
				__('Your section description', 'ctcrud'),
				[$this, 'ctcrud_settings_section_callback'],
				$this->pluginPage
			);

			add_settings_field(
				'ctcrud_text_field_0',
				__('Settings field description', 'ctcrud'),
				[$this, 'ctcrud_text_field_0_render'],
				$this->pluginPage,
				'ctcrud_pluginPage_section'
			);

			add_settings_field(
				'ctcrud_text_field_1',
				__('Settings field description', 'ctcrud'),
				[$this, 'ctcrud_text_field_1_render'],
				$this->pluginPage,
				'ctcrud_pluginPage_section'
			);



			register_setting(
				$this->pluginPage,		// option_group
				'ctcrud_per_page',			// option_name
				[
					'type'					=> 'integer',
					// 'group'					=> $this->getConfig('prefix').'_options_group',
					'description'			=> 'Table rows per page',
					'sanitize_callback'	=> [$this, 'sanitizeInteger'],		// sanitize_integer_field_callback
					'show_in_rest'			=> false,
					'default'				=> 20,
				]
			);

			add_settings_field(
				'ctcrud_per_page',
				__('Rows per page', 'ctcrud'),
				[$this, 'ctcrud_per_page_render'],
				$this->pluginPage,
				'ctcrud_pluginPage_section'
			);
		}



		public function ctcrud_per_page_render()
		{

			$option = get_option('ctcrud_per_page');
			?>
			<input type='text' name='ctcrud_per_page' value='<?= $option ?>'>
			<?php

		}


		public function ctcrud_text_field_0_render()
		{

			$options = get_option('ctcrud_settings');
			?>
			<input type='text' name='ctcrud_settings[ctcrud_text_field_0]' value='<?php echo $options['ctcrud_text_field_0']; ?>'>
			<?php

		}


		public function ctcrud_text_field_1_render()
		{

			$options = get_option('ctcrud_settings');
			?>
			<input type='text' name='ctcrud_settings[ctcrud_text_field_1]' value='<?php echo $options['ctcrud_text_field_1']; ?>'>
			<?php
		}



		public function ctcrud_settings_section_callback()
		{
			echo __('This section description', 'ctcrud');
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
				<?php
					settings_fields($this->pluginPage );
					do_settings_sections($this->pluginPage );
					submit_button();
				?>
			</form>
			<?php
		}




		public function sanitizeInteger($el)
		{
			return intval($el);
		}
	}
