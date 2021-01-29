<?php
namespace Esterisk\Form\Field;

class FieldBlock extends Field
{
	var $fields = [];
	var $fieldtype = 'block';
	var $template = 'block';
	var $title = false;
	var $controlblock = true;
	var $triggerField = false;
	var $showValue = '';
	var $triggerButton = false;
	var $border = true;

	public function addFields($fields)
	{
		if (!is_array($fields)) $this->fields[] = $fields;
		else $this->fields = array_merge($this->fields, $fields);
		return $this;
	}
	
	public function getFieldList()
	{	
		return $this->fields;
	}

}
