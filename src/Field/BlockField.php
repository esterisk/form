<?php
namespace Esterisk\Form\Field;

class BlockField extends Field
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
	var $trigger = false;
	var $baseTemplate = 'esterisk.form.field.layouts.block-field-container';

	public function getFieldList()
	{
	    $fields = [];
	    foreach ($this->fields as $field) {
	        $fields = array_merge( $fields, $field->getFieldList() );
	    }
		return $fields;
	}

	public function trigger($names, $values)
	{
	    if ($names == '*' || !$names) $names = [];
	    elseif (!is_array($names)) $names = [ $names ];
	    if (!is_array($values)) $values = [ $values ];
        $this->trigger = implode('|',$names).'='.implode('|',$values);
        return $this;
	}

}
