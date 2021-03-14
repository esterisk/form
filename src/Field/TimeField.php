<?php
namespace Esterisk\Form\Field;

class TimeField extends TextField
{
	var $Length;
	var $fieldtype = 'text';
	var $rules = [ 'date_format:H:i' ];
	var $placeholder = 'hh:pp';

	private function humanToDb($value) 
	{	
		if (!preg_match('|(\d+):(\d+)(:(\d+)?)|', $value, $m)) return '00:00:00';
		$value =  str_pad(intval($m[5]),2,'0',STR_PAD_LEFT).':'.str_pad(intval($m[6]),2,'0',STR_PAD_LEFT).':'.str_pad(intval($m[8]),2,'0',STR_PAD_LEFT);
		return $value;
	}

	private function dbToHuman($value = false)
	{
		if ($value == '00:00:00') return '';
		if (!preg_match('|(\d+):(\d+):(\d+)|', $value, $m)) return '';
		$value =  $m[1].':'.$m[2];
		return $value;
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
