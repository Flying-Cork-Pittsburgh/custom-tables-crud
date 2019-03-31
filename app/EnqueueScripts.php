<?php

	namespace PiotrKu\CustomTablesCrud;


	class EnqueueScripts extends Plugin {

		function __construct() {

			// Enqueue frontend/backend scripts
			add_action('wp_enqueue_scripts', array($this, 'enqueueFrontendScripts'));
			add_action('admin_enqueue_scripts', array($this, 'enqueueAdminScripts'));
			add_action('admin_head', array($this, 'injectAdminJavascriptSettings'));
			// add_action('wp_head', array($this, 'injectJavascriptSettings'), 1);

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

			// Enqueue script dependencies
			// $this->enqueue_common_scripts();

			// // Enqueuing custom CSS for child theme (Twentysixteen was used for testing)
			// wp_enqueue_style('custom-tables-crud', Helpers::get_script_url('assets/css/custom-tables-crud.css'), null, Helpers::get_script_version('assets/css/custom-tables-crud.css'));

			// // Enqueue frontend JavaScript
			// wp_enqueue_script('custom-tables-crud', Helpers::get_script_url('assets/js/custom-tables-crud.js'), array('jquery', 'jquery-waituntilexists'), Helpers::get_script_version('assets/js/custom-tables-crud.js'), true );
			// wp_localize_script('custom-tables-crud', $this->prefix('ajax_filter_params'), array('ajax_url' => admin_url('admin-ajax.php')) );

		}

		/**
			* Enqueue scripts used in WP admin interface
			* @since 0.1.0
			*/
		public function enqueueAdminScripts() {

			// Enqueue script dependencies
			// $this->enqueue_common_scripts();

			wp_enqueue_style(
				$this->getConfig('pluginSlug'),
				// $this->getConfig('pluginUrl') . '/assets/css/' . $this->getConfig('pluginSlug') . '-admin.min.css',
				$this->getConfig('pluginUrl') . '/assets/css/' . 'styles.css',
				null,
				$this->getConfig('pluginVersion'));

			wp_enqueue_script(
				$this->getConfig('pluginSlug'),
				// $this->getConfig('pluginUrl') . '/assets/js/' . $this->getConfig('pluginSlug') . '-admin.min.css',
				$this->getConfig('pluginUrl') . '/assets/js/' . 'scripts.js',
				null,
				$this->getConfig('pluginVersion'),
				true);

			// wp_localize_script('custom-tables-crud-admin',
			// 	$this->prefix('ajax_filter_params'),
			// 	array('ajax_url' => admin_url('admin-ajax.php')));

		}

		/**
			* Enqueue scripts common to the public site and WP Admin
			* @since 0.3.0
			*/
		// private function enqueue_common_scripts() {

		// 	// Enqueue common (frontend/backend) JavaScript
		// 	wp_enqueue_script('jquery-waituntilexists', Helpers::get_script_url('assets/components/jq.waituntilexists/jquery.waitUntilExists.min.js', false ), array('jquery'), '0.1.0');

		// }

		/**
			* Enqueue Font Awesome
			* @since 0.1.0
			*/
		// public function enqueue_font_awesome() {

		// 	wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v5.6.1/css/all.css', null, null );
		// 	wp_enqueue_style('font-awesome-shims', 'https://use.fontawesome.com/releases/v5.6.1/css/v4-shims.css', [ 'font-awesome' ], null );

		// }

		/**
			* Inject JavaScript settings into header. You can add any variables/settings
			*    that you want to make available to your JavaScripts.
			* @since 0.3.0
			*/
		public function injectAdminJavascriptSettings() {

			$lines = [
				'var ctcrud = {};',
				'ctcrud.something = \'something\';',
				'ctcrud.ajax_url = \'' . admin_url('admin-ajax.php') . '?action=ctcrud_field_update\';',
			];

			echo "<script type=\"text/javascript\">\n";
			foreach ($lines as $line)
			{
				echo "{$line}\n";
			}
			echo "</script>;\n";

		}
	}
