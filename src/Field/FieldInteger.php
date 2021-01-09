<?php
namespace Esterisk\Form\Field;

class FieldInteger extends FieldReal
{
	var $decimals = 0;
	
	public function numberStep()
	{
		return '1';
	}
	
	public function numberMax()
	{
		return $this->max !== null ? ' max="'.$this->max.'"' : '';
	}
	
	public function numberMin()
	{
		return $this->min !== null ? ' max="'.$this->min.'"' : '';
	}

}
