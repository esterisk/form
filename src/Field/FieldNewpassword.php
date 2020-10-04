<?php
namespace Esterisk\Form\Field;

class FieldNewpassword extends Field
{
	var $fieldtype = 'password';
	var $help = 'La password deve essere di almeno otto caratteri e contenere almeno una lettera maiuscola, una lettera minuscola, un numero e un carattere speciale (#, *, ? ecc.)';
	var $required = true;
	var $template = 'newpassword';
	var $rules = [ 'required_with:password_confirmation','string','confirmed','min:8','max:30' ];
	
	public function validator()
	{
		if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@()$%^&*=_{}[\]:;\"'|\\<>,.\/~`±§+-]).{8,30}$/", $this->requestValue())) {
			return 'la password non rispetta le regole indicate';
        } else return true;
    	}
	
}
