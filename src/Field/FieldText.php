<?php
namespace Esterisk\Form\Field;

class FieldText extends Field
{
	var $length = 255;
	var $fieldtype = 'text';
	var $template = 'text';
	var $emptyValue = '';

	public function getRules()
	{
		$this->rule('max:'.$this->length);
		return parent::getRules();
	}

}
