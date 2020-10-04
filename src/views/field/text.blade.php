		<label for="{{ $field->name }}" class="col-md-4 control-label">{{ $field->label }}</label>

		<div class="col-md-8">
			<input id="{{ $field->name }}" type="{{ $field->fieldtype }}" class="form-control" name="{{ $field->name }}" value="{{ $field->getDefault() }}"{{ $field->isrequired() }} placeholder="{{ $field->placeholder }}">
			@if (!empty($field->help))
			<small class="form-text text-muted">{{ $field->help }}</small>
			@endif

			@if ($errors->has($field->name))
				<span class="help-block">
					<strong>{{ $errors->first($field->name) }}</strong>
				</span>
			@endif
		</div>
