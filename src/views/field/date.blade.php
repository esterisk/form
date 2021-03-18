@extends($field->getLayoutTemplate())

@section('editfield-'.$field->name)
			<input id="{{ $field->name }}" type="{{ $field->fieldtype }}" class="form-control" name="{{ $field->name }}" value="{{ $field->getDefault() }}"{{ $field->isrequired() }} placeholder="{{ $field->placeholder }}">
			
			@foreach($field->shortcuts as $key => $label)
				<button class="btn btn-sm form-shortcut" data-value="{{ date('d/m/Y',strtotime($key)) }}" onclick="$('#{{ $field->name }}').val($(this).data('value'));return false">{{ $label }}</button>
			@endforeach
			
@endsection
