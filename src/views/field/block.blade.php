		
		@if($field->triggerButton)
		<button type="button" class="btn btn-outline-secondary form-wrapper-opener" rel="{{ $field->name }}">Apri {{ $field->title }}</button>
		@endif

		<div {!! ($field->triggerField || $field->triggerButton) ? ' style="display:none"' : '' !!} id="{{ $field->name }}">
			<div class="row"><div class="col-sm-12">

				<div class="form-wrapper {{ $field->title ? ' with-title' : '' }}">
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
				$('[name={{ $field->triggerField }}]').change( function() {
					if ($('#{{ $field->triggerField.'-'.$field->showValue }}:checked').val()
						|| $('select[name={{ $field->triggerField }}]').val() == '{{ $field->showValue }}') 
						$('#{{ $field->name }}').slideDown(); else $('#{{ $field->name }}').slideUp();
				});
			}
			$(document).ready( function() { {{ $field->name }}_conditional() });
			{{ $field->name }}_conditional();
		</script>
		@endif
		