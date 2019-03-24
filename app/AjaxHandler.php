<?php

	namespace PiotrKu\CustomTablesCrud;

	use PiotrKu\CustomTablesCrud\Plugin;


	class AjaxHandler
	{
		function __construct()
		{
			add_action('wp_ajax_ctcrud_field_update', [$this, 'handleFieldUpdateRequest']);
		}


		public function handleFieldUpdateRequest()
		{
			// read raw ajax request fron axios
			$data = json_decode(file_get_contents("php://input"), true);

			$data = $this->initialDataParsing($data);
			echo '<pre>';
			print_r($data);
			echo '</pre>';
			die('');

			return ' here\'s ajax backeng handler';
		}


		public function initialDataParsing($data)
		{
			$post = $data['inputs'];

			foreach ($post as $post_key => $post_input) {
				$post[$post_key] = $this->initialCleanUp($post_input);
				$post[$post_key] = trim($post[$post_key]);
				$post[$post_key] = stripslashes($post[$post_key]);
			}

			return $post;
		}


		public function initialCleanUp($input)
		{
			$max_input_length = 500;
			return substr(filter_var($input, FILTER_SANITIZE_STRING), 0, $max_input_length);
		}
	}
