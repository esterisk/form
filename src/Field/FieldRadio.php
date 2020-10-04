<?php
namespace Esterisk\Form\Field;

class FieldRadio extends Field
{
	var $fieldtype = 'radio';
	var $template = 'radio';
	var $options = [];

	public function addOptions($options)
	{
		$this->options = array_merge($this->options, $options);
	}

	public function getRules()
	{
		$this->rule('in:'.implode(',',array_keys($this->options)));
		return parent::getRules();
	}

}
