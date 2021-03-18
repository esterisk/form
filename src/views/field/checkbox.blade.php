@extends($field->getLayoutTemplate())

@section('editfield-'.$field->name)
			<div class="custom-control custom-checkbox">
			  <input class="custom-control-input" type="{{ $field->fieldtype }}" value="{{ $field->value }}" id="{{ $field->name }}" name="{{ $field->name }}"{{ $field->checked($field->value) }}>
			  <label class="custom-control-label" for="{{ $field->name }}">{{ $field->label }}</label>
			</div>
@endsection
