@extends($field->getLayoutTemplate())

@section('editfield-'.$field->name)
	@if($field->withRange)
	    <div class="row"><div class="col-sm-2 col-xs-12">
	@endif

	@if($field->prepend)
		<div class="input-group flex-nowrap">
  			<div class="input-group-prepend"><span class="input-group-text" id="addon-wrapping">{{ $field->prepend }}</span></div>
	@endif
	@if($field->unitField)
		<div class="input-group flex-nowrap">
            <div class="input-group-prepend unit-menu">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $field->getDefaultUnit() }}</button>
                <input id="{{ $field->unitFormName() }}"
                    type="hidden"
				    name="{{ $field->unitFormName() }}"
				    value="{{ $field->getDefaultUnit() }}"
				    data-unit-to="{{ $field->name }}"
				/>
                <div class="dropdown-menu">
                    @foreach ($field->unitOptions as $unit)
                    <a class="dropdown-item" href="#" data-unit="{{ $unit }}" data-unit-field="{{ $field->unitFormName() }}">{{ $unit }}</a>
                    @endforeach
                </div>
            </div>
	@endif
		<input id="{{ $field->name }}" type="{{ $field->fieldtype }}" class="form-control" step="{{ $field->numberStep() }}" {{ $field->numberMin() }}{{ $field->numberMax() }}name="{{ $field->name }}" value="{{ $field->getDefault() }}"{{ $field->isrequired() }} placeholder="{{ $field->placeholder }}"{!! $field->withRange ? ' data-range="true"' : '' !!}>
	@if($field->prepend || $field->unitField)
		</div>
	@endif

	@if($field->withRange)
	    </div>
	    <div class="col-sm-10 d-xs-none">
            <input type="range" class="custom-range" min="{{ $field->withRange[0] }}" max="{{ $field->withRange[1] }}" data-integer="{{ $field->name }}" value="{{ $field->getDefault() }}">
        </div>
        </div>
    @endif

@endsection
