<?php
namespace Esterisk\Form;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Validator;

class Form
{
	var $fields = [];
	var $action = '';
	var $method = 'post';
	var $defaults = null;
	var $htmlclass = '';
	var $title = '';
	var $instructions = '';
	var $fieldList = [];

	public function __construct($options = [])
	{
		$this->initForm();
		$this->options($options);
	}
	
	public function options($options)
	{
		if (!empty($options['action'])) $this->action = $options['action'];
		if (!empty($options['method'])) $this->method = $options['method'];
		if (!empty($options['fields'])) $this->fields = $options['fields'];
		if (!empty($options['defaults'])) $this->defaults = $options['defaults'];
		if (isset($options['htmlclass'])) $this->htmlclass = $options['htmlclass'];
		if (!empty($options['submit'])) $this->fields['submit']->label = $options['submit'];
		return $this;
	}
	
	public function initForm()
	{
		$this->action = url()->current();
		$this->method = 'post';
		return $this;
	}
	
	public function __get($property) {
		if (method_exists($this, $method = 'get'.ucfirst($property))) return $this->$method();
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if (method_exists($this, $method = 'set'.ucfirst($property))) return $this->$method($value);
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
		return $this;
	}
		
	public function __call($property, $value = null) {
		if (!count($value)) $value = [ 1 ];
		if (property_exists($this, $property) || property_exists($this, $property = snake_case($property))) {
			if (method_exists($this, $method = 'set'.ucfirst($property))) return $this->$method($value);
			$this->$property = $value[0];
		}
		return $this;
	}

	public function field($fieldtype, $name = '') {
		$class = 'Esterisk\Form\Field\Field'.ucfirst($fieldtype);
		return new $class($name, $this);
	}

	public function addFields($fields) {
		foreach($fields as $field) {
			$key = $field->name ?: $field->fieldtype;
			$this->fields[ $key ] = $field;
			foreach ( $field->getFieldList() as $fd) {
				$key = $fd->name ?: $fd->fieldtype;
				$this->fieldList[ $key ] = $fd;
			}
		}
	}
	
	public function validation() {
		$rules = [];
		foreach ($this->fieldList as $field) {
		//	if (method_exists($field, 'validator')) $field->validator();
			if ($field->name) {
				if ($field->arrayField) $rules[$field->name.'.*'] = $field->getRules();
				else $rules[$field->name] = $field->getRules();
			}
		}
		return $rules;
	}
	
	public function sanitize($request)
	{
		$input = $request->all();
		foreach ($this->fieldList as $field) if (isset($input[$field->name]) && method_exists($field, 'sanitize')) { 
			$sanitizedValue = $field->sanitize($input[$field->name], $input); 
			if ($sanitizedValue != $input[$field->name]) {
				$input[$field->name] = $sanitizedValue;
				$request->replace($input);
			}
		}
	}
	
	public function validate(Request $request) {
		$this->sanitize($request);
		$rules = $this->validation();
		$validator = Validator::make($request->all(), $rules);
		$validator->after(function ($validator) {
			foreach ($this->fieldList as $field) if (method_exists($field, 'validator')) {
				$name = $field->name;
				$result = $field->validator();
				if ($result !== true) {
					$validator->errors()->add($field->name, $result);
				}
			}
		});
		
		if ($validator->fails()) {
			throw new \Illuminate\Validation\ValidationException($validator);
		}
		return $validator;
	}
	
	public function salvable($request) {
		$src = $request->all();
		$dst = [];
		foreach ($src as $key => $value) {
			if (isset($this->fieldList[$key])) $dst[$key] = $this->fieldList[$key]->prepareForSave($value);
		}
		foreach ($this->fieldList as $key => $fd) {
			if (!isset($dst[$key])) $dst[$key] = $fd->emptyValue;
		}
		return $dst;
	}

	public function saveRelations($record) {
		$rules = [];
		foreach ($this->fieldList as $field) {
			if (method_exists($field, 'save')) $field->save($record);
		}
	}
	
	public function save($record, $request) {
		$record->save($this->salvable($request));
		$this->saveRelations($record);
	}

}
