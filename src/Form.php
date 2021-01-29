<?php
namespace Esterisk\Form;
use Esterisk\Form\Field\Field;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Validator;

class Form
{
	var $fields = [];
	var $action = '';
	var $method = 'post';
	var $defaults = null;
	var $relationDefaults = null;
	var $htmlclass = '';
	var $title = '';
	var $instructions = '';
	var $fieldList = [];
	var $relationFieldList = [];
	var $scriptLibs = [];
	var $resource = null;
	var $original = null;
	var $temporaryId = null;
	var $id = null;
	var $relations = [];
	var $validableFieldList;
	var $salvableFieldList;
	var $relationValues;

	public function __construct($options = [])
	{
		$this->initForm();
		$this->options($options);
		$this->id = 'form'.bin2hex(random_bytes(16));
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

	public static function field($fieldtype, $name = null) {
		if (preg_match('/\\\\/',$fieldtype)) return new $fieldtype($name); // it's already a class name
		else return Field::$fieldtype($name);
	}

	public function addFields($fields) {
		foreach($fields as $field) {
			$key = $field->name;
			$field->attachForm($this);
			
			$this->fields[ $key ] = $field;
			foreach ( $field->getFieldList() as $fd) {
				$key = $fd->name ?: $fd->fieldtype;
				$fd->attachForm($this);
				$this->fieldList[ $key ] = $fd;
				if (count($fd->scriptLibs)) $this->scriptLibs = array_merge($this->scriptLibs, $fd->scriptLibs);
			}
			if ($field->isRelationField) {
				$this->relationFieldList[ $key ] = $field;
			}
			if ($field->relationName) {
				$this->addRelation($field->relationName);
			}
		}
		if (count($this->scriptLibs)) $this->scriptLibs = array_unique($this->scriptLibs);
	}
	
	public function addRelation($relationName)
	{
		if (!in_array($relationName, $this->relations)) $this->relations[] = $relationName;
	}
	
	public function setDefault($key, $value)
	{
		$this->defaults[$key] = $value;
	}
	
	public function setRelationDefaults($relation, $defaults) {
		$this->relationDefaults[$relation] = $defaults;
	}
	
	public function getRelationDefaults($relation) {
		return $this->relationDefaults[$relation];
	}
	
	public function defaultsFromRequest(Request $request)
	{
		foreach (array_keys($this->fieldList) as $key) {
			if ($request->has($key)) $this->defaults[$key] = $request->$key;
		}
	}
	
	public function hasField($key) {
		return isset($this->fieldList[ $key ]);
	}
	
	public function selectValidableFields($request)
	{
		$this->validableFieldList = [];
		foreach($this->fields as $name => $fieldSet ) {
			foreach ( $fieldSet->validableFields($request) as $field ) {
				$this->validableFieldList[] = $field;
			}
		}
	}
	
	public function selectSalvableFields($request)
	{
		$this->salvableFieldList = [];
		foreach($this->fields as $name => $field ) {
			foreach ( $field->salvableFieldList($request) as $fd ) {
				$$this->salvableFieldList[] = $fd;
			}
		}
	}
	
	public function validation($request) {
		$rules = [];
		foreach ($this->validableFieldList as $field) {
		//	if (method_exists($field, 'validator')) $field->validator();
			if ($field->name) {
				$rules = array_merge($rules, $field->getRules());
			}
		}
		return $rules;
	}
	
	public function redeemId($request) {
		if ($request->_formid) $this->id = $request->_formid;
	}
	
	public function sanitize($request)
	{
		$input = $request->all();
		
		foreach ($this->validableFieldList as $field) if (isset($input[$field->name]) && method_exists($field, 'sanitize')) { 
			$sanitizedValue = $field->sanitize($input[$field->name], $input); 
			if ($sanitizedValue != $input[$field->name]) {
				$input[$field->name] = $sanitizedValue;
				$request->replace($input);
			}
		}
	}
	
	public function validate(Request $request) {
		$this->selectValidableFields($request);
		$this->redeemId($request);
		$this->sanitize($request);
		$rules = $this->validation($request);
		$validator = Validator::make($request->all(), $rules);
		$validator->after(function ($validator) {
			foreach ($this->validableFieldList as $field) if (method_exists($field, 'validator')) {
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
	
	public function salvable($request, $original = null) {
		$dst = [];
		if ($original) $this->original = $original;
		foreach ($this->fieldList as $key => $fd) {
			$dst = array_merge($dst, $fd->salvableFields($request));
		}

		if ($this->relations) {
			$this->relationValues = [];
			foreach ($this->fieldList as $key => $fd) {
				$this->relationValues = array_merge($this->relationValues, $fd->salvableRelations($request));
			}
		}
		return $dst;
	}
	
	public function salvableRelations($relation) {
		if (isset($this->relationValues[$relation])) return $this->relationValues[$relation];
		else return null;
	}

	public function afterSave($request, $record) {
		$updates = [];
		foreach ($this->fieldList as $key => $fd) {
			$value = $fd->afterSave($request, $record);
			if ($value) $updates[$key] = $value;
		}
		return $updates;
	}
	
	public function reloadFields($record) {
		$updated = [];
		foreach ($this->fieldList as $key => $fd) {
			if ($fd->reloadAfterSave) $updated[$key] = $fd->render($record->$key, $record);
		}
		return $updated;
	}
	

	public function saveRelations($record) {
		$rules = [];
		foreach ($this->fieldList as $field) {
			if (method_exists($field, 'save')) $field->save($record);
		}
	}
	
	public function save($record, $request) {
		$record->update($this->salvable($request));
		$this->saveRelations($record);
	}
	
	public function model()
	{
		return $this->resource->model;
	}
	
	public function recordKey()
	{
		return $this->fieldList['_id']->getDefault();
	}
	
	
	public function temporaryId()
	{
		
	}

}
