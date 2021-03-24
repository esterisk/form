@extends($field->getLayoutTemplate())

@section('editfield-'.$field->name)
        <div class="select-group">
			<select class="form-control" id="{{ $field->name }}" name="{{ $field->name }}"{{ $field->isrequired() }}{!! $field->onOther ? ' data-onother="'.$field->onOther.'"' : '' !!} data-default="{{ $field->getDefault() }}">
			@foreach ($field->options as $value => $label)
			    @if(substr($value,0,1) == '[')
			    <optgroup label="{{ $label }}">
			    @elseif($(substr($value,0,1) == ']')
			    </optgroup>
				@else
				    <option{{ $field->selected($value) }} value="{{ $value }}">{{ $label }}</option>
				@endif
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

@if($field->conditionalOptions)
<script>
$(function() {
	pushConditions(
	    '{{ $field->name }}',
	    '{{ $field->getTriggerField() }}',
	    {!! $field->getTriggerOptionsEncoded() !!}
	);
});
</script>
@endif
@endsection
