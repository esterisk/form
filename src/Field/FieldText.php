<?php
namespace Esterisk\Form\Field;

class FieldText extends Field
{
	var $length = 255;
	var $fieldtype = 'text';
	var $template = 'text';

	public function getRules()
	{
		$this->rule('max:'.$this->length);
		return parent::getRules();
	}

}
