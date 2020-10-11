		<label class="col-md-4 control-label">{{ $field->label }}</label>

		<div class="col-md-8">
			<div class="row">
			@foreach ($field->fields as $subfield)
			<div class="form-check checkbox col-md-12">
			  <label class="form-check-label" for="{{ $subfield->name }}">
			  <input class="form-check-input" type="{{ $subfield->fieldtype }}" value="{{ $subfield->value }}" id="{{ $subfield->name }}" name="{{ $subfield->name }}"{{ $subfield->checked($subfield->value) }}>
				{{ $subfield->label }}
			  </label>
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
