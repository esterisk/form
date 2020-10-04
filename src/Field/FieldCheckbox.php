<?php
namespace Esterisk\Form\Field;

class FieldCheckbox extends Field
{
	var $value = true;
	var $fieldtype = 'checkbox';
	var $template = 'checkbox';
	
	public function defaultOn()
	{
		$this->defaultValue = $this->value;
		return $this;
	}

}
