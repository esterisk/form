@extends($field->hidden() ? 'esterisk.form.field.silent-field' : $field->baseTemplate)

@section('editfield-'.$field->name)
	@if ($field->fieldtype == 'hidden')
			<input id="{{ $field->name }}" type="hidden" name="{{ $field->name }}" value="{{ $field->getDefault() }}">
	@endif
	@if (!$field->hidden())
			{{ $field->show($field->getDefault()) }}
	@endif
@endsection
