		<label class="col-md-4 control-label">&nbsp;</label>

		<div class="col-md-8">
			<div class="form-check">
			  <label class="form-check-label" for="{{ $field->name }}">
			  <input class="form-check-input" type="{{ $field->fieldtype }}" value="{{ $field->value }}" id="{{ $field->name }}" name="{{ $field->name }}"{{ $field->checked($field->value) }}>
				{{ $field->label }}
			  </label>
			</div>

			@if (!empty($field->help))
			<small class="form-text text-muted">{{ $field->help }}</small>
			@endif

			@if ($errors->has($field->name))
				<span class="help-block">
					<strong>{{ $errors->first($field->name) }}</strong>
				</span>
			@endif
		</div>
