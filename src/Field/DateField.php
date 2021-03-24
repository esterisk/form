<?php
namespace Esterisk\Form\Field;
if (!defined('ZERODATE')) define('ZERODATE','0000-01-01');

class DateField extends TextField
{
	var $Length;
	var $fieldtype = 'date';
	var $template = 'date';
	var $rules = [ 'date_format:j/m/Y' ];
	var $placeholder = 'gg/mm/aaaa';
	var $shortcuts = [ 'today' => 'oggi', 'tomorrow' => 'domani' ];
	var $emptyValue = ZERODATE;
	var $relative = false;
	var $showAlign = 'right';

	static private function dbToHuman($value = false)
	{
		$value = substr($value, 0, 10);
		if (
			!$value
			|| $value == ZERODATE
			|| $value == '0000-00-00'
			|| !preg_match('|(\d+)-(\d+)-(\d+)|', $value, $m)
		) {
			$value = '';
		} else {
			$value =  ltrim($m[3],'0').'/'.$m[2].'/'.$m[1];
		}
		return $value;
	}

	public function sanitize($value)
	{
		if (!preg_match('|(\d+)[/-](\d+)[/-](\d+)|', $value, $m) || $value == ZERODATE) $value = ZERODATE;
		return intval($m[1]).'/'.(intval($m[2]) < 10 ? '0' : '').intval($m[2]).'/'.(intval($m[3]) < 100 ? (intval($m[3]) < date('y') ? '20' : '19') : '').intval($m[3]);
	}

	static public function humanToDb($value)
	{
		if (!preg_match('|(\d+)[/-](\d+)[/-](\d+)|', $value, $m) || $value == ZERODATE) $value = ZERODATE;
		else $value =
			(intval($m[3]) + (intval($m[3]) < 100 ? 2000 : 0)).'-'.
			str_pad(intval($m[2]),2,'0',STR_PAD_LEFT).'-'.
			str_pad(intval($m[1]),2,'0',STR_PAD_LEFT);
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
		if ($this->relative) {
			if ($value == date('Y-m-d')) return 'oggi';
			if ($value == date('Y-m-d', strtotime('+1 day'))) return 'domani';
			if ($value == date('Y-m-d', strtotime('-1 day'))) return 'ieri';
			for ($i = 2; $i <= 5; $i++) {
				if ($value == date('Y-m-d', strtotime('+'.$i.' day'))) return 'tra '.$i.' giorni';
				if ($value == date('Y-m-d', strtotime('-'.$i.' day'))) return $i.' giorni fa';
			}
		}
		return $this->dbToHuman($value);
	}

}
