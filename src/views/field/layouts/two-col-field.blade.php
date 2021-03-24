	@if ($field->includedInForm())
	    <div class="row">
	    @if($field->layoutLabelPosition == 'before')
		<label for="{{ $field->name }}" class="{{ $field->layoutLabelCols }} control-label">{{ $field->label }}</label>
        @endif

		<div class="{{ $field->layoutInputCols }}">
            @if($field->layoutLabelPosition == 'inline')
            <label for="{{ $field->name }}" class="inline-label control-label">{{ $field->label }}</label>
            @endif

            @if($field->layoutLabelPosition == 'floating')
            <label for="{{ $field->name }}" class="esterisk-float-label control-label">
            @endif

		@if ($field->editedInForm())
			@yield('editfield-'.$field->name)
		@else
			{{ $field->show($field->getDefault()) }}
		@endif

            @if($field->layoutLabelPosition == 'floating')
                <span>{{ $field->label }}</span>
            </label>
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

	    @if($field->layoutLabelPosition == 'after')
		<label for="{{ $field->name }}" class="{{ $field->layoutLabelCols }} control-label">{{ $field->label }}</label>
        @endif
        </div>
	@endif
