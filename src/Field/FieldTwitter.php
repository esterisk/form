<?php
namespace Esterisk\Form\Field;

class FieldTwitter extends FieldText
{
	var $Length;
	var $fieldtype = 'text';

	public function prepareForSave($value) 
	{	
		if (preg_match('|https?://(www.)twitter.com/([a-zA-Z0-9_]+)|', $value, $match)) $value = '@'.$match[2];
		elseif (preg_match('|^\@?([a-zA-Z0-9_]+)|', $value, $match)) $value = '@'.$match[1];
		else $value = '';
		return $value;
	}

}
