<?php
namespace Esterisk\Form\Field;

class ConditionalblockField extends Field
{
	var $fields = [];
	var $fieldtype = 'conditionalblock';
	var $template = 'block';
	var $controlblock = true;
	var $triggerField = '';
	var $showValue = '';
	var $title = false;
	
	public function addFields($fields)
	{
		if (!is_array($fields)) $this->fields[] = $fields;
		else $this->fields = array_merge($this->fields, $fields);
		return $this;
	}

}
