<?php
namespace Esterisk\Form\Field;

class CheckboxclusterField extends Field
{
	var $fields = [];
	var $fieldtype = 'checkboxcluster';
	var $template = 'checkboxcluster';
	
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
