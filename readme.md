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
use Esterisk\Form;
use Illuminate\Validation\Rule;

class MyForm extends Form
{
	
	public function initForm() 
	{
		$this->action = route('register');
		$this->method = 'post';
		$this->addFields([
			$this->field('email','email')->label('Indirizzo email')->required()->rule(Rule::unique('users')),
			$this->field('Newpassword','password')->label('Password')->required(),
			$this->field('text','name')->label('Nome e cognome')->required(),
			$this->field('text','twitter')->label('Handle Twitter')->placeholder('@'),
			$this->field('select','format')->label('Formato email')->options([ 'text' => 'Text', 'html' => 'Html' ])->setDefaultValue('html'),
			$this->field('submit')->label('Aggiorna'),
		]);
	}

}
```

Pass the $form instance to the view and insert the form simply like this:

```html
	<div class="panel-body">
		@include('form.form', [ 'form' => $form ])
	</div>

```

The form and fields views are based on Bootstrap classes.

In your controller show the form page like this:

```php
    public function showForm(MyForm $form)
    {
    		$user = \Auth::user();
    		$data = [
    			'form' => $form->options([ 'defaults' => $user ]),
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

## Fields Type and options

tbc.



