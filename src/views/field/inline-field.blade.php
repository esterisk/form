	@if ($field->includedInForm())
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
	@endif
