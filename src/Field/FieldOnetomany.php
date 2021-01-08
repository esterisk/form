<?php
namespace Esterisk\Form\Field;

class FieldOnetomany extends FieldSet
{
	var $fieldtype = 'onetomany';
	var $template = 'onetomany';
	var $options = [];
	var $arrayField = true;
	var $relation_table = '';
	var $relation_id = 'id';
	var $relation_key = '';
	var $relation_field = '';
	var $relation_morph = false; // [ 'field' => '', 'value' => ''];
	var $values;
	var $showItemClosure = null;
	var $shownFields = [];
	var $resource = null;
	var $relatedResource;
	var $relationName;
	var $orderBy = null;
	var $isRelationField = true;
	var $reloadAfterSave = true;

	public function getFieldList()
	{	
		return [];
	}
	
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
	
	public function editItemLink($item) {
		return $this->resource->commandRoute('edit', $item->id);
	}
	
	public function newItemLink() {
		return $this->resource->commandRoute('new');
	}
	
	public function showItem($item) {
		if ($proc = $this->showItemClosure) return $proc($item);
		else {
			$title = $this->relation_field;
			$id = $this->relation_id;
			return [ $item->$id, $item->$title ];
		}
	}
/*
	private function currentValues($record)
	{
		if (!$this->form->defaults) return [];
		$query = \DB::table($this->relation_table)->where($this->relation_key,'=',$record->getKey());
		if ($this->relation_morph) $query = $query->where($this->relation_morph['field'],'=',$this->relation_morph['value']);
		$query = $query->select( array_unique(array_merge([$this->relation_id, $this->relation_field], $this->shownFields)) );
		return $query->get();
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
*/
	public function prepareForEdit($value = false)
	{
//		return $this->currentValues($this->form->defaults);
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
	
	public function resourceRelation($resourceSlug, $relation)
	{
		$this->relatedResource = $resourceSlug;
		$this->relationName = $relation;
		return $this;
	}
	
	public function relationable()
	{
		return $this->form->recordKey() > 0;
	}
	
	public function localKey()
	{
		$relation = $this->relationName;
		return $this->form->resource->model->$relation()->getForeignKeyName();
	}
	
	public function getList()
	{
		$table = $this->form->resource->relatedList($this->relatedResource, $this->name, $this->form->recordKey(), $this->orderBy, [ $this->localKey() => $this->form->recordKey() ]);
		return $table['html'];
	}

}
