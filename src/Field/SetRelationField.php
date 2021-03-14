<?php
namespace Esterisk\Form\Field;

class SetRelationField extends SetField
{
	var $fieldtype = 'checkbox';
	var $template = 'set';
	var $options = [];
	var $arrayField = true;
	var $relation_table = '';
	var $relation_key = '';
	var $relation_field = '';
	var $relation_morph = false; // [ 'field' => '', 'value' => ''];
	var $values;
	
	public function relationMorph($field, $value) {
		$this->relation_morph = [ 'field' => $field, 'value' => $value ];
		return $this;
	}

	public function relation($table, $key, $field) {
		$this->relationTable($table);
		$this->relationKey($key);
		$this->relationField($field);
		return $this;
	}
	
	private function currentValues($record)
	{
		if (!$this->form->defaults) return [];
		$query = \DB::table($this->relation_table)->where($this->relation_key,'=',$record->getKey());
		if ($this->relation_morph) $query = $query->where($this->relation_morph['field'],'=',$this->relation_morph['value']);
		return $query->select($this->relation_field)->pluck($this->relation_field)->toArray();
	}

	private function removeObsoletes($record)
	{
		$query = \DB::table($this->relation_table)->where($this->relation_key,'=',$record->getKey());
		if ($this->relation_morph) $query = $query->where($this->relation_morph['field'],'=',$this->relation_morph['value']);
		if (is_array($this->values)) $query = $query->whereNotIn($this->relation_field, $this->values);
		$query->delete();
	}

	private function missingValues($record)
	{
		$remaining = $this->currentValues($record);
		if ($this->values) {
			$missing = array_diff($this->values, $remaining);
			return $missing;
		} else return $remaining;
	}
	
	private function insertMissingValues($record)
	{
		$values = $this->missingValues($record);
		foreach ($values as $value) {
			$rec = [
				$this->relation_key => $record->getKey(),
				$this->relation_field => $value,
			];
			if ($this->relation_morph) $rec[$this->relation_morph['field']] = $this->relation_morph['value'];
			\DB::table($this->relation_table)->insert($rec);
		}
	}

	public function prepareForEdit($value = false)
	{
		return $this->currentValues($this->form->defaults);
	}

	public function prepareForSave($value) 
	{	
		if (is_array($value)) $this->values = $value;
		if (is_object($value)) $this->values = $value->toArray();
		return implode(',',$this->values);
	}
	
	public function save($record)
	{
		// Rimuovi quelli che non ci sono più
		$this->removeObsoletes($record);

		// Aggiungi quelli nuovi
		$this->insertMissingValues($record);		
	}

}
