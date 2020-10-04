@if ($errors->any())
<div class="alert alert-warning" role="alert">
Ci sono errori nei campi {{ implode(', ',$errors->all()) }}
</div>
@endif

<form class="form-horizontal{{ $form->htmlclass ? ' '.$form->htmlclass : '' }}" method="{{ $form->method }}" action="{{ $form->action }}">
	@if ($form->method == 'post')
	{{ csrf_field() }}
	@endif
	@foreach ($form->fields as $field)
	@if(!$field->controlblock)
	<div class="form-group 
		field-{{ $field->name}} 
		fieldtype-{{ $field->fieldtype }} 
		fieldtpl-{{ $field->template }}
		{{ $errors->has($field->name) ? ' has-error' : '' }}">
	@endif
		@include('esterisk.form.field.'.$field->template, [ 'field' => $field ])
	@if(!$field->controlblock)
	</div>
	@endif
	@endforeach
</form>
