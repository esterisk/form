<?php
namespace Esterisk\Form\Field;
if (!defined('ZERODATE')) define('ZERODATE','0000-01-01');
if (!defined('ZEROTIME')) define('ZEROTIME','00:00');

class DateTimeField extends Field
{
	var $Length;
	var $fieldtype = 'text';
	var $template = 'datetime';
	var $subRules = [ 'date' => [ 'date_format:j/n/Y' ], 'time' => [ 'date_format:H:i' ] ];
	var $placeholder = [ 'date' => 'gg/mm/aaaa', 'time' => 'hh:pp' ];
	var $shortcuts = [ 'now' => 'oggi', 'tomorrow' => 'domani' ];
	var $emptyValue = ZERODATE.' 00:00:00';
	var $showAlign = 'right';

	static private function dbToHuman($value = false)
	{
		if (is_object($value)) $value = $value->format('Y-m-d H:i:s');
		if (
			!$value
			|| $value == ZERODATE.' 00:00:00'
			|| $value == '0000-00-00 00:00:00'
			|| !preg_match('|(\d+)[/-](\d+)[/-](\d+) (\d+):(\d+):(\d+)|', $value, $m)
		) {
			$value = [ 'date' => '', 'time' => '' ];
		} else {
			$value =  [ 'date' => ltrim($m[3],'0').'/'.$m[2].'/'.$m[1], 'time' => $m[4].':'.$m[5] ];
		}
		return $value;
	}

	static public function sanitize($value)
	{
		if (empty($value['date'])) return ZERODATE.' 00:00:00';

		if (!preg_match('|(\d+)[/-](\d+)[/-](\d+)|', $value['date'], $m) || $value['date'] == ZERODATE) $value['date'] = ZERODATE;
		$value['date'] = intval($m[1]).'/'.(intval($m[2]) < 10 ? '0' : '').intval($m[2]).'/'.(intval($m[3]) < 100 ? (intval($m[3]) < date('y') ? '20' : '19') : '').intval($m[3]);

		if (!preg_match('|(\d+)[:\.,](\d+)([:\.,](\d+))?|', $value['time'], $m) || $value['time'] == ZEROTIME) $value['time'] = ZEROTIME;
		$value['time'] = (intval($m[1]) < 10 ? '0' : '').intval($m[1]).':'.(intval($m[2]) < 10 ? '0' : '').intval($m[2]).':'.(intval($m[3]) < 10 ? '0' : '').intval($m[3])

		return $value['date'].' '.$value['time'];
	}

	}

	static public function readable($value)
	{
		$human = self::dbToHuman($value);
		if ($human['date'] == '') return '';
		return implode(', ', $human);
	}

	static public function humanToDb($value)
	{
		$date = null; $time = null;
		if (!is_array($value)) list($date, $time) = explode(' ',$value);
		else {
			if (isset($value['date'])) $date = $value['date'];
			if (isset($value['time'])) $time = $value['time'];
		}
		if (!preg_match('|(\d+)[/-](\d+)[/-](\d+)|', $date, $m) || $date == ZERODATE) $date = ZERODATE;
		else $date =  (intval($m[3]) + (intval($m[3]) < 100 ? 2000 : 0)).'-'.str_pad(intval($m[2]),2,'0',STR_PAD_LEFT).'-'.str_pad(intval($m[1]),2,'0',STR_PAD_LEFT);

		if (!preg_match('|(\d+):(\d+)(:(\d+)?)|', $time, $m)) $time = '00:00:00';
		else $time =  str_pad(intval($m[1]),2,'0',STR_PAD_LEFT).':'.str_pad(intval($m[2]),2,'0',STR_PAD_LEFT).':'.str_pad(intval($m[3]),2,'0',STR_PAD_LEFT);

		return $date.' '.$time;
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
		$human = self::dbToHuman($value);
		if ($human['date'] == '') return '';
		return implode(', ', $human);
	}

}
