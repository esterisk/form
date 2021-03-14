<?php
namespace Esterisk\Form\Field;

class IntegerField extends RealField
{
	var $decimals = 0;
	var $withRange = false;

	public function numberStep()
	{
		return '1';
	}

	static public function humanToDb($value)
	{
		return intval(str_replace(',','.',$value));
	}

}
