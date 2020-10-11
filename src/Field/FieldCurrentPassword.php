<?php
namespace Esterisk\Form\Field;

class FieldCurrentPassword extends FieldPassword
{
	var $fieldtype = 'password';
	var $rules = [ 'required','string' ];

	public function prepareForEdit($value) 
		return '';
	}
}
