<?php
namespace Esterisk\Form\Field;

class FieldSubmit extends Field
{
	var $fieldtype = 'submit';
	var $template = 'submit';
	var $reset = null;

	public function getFieldList()
	{	
		return [];
	}
	
}
