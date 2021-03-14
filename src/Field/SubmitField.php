<?php
namespace Esterisk\Form\Field;

class SubmitField extends Field
{
	var $fieldtype = 'submit';
	var $template = 'submit';
	var $reset = null;
	var $close = null;
	var $andContinue = null;

	public function getFieldList()
	{	
		return [];
	}
	
}
