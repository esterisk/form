<?php
namespace Esterisk\Form\Field;
use Illuminate\Support\Str;

class Field
{
	var $form;
	var $parent;
	var $name;
	var $label;
	var $help = '';
	var $placeholder = '';
	var $required = false;
 	var $rules = [];
 	var $subRules = false; // if field is represented through sub fields (es. datetime -> date, time) rules for subfields
	var $fieldtype = 'text';
	var $template = 'text';
	var $customTemplate = false; // outside esterisk.form.field tree
	var $fieldLayoutTemplate = false; // layout of contained fields
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
	var $baseTemplate = null;
	var $title;
	var $cols;
	var $relationIndex = null;
	var $fields = null; // for container fields
	// layout settings
	var $layoutLabelCols = 'col-lg-3 col-md-4 col-sm-12';
	var $layoutInputCols = 'col-lg-9 col-md-8 col-sm-12';
	var $layoutLabelPosition = 'before';
	var $layoutBlockCols = 'col-sm-12';
    var $labelPositions = [ 'before', 'after', 'floating', 'inline', 'no' ];

	public function __construct($name = null, $form = null)
	{
		$this->name = $name;
		if ($form) $this->form = $form;
	}

    /* lista di tutti i campi */
	public function getFieldList() { return [ $this ]; }

    /* lista dei campi mostrati come primo livello dal template del form (eventuali sottocampi saranno nel template del campo) */
	public function mainEditFields() { return [ $this ]; }

	/* lista dei campi da validare */
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
		$class = __NAMESPACE__.'\\'.ucfirst(Str::camel($fieldtype)).'Field';
		return new $class($name);
    }

    public static function create($fieldClass, $name)
    {
		return new $fieldClass($name);
    }

    public function template($templateName = null, $custom = false)
    {
        /* without params returns template to use */
        if (!$templateName) {
            return $this->customTemplate ? $this->template : 'esterisk.form.field.'.$this->template;
        } else {
            $this->template = $templateName;
            $this->customTemplate = $custom;
            return $this;
        }
    }

    public static function submit($label)
    {
		return (new \Esterisk\Form\Field\SubmitField())->label($label);
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
		if (is_array($this->fields)) {
			foreach ($this->fields as $field) {
				$field->attachForm($form);
			}
		}
		if (!$this->parent) $this->setParent($form);
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

    public function setDefault($value)
    {
        $this->defaultValue = $value;
        return $this;
    }

    function relationName($form, $relationName, $index) {
        $this->name = $form->relationName($relationName, $index, $this->name);
        $this->relationIndex = $index;
    }

    function relationSibling($fieldname)
    {
        return str_replace('{}','{'. $this->relationIndex.'}', $fieldname);
    }

	public function getDefault($name = null)
	{
		$defaultValue = null;

		if (is_object($this->form)) $defaultValue = $this->form->getDefault($name ?: $this->name);
		if (!$name || $name == $this->name) {
    		if ($this->defaultValue && $defaultValue === null) $defaultValue = $this->defaultValue;
    	}

		return old($name ?: $this->name, $this->prepareForEdit($defaultValue));
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

	public function getLayoutTemplate()
	{
		if ($this->baseTemplate) return $this->baseTemplate;
		else return $this->parent->getFieldLayoutTemplate();
	}

	public function getFieldLayoutTemplate()
	{
		if ($this->fieldLayoutTemplate) return $this->fieldLayoutTemplate;
		else return $this->parent->getFieldLayoutTemplate();
	}

	public function setParent($formOrField)
	{
	    $this->parent = $formOrField;
	}

    /* for container fields */
	public function addFields($fields)
	{
		if (!is_array($fields)) $fields = [ $fields ];
		foreach ($fields as $field) $field->setParent($this);
        $this->fields = array_merge($this->fields, $fields);
		return $this;
	}

    public function layout($inputCols, $labelCols = null, $labelPosition = null, $blockCols = null)
    {
        $label = null;
        $input = null;
        $position = null;
        $block = null;

        if (is_array($inputCols)) {
            if (isset($inputCols['label']) && ($col = intval($inputCols['label'])) <= 12 && ($col > 0)) $label = $col;
            if (isset($inputCols['input']) && ($col = intval($inputCols['input'])) <= 12 && ($col > 0)) $input = $col;
            if (isset($inputCols['field']) && ($col = intval($inputCols['field'])) <= 12 && ($col > 0)) $input = $col;
            if (isset($inputCols['block']) && ($col = intval($inputCols['block'])) <= 12 && ($col > 0)) $block = $col;
            if (isset($inputCols['position']) && in_array($inputCols['position'], $this->labelPositions)) $position = $inputCols['position'];
        } else {
            if (isset($inputCols) && ($col = intval($inputCols)) <= 12 && ($col > 0)) $input = $col;
            if (isset($labelCols) && ($col = intval($labelCols)) <= 12 && ($col > 0)) $label = $col;
            if (isset($blockCols) && ($col = intval($blockCols)) <= 12 && ($col > 0)) $block = $col;
            if (isset($labelPosition) && in_array($labelPosition, $this->labelPositions)) $position = $labelPosition;
        }

        if ($input > 0 && $input <= 12 && $label === null) $label = 12 - $input;

        if ($label > 0) $this->layoutLabelCols = 'col-md-'.$label.' col-sm-12';
        if ($input > 0) $this->layoutInputCols = 'col-md-'.$input.' col-sm-12';
        if ($block > 0) $this->layoutBlockCols = 'col-md-'.$block.' col-sm-12';
        if ($position) $this->layoutLabelPosition = $position;

        return $this;
    }

}
