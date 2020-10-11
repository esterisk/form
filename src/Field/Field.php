<?php
namespace Esterisk\Form\Field;

class Field
{
	var $form;
	var $name;
	var $label;
	var $help = '';
	var $placeholder = '';
	var $required = false;
 	var $rules = [];
	var $fieldtype = 'text';
	var $template = 'text';
	var $defaultValue = null;
	var $arrayField = false;
	var $controlblock = false;
	var $emptyValue = false;

	public function __construct($name, $form)
	{
		$this->name = $name;
		$this->form = $form;
	}
	
	public function getFieldList()
	{	
		return [ $this ];
	}
	
	public function __get($property) {
		if (property_exists($this, $property)) {
			if (method_exists($this, $method = 'get'.ucfirst($property))) return $this->$method();
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			if (method_exists($this, $method = 'set'.ucfirst($property))) return $this->$method($value);
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

	public function getRules()
	{
		if ($this->required) $this->rule('required');
		return $this->rules;
	}
	
	public function rule($rule)
	{
		$ruleName = preg_replace('|:.+|','',$rule);
		if (!preg_grep ('|^'.$ruleName.'[:$]|', $this->rules)) $this->rules[] = $rule;
		return $this;
	}
	
	public function checked($value)
	{
		if ($this->isRightOption($value)) return ' checked';
		else return '';
	}
	
	public function isrequired()
	{
		if ($this->required) return ' required';
		else return '';
	}
	
	public function selected($value)
	{
		if ($this->isRightOption($value)) return ' selected';
		else return '';
	}
	
	public function requestValue()
	{
		$name = $this->name;
		return request()->$name;
	}
	
	public function prepareForEdit($value)
	{
		return $value;
	}
	
	public function prepareForSave($value)
	{
		return $value;
	}
	
	public function getDefault()
	{
		$name = $this->name;
		
		if (is_object($this->form->defaults)) 
			$defaultValue = (isset($this->form->defaults->$name) ? $this->form->defaults->$name : null );
		elseif (is_array($this->form->defaults))
			$defaultValue = (isset($this->form->defaults[$name]) ? $this->form->defaults[$name] : null );
		else $defaultValue = $this->form->defaults;
		
		if ($this->defaultValue && $defaultValue === null) $defaultValue = $this->defaultValue;
		
		return old($name, $this->prepareForEdit($defaultValue));
	}

	public function isRightOption($value)
	{
		if ($value == $this->getDefault()) return true;
	}

}
