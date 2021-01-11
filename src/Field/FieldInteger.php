<?php
namespace Esterisk\Form\Field;

class FieldInteger extends FieldReal
{
	var $decimals = 0;
	
	public function numberStep()
	{
		return '1';
	}
	
	static public function humanToDb($value)
	{
		return intval(str_replace(',','.',$value));
	}

}
