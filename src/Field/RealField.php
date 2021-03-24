<?php
namespace Esterisk\Form\Field;

class RealField extends Field
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
	var $prepend = null;
	var $unitOptions = null;
	var $unitField = null;

	public function numberStep()
	{
		if ($this->decimals) return pow(10, -$this->decimals);
		else return '0.01';
	}

	public function min($min)
	{
		$this->min = $min;
		$this->rules[] = 'min:'.$min;
		return $this;
	}

	public function max($max)
	{
		$this->max = $max;
		$this->rules[] = 'max:'.$max;
		return $this;
	}

	public function unit($unit, $options = null)
	{
	    if (!$options) {
    		$this->prepend = $unit;
    	} else {
    	    $this->unitField = $unit;
    	    $this->unitOptions = $options;
    	}
		return $this;
	}

	public function numberMax()
	{
		return $this->max !== null ? ' max="'.$this->max.'"' : '';
	}

	public function numberMin()
	{
		return $this->min !== null ? ' max="'.$this->min.'"' : '';
	}

	public function getDefaultUnit()
	{
		if ($this->unitField) return $this->getDefault($this->unitField) ?: $this->unitOptions[0];
		else return null;
	}

	public function unitFormName()
	{
	    if ($this->unitField) return $this->unitField;
	    return $this->name.'__unit';
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

	public function salvableFields($request) {
		$result = [];
		if ($value = $request->get($this->name)) $result[ $this->name ] = $this->prepareForSave($value);
		else $result[ $this->name ] = $this->emptyValue;

		if ($this->unitField) {
            $result[ $this->unitField ] = $request->get($this->unitField);
    //		dd($result);
		}
		return $result;
	}

}
