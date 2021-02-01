
		@if($field->triggerButton)
		<button type="button" class="btn btn-outline-secondary form-wrapper-opener" rel="{{ $field->name }}">Apri {{ $field->title }}</button>
		@endif

		<div class="container form-block" {!! ($field->triggerField || $field->triggerButton) ? ' style="display:none"' : '' !!} id="{{ $field->name }}">
			<div class="row"><div class="col-sm-12">

				<div class="{{ $field->border ? 'form-wrapper ' : '' }}{{ $field->title ? ' with-title' : '' }}">
			@if ($field->title)
				<h3>{{ $field->title }}</h3>
			@endif

			@foreach ($field->fields as $subfield)
			<div class="form-group row
				field-{{ $subfield->name}}
				fieldtype-{{ $subfield->fieldtype }}
				fieldtpl-{{ $subfield->template }}
				{{ $errors->has($subfield->name) ? ' has-error' : '' }}">
				@include('esterisk.form.field.'.$subfield->template, [ 'field' => $subfield ])
			</div>
			@endforeach
				</div>

			</div></div>

			@if($field->triggerButton)
			<button type="button" class="btn btn-outline-secondary form-wrapper-closer" rel="{{ $field->name }}">Chiudi {{ $field->title }}</button>
			@endif
		</div>
		@if($field->triggerButton)
		<script>
			$(document).ready( function() {
				$(document).on('click', '.form-wrapper-opener[rel="{{ $field->name }}"]', function() { $('#{{ $field->name }}').slideDown(); $('.form-wrapper-opener[rel="{{ $field->name }}"]').slideUp(); })
				$(document).on('click', '.form-wrapper-closer[rel="{{ $field->name }}"]', function() { $('#{{ $field->name }}').slideUp(); $('.form-wrapper-opener[rel="{{ $field->name }}"]').slideDown(); })
			});
		</script>
		@endif

		@if($field->triggerField)
		<script>
			function {{ $field->name }}_conditional() {
			    @if (preg_match('/\*(.+)/', $field->triggerField, $m))
				$('{{ $m[1] }}').change( function() {
			    @else
				$('[name={{ $field->triggerField }}]').change( function() {
				@endif
					var opened = false;
				@if(is_array($field->showValue))
					@foreach ($field->showValue as $v)
					if ($('#{{ $field->triggerField.'__'.$v }}:checked').val() || $('select[name={{ $field->triggerField }}]').val() == '{{ $v }}') opened = true;
					@endforeach
				@else
					if ($('#{{ $field->triggerField.'__'.$field->showValue }}:checked').val() || $('select[name={{ $field->triggerField }}]').val() == '{{ $field->showValue }}')  opened = true;
				@endif
					if (opened) $('#{{ $field->name }}').slideDown(); else $('#{{ $field->name }}').slideUp();
				});
			}
			$(document).ready( function() { {{ $field->name }}_conditional() });
			{{ $field->name }}_conditional();
		</script>
		@endif
