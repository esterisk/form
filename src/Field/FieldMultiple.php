<?php
namespace Esterisk\Form\Field;

class FieldMultiple extends FieldSet
{
	var $fields = [];
	var $fieldtype = 'multiple';
	var $template = 'multiple';
	var $options = [];
	var $arrayField = true;
	var $relation_morph = false; // [ 'field' => '', 'value' => ''];
	var $values;
	var $fieldTemplates;
	var $rowFields = [];
	var $relationName = null;

	public function addFields($fields)
	{
		if (!is_array($fields)) $fields = [ $fields ];
		foreach ($fields as $field) {
			$field->attachForm($this->form);
			$field->baseTemplate('esterisk.form.field.inline-field');
			$this->rowFields[ $field->name ] = $field;
			if (!$field->cols) $field->cols = round(11/count($fields));
		}
		
		return $this;
	}
	
	public function getRowsNumber()
	{
		return 3;
	}
	
	public function relation($relationName)
	{
		$this->relationName = $relationName;
		return $this;
	}
	
	private function requestToRows()
	{
		$rows = [];
		$row_numbers = [];
		$cnt = 0;
		foreach (request()->all() as $name => $value) {
			if (preg_match('|'.$this->name.'\{(\d+)\}_([a-z0-9_\[\]]+)|', $name, $m) && $value !== null) {
				if (isset($this->rowFields[$m[2]])) {
					$field = $this->rowFields[$m[2]];
					$fd = clone $field;
					$fd->name($name);
					$fd->defaultValue($value);
					if (!isset($row_numbers[intval($m[1])])) $row_numbers[intval($m[1])] = $cnt++;
					$rows[$row_numbers[intval($m[1])]][] = $fd;
				}
			}
		}
		return $rows;
	}
	
	public function getFormRows()
	{
		/* From old rows */
		if (request()->method() != 'post') {
			$rows = [];
			$cnt = 0;
			foreach ($this->form->getRelationDefaults($this->relationName) as $defaults) {
				$row = []; 
				foreach ($this->rowFields as $name => $rowfield) {
					$field = clone $rowfield;
					$field->name = $this->name.'{'.$cnt.'}_'.$field->name;
					if (isset($defaults->$name)) {
						$field->defaultValue($defaults->$name);
					} else {
						$field->defaultValue($field->emptyValue);
					}
					$row[] = $field;
				}
				$rows[$cnt++] = $row;
			}
		} else {
			$rows = $this->requestToRows();
			$cnt = count($rows);
		}
		
		/* Empty (new) row */
		$row = [];
		foreach ($this->rowFields as $name => $rowfield) {
			$field = clone $rowfield;
			$field->name = $this->name.'{'.$cnt.'}_'.$field->name;
			$row[] = $field;
		}
		$rows[] = $row;
		return $rows;
	}
	
	public function getTemplateRow()
	{
		$row = [];
		foreach ($this->rowFields as $name => $rowfield) {
			$field = clone $rowfield;
			$field->name = $this->name.'{}_'.$field->name;
			$field->defaultValue($field->defaultValue);
			$row[] = $field;
		}
		return $row;
	}
	
	public function getFieldList()
	{	
		return [ $this ];
	}
	
	public function validableFields($request)
	{
		$validableFields = [];
		foreach ($request->all() as $name => $value) {
			if (preg_match('|'.$this->name.'\{(\d+)\}_([a-z0-9_\[\]]+)|', $name, $m) && $value !== null) {
				$field = $this->rowFields[$m[2]];
				$fd = clone $field;
				$fd->name($name);
				$validableFields[] = $fd;
			}
		}
		return $validableFields;
	}

	public function salvableFields($request) { 
		return [];
	}

	public function salvableRelations($request)
	{
		$data = [];
		$row_numbers = [];
		$cnt = 0;
		foreach (request()->all() as $name => $value) {
			if (preg_match('|'.$this->name.'\{(\d+)\}_([a-z0-9_\[\]]+)|', $name, $m) && $value !== null) {
				$basename = $m[2];
				if (isset($this->rowFields[$basename])) {
					$field = $this->rowFields[$basename];
					$fd = clone $field;
					$fd->name($name);
					
					$value = $fd->prepareForSave($value);

					if (!isset($row_numbers[intval($m[1])])) $row_numbers[intval($m[1])] = $cnt++;
					$data[$row_numbers[intval($m[1])]][$basename] = $value;
				}
			}
		}
		return [ $this->relationName => $data ];
	}

	public function validator()
	{	
		$input = [];
		foreach (request()->all() as $name => $value) {
			if (preg_match('|'.$this->name.'\{(\d+)\}.([a-z\[\]]+)|', $name, $m) && $value !== null) {
				$input[intval($m[1])][$m[2]] = $value; // legge in un array le varie righe. Se una riga è tutta null viene ignorata.
			}
		}
/*		
		foreach ($this->input as $number => $row) {
			// valida
		}
*/
	}
	
	
	public function save($record)
	{
		// Rimuovi quelli che non ci sono più
		$this->removeObsoletes($record);

		// Aggiungi quelli nuovi
		$this->insertMissingValues($record);		
	}

}
