		<label for="{{ $field->name }}" class="col-md-4 control-label">{{ $field->label }}</label>

		<div class="col-md-8" style="margin-bottom:15px;">
			<input id="{{ $field->name }}" type="{{ $field->fieldtype }}" class="form-control" name="{{ $field->name }}" value="{{ $field->getDefault() }}"{{ $field->isrequired() }}>
			@if (!empty($field->help))
			<small class="form-text text-muted">{{ $field->help }}</small>
			@endif

			@if ($errors->has($field->name))
				<span class="help-block">
					<strong>{{ $errors->first($field->name) }}</strong>
				</span>
			@endif
		</div>

		<label for="password_confirmation" class="col-md-4 control-label">Conferma password</label>

		<div class="col-md-8">
			<input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="" required>

			@if ($errors->has('password_confirmation'))
				<span class="help-block">
					<strong>{{ $errors->first('password_confirmation') }}</strong>
				</span>
			@endif
		</div>
