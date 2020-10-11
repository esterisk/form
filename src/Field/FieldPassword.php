<?php
namespace Esterisk\Form\Field;

class FieldPassword extends Field
{
	var $fieldtype = 'password';
	var $rules = [ 'string' ];

	public function prepareForSave($value) {
		return \Illuminate\Support\Facades\Hash::make($value);
	}

	public function prepareForEdit($value) {
		return '';
	}

}
