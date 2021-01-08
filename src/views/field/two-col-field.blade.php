	@if ($field->includedInForm())
		<label for="{{ $field->name }}" class="col-lg-3 col-md-4 col-sm-12 control-label">{{ $field->label }}</label>

		<div class="col-lg-9 col-md-8 col-sm-12">
		@if ($field->editedInForm())
			@yield('editfield-'.$field->name)
		@else
			{{ $field->show($field->getDefault()) }}
		@endif
			@if (!empty($field->help))
			<small class="form-text text-muted">{{ $field->help }}</small>
			@endif

			<div class="invalid-feedback"></div>
			@if ($errors->has($field->name))
				<span class="help-block">
					<strong>{{ $errors->first($field->name) }}</strong>
				</span>
			@endif
		</div>
	@endif
	