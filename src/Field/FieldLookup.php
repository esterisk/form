<?php
namespace Esterisk\Form\Field;
use Illuminate\Http\Request;

class FieldLookup extends Field
{
	var $length = 255;
	var $fieldtype = 'text';
	var $template = 'lookup';
	var $dataSrcUrl = '';
	var $noResultMessage = 'Nessun risultato trovato';
	var $lookupModel;
	var $lookupQueryTable;
	var $lookupQueryId;
	var $lookupQueryLabel;
	var $lookupFilter;

	public function getDefaultLabel()
	{
		$value = $this->getDefault();
		if (!$value) return '';
		$rec = $this->initQuery()->where($this->lookupQueryId,'=',$value)->first();
		if ($rec) return $rec->label;
		return '';
	}
	
	public function lookupSource()
	{
		if ($this->dataSrcUrl) return $this->dataSrcUrl;
		if ($this->form->resource) return $this->form->resource->lookupRoute($this->name);
		return false;
	}
	
	public function initQuery()
	{
		if ($this->lookupModel) {
			$query = new $this->lookupModel;
		} elseif ($this->lookupQueryTable) {
			$query = \DB::table($this->lookupQueryTable);
		}
		return $query
			->select(\DB::raw($this->lookupQueryLabel.' AS label,'.$this->lookupQueryId.' AS value'))
			->orderBy(\DB::raw('LENGTH('.$this->lookupQueryLabel.')'));
	}
	
	public function executeLookup(Request $request)
	{
		return $this->initQuery()
			->whereRaw($this->lookupQueryLabel.' LIKE "%'.str_replace('"','',$request->search).'%"')
			->limit(12)
			->get();
	}

}
