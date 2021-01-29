@extends($field->baseTemplate)

@section('editfield-'.$field->name)
			<select class="form-control" id="{{ $field->name }}" name="{{ $field->name }}"{{ $field->isrequired() }}>
			@foreach ($field->options as $value => $label)
				<option{{ $field->selected($value) }} value="{{ $value }}">{{ $label }}</option>
			@endforeach
			</select>
@endsection
