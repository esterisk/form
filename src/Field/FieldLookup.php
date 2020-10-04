<?php
namespace Esterisk\Form\Field;

class FieldLookup extends Field
{
	var $length = 255;
	var $fieldtype = 'text';
	var $template = 'lookup';
	var $dataSrcUrl = '';
	var $noResultMessage = 'Nessun risultato trovato';
	var $lookupQueryTable;
	var $lookupQueryId;
	var $lookupQueryLabel;

	public function getDefaultText()
	{
		$value = $this->getDefault();
		if (!$value) return '';
		$rec = \DB::table($this->lookupQueryTable)->where($this->lookupQueryId,'=',$value)->select(\DB::raw($this->lookupQueryLabel.' as label'))->first();
		if ($rec) return $rec->label;
		return '';
	}

	public function executeLookup($q)
	{
		return \DB::table($this->lookupQueryTable)
			->whereRaw($this->lookupQueryLabel.' LIKE "%'.str_replace('"','',$q).'%"')
			->select(\DB::raw($this->lookupQueryLabel.' AS text,'.$this->lookupQueryId.' AS value'))
			->orderBy(\DB::raw('LENGTH('.$this->lookupQueryLabel.')'))
			->limit(12)
			->get();
	}

}
