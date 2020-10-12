## Esterisk Form

This package help make quicker forms, optizing rendering, validating and retrieving and saving valus.

## Installation

Require this package with composer:

```shell
composer require esterisk/form
```

After updating composer, add the ServiceProvider to the providers array in config/app.php

```php
   Esterisk\Form\FormServiceProvider::class,
```
Copy the package views to your local config with the publish command:

```shell
php artisan vendor:publish --provider="Esterisk\Form\FormServiceProvider"
```

## Usage

Create a Form Class inserting all the form fields:

```php
<?php
namespace App\Http\Forms;
use Esterisk\Form\Form;
use Illuminate\Validation\Rule;

class MyForm extends Form
{
	
	public function initForm() 
	{
		$this->action(route('register'))
			->method('post')
			->addFields([
				$this->field('email','email')->label('Indirizzo email')->required()->rule(Rule::unique('users')),
				$this->field('Newpassword','password')->label('Password')->required(),
				$this->field('text','name')->label('Nome e cognome')->required(),
				$this->field('text','twitter')->label('Handle Twitter'),
				$this->field('select','format')->label('Formato email')->options([ 'text' => 'Text', 'html' => 'Html' ])->setDefaultValue('html'),
				$this->field('submit')->label('Aggiorna'),
			]);
		return $this;
	}

}
```

In your controller show the form page like this:

```php
    public function showForm(MyForm $form)
    {
    		$user = \Auth::user();
    		$data = [
    			'form' => $form->defaults($user),
    		];
        return view('mypageview', $data );
    }
```

And get and validate like this:

```php
    public function saveProfile(Request $request, MyForm $form)
    {
    		$user = \Auth::user();
    		$form->validate($request);
	   	$user->update($form->salvable($request));
	    	$request->session()->flash('profilestatus', 'Profilo aggiornato');
        return redirect()->route('profilo');
    }
```

Pass the $form instance to the view and insert the form simply like this:

```html
	<div class="panel-body">
		@form($form)
	</div>

```

The form and fields views are based on Bootstrap classes.

## Create a form




## Fields Type and options

### Common Field options

	* $field->name($name) sets the "name" property of the field
	* $field->label($label) sets the label text of the field
	* $field->help($help) sets a text that will be shown in small size under the field
	* $field->placeholder($placeholder) sets the field placeholder (for text and textarea fields)
	* $field->required(true) sets the field as mandatory
	* $field->rules([ 'rule1', 'rule2'… ]) sets the validation rules of the field
	* $field->defaultValue($value) sets the field default value
	* $field->emptyValue($value) sets the field value if not set (for radio/checkboxes)

### Field Types

#### text

	Creates a type "text" field.	

	* $field->length($max) sets text maximum length

#### textarea
	
	Creates a type "textarea" field.	

	* $field->rows($rows) sets textarea rows

#### twitter

	Extends "text". Creates a type "text" field and validate it as a Twitter handle. Adds a "@" at the left side if not inserted.	

#### email

	Extends "text". Creates a type "email" field and validate it as an email field.

#### url

	Extends "text". Creates a type "url" field and validate it as an url field. It checks if the url is reachable. It removes tracking parameters ('utm_*', 'fbclid', etc).

#### checkbox

	Creates a type "checkbox" field. 

	* $field->value($value) sets field value.

#### radio

	Creates a set of type "radio" field. 

	* $field->options([ [ $value, $label ], … ]) sets radio fields' values and labels.

#### select

	Creates type "select" field. 

	* $field->options([ [ $value, $label ], … ]) sets the select options values and labels.

#### submit

	Creates type "submit" button. 

	* $field->reset(true) adds a "reset" button.


