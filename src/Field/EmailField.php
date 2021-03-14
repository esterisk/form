<?php
namespace Esterisk\Form\Field;

class EmailField extends TextField
{
	var $Length;
	var $fieldtype = 'email';
	var $rules = [ 'email' ];
	
	function show($value)
	{
		return '<a href="mailto:'.$value.'">'.$value.'</a>';
	}

}
