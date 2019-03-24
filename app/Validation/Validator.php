<?php

	namespace PiotrKu\CustomTablesCrud\Validation;


	class Validator
	{

		function __construct()
		{
		}


		public function validateFieldValue($field, $value)
		{
			if ($field['editable'] != 1) return;

			switch ($field['case'])
			{
				case 'float':
					// if empty is good then fine
					if (empty($value) && ($field['null'])) return '';

					$value = filter_var($value, FILTER_SANITIZE_FLOAT);
					if (!filter_var($value, FILTER_VALIDATE_FLOAT)) return;

					return floatval($value);
					break;

				case 'int':
					// if empty is good then fine
					if (empty($value) && ($field['null'])) return '';

					$value = filter_var($value, FILTER_SANITIZE_INT);
					if (!filter_var($value, FILTER_VALIDATE_INT)) return;

					return intval($value);
					break;

				case 'boolean':
					// if empty is good then fine
					if (empty($value) && ($field['null'])) return '';

					$value = filter_var($value, FILTER_SANITIZE_BOOLEAN);
					if (!filter_var($value, FILTER_VALIDATE_BOOLEAN)) return;

					return boolval($value);
					break;

				case 'string':
					// if empty is good then fine
					if (empty($value) && ($field['null'])) return '';

					$value = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					return $value;
					break;

				default:
					return;
			}

		}
	}

