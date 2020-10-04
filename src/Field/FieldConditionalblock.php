<?php
namespace Esterisk\Form\Field;

class FieldConditionalblock extends Field
{
	var $fields = [];
	var $fieldtype = 'conditionalblock';
	var $template = 'conditionalblock';
	var $controlblock = true;
	var $triggerField = '';
	var $showValue = '';
	
	public function addFields($fields)
	{
		if (!is_array($fields)) $this->fields[] = $fields;
		else $this->fields = array_merge($this->fields, $fields);
		return $this;
	}

}
