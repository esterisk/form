@extends($field->getLayoutTemplate())

@section('editfield-'.$field->name)
			@foreach ($field->options as $value => $label)
			<div class="custom-control custom-radio{{ count($field->options) <= 4 ? ' custom-control-inline' : ''}}">
			    <input class="custom-control-input" type="{{ $field->fieldtype }}" value="{{ $value }}" id="{{ $field->name }}-{{ $value }}" name="{{ $field->name }}"{{ $field->checked($value) }}>
			    <label class="custom-control-label" for="{{ $field->name }}-{{ $value }}">{{ $label }}</label>
			</div>
			@endforeach
@endsection
