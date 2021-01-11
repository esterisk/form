@extends('esterisk.form.field.two-col-field')

@section('editfield-'.$field->name)
	@if($field->prepend)
		<div class="input-group flex-nowrap">
  			<div class="input-group-prepend"><span class="input-group-text" id="addon-wrapping">{{ $field->prepend }}</span></div>
	@endif
			<input id="{{ $field->name }}" type="{{ $field->fieldtype }}" class="form-control" step="{{ $field->numberStep() }}" {{ $field->numberMin() }}{{ $field->numberMax() }}name="{{ $field->name }}" value="{{ $field->getDefault() }}"{{ $field->isrequired() }} placeholder="{{ $field->placeholder }}">			
	@if($field->prepend)
		</div>
	@endif
@endsection
