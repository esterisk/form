<?php
namespace Esterisk\Form\Field;

class SetField extends Field
{
	var $fieldtype = 'checkbox';
	var $template = 'set';
	var $options = [];
	var $arrayField = true;

	public function addOptions($options)
	{
		$this->options = array_merge($this->options, $options);
	}

	public function getRules()
	{
		if (!is_object($this->options)) $this->options = collect($this->options);
		$this->rule('in:'.$this->options->keys()->implode(','));
		return parent::getRules();
	}

	public function isRightOption($value)
	{
		if (in_array($value, $this->getDefault())) return true;
	}
	
	public function itemId($key)
	{
		return $this->name.'__'.$key;
	}

	public function itemName()
	{
		return $this->name.'[]';
	}
	
	public function prepareForEdit($value)
	{
		if (!is_array($value)) return explode(',',$value);
	}

	public function prepareForSave($value) 
	{	
		if (is_array($value)) return implode(',',$value);
		if (is_object($value)) return $value->implode(',');
		return $value;
	}

}
