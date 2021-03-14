<?php
namespace Esterisk\Form\Field;

class CurrentPasswordField extends PasswordField
{
	var $fieldtype = 'password';
	var $rules = [ 'required','string' ];

	public function prepareForEdit($value) 
		return '';
	}
}
