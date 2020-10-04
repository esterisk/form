		<label for="{{ $field->name }}" class="col-md-4 control-label">{{ $field->label }}</label>

		<div class="col-md-8">
			<textarea id="{{ $field->name }}" class="form-control" name="{{ $field->name }}" rows="{{ $field->rows }}" placeholder="{{ $field->placeholder }}"{{ $field->isrequired() }}>{{ $field->getDefault() }}</textarea>
			@if (!empty($field->help))
			<small class="form-text text-muted">{{ $field->help }}</small>
			@endif

			@if ($errors->has($field->name))
				<span class="help-block">
					<strong>{{ $errors->first($field->name) }}</strong>
				</span>
			@endif
		</div>
