<?php

	namespace PiotrKu\CustomTablesCrud;


	class EnqueueScripts extends Plugin {

		function __construct() {

			// Enqueue frontend/backend scripts
			add_action('wp_enqueue_scripts',			array($this, 'enqueueFrontendScripts'));
			add_action('admin_enqueue_scripts',		array($this, 'enqueueAdminScripts'));
			add_action('admin_head',					array($this, 'injectAdminJavascriptSettings'));
			add_action('wp_head',						array($this, 'injectFrontdendJavascriptSettings'), 1);

			// Inject plugin settings into page head
			// $this->injectJavascriptSettings();

			// Example - Load Font Awesome from CDN, if enabled in Settings Page
			// $enqueue_font_awesome = $this->get_carbon_plugin_option('enqueue_font_awesome');
			// if ($enqueue_font_awesome) {
			// 	if (in_array('frontend', $enqueue_font_awesome))
			// 		add_action('wp_enqueue_scripts', array($this, 'enqueue_font_awesome'));
			// 	if (in_array('backend', $enqueue_font_awesome))
			// 		add_action('admin_enqueue_scripts', array($this, 'enqueue_font_awesome'));
			// }
		}

		/**
			* Enqueue scripts used on frontend of site
			* @since 0.1.0
			*/
		public function enqueueFrontendScripts() {

			// Enqueuing custom CSS for child theme (Twentysixteen was used for testing)
			wp_enqueue_style(
				$this->getConfig('pluginSlug'),
				$this->getConfig('pluginUrl') . '/assets/css/' . 'styles_frontend.css',
				null,
				$this->getConfig('pluginVersion'));

			// Enqueue frontend JavaScript
			wp_enqueue_script(
				$this->getConfig('pluginSlug'),
				$this->getConfig('pluginUrl') . '/assets/js/' . 'scripts_frontend.js',
				null,
				$this->getConfig('pluginVersion'),
				true);
		}

		/**
			* Enqueue scripts used in WP admin interface
			* @since 0.1.0
			*/
		public function enqueueAdminScripts()
		{
			wp_enqueue_style(
				$this->getConfig('pluginSlug'),
				// $this->getConfig('pluginUrl') . '/assets/css/' . $this->getConfig('pluginSlug') . '-admin.min.css',
				$this->getConfig('pluginUrl') . '/assets/css/' . 'styles_admin.css',
				null,
				$this->getConfig('pluginVersion'));

			wp_enqueue_script(
				$this->getConfig('pluginSlug'),
				// $this->getConfig('pluginUrl') . '/assets/js/' . $this->getConfig('pluginSlug') . '-admin.min.css',
				$this->getConfig('pluginUrl') . '/assets/js/' . 'scripts_admin.js',
				null,
				$this->getConfig('pluginVersion'),
				true);

			// wp_localize_script('custom-tables-crud-admin',
			// 	$this->prefix('ajax_filter_params'),
			// 	array('ajax_url' => admin_url('admin-ajax.php')));

		}

		/**
			* Inject JavaScript settings into header. You can add any variables/settings
			*    that you want to make available to your JavaScripts.
			* @since 0.3.0
			*/
		public function injectAdminJavascriptSettings()
		{
			$lines = [
				'var ctcrud = {};',
				'ctcrud.ajax_url = \'' . admin_url('admin-ajax.php') . '?action=ctcrud_field_update\';',
				// 'ctcrud.something = \'something\';',
			];

			echo "<script type=\"text/javascript\">\n";
			foreach ($lines as $line)
			{
				echo "{$line}\n";
			}
			echo "</script>;\n";
		}


		public function injectFrontdendJavascriptSettings()
		{
			# code...
		}
	}
