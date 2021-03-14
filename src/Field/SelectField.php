<?php
namespace Esterisk\Form\Field;

class SelectField extends Field
{
	var $fieldtype = 'select';
	var $template = 'select';
	var $options = [];
	var $onOther = null;
	var $otherField = null;
	var $otherPlaceholder = 'specifica altro';
	var $conditionalOptions = null;

	public function addOptions($options)
	{
		$this->options = array_merge($this->options, $options);
	}

	public function enum($options)
	{
		$this->options = array_combine($options, $options);
		return $this;
	}

	public function onOther($onOther, $otherField = null)
	{
	    $this->onOther = $onOther;
	    if ($otherField) $this->otherField = $otherField;
	    return $this;
	}

    function relationName($form, $relationName, $index) {
        parent::relationName($form, $relationName, $index);
        if ($this->otherField) $this->otherField = $form->relationName($relationName, $index, $this->otherField);
    }

	public function getRules()
	{
		$this->rule('in:'.implode(',',array_keys($this->options)));
		return parent::getRules();
	}

	public function getDefault($name = null)
	{
		if ($name || $this->otherField) return parent::getDefault($name);

		$mydefault = parent::getDefault();

		if ($mydefault && (!isset($this->options[ $mydefault ]))) {
		    if (!$this->options && $this->conditionalOptions) return $mydefault;
		    if ($this->onOther && !$this->otherField) return $this->onOther;
		}
		else return $mydefault;
	}

	public function getDefaultOther()
	{
		if ($this->otherField) return $this->getDefault($this->otherField);
		else {
    		$mydefault = parent::getDefault();
		    if ($mydefault && !isset($this->options[ $mydefault ])) return $this->getDefault();
		    else return '';
		}
	}

	public function otherFormName()
	{
	    if ($this->otherField) return $this->otherField;
	    return $this->name.'__other';
	}

	public function salvableFields($request) {
		$result = [];
		if ($value = $request->get($this->name)) $result[ $this->name ] = $this->prepareForSave($value);
		else $result[ $this->name ] = $this->emptyValue;

		if ($this->otherField) {
            $result[ $this->otherField ] = $request->get($this->otherField);
		}
		return $result;
	}

	public function trigger($triggerField, $options)
	{
	    $this->conditionalOptions = [
	        'trigger' => $triggerField,
	        'options' => $options,
	    ];
	    return $this;
	}

	public function getTriggerField()
    {
        return $this->relationSibling($this->conditionalOptions['trigger']);
    }

	public function getTriggerOptions()
    {
        return $this->conditionalOptions['options'];
    }

	public function getTriggerOptionsEncoded()
    {
        return json_encode($this->getTriggerOptions());
    }

}
