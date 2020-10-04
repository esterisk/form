		<label class="col-md-4 control-label">{{ $field->label }}</label>

		<div class="col-md-8">
			@foreach ($field->options as $value => $label)
			<div class="form-check">
			  <label class="form-check-label" for="{{ $field->name }}-{{ $value }}">
			  <input class="form-check-input" type="{{ $field->fieldtype }}" value="{{ $value }}" id="{{ $field->name }}-{{ $value }}" name="{{ $field->name }}"{{ $field->checked($value) }}>
				{{ $label }}
			  </label>
			</div>
			@endforeach

			@if (!empty($field->help))
			<small class="form-text text-muted">{{ $field->help }}</small>
			@endif

			@if ($errors->has($field->name))
				<span class="help-block">
					<strong>{{ $errors->first($field->name) }}</strong>
				</span>
			@endif
		</div>
