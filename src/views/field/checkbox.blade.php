@extends('esterisk.form.field.two-col-field')

@section('editfield-'.$field->name)
			<div class="form-check">
			  <label class="form-check-label" for="{{ $field->name }}">
			  <input class="form-check-input" type="{{ $field->fieldtype }}" value="{{ $field->value }}" id="{{ $field->name }}" name="{{ $field->name }}"{{ $field->checked($field->value) }}>
				{{ $field->label }}
			  </label>
			</div>
@endsection
