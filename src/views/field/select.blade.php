@extends($field->baseTemplate)

@section('editfield-'.$field->name)
        <div class="select-group">
			<select class="form-control" id="{{ $field->name }}" name="{{ $field->name }}"{{ $field->isrequired() }}{!! $field->onOther ? ' data-onother="'.$field->onOther.'"' : '' !!}>
			@foreach ($field->options as $value => $label)
				<option{{ $field->selected($value) }} value="{{ $value }}">{{ $label }}</option>
			@endforeach
			</select>
		@if($field->onOther)
			<div class="select-other"{!! ($field->getDefault() == $field->onOther) ? '' : ' style="display:none"' !!}>
				<input id="{{ $field->otherFormName() }}" type="text"
				    class="form-control"
				    name="{{ $field->otherFormName() }}"
				    value="{{ $field->getDefaultOther() }}"
				    placeholder="{{ $field->otherPlaceholder }}"
				    data-other-to="{{ $field->name }}"
				/>
			</div>
		@endif
		</div>
@endsection
