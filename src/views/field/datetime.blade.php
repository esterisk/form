@extends($field->baseTemplate)

@section('editfield-'.$field->name)
			<div class="input-group">
				<input id="{{ $field->name }}__date" type="{{ $field->fieldtype }}" class="form-control col-sm-4 mb-2" name="{{ $field->name }}[date]" value="{{ $field->getDefault()['date'] }}"{{ $field->isrequired() }} placeholder="{{ $field->placeholder['date'] }}">
				<input id="{{ $field->name }}__time" type="{{ $field->fieldtype }}" class="form-control col-sm-2" name="{{ $field->name }}[time]" value="{{ $field->getDefault()['time'] }}" placeholder="{{ $field->placeholder['time'] }}">
			</div>
			
			@foreach($field->shortcuts as $key => $label)
				<button class="btn btn-sm form-shortcut" data-value-date="{{ date('d/m/Y',strtotime($key)) }}" data-value-time="{{ date('H:i',strtotime($key)) }}" onclick="$('#{{ $field->name }}__date').val($(this).data('value-date'));$('#{{ $field->name }}__time').val($(this).data('value-time'));return false">{{ $label }}</button>
			@endforeach
			
@endsection
