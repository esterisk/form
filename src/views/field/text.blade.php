@extends($field->getLayoutTemplate())

@section('editfield-'.$field->name)
			<input id="{{ $field->name }}" type="{{ $field->fieldtype }}" class="form-control" name="{{ $field->name }}" value="{{ $field->getDefault() }}"{{ $field->isrequired() }} placeholder="{{ $field->placeholder }}">
@endsection
