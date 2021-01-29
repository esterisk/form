<?php
namespace Esterisk\Form\Field;
use Illuminate\Support\Str;

class Field
{
	var $form;
	var $name;
	var $label;
	var $help = '';
	var $placeholder = '';
	var $required = false;
 	var $rules = [];
 	var $subRules = false; // if field is represented through sub fields (es. datetime -> date, time) rules for subfields
	var $fieldtype = 'text';
	var $template = 'text';
	var $defaultValue = null;
	var $arrayField = false;
	var $controlblock = false;
	var $source_id = false;
	var $emptyValue = false;
	var $sortDirection = 'asc';
	var $showAlign = 'left';
	var $shortcuts = null;
	var $uneditable = false;
	var $visible = true;
	var $scriptLibs = [];
	var $record = null;
	var $reloadAfterSave = false;
	var $isRelationField = false;
	var $baseTemplate = 'esterisk.form.field.two-col-field';
	var $title;
	var $cols;

	public function __construct($name = null, $form = null)
	{
		$this->name = $name;
		if ($form) $this->form = $form;
	}
	
	public function getFieldList()
	{	
		return [ $this ];
	}
	
	public function validableFields($request) { return $this->getFieldList(); }
	
	public function __get($property) {
		if (property_exists($this, $property)) {
			if (method_exists($this, $method = 'get'.ucfirst($property))) return $this->$method();
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			if (method_exists($this, $method = 'set'.ucfirst($property))) return $this->$method($value);
			$this->$property = $value;
		}
		return $this;
	}
	
	public function __call($property, $value) {
		if (!count($value)) $value = [ 1 ];
		if (property_exists($this, $property) || property_exists($this, $property = snake_case($property))) {
			if (method_exists($this, $method = 'set'.ucfirst($property))) return $this->$method($value);
			$this->$property = $value[0];
		}
		return $this;
	}

    public static function __callStatic($fieldtype, $params)
    {
    	$name = $params[0] ?: $fieldtype;
		$class = 'Esterisk\Form\Field\Field'.ucfirst(Str::camel($fieldtype));
		return new $class($name);
    }

    public static function submit($label)
    {
		return (new \Esterisk\Form\Field\FieldSubmit())->label($label);
    }

    public static function make($name = null)
    {
		$class = __CLASS__;
		return new $class($name ?: basename(__CLASS__));
    }
    
    public function attachForm($form)
    {
		if (!$this->form) {
			$this->form = $form;
			$this->setupForm();
		}
    }
    
    public function setupForm()
	{
	}

	public function getRules()
	{
		if ($this->required) {	
			$this->rule('required');
		} else {
			$this->rule('nullable');
		}
		$rules = [];
		
		if ($this->arrayField) {
			$rules[$this->name.'.*'] = $this->rules;
		}
		
		elseif ($this->subRules) {
			foreach ($this->subRules as $key => $subrules) {
				$rules[$this->name.'.'.$key] = array_merge($this->rules, $subrules);
			}
		}
		
		else {
			$rules[$this->name] = $this->rules;
		}

		return $rules;
	}
	
	public function rule($rule, $sub = null)
	{
		$ruleName = preg_replace('|:.+|','',$rule);
		if ($this->subRules && $sub) {
			if (!in_array($rule, $this->subRules[$sub])) $this->subRules[$sub][] = $rule;
		} else {
			if (!in_array($rule, $this->rules)) $this->rules[] = $rule;
		}
		return $this;
	}
	
	public function removeRule($rule, $sub = null)
	{
		if ($this->subRules && $sub) {
			if (($idx = array_search($rule, $this->subRules[$sub])) !== false) {
				unset($this->subRules[$sub][$idx]);
			}
		} else {
			if (($idx = array_search($rule, $this->rules)) !== false) {
				unset($this->rules[$idx]);
			}
		}
		return $this;
	}
	
	public function render($value = null, $record = null )
	{
		if ($value) $this->defaultValue = $value;
		return view('esterisk.form.field.'.$this->template, [ 'field' => $this, 'record' => $record ])->render();
	}
	
	public function checked($value)
	{
		if ($this->isRightOption($value)) return ' checked';
		else return '';
	}
	
	public function isrequired()
	{
		if ($this->required) return ' required';
		else return '';
	}
	
	public function selected($value)
	{
		if ($this->isRightOption($value)) return ' selected';
		else return '';
	}
	
	public function requestValue()
	{
		$name = $this->name;
		return request()->$name;
	}
	
	public function prepareForEdit($value)
	{
		return $value;
	}

	public function salvableFields($request) { 
		if ($value = $request->get($this->name)) return [ $this->name => $this->prepareForSave($value) ];
		else return [ $this->name => $this->emptyValue ];
	}

	public function salvableRelations($request) { 
		return [];
	}
	
	public function prepareForSave($value)
	{
		return $value;
	}
	
	public function getDefault($name = null)
	{
		if (!$name) $name = $this->name;
		$defaultValue = null;
		
		if (is_object($this->form)) {
			if (is_object($this->form->defaults)) 
				$defaultValue = (isset($this->form->defaults->$name) ? $this->form->defaults->$name : null );
			elseif (is_array($this->form->defaults))
				$defaultValue = (isset($this->form->defaults[$name]) ? $this->form->defaults[$name] : null );
			else $defaultValue = $this->form->defaults;
		}
		
		if ($this->defaultValue && $defaultValue === null) $defaultValue = $this->defaultValue;
		
		return old($name, $this->prepareForEdit($defaultValue));
	}

	public function isRightOption($value)
	{
		if ($value == $this->getDefault()) return true;
	}
	
	public function show($value)
	{
		return $value;
	}
	
	public function includedInForm()
	{
		return $this->visible && !$this->uneditable;
	}
	
	public function editedInForm()
	{
		return !$this->uneditable;
	}
	
	public function hide()
	{
		$this->visible(false);
		return $this;
	}
	
	public function hidden()
	{
		return !$this->visible;
	}
	
	public function afterSave($request, $record)
	{
	}

}
