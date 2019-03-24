<?php

	namespace PiotrKu\CustomTablesCrud;

	use PiotrKu\CustomTablesCrud\Plugin;
	use PiotrKu\CustomTablesCrud\Validation\Validator;


	class AjaxHandler
	{
		private $_data		= '';
		private $_table	= '';
		private $_id		= '';

		function __construct()
		{
			add_action('wp_ajax_ctcrud_field_update', [$this, 'handleFieldUpdateRequest']);
		}


		public function handleFieldUpdateRequest()
		{
			// read raw ajax request fron axios
			$data = json_decode(file_get_contents("php://input"), true);

			$this->_data = $this->initialDataParsing($data);

			$this->_table	= $this->getRequestedTable();
			$this->_field	= $this->getRequestedField();
			$this->_id		= $this->getRequestedId();
			$this->_value	= $this->_data['value'];

			$validator = new Validator();
			$this->_value = $validator->validateFieldValue($this->_field, $this->_value);

			if (null === $this->_value) wp_send_json_error('Invalid field value');

			$dbh = Plugin::getConfig('connection');
			$updated_rows = $dbh->updateField($this->_table, $this->_field, $this->_id, $this->_value);

			if ($updated_rows === 1) {
				wp_send_json_success('Field has been updated');
			} else {
				wp_send_json_error('Field update error');
			}
		}


		public function getRequestedTable()
		{
			if (empty($this->_data['page'])) wp_send_json_error('Table unknown');

			$table_name = str_replace(Plugin::getConfig('prefix') . '_', '', $this->_data['page']);

			if (Plugin::tableExists($table_name)) return $table_name;

			wp_send_json_error('Table does not exist');
		}


		public function getRequestedField()
		{
			if (empty($this->_data['field'])) wp_send_json_error('Field unknown');

			$field_info = Plugin::getFieldInfo($this->_table, $this->_data['field']);

			if ($field_info) return $field_info;

			wp_send_json_error('Field does not exist');
		}


		public function getRequestedId()
		{
			if (empty($this->_data['id'])) wp_send_json_error('Id unknown');
			if (!intval($this->_data['id']) || intval($this->_data['id']) <= 0) wp_send_json_error('Id invalid');

			return intval($this->_data['id']);
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
