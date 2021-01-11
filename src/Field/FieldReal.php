<?php
namespace Esterisk\Form\Field;

class FieldReal extends FieldText
{
	var $Length;
	var $fieldtype = 'number';
	var $template = 'number';
	var $rules = [ 'numeric' ];
	var $emptyValue = 0;
	var $showAlign = 'right';
	var $max = null;
	var $min = null;
	var $decimals = 2;
	
	public function numberStep()
	{
		if ($this->decimals) return pow(10, -$this->decimals);
		else return '0.01';
	}
	
	public function min($min)
	{
		$this->min = $min;
		$this->rules[] = 'min:'.$min;
	}
	
	public function max($max)
	{
		$this->max = $max;
		$this->rules[] = 'max:'.$max;
	}
	
	public function numberMax()
	{
		return $this->max !== null ? ' max="'.$this->max.'"' : '';
	}
	
	public function numberMin()
	{
		return $this->min !== null ? ' max="'.$this->min.'"' : '';
	}

	static private function dbToHuman($value = false)
	{
		return str_replace('.',',',$value);
	}

	static public function humanToDb($value)
	{
		return floatval(str_replace(',','.',$value));
	}

	public function prepareForSave($value) 
	{
		return $this->humanToDb($value);
	}
	
	public function prepareForEdit($value = false)
	{
		return $this->dbToHuman($value);
	}
	
	public function show($value)
	{
		return $this->dbToHuman($value);
	}

}
