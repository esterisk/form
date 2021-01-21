<?php
namespace Esterisk\Form\Field;

class FieldSelect extends Field
{
	var $fieldtype = 'select';
	var $template = 'select';
	var $options = [];

	public function addOptions($options)
	{
		$this->options = array_merge($this->options, $options);
	}
	
	public function enum($options)
	{
		$this->options = array_combine($options, $options);
		return $this;
	}

	public function getRules()
	{
		$this->rule('in:'.implode(',',array_keys($this->options)));
		return parent::getRules();
	}

}
