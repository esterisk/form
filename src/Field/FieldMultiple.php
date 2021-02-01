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
	var $startingRow = false;

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

	public function attachForm($form)
	{
	    parent::attachForm($form);
	    foreach ($this->rowFields as $key => $field) $field->attachForm($form);
	}

	public function relation($relationName)
	{
		$this->relationName = $relationName;
		return $this;
	}

	public function getLabels()
	{
	    $labels = [];
        foreach ($this->rowFields as $name => $rowfield) {
            $labels[$rowfield->label] = $rowfield->cols;
        }
	    return $labels;
	}

	public function showLabels()
	{
	    if ($this->startingRow
	    	|| count($this->fromRequest()) > 0
	    	|| count($this->form->getRelationDefaults($this->relationName)) > 0) return true;
	    else return false;
	}

	private function fromRequest($null_is_ok = false)
	{
	    $input = [];
	    foreach (request()->all() as $name => $value) {
	        if ($coord = $this->form->isRelationKey($name)) {
			    if ($value !== null || $null_is_ok) $input[] = (object)[ 'name' => $name, 'number' => $coord['index'], 'field' => $coord['field'], 'value' => $value ];
			}
		}
		return $input;
	}

	private function compositeName($field, $rownum = '')
	{
	    return $this->name.'{'.$rownum.'}_'.$field->name;
	}


	private function requestToRows()
	{
		$rows = [];
		$row_numbers = [];
		$cnt = 0;
		foreach ($this->fromRequest() as $row) {
            if (isset($this->rowFields[$row->field])) {
                $field = $this->rowFields[$row->field];
                $fd = clone $field;
                $fd->relationName($this->form, $this->relationName, $row->number);
                $fd->defaultValue($row->value);
                if (!isset($row_numbers[$row->number])) $row_numbers[$row->number] = $cnt++;
                $rows[$row_numbers[$row->number]][] = $fd;
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
					$field->relationName($this->form, $this->relationName, $cnt);

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
		if ($this->startingRow) {
			$row = [];
			foreach ($this->rowFields as $name => $rowfield) {
				$field = clone $rowfield;
				$field->relationName($this->form, $this->relationName, $cnt);
				$row[] = $field;
			}
			$rows[] = $row;
		}
		return $rows;
	}

	public function getTemplateRow()
	{
		$row = [];
		foreach ($this->rowFields as $name => $rowfield) {
			$field = clone $rowfield;
			$field->relationName($this->form, $this->relationName, '');
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
		foreach ($this->fromRequest() as $row) {
            $field = $this->rowFields[$row->field];
            $fd = clone $field;
            $fd->name($row->name);
            $validableFields[] = $fd;
		}
		return $validableFields;
	}

	public function salvableFields($request) {
		return [];
	}

	public function salvableRelations($request)
	{
		$temp = [];
		$row_numbers = [];
		$cnt = 0;
		foreach ($this->fromRequest() as $row) {
            if (isset($this->rowFields[$row->field])) {
                $field = $this->rowFields[$row->field];
                $fd = clone $field;
                $fd->relationName($this->form, $this->relationName, $row->number);
                $rvalues = $fd->salvableFields($request);
                $values = [];
                foreach ($rvalues as $key => $value) {
                    if ($rel = $this->form->isRelationKey($key)) {
                        $values[$rel['field']] = $value;
                    }
                }

                if (!isset($temp[$row->number])) $temp[$row->number] = [];
                $temp[$row->number] = array_merge($temp[$row->number], $values);
            }
		}

        /* reorganize numbers and exclude empty rows */
		$data = [];
		foreach ($temp as $number => $row) {
		    $is_null = true;
		    foreach ($row as $key => $value) if ($value) $is_null = false;
		    if (!$is_null) $data[] = $row;
		}

		return [ $this->relationName => $data ];
	}

	public function getRules() { return []; }

/*
	public function validator()
	{
		$input = [];
		foreach ($this->fromRequest() as $row) {
			$input[$row->number][$row->field] = $row->value; // legge in un array le varie righe. Se una riga è tutta null viene ignorata.
		}
	}
*/

	public function save($record)
	{
		// Rimuovi quelli che non ci sono più
		$this->removeObsoletes($record);

		// Aggiungi quelli nuovi
		$this->insertMissingValues($record);
	}

}
