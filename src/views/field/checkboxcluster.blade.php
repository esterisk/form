@extends($field->getLayoutTemplate())

@section('editfield-'.$field->name)
			@foreach ($field->fields as $subfield)
			<div class="custom-control custom-checkbox checkbox">

			  <input class="custom-control-input" type="{{ $subfield->fieldtype }}" value="{{ $subfield->value }}" id="{{ $subfield->name }}" name="{{ $subfield->name }}"{{ $subfield->checked($subfield->value) }}>
			  <label class="custom-control-label" for="{{ $subfield->name }}">{{ $subfield->label }}</label>
				@if (!empty($subfield->help))
				<small class="form-text text-muted">{{ $subfield->help }}</small>
				@endif
				@if ($errors->has($subfield->name))
					<span class="help-block">
						<strong>{{ $errors->first($subfield->name) }}</strong>
					</span>
				@endif
			</div>
			@endforeach
@endsection
