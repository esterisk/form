<?php
namespace Esterisk\Form\Field;

class FieldId extends FieldHidden
{
	var $fieldtype = 'hidden';
	var $template = 'id-field';
	var $emptyValue = 0;
	var $activateDirty = false;
	var $hide = false;
	var $reloadAfterSave = true;

}
