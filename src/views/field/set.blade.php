		<label class="col-md-4 control-label">{{ $field->label }}</label>

		<div class="col-md-8">
			<div class="row">
			@foreach ($field->options as $key => $label)
			<div class="checkbox col-md-6">
				<label>
					<input type="checkbox" class="form-check-input" value="{{ $key }}" type="{{ $field->fieldtype }}" id="{{ $field->itemID($key) }}" name="{{ $field->itemName() }}"{{ $field->checked($key) }}>
					{{ $label }}
				</label>
			</div>
			@endforeach
			</div>

			@if (!empty($field->help))
			<small class="form-text text-muted">{{ $field->help }}</small>
			@endif

			@if ($errors->has($field->name.'.*'))
				<span class="help-block">
					<strong>{{ $errors->first($field->name.'.*') }}</strong>
				</span>
			@endif
		</div>
