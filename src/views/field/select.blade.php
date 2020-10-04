		<label for="{{ $field->name }}" class="col-md-4 control-label">{{ $field->label }}</label>

		<div class="col-md-8">
			<select class="form-control" id="{{ $field->name }}" name="{{ $field->name }}"{{ $field->isrequired() }}>
			@foreach ($field->options as $value => $label)
				<option{{ $field->selected($value) }} value="{{ $value }}">{{ $label }}</option>
			@endforeach
			</select>

			@if ($errors->has($field->name))
				<span class="help-block">
					<strong>{{ $errors->first($field->name) }}</strong>
				</span>
			@endif
		</div>
