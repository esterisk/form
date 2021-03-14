<?php
namespace Esterisk\Form\Field;

class YesnoField extends Field
{
	var $value = true;
	var $fieldtype = 'radio';
	var $template = 'radio';
	var $options = [ 1 => 'Sì', 0 => 'No' ];
	
	public function defaultOn()
	{
		$this->defaultValue = $this->value;
		return $this;
	}

}
